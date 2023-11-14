@extends( admin_layout('master') )


@section('title')
    {{ $pageMode }} {{ $module->title }}
@stop


@section('head_page_level_plugins')
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
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
                        <a href="{{ route('lane-manage') }}">{{ $module->father->title }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ route('lane-manage') }}">Manage</a>
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
                    <div class="portlet light bordered form-fit">

                        <div class="portlet-title">
                            <div class="caption">
                                <i class="{{ $module->father->icon }} font-blue-hoki"></i>
                                <span class="caption-subject font-blue-hoki sbold uppercase"> {{ $module->father->title }} Create </span>
                            </div>
                            <div class="actions hide">
                                <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                    <i class="icon-cloud-upload"></i>
                                </a>
                                <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                    <i class="icon-wrench"></i>
                                </a>
                                <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                    <i class="icon-trash"></i>
                                </a>
                            </div>
                        </div>

                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
	                        @if(strtolower($pageMode) != 'edit')
	                            {!! Form::open(['route' => 'lane-create', 'class' => 'form-horizontal form-bordered']) !!}
	                        @else
	                            {!! Form::model($lane, ['method' => 'PUT', 'route' => ['lane-update.lane', $lane->id], 'class' => 'form-horizontal form-bordered']) !!}
	                        @endif
	                            @include(admin_view('lanes.form'))
	                        {!! Form::close() !!}
                            <!-- END FORM-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- END CONTENT BODY -->
    </div>
@stop


@section('foot_page_level_plugins')
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') }}" type="text/javascript"></script>
@stop


@section('foot_page_level_scripts')
    <script src="{{ admin_asset('assets/pages/scripts/components-editors.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
@stop


@section('foot_resources')
	@if(env('ADMIN_CLIENT_VALIDATIONS', 1))
		<!-- Laravel Javascript Validation -->
		<script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
		@if($pageMode != 'Edit')
			{!! JsValidator::formRequest('App\Http\Requests\Backend\LaneStoreRequest') !!}
		@else
			{!! JsValidator::formRequest('App\Http\Requests\Backend\LaneUpdateRequest') !!}
		@endif
	@endif

    <script type="text/javascript">
        $(function(){
            $('input#title').on('keyup', function() {
                $('#slug').val( main.convertToSlug( $(this).val() ) );
            });

            $(".select2").select2();

            var placeholder = "Select";
            $(".select2-multiple").select2({
                //placeholder: placeholder,
                width: null
            });

        });
    </script>
@stop
