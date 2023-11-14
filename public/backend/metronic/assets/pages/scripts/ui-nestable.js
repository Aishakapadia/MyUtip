var UINestable = function () {

    return {
        //main function to initiate the module
        init: function () {

            $('#nestable_list_menu').on('click', function (e) {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });

            var updateOutput = function (e) {
                var list = e.length ? e : $(e.target), output = list.data('output');
                var data = {
                    list: list.nestable('serialize')
                };

                main.ajax($('body').data('url') + '/navigation/update-navigation-sorting', 'POST', data, function (response) {

                }, function () {
                    swal({
                        title: 'Unable to save new list order',
                        type: 'error'
                    });
                });
            };

            $('.sortable_navigation').nestable({
                group: 2,
                maxDepth: 3,
            }).on('change', updateOutput);

        }

    };

}();

jQuery(document).ready(function () {
    UINestable.init();

    /**
     * Navigation Menu Tabs
     */
    $('.nav-tabs li a').on('click', function () {

        var navId = $(this).data('menu-id');

        main.ajax($('body').data('url') + '/navigation/page-list', 'POST', {'navId': navId}, function (response) {
            //$('.sortable_navigation').html(response);
        });
    });

    $(document).on('change', '.check-all-pages', function () {
        $('.pages').prop('checked', $(this).prop("checked"));
    });

    $(document).on('change', '.check-all-links', function () {
        $('.links').prop('checked', $(this).prop("checked"));
    });

    $(document).on('change', '.check-all-lists', function () {
        $('.lists').prop('checked', $(this).prop("checked"));
    });


    /**
     * Add new link to list
     */
    $(document).on('click', '#add_list', function () {
        var navTitle = $('#title').val();
        var navUrl = $('#url').val();

        var navId = $('.tab-content .active').data('menu-id');
        var sortableList = 'nestable_list_' + navId;

        var data = {
            'title': navTitle,
            'url': navUrl
        };

        main.ajax($('body').data('url') + '/navigation/add-list', 'POST', data, function (response) {

            //$('#menu_lists').html(response);
            loadModuleLinks();

            // reset form
            $("#title").val('').parents('.form-group').removeClass('has-error');
            $("#url").val('').parents('.form-group').removeClass('has-error');
            $("#title" + ' ~ span.help-block').show().html('');
            $("#url" + ' ~ span.help-block').show().html('');

        }, function (reject) {
            if (reject.status === 422) {
                var errors = $.parseJSON(reject.responseText);
                $.each(errors.error, function (key, val) {
                    $("#" + key).parents('.form-group').addClass('has-error');
                    $("#" + key + ' ~ span.help-block').show().html(val);
                });
            }
        });
    });

    /**
     * Change the status of a list "read" = "0" to hide it from lists.
     */
    $(document).on('click', '.hide-list', function (e) {
        e.preventDefault();

        var el = $(this);
        var id = el.data('id');

        // main.ajax($('body').data('url') + '/navigation/hide-list', 'POST', {id: id}, function (response) {
        //     if (response.status) {
        //         el.parents('tr').fadeOut();
        //     }
        // });

        $('.hide-list').confirmation('hide'); // hide others
        $(this).confirmation('show'); // show confirmation box
        $(this).on('confirmed.bs.confirmation', function (e) { // take action if yes
            e.preventDefault();
            main.ajax($('body').data('url') + '/navigation/hide-list', 'POST', {id: id}, function (response) {
                if (response.status) {
                    el.parents('tr').fadeOut();
                }
            });
        });
    });

    /**
     * Delete a record from menu_lists table.
     * It will remove the links data.
     */
    $(document).on('click', '.remove-list', function (e) {
        e.preventDefault();

        var el = $(this);
        var id = el.data('id');

        // if (confirm('Are you sure?')) {
        //     main.ajax($('body').data('url') + '/navigation/remove-list', 'POST', {id: id}, function (response) {
        //         if (response.status) {
        //             el.parents('tr').fadeOut();
        //         }
        //     });
        // }

        $('.remove-list').confirmation('hide'); // hide others
        $(this).confirmation('show'); // show confirmation box
        $(this).on('confirmed.bs.confirmation', function (e) { // take action if yes
            e.preventDefault();
            main.ajax($('body').data('url') + '/navigation/remove-list', 'POST', {id: id}, function (response) {
                if (response.status) {
                    el.parents('tr').fadeOut();
                }
            });
        });

    });


    $(document).on('click', '.remove_location_list', function (e) {
        e.preventDefault();

        var el = $(this);
        var menu_list_id = el.data('menu-list-id');
        var location_id = $('.tab-content .active').data('menu-id');
        var data = {
            'location_id': location_id,
            'menu_list_id': menu_list_id
        };

        main.ajax($('body').data('url') + '/navigation/remove-list-from-location', 'POST', data, function (response) {
            if (response.status == true) {
                loadModuleLocations();
            }
        });
    });

    /**
     * Add created links to list
     */
    $(document).on('click', '#add_link_to_list', function (e) {
        e.preventDefault();

        var checkedValues = $('.links:checked').map(function () {
            //return this.id;
            return $(this).data('id');
        }).get();

        if(checkedValues.length > 0) {
            main.ajax($('body').data('url') + '/navigation/add-link-to-list', 'POST', {ids: checkedValues}, function (response) {
                if (response.status == true) {
                    loadModuleLists();
                    $('.check-all-links').prop('checked', false);
                    $('.links').prop('checked', false);
                }
            });
        } else {
            swal({
                title: 'Please select some links first.',
                type: 'warning'
            });
        }
    });


    /**
     * Add created pages to list
     */
    $(document).on('click', '#add_page_to_list', function (e) {
        e.preventDefault();

        var checkedValues = $('.pages:checked').map(function () {
            //return this.id;
            return $(this).data('id');
        }).get();

        if(checkedValues.length > 0) {
            main.ajax($('body').data('url') + '/navigation/add-page-to-list', 'POST', {ids: checkedValues}, function (response) {
                if (response.status == true) {
                    loadModuleLists();
                    $('.check-all-pages').prop('checked', false);
                    $('.pages').prop('checked', false);
                }
            });
        } else {
            swal({
                title: 'Please select some pages first.',
                type: 'warning'
            });
        }
    });


    /**
     * Add list to locations.
     */
    $(document).on('click', '#add_list_to_location', function (e) {
        e.preventDefault();

        var location_id = $('.tab-content .active').data('menu-id');
        //var nestable_list = 'nestable_list_' + location_id;
        var checkedValues = $('.lists:checked').map(function () {
            return this.id;
        }).get();

        var data = {
            'location_id': location_id,
            'list_ids': checkedValues
        };

        if(checkedValues.length > 0) {
            main.ajax($('body').data('url') + '/navigation/add-list-to-location', 'POST', data, function (response) {
                if (response.status == true) {
                    loadModuleLocations(location_id);
                    $('.check-all-lists').prop('checked', false);
                    $('.lists').prop('checked', false);
                }
            });
        } else {
            swal({
                title: 'Please select some list first.',
                type: 'warning'
            });
        }
    });


    loadModuleLinks();
    loadModuleLists();
    loadModuleLocations();

});

function loadModuleLinks() {
    main.ajax($('body').data('url') + '/navigation/load-module-links', 'GET', {}, function (response) {
        $('#module-links').html(response);
    });
}

function loadModuleLists() {
    main.ajax($('body').data('url') + '/navigation/load-module-lists', 'GET', {}, function (response) {
        $('#module-lists').html(response);
    });
}

function loadModuleLocations(location_id) {

    main.ajax($('body').data('url') + '/navigation/load-module-locations', 'GET', {location_id: location_id}, function (response) {
        $('#module-locations').html(response);
        UINestable.init();
    });
}