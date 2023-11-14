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

        #datatable_ajax > thead > tr.heading > th:nth-child(7),
        #datatable_ajax > thead > tr.heading > th:nth-child(8),
        #datatable_ajax > thead > tr.heading > th:nth-child(14),
        #datatable_ajax > thead > tr.heading > th:nth-child(16),
        #datatable_ajax > thead > tr.heading > th:nth-child(17),
        #datatable_ajax > thead > tr.heading > th:nth-child(19)
        { width: 100px; min-width: 100px; }

        #datatable_ajax > thead > tr.heading > th:nth-child(2),
        #datatable_ajax > thead > tr.heading > th:nth-child(3),
        #datatable_ajax > thead > tr.heading > th:nth-child(5),
        #datatable_ajax > thead > tr.heading > th:nth-child(15),
        #datatable_ajax > thead > tr.heading > th:nth-child(18)
        { width: 130px; min-width: 130px; }

        #datatable_ajax > thead > tr.heading > th:nth-child(4),
        #datatable_ajax > thead > tr.heading > th:nth-child(6),
        #datatable_ajax > thead > tr.heading > th:nth-child(10),
        #datatable_ajax > thead > tr.heading > th:nth-child(11),
        #datatable_ajax > thead > tr.heading > th:nth-child(13)
        { width: 150px; min-width: 150px; }

        #datatable_ajax > thead > tr.heading > th:nth-child(9),
        #datatable_ajax > thead > tr.heading > th:nth-child(12)
        { width: 210px; min-width: 210px; }




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
                        <a href="{{ route('induction-manage') }}">{{ $module->father->title }}</a>
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
                                    {{--@if(PermissionHelper::isAllowed('pop/create'))--}}
                                        {{--<a href="{{ route('pop-create') }}" class="btn btn-transparent green btn-outline btn-outline btn-circle btn-sm active">--}}
                                            {{--<i class="fa fa-plus"></i> New--}}
                                        {{--</a>--}}
                                    {{--@endif--}}
                                    @if(PermissionHelper::isAllowed('induction/download'))
                                        <button class="btn btn-transparent blue btn-outline btn-circle btn-sm btn-export">
                                            <i class="fa fa-download"></i> Export
                                        </button>
                                    @endif
                                </div>
                                {{--<div class="btn-group">--}}
                                    {{--<a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">--}}
                                        {{--<i class="fa fa-share"></i>--}}
                                        {{--<span class="hidden-xs"> Tools </span>--}}
                                        {{--<i class="fa fa-angle-down"></i>--}}
                                    {{--</a>--}}
                                    {{--<ul class="dropdown-menu pull-right">--}}
                                        {{--<li>--}}
                                            {{--<a href="javascript:;"> Export to Excel </a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="javascript:;"> Export to CSV </a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="javascript:;"> Export to XML </a>--}}
                                        {{--</li>--}}
                                        {{--<li class="divider"> </li>--}}
                                        {{--<li>--}}
                                            {{--<a href="javascript:;"> Print Invoices </a>--}}
                                        {{--</li>--}}
                                    {{--</ul>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                        <div class="portlet-body">

                            <div class="table-container">

                                {{--<div class="table-actions-wrapper">--}}
                                    {{--<span> </span>--}}
                                    {{--<select class="table-group-action-input form-control input-inline input-small input-sm">--}}
                                        {{--<option value="">Select...</option>--}}
                                        {{--<option value="delete">Delete</option>--}}
                                        {{--<option value="active">Active</option>--}}
                                        {{--<option value="inactive">Inactive</option>--}}
                                    {{--</select>--}}
                                    {{--<button class="btn btn-sm btn-default table-group-action-submit">--}}
                                        {{--<i class="fa fa-check"></i> Submit--}}
                                    {{--</button>--}}
                                {{--</div>--}}

                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                    <thead>
                                    <tr role="row" class="heading">
                                        <th width="2%">
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input type="checkbox" class="group-checkable" data-set="#sample_2 .checkboxes" />
                                                <span></span>
                                            </label>
                                        </th>
                                        <th width=""> Region </th>
                                        <th width=""> Distributor </th>
                                        <th width=""> TM Name </th>
                                        <th width=""> DSR Code </th>
                                        <th width=""> DSR Name </th>
                                        <th width=""> PJP Code </th>
                                        <th width=""> Working Date </th>
                                        <th width=""> POP Code </th>
                                        <th width=""> POP Name </th>
                                        <th width=""> Channel </th>
                                        <th width=""> Pop Address </th>
                                        <th width=""> Retailer Name </th>
                                        <th width=""> Retailer Contact </th>
                                        <th width=""> Retailer NIC </th>
                                        <th width=""> TM Status </th>
                                        <th width=""> SMOLLAN Status </th>
                                        <th width=""> Created At </th>
                                        <th width=""> Actions </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td> </td>
                                        <td>
                                            {{ Form::select('region', $regions, null, ['class' => 'form-control form-filter input-sm select2']) }}
                                        </td>
                                        <td>
                                            {{ Form::select('distributor', $distributors, null, ['class' => 'form-control form-filter input-sm select2']) }}
                                        </td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="name"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="dsr_code"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="dsr_name"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="pjp_code"></td>
                                        <td>
                                            {{--<input type="text" class="form-control form-filter input-sm" name="doc_date">--}}
                                        </td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="pop_code"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="pop_name"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="channel_name_new"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="address"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="retailer_name"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="retailer_contact"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="retailer_nic"></td>
                                        <td>{{ Form::select('verification_status_tm', ['' => 'All', 0 => 'System Approved', 1 => 'TM Approved', 2 => 'TM Rejected', '3' => 'Pending at TM'], null, ['class' => 'form-control form-filter input-sm']) }}</td>
                                        <td>{{ Form::select('verification_status_smollan', ['' => 'All', 0 => 'N/A', 1 => 'Approved', 2 => 'Rejected'], null, ['class' => 'form-control form-filter input-sm']) }}</td>
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
    <script src="{{ admin_asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
@stop

@section('foot_page_level_scripts')
    {{--<script src="{{ admin_asset('assets/pages/scripts/ui-confirmations.min.js') }}" type="text/javascript"></script>--}}
    <script src="{{ admin_asset('assets/custom/scripts/data-table-helper.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/pages/scripts/ui-confirmations.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/pages/scripts/ui-sweetalert.min.js') }}" type="text/javascript"></script>
@stop

@section('foot_resources')
    <script>
        var xGrid = new DataTableHelper( "#datatable_ajax", '{{ admin_url('/induction/search-data') }}' );
        jQuery(document).ready(function() {

            $('.select2').select2();

            xGrid.setModuleName('induction');

            xGrid.onBeforeLoad( function() {

                //init date pickers for filters
                $('.date-picker').datepicker({
                    rtl: App.isRTL(),
                    autoclose: true,
                    clearBtn: true,
                    format: 'mm/dd/yyyy'
                });

                // Delete a induction if confirm.
                $(document).on('click', '.btn_confirmation', function() {
                    $(this).confirmation('hide'); // hide others
                    $(this).confirmation('show'); // show confirmation box
                    $(this).on('confirmed.bs.confirmation', function (e) { // take action if yes
                        e.preventDefault();
                        var self = $(this);
                        main.adminAjax('induction/delete', 'POST', {id: self.data('id')}, function(data) {
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

            xGrid.setSortColumn(17, 'desc');
            xGrid.setOrderableColumnList([0, 18], false);
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
                xGrid.download('{{ admin_url('/induction/download') }}');
            });

            main.initHorizontalScroll('.table-responsive');

        });
    </script>
@stop
