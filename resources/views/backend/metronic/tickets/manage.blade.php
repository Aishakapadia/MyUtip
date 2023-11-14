@extends( admin_layout('master') )

@section('title')
    Manage {{ $module->father->title }}
@stop

@section('head_page_level_plugins')
    <link href="{{ admin_asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        #datatable_ajax > thead > tr.heading > th[data-col="ticket_number"],
        #datatable_ajax > thead > tr.heading > th[data-col="material_type"],
        #datatable_ajax > thead > tr.heading > th[data-col="quantity"],
        #datatable_ajax > thead > tr.heading > th[data-col="unit"],
        #datatable_ajax > thead > tr.heading > th[data-col="weight"]
        { width: 80px; min-width: 80px; }

        #datatable_ajax > thead > tr.heading > th[data-col="created_at"],
        #datatable_ajax > thead > tr.heading > th[data-col="actions"]
        { width: 110px; min-width: 110px; }

        #datatable_ajax > thead > tr.heading > th[data-col="from_site"],
        #datatable_ajax > thead > tr.heading > th[data-col="to_site"],
        #datatable_ajax > thead > tr.heading > th[data-col="vehicle_type"],
        #datatable_ajax > thead > tr.heading > th[data-col="transporter"],
        #datatable_ajax > thead > tr.heading > th[data-col="vehicle_number"],
        #datatable_ajax > thead > tr.heading > th[data-col="driver_contact"],
        #datatable_ajax > thead > tr.heading > th[data-col="eta"],
        #datatable_ajax > thead > tr.heading > th[data-col="transporter_status"],
        #datatable_ajax > thead > tr.heading > th[data-col="po_number"],
        #datatable_ajax > thead > tr.heading > th[data-col="ticket_status"],
        #datatable_ajax > thead > tr.heading > th[data-col="delivery_challan_number"]
        { width: 180px; min-width: 180px; }

        #datatable_ajax > thead > tr.heading > th[data-col="material"]
        { width: 250px; min-width: 250px; }

        #datatable_ajax > thead > tr.heading > th[data-col="remarks"]
        { width: 300px; min-width: 300px; }

    </style>
@stop

@section('head_resources')

@stop

@section('content')
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
            <!-- BEGIN PAGE HEADER-->

            <!-- BEGIN PAGE BAR -->
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ route('ticket-manage') }}">{{ $module->father->title }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>Manage</span>
                    </li>
                </ul>
            </div>
            <!-- END PAGE BAR -->

            <!-- BEGIN PAGE TITLE-->
            <h1 class="page-title"> {{ $module->father->title }}
                <small>manage</small>
            </h1>
            <!-- END PAGE TITLE-->

            <!-- END PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">

                    <div class="note note-info hide">
                        <p> NOTE: The below datatable is not connected to a real database so the filter and sorting is just simulated for demo purposes only. </p>
                    </div>
                    @foreach ( ['danger', 'warning', 'success', 'info'] as $msg )
                        @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                        @endif
                    @endforeach

                    <!-- Begin: life time stats -->
                    <div class="portlet light portlet-fit portlet-datatable bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="{{ $module->father->icon }} font-green"></i>
                                <span class="caption-subject font-green sbold uppercase"> {{ $module->father->title }} Listing </span>
                            </div>
                            <div class="actions">
                                <div class="btn-group btn-group-devided">
                                    @if(PermissionHelper::isAllowed('ticket/create'))
                                        <a href="{{ route('ticket-create') }}" class="btn btn-transparent green btn-outline btn-outline btn-circle btn-sm active">
                                            <i class="fa fa-plus"></i> New
                                        </a>
                                    @endif
                                    @if(PermissionHelper::isAllowed('ticket/download'))
                                        <button class="btn btn-transparent blue btn-outline btn-circle btn-sm btn-export">
                                            <i class="fa fa-download"></i> Export
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">

                            <div class="table-container">

                                <div class="table-actions-wrapper">
                                    <span> </span>
                                    <select class="table-group-action-input form-control input-inline input-small input-sm">
                                        <option value="">Select...</option>
                                        {{--<option value="approve">Approve</option>--}}
                                        @if(PermissionHelper::isAllowed('ticket/delete'))
                                            <option value="delete">Delete</option>
                                        @endif
                                    </select>
                                    <button class="btn btn-sm btn-default table-group-action-submit">
                                        <i class="fa fa-check"></i> Submit
                                    </button>
                                </div>

                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                    <thead>
                                    <tr role="row" class="heading">
                                        <th width="2%" data-col="checkbox">
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input type="checkbox" class="group-checkable" data-set="#sample_2 .checkboxes" />
                                                <span></span>
                                            </label>
                                        </th>
                                        <th width="" data-col="ticket_number"> Ticket # </th>
                                        <th width="" data-col="vehicle_type"> Vehicle Type </th>
                                        <th width="" data-col="from_site"> From Site </th>
                                        <th width="" data-col="to_site"> To Site </th>
                                        {{--<th width="" data-col="material_type"> Material Type </th>--}}
                                        {{--<th width="" data-col="material"> Material </th>--}}
                                        {{--<th width="" data-col="quantity"> Quantity </th>--}}
                                        {{--<th width="" data-col="unit" id="myunit"> Unit </th>--}}
                                        {{--<th width="" data-col="weight"> Weight (KG) </th>--}}
                                        <th width="" data-col="transporter"> Transporter </th>
                                        <th width="" data-col="vehicle_number"> Vehicle Number </th>
                                        <th width="" data-col="driver_contact"> Driver Contact </th>
                                        <th width="" data-col="eta"> ETA </th>
                                        <th width="" data-col="transporter_status"> Transporter Status </th>
                                        <th width="" data-col="delivery_challan_number"> Delivery Challan Number </th>
                                        <th width="" data-col="ticket_status"> Ticket Status </th>
                                        <th width="" data-col="remarks"> Remarks </th>
                                        {{--<th width="" data-col="po_number"> PO Number </th>--}}
                                        <th width="" data-col="created_at"> Created At </th>
                                        <th width="" data-col="actions"> Actions </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td> </td>
                                        <td>
                                            <input type="text" class="form-control form-filter input-sm" name="id">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-filter input-sm" name="vehicle_type">
                                        </td>
                                        <td>
                                            {{ Form::select('site_from', $sites, null, ['class' => 'form-control form-filter input-sm select2']) }}
                                        </td>
                                        <td>
                                            {{ Form::select('site_to', $sites, null, ['class' => 'form-control form-filter input-sm select2']) }}
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <input type="text" class="form-control form-filter input-sm" name="delivery_challan_number">
                                        </td>
                                        <td>
                                        {{ Form::select('ticket_status', $statuses, null, ['class' => 'form-control form-filter input-sm']) }}
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-filter input-sm" name="remarks">
                                        </td>
                                        <td>
                                            <div class="input-group date date-picker margin-bottom-5" data-date-format="dd/mm/yyyy">
                                                <input type="text" class="form-control form-filter input-sm" readonly name="created_at_from" placeholder="From">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            <div class="input-group date date-picker" data-date-format="dd/mm/yyyy">
                                                <input type="text" class="form-control form-filter input-sm" readonly name="created_at_to" placeholder="To">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="margin-bottom-5">
                                                <button class="btn btn-sm btn-success filter-submit margin-bottom">
                                                    <i class="fa fa-search"></i> Search</button>
                                            </div>
                                            <button class="btn btn-sm btn-default filter-cancel">
                                                <i class="fa fa-times"></i> Reset</button>
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody> </tbody>
                                </table>

                            </div><!-- .table-container -->

                        </div><!-- .portlet-body -->
                    </div>
                    <!-- End: life time stats -->
                </div>
            </div>
        </div>
        <!-- END CONTENT BODY -->
    </div>
@stop

@section('foot_page_level_plugins')
    <script src="{{ admin_asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    {{--<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-confirmation/1.0.7/bootstrap-confirmation.min.js"></script>--}}
@stop

@section('foot_page_level_scripts')
    {{--<script src="{{ admin_asset('assets/pages/scripts/ui-confirmations.min.js') }}" type="text/javascript"></script>--}}
    <script src="{{ admin_asset('assets/custom/scripts/data-table-helper.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/pages/scripts/ui-confirmations.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/pages/scripts/ui-sweetalert.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
@stop

@section('foot_resources')
    <script>
        var defaultSortedColumn = $('#datatable_ajax th[data-col="created_at"]').index();

        var unSortableColumns = [
            'checkbox',
            'material_type',
            'material',
            'quantity',
            'unit',
            'po_number',
            'weight',
            'actions'
        ];

        /**
         * Get all non-sortable columns and return their indexes
         *
         * @param list
         */
        function nonSortableColumns(list) {
            var output = [];
            if(list.length > 0) {
                for(i=0; i<list.length; i++) {
                    output.push($('#datatable_ajax th[data-col="' + list[i] + '"]').index());
                }
            }
            return output;
        }

        var xGrid = new DataTableHelper( "#datatable_ajax", '{{ admin_url('/ticket/search-data') }}' );
        jQuery(document).ready(function() {

            $(".select2").select2();

            xGrid.setModuleName('ticket');

            xGrid.onBeforeLoad( function() {

                //init date pickers for filters
                $('.date-picker').datepicker({
                    rtl: App.isRTL(),
                    autoclose: true,
                    clearBtn: true,
                    format: 'mm/dd/yyyy'
                });

                // Delete a vehicle type if confirm.
                $(document).on('click', '.btn_confirmation', function() {
                    $(this).confirmation('hide'); // hide others
                    $(this).confirmation('show'); // show confirmation box
                    $(this).on('confirmed.bs.confirmation', function (e) { // take action if yes
                        e.preventDefault();
                        var self = $(this);
                        main.adminAjax('ticket/delete', 'POST', {id: self.data('id')}, function(data) {
                            if(data.success) {
                                self.parents('tr').fadeOut();
                                swal({
                                    title: 'Record has been deleted',
                                    type: 'success',
                                    confirmButtonClass: 'btn-success'
                                });
                            }
                        });
                    });
                });

            });

            xGrid.onLoad( function(response) {
                $('.alert-danger').fadeOut();
            });

            xGrid.setSortColumn(defaultSortedColumn, 'desc');
            xGrid.setOrderableColumnList(nonSortableColumns(unSortableColumns), false);
            xGrid.init();

            // Filter Button
            $('.data-filter').on('click', function(e) {
                e.preventDefault();
                var $this = $(this);

                if ( $this.hasClass('selected') ) {
                    return true;
                }
                $this.closest('ul').find('li a').removeClass('selected');
                $this.closest('ul').find('li a i').removeClass('glyphicon-ok').addClass('glyphicon-list');

                $this.addClass('selected');
                $('i', $this).removeClass('glyphicon-list').addClass('glyphicon-ok');

                var filter = $this.data('filter-by');
                xGrid.setAjaxParam('filterBy', filter );
                xGrid.reload();
            });

            // Export Button
            $('.btn-export').on('click', function(e) {
                e.preventDefault();
                xGrid.download('{{ admin_url('/ticket/download') }}');
            });

            // My Horizontal Scroller
            main.initHorizontalScroll('.table-responsive');
        });
    </script>
@stop
