@extends( admin_layout('master') )


@section('title')
    {{ $pageMode }} {{ $module->title }}
@stop


@section('head_page_level_plugins')
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
                        <a href="{{ route('user-manage') }}">{{ $module->father->title }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ route('user-manage') }}">Manage</a>
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
	                            {!! Form::open(['route' => 'user-create', 'class' => 'form-horizontal form-bordered']) !!}
	                        @else
	                            {!! Form::model($user, ['method' => 'PUT', 'route' => ['user-update.user', $user->id], 'class' => 'form-horizontal form-bordered']) !!}
	                        @endif
	                            @include(admin_view('users.form'))
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
    <script src="{{ admin_asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
@stop


@section('foot_page_level_scripts')
    {{--<script src="{{ admin_asset('assets/pages/scripts/components-select2.min.js') }}" type="text/javascript"></script>--}}
@stop


@section('foot_resources')
	@if(env('ADMIN_CLIENT_VALIDATIONS', 1))
		<!-- Laravel Javascript Validation -->
		<script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
		@if($pageMode != 'Edit')
			{!! JsValidator::formRequest('App\Http\Requests\Backend\UserStoreRequest') !!}
		@else
			{!! JsValidator::formRequest('App\Http\Requests\Backend\UserUpdateRequest') !!}
		@endif
	@endif

    <script>
        jQuery(document).ready(function() {

            var placeholder = "Select";

            if($('#role_id').val() == '{{\Config::get('constants.ROLE_ID_TRANSPORTER')}}') {
                $('#transporter_block').show();
            }

            if($('#role_id').val() == '{{\Config::get('constants.ROLE_ID_SITE_TEAM')}}') {
                $('#site_block').show();
            }

            {{--if($('#role_id').val() == '{{\Config::get('constants.ROLE_ID_TM')}}') {--}}
                {{--$('#distributors_block').show();--}}
            {{--}--}}

            $(".select2, .select2-multiple").select2({
                placeholder: placeholder,
                width: null
            });

            $('#role_id').on('change', function () {
                var el = $(this);

                if (el.val() == '{{\Config::get('constants.ROLE_ID_TRANSPORTER')}}') {
                    $('#transporter_block').show();
                } else {
                    $('#transporter_block').hide();
                }

                if (el.val() == '{{\Config::get('constants.ROLE_ID_SITE_TEAM')}}') {
                    $('#site_block').show();
                } else {
                    $('#site_block').hide();
                }

            });

        });
    </script>
@stop
