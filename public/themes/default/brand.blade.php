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
        <div class="box">
            <div class="box_1">
                <h3><span>{{ $brand['brand_name'] }}</span></h3>
                <div class="boxCenterList">
                    <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                        <tr>
                            <td bgcolor="#ffffff" width="200" align="center" valign="middle">
                                <div style="width:200px; overflow:hidden;">
                                    @if($brand['brand_logo'])
                                        <img src="data/brandlogo/{{ $brand['brand_logo'] }}"/>
                                    @endif
                                </div>
                            </td>
                            <td bgcolor="#ffffff">
                                {$brand.brand_desc|nl2br}<br/>
                                @if($brand['site_url'])
                                    {{ $lang['official_site'] }} <a href="{{ $brand['site_url'] }}" target="_blank"
                                                                    class="f6">{{ $brand['site_url'] }}</a><br/>
                                @endif
                                {{ $lang['brand_category'] }}<br/>
                                <div class="brandCategoryA">
                                    @foreach($brand_cat_list as $cat)
                                        <a href="{{ $cat['url'] }}" class="f6">{{ $cat['cat_name'] }}
                                            @if($cat['goods_count'])
                                                ({{ $cat['goods_count'] }})
                                            @endif</a>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="blank5"></div>

        <!-- TemplateBeginEditable name="右边区域" -->
        @include('web::library/recommend_best')
        @include('web::library/goods_list')
        @include('web::library/pages')
        <!-- TemplateEndEditable -->


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
