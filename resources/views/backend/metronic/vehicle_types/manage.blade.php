@extends( admin_layout('master') )

@section('title')
    Manage {{ $module->father->title }}
@stop

@section('head_page_level_plugins')
    <link href="{{ admin_asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
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
                        <a href="{{ route('vehicle-type-manage') }}">{{ $module->father->title }}</a>
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
                                    @if(PermissionHelper::isAllowed('vehicle-type/create'))
                                        <a href="{{ route('vehicle-type-create') }}" class="btn btn-transparent green btn-outline btn-outline btn-circle btn-sm active">
                                            <i class="fa fa-plus"></i> New
                                        </a>
                                    @endif
                                    @if(PermissionHelper::isAllowed('page/download'))
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
                                        <option value="delete">Delete</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
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
                                        <th width="15%" data-col="title"> Title </th>
                                        {{--<th width="15%" data-col="slug"> Slug </th>--}}
                                        <th width="25%" data-col="description"> Description </th>
                                        <th width="8%" data-col="sort"> Sort </th>
                                        <th width="8%" data-col="status"> Status </th>
                                        <th width="10%" data-col="created_at"> Created At </th>
                                        <th width="10%" data-col="actions"> Actions </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td> </td>
                                        <td>
                                            <input type="text" class="form-control form-filter input-sm" name="title">
                                        </td>
                                        {{--<td>--}}
                                            {{--<input type="text" class="form-control form-filter input-sm" name="slug">--}}
                                        {{--</td>--}}
                                        <td></td>
                                        <td>
                                            <input type="text" class="form-control form-filter input-sm" name="sort">
                                        </td>
                                        <td>
                                            {{ Form::select('active', ['' => 'All', 1 => 'Active', 0 => 'Inactive'], null, ['class' => 'form-control form-filter input-sm']) }}
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
@stop

@section('foot_resources')
    <script>
        var defaultSortedColumn = $('#datatable_ajax th[data-col="created_at"]').index();

        var unSortableColumns = [
            'checkbox',
            'description',
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

        var xGrid = new DataTableHelper( "#datatable_ajax", '{{ admin_url('/vehicle-type/search-data') }}' );
        jQuery(document).ready(function() {

            xGrid.setModuleName('vehicle-type');

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
                        main.adminAjax('vehicle-type/delete', 'POST', {id: self.data('id')}, function(data) {
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
                xGrid.download('{{ admin_url('/vehicle-type/download') }}');
            });

        });
    </script>
@stop
