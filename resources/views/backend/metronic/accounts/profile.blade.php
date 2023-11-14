@extends( admin_layout('master') )


@section('title')
    My Profile
@stop


@section('head_page_level_plugins')
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ admin_asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/clockface/css/clockface.css') }}" rel="stylesheet" type="text/css" />
@stop


@section('head_resources')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ admin_asset('assets/pages/css/profile.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <link href="{{ admin_asset('assets/custom/croppie/croppie.css') }}" rel="stylesheet" type="text/css" />
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
                        <a href="{{ route('admin-profile') }}">My Profile</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>{{ $pageMode }}</span>
                    </li>
                </ul>
            </div>
            <!-- END PAGE BAR -->

            <!-- BEGIN PAGE TITLE-->
            <h1 class="page-title"> My Profile
                <small>{{ strtolower($pageMode) }}</small>
            </h1>
            <!-- END PAGE TITLE-->
            <!-- END PAGE HEADER-->

            <div class="row">
                <div class="col-md-12">

                    @foreach ( ['danger', 'warning', 'success', 'info'] as $msg )
                        @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                        @endif
                    @endforeach

                    {{--@if ($errors->any())--}}
                        {{--{{ implode('', $errors->all('<div>:message</div>')) }}--}}
                    {{--@endif--}}

                    <!-- BEGIN PROFILE SIDEBAR -->
                    <div class="profile-sidebar">
                        <!-- PORTLET MAIN -->
                        <div class="portlet light profile-sidebar-portlet ">
                            <!-- SIDEBAR USERPIC -->
                            <div class="profile-userpic">
                                <img src="{{ avatar($user) }}" class="img-responsive" alt="">

                                {{--@if($user->avatar != '')--}}
                                    {{--<img src="{{ url('uploads/avatars/' . $user->avatar) }}?{{ time() }}" class="img-responsive" alt="">--}}
                                {{--@else--}}
                                    {{--<img src="{{ admin_asset('assets/pages/media/profile/profile_user.jpg') }}" class="img-responsive" alt="">--}}
                                {{--@endif--}}
                            </div>
                            <!-- END SIDEBAR USERPIC -->
                            <!-- SIDEBAR USER TITLE -->
                            <div class="profile-usertitle">
                                <div class="profile-usertitle-name"> {{ $user->name }} </div>
                                <div class="profile-usertitle-job"> {{ $user->role->title }} </div>
                            </div>
                            <!-- END SIDEBAR USER TITLE -->

                            <!-- SIDEBAR MENU -->
                            <div class="profile-usermenu">
                                <ul class="nav">
                                    <li class="active">
                                        <a href="{{ route('admin-profile') }}"><i class="icon-home"></i> Overview </a>
                                    </li>
                                    {{-- <li>
                                        <a href="{{ route('admin-profile') }}"><i class="icon-settings"></i> Personal Info </a>
                                    </li> --}}
                                    <li>
                                        <a href="{{ route('admin-logout') }}"><i class="icon-key"></i> Logout </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- END MENU -->

                        </div>
                        <!-- END PORTLET MAIN -->

                        <!-- PORTLET MAIN -->
                        {{-- <div class="portlet light ">
                            <!-- STAT -->
                            <div class="row list-separated profile-stat">
                                <div class="col-md-4 col-sm-4 col-xs-6">
                                    <div class="uppercase profile-stat-title"> 37 </div>
                                    <div class="uppercase profile-stat-text"> Projects </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-6">
                                    <div class="uppercase profile-stat-title"> 51 </div>
                                    <div class="uppercase profile-stat-text"> Tasks </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-6">
                                    <div class="uppercase profile-stat-title"> 61 </div>
                                    <div class="uppercase profile-stat-text"> Uploads </div>
                                </div>
                            </div>
                            <!-- END STAT -->
                            <div>
                                <h4 class="profile-desc-title">About Marcus Doe</h4>
                                <span class="profile-desc-text"> Lorem ipsum dolor sit amet diam nonummy nibh dolore. </span>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-globe"></i>
                                    <a href="http://www.keenthemes.com">www.keenthemes.com</a>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-twitter"></i>
                                    <a href="http://www.twitter.com/keenthemes/">@keenthemes</a>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <i class="fa fa-facebook"></i>
                                    <a href="http://www.facebook.com/keenthemes/">keenthemes</a>
                                </div>
                            </div>
                        </div> --}}
                        <!-- END PORTLET MAIN -->
                    </div>
                    <!-- END BEGIN PROFILE SIDEBAR -->

                    <!-- BEGIN PROFILE CONTENT -->
                    <div class="profile-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title tabbable-line">
                                        <div class="caption caption-md">
                                            <i class="icon-globe theme-font hide"></i>
                                            <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                                        </div>
                                        <ul class="nav nav-tabs">
                                            <li class="active">
                                                <a href="#tab_info" data-toggle="tab">Personal Info</a>
                                            </li>
                                            <li>
                                                <a href="#tab_avatar" data-toggle="tab" onclick="load_croppie()">Change Avatar</a>
                                            </li>
                                            <li>
                                                <a href="#tab_password" data-toggle="tab">Change Password</a>
                                            </li>
                                            {{--<li>--}}
                                                {{--<a href="#tab_privacy" data-toggle="tab">Privacy Settings</a>--}}
                                            {{--</li>--}}
                                        </ul>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="tab-content">

                                            <!-- PERSONAL INFO TAB -->
                                            <div class="tab-pane active" id="tab_info">
                                                {!! Form::model($user, ['method' => 'PUT', 'route' => ['account-info-update.user', $user->id], 'class' => '', 'role' => 'form']) !!}

                                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                                        <label class="control-label">Name <span class="required" aria-required="true">*</span></label>
                                                        {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control']) }}
                                                        @if($errors->has('name')) <span class="help-block"> {{ $errors->first('name') }} </span> @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Email <span class="required" aria-required="true">*</span></label>
                                                        {{ Form::email('email', null, ['class' => 'form-control', 'readonly' => true]) }}
                                                    </div>
                                                    {{--<div class="form-group">--}}
                                                        {{--<label class="control-label">Date of Birth <span class="required" aria-required="true">*</span></label>--}}
                                                        {{--{{ Form::text('date_of_birth', $user->date_of_birth ? date('m/d/Y', strtotime(isset($user) ? $user->date_of_birth : '')) : '',--}}
                                                        {{--['class' => 'form-control date-picker', 'readonly' => true])--}}
                                                        {{--}}--}}
                                                    {{--</div>--}}
                                                    <div class="margiv-top-10">
                                                        <button type="submit" class="btn green"> Save Changes </button>
                                                    </div>
                                                {!! Form::close() !!}
                                            </div>
                                            <!-- END PERSONAL INFO TAB -->

                                            <!-- CHANGE AVATAR TAB -->

                                            <div class="tab-pane" id="tab_avatar">
                                                <div class="row">
                                                    <div class="col-md-4 text-center">
                                                        <div id="uploaded-avatar"
                                                             data-avatar_available="{{ $user->avatar ? '1' : '0' }}"
                                                             data-avatar_user="{{ url('uploads/avatars/' . $user->avatar) }}"
                                                             data-avatar_default="{{ admin_asset('assets/pages/media/profile/profile_user.jpg') }}"
                                                        >
                                                            {{--<img src="{{ admin_asset('assets/pages/media/profile/profile_user.jpg') }}" id="uploaded-avatar" style="width:350px;"/>--}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" style="padding-top:98px;">
                                                        <strong>Select Image:</strong>
                                                        <br/>
                                                        <input type="file" id="upload">
                                                        <br/>
                                                        <button class="btn btn-success upload-result">Upload Image</button>
                                                    </div>

                                                    <div class="col-md-4" style="">

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END CHANGE AVATAR TAB -->

                                            <!-- CHANGE PASSWORD TAB -->
                                            <div class="tab-pane" id="tab_password">
                                                <div id="password-form-errors"></div>
                                                {!! Form::model($user, ['method' => 'PUT', 'route' => ['account-password-update.user', $user->id], 'id' => 'password-change-form']) !!}
                                                    <div class="form-group">
                                                        <label class="control-label">Current Password</label>
                                                        {{ Form::password('password_current', ['class' => 'form-control', 'id' => 'password_current']) }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">New Password</label>
                                                        {{ Form::password('password', ['class' => 'form-control', 'id' => 'password']) }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">New Password Confirmation</label>
                                                        {{ Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation']) }}
                                                    </div>
                                                    <div class="margin-top-10">
                                                        <a href="javascript:;" class="btn green" id="jq_change_password"> Change Password </a>
                                                        <a href="javascript:;" class="btn default"> Cancel </a>
                                                    </div>
                                                {!! Form::close() !!}
                                            </div>
                                            <!-- END CHANGE PASSWORD TAB -->
                                            <!-- PRIVACY SETTINGS TAB -->
                                            {{--<div class="tab-pane" id="tab_privacy">--}}
                                                {{--<form action="#">--}}
                                                    {{--<table class="table table-light table-hover">--}}
                                                        {{--<tr>--}}
                                                            {{--<td> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus.. </td>--}}
                                                            {{--<td>--}}
                                                                {{--<div class="mt-radio-inline">--}}
                                                                    {{--<label class="mt-radio">--}}
                                                                        {{--<input type="radio" name="optionsRadios1" value="option1" /> Yes--}}
                                                                        {{--<span></span>--}}
                                                                    {{--</label>--}}
                                                                    {{--<label class="mt-radio">--}}
                                                                        {{--<input type="radio" name="optionsRadios1" value="option2" checked/> No--}}
                                                                        {{--<span></span>--}}
                                                                    {{--</label>--}}
                                                                {{--</div>--}}
                                                            {{--</td>--}}
                                                        {{--</tr>--}}
                                                        {{--<tr>--}}
                                                            {{--<td> Enim eiusmod high life accusamus terry richardson ad squid wolf moon </td>--}}
                                                            {{--<td>--}}
                                                                {{--<div class="mt-radio-inline">--}}
                                                                    {{--<label class="mt-radio">--}}
                                                                        {{--<input type="radio" name="optionsRadios11" value="option1" /> Yes--}}
                                                                        {{--<span></span>--}}
                                                                    {{--</label>--}}
                                                                    {{--<label class="mt-radio">--}}
                                                                        {{--<input type="radio" name="optionsRadios11" value="option2" checked/> No--}}
                                                                        {{--<span></span>--}}
                                                                    {{--</label>--}}
                                                                {{--</div>--}}
                                                            {{--</td>--}}
                                                        {{--</tr>--}}
                                                        {{--<tr>--}}
                                                            {{--<td> Enim eiusmod high life accusamus terry richardson ad squid wolf moon </td>--}}
                                                            {{--<td>--}}
                                                                {{--<div class="mt-radio-inline">--}}
                                                                    {{--<label class="mt-radio">--}}
                                                                        {{--<input type="radio" name="optionsRadios21" value="option1" /> Yes--}}
                                                                        {{--<span></span>--}}
                                                                    {{--</label>--}}
                                                                    {{--<label class="mt-radio">--}}
                                                                        {{--<input type="radio" name="optionsRadios21" value="option2" checked/> No--}}
                                                                        {{--<span></span>--}}
                                                                    {{--</label>--}}
                                                                {{--</div>--}}
                                                            {{--</td>--}}
                                                        {{--</tr>--}}
                                                        {{--<tr>--}}
                                                            {{--<td> Enim eiusmod high life accusamus terry richardson ad squid wolf moon </td>--}}
                                                            {{--<td>--}}
                                                                {{--<div class="mt-radio-inline">--}}
                                                                    {{--<label class="mt-radio">--}}
                                                                        {{--<input type="radio" name="optionsRadios31" value="option1" /> Yes--}}
                                                                        {{--<span></span>--}}
                                                                    {{--</label>--}}
                                                                    {{--<label class="mt-radio">--}}
                                                                        {{--<input type="radio" name="optionsRadios31" value="option2" checked/> No--}}
                                                                        {{--<span></span>--}}
                                                                    {{--</label>--}}
                                                                {{--</div>--}}
                                                            {{--</td>--}}
                                                        {{--</tr>--}}
                                                    {{--</table>--}}
                                                    {{--<!--end profile-settings-->--}}
                                                    {{--<div class="margin-top-10">--}}
                                                        {{--<a href="javascript:;" class="btn red"> Save Changes </a>--}}
                                                        {{--<a href="javascript:;" class="btn default"> Cancel </a>--}}
                                                    {{--</div>--}}
                                                {{--</form>--}}
                                            {{--</div>--}}
                                            <!-- END PRIVACY SETTINGS TAB -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END PROFILE CONTENT -->
                </div>
            </div>
        </div>
        <!-- END CONTENT BODY -->
    </div>
@stop


@section('foot_page_level_plugins')
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/jquery.sparkline.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <script src="{{ admin_asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/clockface/js/clockface.js') }}" type="text/javascript"></script>
@stop


@section('foot_page_level_scripts')
    <script src="{{ admin_asset('assets/pages/scripts/profile.min.js') }}" type="text/javascript"></script>
@stop


@section('foot_resources')
    <script src="{{ admin_asset('assets/custom/croppie/croppie.min.js') }}" type="text/javascript"></script>
    <script>
        $(function () {
            $('body').addClass('page-container-bg-solid');

            if (jQuery().datepicker) {
                $('.date-picker').datepicker({
                    rtl: App.isRTL(),
                    format: 'mm/dd/yyyy',
                    orientation: "left",
                    autoclose: true
                });
            }

            load_croppie();

            $('#jq_change_password').on('click', function (e) {
                e.preventDefault();
                var data = {
                    password_current: $('#password_current').val(),
                    password: $('#password').val(),
                    password_confirmation: $('#password_confirmation').val()
                };
                var user_id = '{{ \Auth::user()->id }}';

                main.adminAjax('account/account-password-update/' + user_id, 'PUT', data, function (response) {
                    console.log(response);
                    if(response.success) {
                        return window.location = main.adminUrl('account/profile');
                    }
                }, function(data) {
                    // Log in the console
                    var errors = data.responseJSON.errors;
                    //console.log(errors);

                    // or, what you are trying to achieve
                    // render the response via js, pushing the error in your
                    // blade page
                    errorsHtml = '<div class="alert alert-danger"><ul>';

                    $.each( errors, function( key, value ) {
                        errorsHtml += '<li>'+ value + '</li>'; //showing only the first error.
                    });
                    errorsHtml += '</ul></di>';

                    $( '#password-form-errors' ).html( errorsHtml ); //appending to a <div id="form-errors"></div> inside form
                }, function(){}, function() {}, {dataType: 'json'});

            });
        });

        function load_croppie() {
            var uploadedAvatar = $('#uploaded-avatar');
            var avatar_default = uploadedAvatar.data('avatar_default');
            var avatar_user = uploadedAvatar.data('avatar_user');
            var avatar = avatar_default;

            if (uploadedAvatar.data('avatar_available') == '1') {
                avatar = avatar_user;
            }

            // console.log(uploadedAvatar.data('avatar_available'));
            // console.log(avatar_default, avatar_user, avatar);

            // destroy croppie instance first
            uploadedAvatar.croppie('destroy');

            avatar_croppie = uploadedAvatar.croppie({
                enableExif: true,
                viewport: {
                    width: 200,
                    height: 200,
                    type: 'circle'
                },
                boundary: {
                    width: 300,
                    height: 300
                }
            });

            avatar_croppie.croppie('bind', {url: avatar});

            $('#upload').on('change', function () {
                var reader = new FileReader();
                reader.onload = function (e) {
                    avatar_croppie.croppie('bind', {
                        url: e.target.result
                    }).then(function () {
                        console.log('jQuery bind complete');
                    });
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('.upload-result').on('click', function (ev) {
                avatar_croppie.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (resp) {

                    html = '<img src="' + resp + '" />';
                    $(".profile-userpic").html($(html).addClass('img-responsive'));

                    var top_bar_html = '<img src="' + resp + '" class="img-circle" />';
                    top_bar_html += '<span class="username username-hide-on-mobile"> {{ \Auth::user()->name }} </span>';
                    top_bar_html += '<i class="fa fa-angle-down"></i>';
                    $("#top_bar_avatar").html($(top_bar_html));

                    $.ajax({
                        url: main.adminUrl('account/avatar'),
                        type: "POST",
                        data: {"avatar": resp},
                        dataType: 'json',
                        success: function (data) {
                            //console.log(data);
                            if (data.status) {
                                $(".profile-userpic").html(html);
                                $("#top_bar_avatar").html(top_bar_html);
                            }

                            swal({
                                title: 'Avatar has been uploaded successfully.',
                                type: 'success'
                            });
                        },
                        error: function () {
                            swal({
                                title: 'Something went wrong, try again.',
                                type: 'info'
                            });
                        }
                    });

                });
            });
        }
    </script>
@stop
