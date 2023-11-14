/**
 * [DataTableHelper Extended datatable helper for moduler base]
 *
 * @author Muhammad Khalil
 * @param {[type]} element [description]
 * @param {[type]} url     [description]
 * @param {[type]} data    [description]
 */
var DataTableHelper = function(element, url, data) {
    //region Variables
    var self = this;
    var instance = null;
    var onBeforeLoadCallback = null;
    var onLoadCallback = null;
    var ajaxParams = {};
    var hiddenField = element + '_TEMP_FIELD';
    var moduleName = null;
    var sortColumn = [
        [0, "asc"]
    ];
    var orderableColumnList = [{ // define columns sorting options(by default all columns are sortable except the first checkbox column)
        'orderable': true,
        'targets': [0]
    }];
    //endregion
    this.onBeforeLoad = function(callback) {
        console.log('onBeforeLoad');
        onBeforeLoadCallback = callback;
    }
    this.onLoad = function(callback) {
        console.log('onLoad');
        onLoadCallback = callback;
    }
    this.setModuleName = function(module_name) {
        moduleName = module_name;
    }
    this.init = function() {
        // onBeforeLoad
        if (onBeforeLoadCallback) {
            onBeforeLoadCallback();
        }
        // custom hidden field for ajax // ADNAN::
        if ($(hiddenField).size() == 0) {
            var $hiddenInput = $('<input/>', {
                type: 'hidden',
                id: hiddenField.substr(1),
                value: ''
            });
            $hiddenInput.appendTo('body');
        }

        instance = new Datatable();
        instance.init({
            src: $(element),
            onSuccess: function(grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
                // onLoad
                if (onLoadCallback) {
                    onLoadCallback(response, grid);
                }
            },
            onError: function(grid) {
                // execute some code on network or other general error
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
                // So when dropdowns used the scrollable div should be removed.
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [25, 50, 100, 250, 500],
                    [25, 50, 100, 250, 500] // change per page values here
                ],
                "pageLength": 25, // default record count per page
                "ajax": {
                    "url": url, // ajax source
                    // ADNAN::{
                    "data": function(data) {
                        var filterAjaxParams = instance.getAjaxParams();
                        $.each(filterAjaxParams, function(key, value) {
                            data[key] = value;
                        });
                        var customData = $(hiddenField).val();
                        if (customData) {
                            var json = JSON.parse(customData);
                            $.each(json, function(key, value) {
                                data[key] = value;
                            });
                        }
                        return data;
                    }
                    // }::
                },
                "columnDefs": orderableColumnList,
                // default sort column
                "order": sortColumn
            }
        });
        //region Group Actions
        instance.getTableWrapper().on('click', '.table-group-action-submit', function(e) {
            e.preventDefault();
            var action = $(".table-group-action-input", instance.getTableWrapper());
            if (action.val() != "" && instance.getSelectedRowsCount() > 0) {
                $.ajax({
                    url: $('body').data('url') + '/' + moduleName + '/group-action',
                    method: 'POST',
                    data: {
                        'ids': instance.getSelectedRows(),
                        'action': action.val()
                    },
                    success: function(response) {
                        if (response.success) {
                            swal({
                                title: response.msg,
                                type: 'success',
                                confirmButtonClass: 'btn-success'
                            });
                            instance.getDataTable().ajax.reload();
                            instance.clearAjaxParams();
                            $('.table-group-action-input').val('');
                        }
                    }
                });
            } else if (action.val() == "") {
                swal({
                    title: 'Please select an action',
                    type: 'warning',
                    confirmButtonClass: 'btn-default'
                });
            } else if (instance.getSelectedRowsCount() === 0) {
                swal({
                    title: 'No record selected',
                    type: 'warning',
                    confirmButtonClass: 'btn-default'
                });
            }
        });
        //endregion
        self.ajax = instance.getDataTable().ajax;
    }
    this.setSortColumn = function(colIndex, direction) {
        sortColumn = [];
        direction = typeof direction == 'undefined' ? 'asc' : direction;
        sortColumn.push([colIndex, direction]);
    }
    this.setOrderableColumnList = function(colIndex, isOrderable) {
        orderableColumnList = [{
            'orderable': isOrderable,
            'targets': colIndex
        }]
    }
    this.setAjaxParam = function(name, value) {
        ajaxParams[name] = value;
    }
    this.clearAjaxParam = function() {
        ajaxParams = {};
        $(hiddenField).val('');
    }
    this.reload = function(jsAjaxParams) {
        if (typeof jsAjaxParams == 'object') {
            //alert( jsAjaxParams ); TODO:: IN PROGRESS
        }
        $(hiddenField).val(JSON.stringify(ajaxParams));
        instance.getDataTable().ajax.reload();
    }
    this.download = function(url, params) {
        var gridParams = xGrid.ajax.params();
        console.log('gridParams: ', gridParams);
        console.log('params: ', params);
        if (typeof params != 'undefined' && params) {
            if (typeof params == 'string') {
                // converting query string to object
                var vars = params.split("&");
                params = {};
                for (var i = 0; i < vars.length; i++) {
                    var pair = vars[i].split("=");
                    pair[0] = decodeURIComponent(pair[0]);
                    pair[1] = decodeURIComponent(pair[1]);
                    // If first entry with this name
                    if (typeof params[pair[0]] === "undefined") {
                        params[pair[0]] = pair[1];
                        // If second entry with this name
                    } else if (typeof params[pair[0]] === "string") {
                        var arr = [params[pair[0]], pair[1]];
                        params[pair[0]] = arr;
                        // If third or later entry with this name
                    } else {
                        params[pair[0]].push(pair[1]);
                    }
                }
            }
            console.log(params);
            if (typeof params == 'object') {
                $.each(params, function(key, value) {
                    gridParams[key] = value;
                });
            }
        }
        var newForm = jQuery('<form>', {
            'action': url,
            'method': 'post',
            'target': '_top'
        }).append(jQuery('<input>', {
            'name': 'jsonForm',
            'value': JSON.stringify(gridParams),
            'type': 'hidden'
        })).append(jQuery('<input>', {
            'name': '_token',
            'value': $('meta[name="csrf-token"]').attr('content'),
            'type': 'hidden'
        }));
        $(document.body).append(newForm);
        newForm.submit();
    }
}
