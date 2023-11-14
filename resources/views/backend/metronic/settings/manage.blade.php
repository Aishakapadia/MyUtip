@extends( admin_layout('master') )


@section('title')
    Manage {{ $module->father->title }}
@stop


@section('head_page_level_plugins')
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-editable/inputs-ext/address/address.css') }}" rel="stylesheet" type="text/css" />
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
                    <a href="{{ route('setting-manage') }}">{{ $module->father->title }}</a>
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
        <div class="m-heading-1 border-green m-bordered">
            <h3>Note:</h3>
            <p> You are going to change CMS system's settings, please don't change anything if you are not sure what you are doing. </p>
        </div>
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase">Settings</span>
                </div>
                <div class="actions hide">
                    <div class="btn-group btn-group-devided" data-toggle="buttons">
                    	<a href="javascript:history.back();" class="btn btn-transparent dark btn-outline btn-circle btn-sm">Back</a>
                    </div>
                </div>
            </div>
            <div class="portlet-body">

                <div class="row">
                    <div class="col-md-12">
                    	<div id="msg"></div>
                        <table id="user" class="table table-bordered table-striped">
                            <tbody>
			                    <tr>
                                    <th style="width:15%"> KEY </th>
                                    <th style="width:50%"> VALUE </th>
                                    <th style="width:35%"> DESCRIPTION </th>
                                </tr>
                                @if($setting_data)
                                @foreach($setting_data as $key => $setting)
	                                <tr>
	                                    <td> {{ strtoupper(str_replace('_', ' ', str_replace('.', ' ', $setting->key))) }} </td>
	                                    <td>
	                                        <a href="javascript:;"
	                                        class="data"
	                                        data-type="text"
	                                        data-pk="{{ $setting->id }}"
	                                        data-name="{{ $setting->value }}"
	                                        data-placement="right"
	                                        data-placeholder="Required"
	                                        data-original-title="Enter your data">
	                                        	{{ $setting->value }}
	                                        </a>
	                                    </td>
	                                    <td>
	                                        <span class="text-muted"> {{ $setting->description }} </span>
	                                    </td>
	                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
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
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/jquery.mockjax.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-editable/inputs-ext/address/address.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-editable/inputs-ext/wysihtml5/wysihtml5.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
@stop


@section('foot_page_level_scripts')
    {{-- <script src="{{ admin_asset('assets/pages/scripts/form-editable.min.js') }}" type="text/javascript"></script> --}}
@stop


@section('foot_resources')
    <script>
        //global settings
        $.fn.editable.defaults.mode = 'inline';
        $.fn.editable.defaults.inputclass = 'form-control';
        $.fn.editable.defaults.url = '/post';

        $.mockjax({
            url: '/post',
            response: function(settings) {
                console.log(settings, this);
            }
        });

        $('.data').editable({
            url: '/panel/setting/update',
            type: 'text',
            validate: function(value) {
                if ($.trim(value) == '') return 'This field is required';
            },
            success: function(data) {
            	//console.log(data);
            },
             error: function(errors) {
             	//console.log(errors);
				// var msg = '';
				// if(errors && errors.responseText) { //ajax error, errors = xhr object
				//    msg = errors.responseText;
				// } else { //validation error (client-side or server-side)
				//    $.each(errors, function(k, v) { msg += k+": "+v+"<br>"; });
				// }
				// $('#msg').removeClass('alert-success').addClass('alert-error').html(msg).show();
	       }
        });

    </script>
@stop
