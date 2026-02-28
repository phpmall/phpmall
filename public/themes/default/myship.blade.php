<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="Keywords" content="{{ $keywords }}"/>
    <meta name="Description" content="{{ $description }}"/>
    <title>{{ $page_title }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link href="{{ $ecs_css_path }}" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/myship.js') }}"></script>
</head>

<body>
@include('web::library/page_header')
<!--当前位置 start-->
<div class="block box">
    <div id="ur_here">
        @include('web::library/ur_here')
    </div>
</div>
<!--当前位置 end-->
<div class="blank"></div>
<div class="block">
    <h5><span>{{ $lang['shipping_method'] }}</span></h5>
    <div class="blank"></div>
    <!-- 开始收货人信息填写界面 -->
    <script type="text/javascript" src="{{ asset('js/region.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
    <script type="text/javascript">
        region.isAdmin = false;
        @foreach($lang['flow_js'] as $item => $key)
        var {{ $key }} = "{{ $item }}";
        @endforeach


            onload = function () {
            if (!document.all) {
                document.forms['theForm'].reset();
            }
        }

        /* *
         * 检查收货地址信息表单中填写的内容
         */
        function checkForm(frm) {
            var msg = new Array();
            var err = false;

            if (frm.elements['country'].value == 0) {
                msg.push(country_not_null);
                err = true;
            }

            if (frm.elements['province'].value == 0 && frm.elements['province'].length > 1) {
                err = true;
                msg.push(province_not_null);
            }

            if (frm.elements['city'].value == 0 && frm.elements['city'].length > 1) {
                err = true;
                msg.push(city_not_null);
            }

            if (frm.elements['district'].length > 1) {
                if (frm.elements['district'].value == 0) {
                    err = true;
                    msg.push(district_not_null);
                }
            }

            if (err) {
                message = msg.join("\n");
                alert(message);
            }
            return !err;
        }


    </script>
    <form action="myship.php" method="post" name="theForm" id="theForm" onsubmit="return checkForm(this)">
        @include('web::Library/myship')
    </form>
</div>
<div class="blank5"></div>
<!--帮助-->
<div class="block">
    <div class="box">
        <div class="helpTitBg clearfix">
            @include('web::library/help')
        </div>
    </div>
</div>
<div class="blank"></div>
<!--帮助-->
@include('web::library/page_footer')
</body>

</html>
