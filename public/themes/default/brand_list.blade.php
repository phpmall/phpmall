<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="Keywords" content="{{ $keywords }}"/>
    <meta name="Description" content="{{ $description }}"/>
    <title>{{ $page_title }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link href="{{ $ecs_css_path }}" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/global.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/compare.js') }}"></script>
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
<div class="block clearfix">
    <!--left start-->
    <div class="AreaL">
        <!-- TemplateBeginEditable name="左边区域" -->
        @include('web::library/cart')
        @include('web::library/category_tree')
        @include('web::library/filter_attr')
        @include('web::library/price_grade')
        <!-- TemplateEndEditable -->
        <!-- TemplateBeginEditable name="左边广告区域（宽200px）" -->
        <!-- TemplateEndEditable -->
        <!--AD end-->
        @include('web::library/history')
    </div>
    <!--left end-->
    <!--right start-->
    <div class="AreaR">
        <!-- TemplateBeginEditable name="通栏广告区域（宽750px）" -->
        <!-- TemplateEndEditable -->
        <div class="blank5"></div>
        <h3 class="border"><span>{{ $lang['all_brand'] }}</span></h3>
        <div id="brandList" class="clearfix">
            @foreach($brand_list as $brand_data)
                <div class="brandBox">
                    <h4><span>{{ $brand_data['brand_name'] }}</span>({{ $brand_data['goods_num'] }})</h4>
                    @if($brand_data['brand_logo'])
                        <div class="brandLogo">
                            <a href="{{ $brand_data['url'] }}"><img src="data/brandlogo/{{ $brand_data['brand_logo'] }}"
                                                                    alt="{{ $brand_data['brand_name'] }} ({{ $brand_data['goods_num'] }})"/></a>
                        </div>
                    @endif
                    <p title="{{ $brand_data['brand_desc'] }}">{{ $brand_data['brand_desc'] }}</p>
                </div>
            @endforeach

        </div>
        <div class="blank5"></div>
        <div class="dashed"></div>
    </div>
    <!--right end-->
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
