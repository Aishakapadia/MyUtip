<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {!! $settings['site_name'] !!}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="Preview page of Metronic Admin Theme #1 for statistics, charts, recent events and reports" name="description" />
    <meta content="" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    @yield('head_page_level_plugins')
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{ admin_asset('assets/global/css/components.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ admin_asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->

    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="{{ admin_asset('assets/layouts/layout/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/layouts/layout/css/themes/darkblue.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
    <!-- END THEME LAYOUT STYLES -->

    @yield('head_resources')

    <!-- BEGIN THEME CUSTOM STYLES -->
    <link href="{{ admin_asset('assets/layouts/layout/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <!-- END THEME CUSTOM STYLES -->

    <link rel="shortcut icon" type="image/x-icon" href="{{ url('/favicon.ico') }}">
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white " data-url="{{ admin_url('/') }}">
<div class="page-wrapper">

    @include( admin_view('partials.header') )

    <!-- BEGIN HEADER & CONTENT DIVIDER -->
    <div class="clearfix"> </div>
    <!-- END HEADER & CONTENT DIVIDER -->

    <!-- BEGIN CONTAINER -->
    <div class="page-container">

        @include( admin_view('partials.sidebar') )

        <!-- BEGIN CONTENT -->
        @yield('content')
        <!-- END CONTENT -->

        {{--@include( admin_view('partials.quick_sidebar') )--}}

    </div>
    <!-- END CONTAINER -->

    @include( admin_view('partials.footer') )

</div>

{{--@include( admin_view('partials.quick_nav') )--}}

<!--[if lt IE 9]>
<script src="{{ admin_asset('assets/global/plugins/respond.min.js') }}"></script>
<script src="{{ admin_asset('assets/global/plugins/excanvas.min.js') }}"></script>
<script src="{{ admin_asset('assets/global/plugins/ie8.fix.min.js') }}"></script>
<![endif]-->

<!-- BEGIN CORE PLUGINS -->
<script src="{{ admin_asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
@yield('foot_page_level_plugins')
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{ admin_asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
@yield('foot_page_level_scripts')
<!-- END PAGE LEVEL SCRIPTS -->

<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="{{ admin_asset('assets/layouts/layout/scripts/layout.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/layouts/layout/scripts/demo.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
<script src="{{ admin_asset('assets/layouts/global/scripts/quick-nav.min.js') }}" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<!-- BEGIN KHALIL-CUSTOM SCRIPTS -->
<script src="{{ admin_asset('assets/custom/scripts/main.js') }}" type="text/javascript"></script>
<!-- END KHALIL-CUSTOM SCRIPTS -->

<script>
    $(document).ready(function()
    {
        $('#clickmewow').click(function()
        {
            $('#radio1003').attr('checked', 'checked');
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $( document ).ajaxStart(function() {
            console.log('show loading...');

             App.blockUI({
                 target: '#datatable_ajax',
                 animate: true
             });
        });

        $( document ).ajaxComplete(function( event, request, settings ) {
            console.log('stop loading...');

            App.unblockUI('#datatable_ajax');
        });

        //region Sidebar State Remember
        $('.sidebar-toggler').on('click', function() {
            if(!$('body').hasClass('page-sidebar-closed')) {
                main.setCookie("sidebar-closed", true, 200);
            } else {
                main.setCookie("sidebar-closed", false, 200);
            }
        });

        if(main.getCookie('sidebar-closed') == "false") {
            $('body').removeClass('page-sidebar-closed');
            $('.page-sidebar-menu').removeClass('page-sidebar-menu-closed');
        } else {
            $('body').addClass('page-sidebar-closed');
            $('.page-sidebar-menu').addClass('page-sidebar-menu-closed');
        }
        //endregion


    });
</script>

@yield('foot_resources')

</body>

</html>
