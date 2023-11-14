var UINestable = function () {
    var updateOutput = function (e) {
        var list = e.length ? e : $(e.target),
            output = list.data('output');
        // Khalil
        var data = {
            list: list.nestable('serialize')
        };
        console.log(data);
        main.adminAjax('navigation/update-navigation-sorting', 'POST', data, function (response) {
        }, function () {
            console.log("Unable to save new list order");
        });
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };
    return {
        //main function to initiate the module
        init: function () {
            //*//
            // activate Nestable for list 1
            // $('#nestable_list_1').nestable({
            //     group: 1
            // }).on('change', updateOutput);
            // // output initial serialised data
            // updateOutput($('#nestable_list_1').data('output', $('#nestable_list_1_output')));

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
            //*//

            $('.sortable_navigation').nestable({
                group: 1,
                maxDepth: 3,
            }).on('change', updateOutput);
            updateOutput($('.sortable_navigation').data('output', $('#nestable_list_1_output')));
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
        main.adminAjax('navigation/page-list', 'POST', {
            'navId': navId
        }, function (response) {
            $('.sortable_navigation').html(response);
        }, null, function () {
            App.blockUI({
                target: '.tab-content'
            });
        }, function () {
            App.unblockUI('.tab-content');
        });
    });
    $('.group-checkable').change(function () {
        $('.page-nav-links').prop('checked', $(this).prop("checked"));
    });
    $('.page-nav-links').change(function () {
        //console.log($('.page-nav-links').length, $('.page-nav-links:checked').length);
        if ($('.page-nav-links').length == $('.page-nav-links:checked').length) {
            $('.group-checkable').prop('checked', true);
        } else {
            $('.group-checkable').prop('checked', false);
        }
    });
    /**
     * Add nav by pages
     */
    $('#addPageToNav').click(function () {
        var navId = $('.tab-content .active').data('menu-id');
        var sortableList = 'nestable_list_' + navId;
        //console.log(sortableList);
        $('.page-nav-links ').each(function () {
            var el = $(this);
            if (el.prop('checked')) {
                var pageId = $(this).attr('id');
                var pageTitle = $(this).attr('data-title');
                var pageSlug = $(this).attr('data-slug');
                var data = {
                    'pageId': pageId,
                    'navId': navId,
                    'pageTitle': pageTitle,
                    'pageSlug': pageSlug
                };
                main.adminAjax('navigation/save-navigation', 'POST', data, function (response) {
                    //console.log(response);
                    $('#' + sortableList).html(response);
                    // Update checkboxes states
                    el.prop('checked', false);
                    //$.uniform.update();
                }, null, function () {
                    App.blockUI({
                        target: '.tab-content'
                    });
                }, function () {
                    App.unblockUI('.tab-content');
                });
            }
        });
    });
    /**
     * Add nav by Url
     */
    $('#addUrlToNav').click(function () {
        var navTitle = $('#nav-title').val();
        var navUrl = $('#nav-url').val();
        var navId = $('.tab-content .active').data('menu-id');
        var sortableList = 'nestable_list_' + navId;
        /** Validate */
        if (navUrl == '') {
            alert('Please enter url');
            $('#nav-url').focus();
            return false;
        }
        if (navTitle == '') {
            alert('Please enter title');
            $('#nav-title').focus();
            return false;
        }
        if (navTitle && navUrl) {
            var data = {
                'navId': navId,
                'navTitle': navTitle,
                'navUrl': navUrl
            };
            main.adminAjax('navigation/save-nav-by-url', 'POST', data, function (response) {
                $('#' + sortableList).html(response);
                // empty form
                $('#nav-url').val('');
                $('#nav-title').val('');
            });
        }
    });
    /**
     * Remove nav element
     */
    $(document).on('click', '.removeNav', function () {
        var el = $(this);
        var navId = el.data('nav-id');
        main.adminAjax('navigation/remove-nav', 'POST', {
            'navId': navId
        }, function (response) {
            el.parent().parent().parent().fadeOut();
        });
    });
});
