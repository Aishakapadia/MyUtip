<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MYUTIP</title>
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
    <link href="" rel="shortcut icon" type="image/x-icon">
    <meta name="description" content=" "/>
    <style>
        .h-img{padding: 40px 0 30px 64px; border-bottom: solid 3px #2b3643;}
        .c-team,.c-title,.nc-m-wrap p{color:#000;font-size:18px}.centerd-heading2,.footer{text-align:center}.centerd-heading2 h2,.footer-links li,.social ul li{display:inline-block}body,html{font-family:'Open Sans',sans-serif;margin:0 auto;padding:0}.n-wrap{max-width:1027px;margin:0 auto;background:#fff;min-height:800px;border-bottom:20px solid #2b3643;box-sizing:border-box;-webkit-box-sizing:border-box;box-shadow:3px 3px 32px rgba(0,0,0,.08),3px -3px 32px rgba(0,0,0,.08)}.etc-msg p,.j-us,.nc-m-wrap p{margin-top:0;margin-bottom:0}.nc-wrap{padding-left:45px;padding-right:45px;padding-top:60px}.nc-m-wrap{padding-left:25px;padding-right:25px}.c-title{padding-bottom:50px}.nc-m-wrap p{padding-bottom:10px}.c-team{padding-bottom:50px;padding-top:40px}.etc-msg,.etc-msg p,.j-us{font-size:14px;color:#a39f9e}.etc-msg p{padding-bottom:10px}.mfmt{padding-top:25px}.footer,.j-us{padding-top:40px}.footer{border-top:1px solid #f2f2f2;margin-top:40px;padding-bottom:30px}.footer p{color:#000;text-transform:uppercase}.footer p a{color:#2b3643;text-decoration:none}a{text-decoration:none!important}.centerd-heading2 h2{font-size:52px;line-height:55px;color:#2b3643;position:relative;border-bottom:1px solid #2b3643;margin-top:0;margin-bottom:20px;font-weight:300;padding-bottom:15px;padding-left:30px;padding-right:30px}.social ul li{padding:5px 15px}.social ul li:first-child{padding-left:10px}.footer-links{padding-top:5px}.footer-links ul{padding:0}.footer-links li a,.footer-links li span{color:#000;text-transform:uppercase;font-size:12px;padding:5px}.oringe-text{color:#f1a226}
    </style>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
</head>
<body>

<div class="nbg">
    <div class="n-wrap">

        <div class="h-img" style="background: #364150">
            <a href="{{ url('/') }}">
                <img src="{{ url('delivery-truck_128_128.png') }}" alt="" />
                <strong style="font-size: 33px; top: -46px; position: relative; left: 32px; color: #ffffff;">
                    UNILEVER TRANSPORT INBOUND PORTAL
                </strong>
            </a>
        </div>


        <div class="nc-wrap">
            <div class="nc-m-wrap">

                @yield('content')
                <div class="c-team">{!! $settings['site_name'] !!} TEAM</div>
            </div>
        </div>


        <div class="footer">
            <div class="container">
                <div class="centerd-heading2">
                    <h2>Follow us</h2>
                </div>
                <div class="social">
                    <ul>
                        <li><a href="#"><img src="{{ admin_asset('social_images/linkdin.png') }}" alt="" class="img-responsive"></a></li>
                        <li><a href="#"><img src="{{ admin_asset('social_images/tw.png') }}" alt="" class="img-responsive"></a></li>
                        <li><a href="#"><img src="{{ admin_asset('social_images/fb.png') }}" alt="" class="img-responsive"></a></li>
                        <li><a href="#"><img src="{{ admin_asset('social_images/insta.png') }}" alt="" class="img-responsive"></a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <ul>
                        <li><a href="http://www.myutip.com/">Home</a> <span> | </span></li>
                        <li><a href="http://www.myutip.com/panel/dashbaord">Dashboard</a> <span> | </span></li>
                        <li><a href="http://www.myutip.com/contact">Contact Us</a></li>
                    </ul>
                </div>
                <div class="copyrights">
                    <p>&copy;
                        {{--<script>new Date().getFullYear() > 2017 && document.write(new Date().getFullYear());</script>--}}
                        {{ date('Y') }} - <a href="{{ url('/') }}">{!! $settings['site_name'] !!}.</a> ALL RIGHTS RESERVED.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
