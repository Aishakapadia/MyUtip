@extends( admin_layout('master') )

@section('title')
    Pop {{ $pageMode }}
@stop

@section('head_page_level_plugins')
    <link href="{{ admin_asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('head_resources')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ admin_asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
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
                        <a href="{{ route('pop-manage') }}">{{ $module->father->title }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ route('pop-manage') }}">Manage</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>{{ $pageMode }}</span>
                    </li>
                </ul>
            </div>
            <!-- END PAGE BAR -->

            <!-- BEGIN PAGE TITLE-->
            <h1 class="page-title"> {{ $module->father->title }}
                <small>{{ strtolower($pageMode) }}</small>
            </h1>
            <!-- END PAGE TITLE-->
            <!-- END PAGE HEADER-->

            <div class="row">
                <div class="col-md-12">
                    <div class="portlet-body form">

                        @include('backend.metronic.errors.errors')

                        <div class="portlet box blue">

                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-plus-circle"></i>{{ $pageMode }} Pop
                                </div>
                            </div>

                            <div class="portlet-body form">

                                <!-- BEGIN FORM-->
                                {!! Form::open( array('url' => admin_url('induction/approve'), 'class' => 'horizontal-form', 'pop' => 'form') ) !!}
                                <div class="form-body">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Description <span class="required">*</span></label>
                                                {!! Form::hidden('pop_id', Request::segment(4)) !!}
                                                {!! Form::textarea('comments', null, ['class' => 'form-control', 'id' => 'comments']) !!}
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->

                                </div><!-- .form-body -->

                                <div class="form-actions right">
                                    <button type="button" class="btn default" onclick="window.history.go(-1);">Cancel</button>
                                    <button type="submit" class="btn blue"><i class="fa fa-check"></i> Save</button>
                                </div>
                                {!! Form::close() !!}
                                <!-- END FORM-->

                            </div><!-- .portlet-body form -->

                        </div><!-- .portlet box blue -->
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop

@section('foot_page_level_plugins')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ admin_asset('assets/custom/scripts/data-table-helper.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
@stop


@section('foot_page_level_scripts')
    <!-- BEGIN CUSTOM SCRIPTS -->
    <script src="{{ admin_asset('assets/custom/scripts/main.js') }}" type="text/javascript"></script>
    <!-- END CUSTOM SCRIPTS -->
@stop

@section('foot_resources')
    <script>
        $(function() {
            $('#title').on('keyup', function () {
                var title = $(this).val();
                $('#slug').val(main.convertToSlug(title));
            });
        });
    </script>
@stop
