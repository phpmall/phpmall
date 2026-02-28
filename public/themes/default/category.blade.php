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
    @if($cat_style)
        <link href="{{ $cat_style }}" rel="stylesheet" type="text/css"/>
    @endif

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
        @include('web::library/history')
        <!-- TemplateEndEditable -->
        <!-- TemplateBeginEditable name="左边广告区域（宽200px）" -->
        <!-- TemplateEndEditable -->
        <!--AD end-->
    </div>
    <!--left end-->
    <!--right start-->
    <div class="AreaR">
        <!--组合搜索 开始-->
        @if($brands['1'] || $price_grade['1'] || $filter_attr_list)
            <div class="box">
                <div class="box_1">
                    <h3><span>{{ $lang['goods_filter'] }}</span></h3>
                    @if($brands['1'])
                        <div class="screeBox">
                            <strong>{{ $lang['brand'] }}：</strong>
                            @foreach($brands as $brand)
                                @if($brand['selected'])
                                    <span>{{ $brand['brand_name'] }}</span>
                                @else
                                    <a href="{{ $brand['url'] }}">{{ $brand['brand_name'] }}</a>&nbsp;
                                @endif
                            @endforeach
                        </div>
                    @endif
                    @if($price_grade['1'])
                        <div class="screeBox">
                            <strong>{{ $lang['price'] }}：</strong>
                            @foreach($price_grade as $grade)
                                @if($grade['selected'])
                                    <span>{{ $grade['price_range'] }}</span>
                                @else
                                    <a href="{{ $grade['url'] }}">{{ $grade['price_range'] }}</a>&nbsp;
                                @endif
                            @endforeach
                        </div>
                    @endif
                    @foreach($filter_attr_list as $filter_attr)
                        <div class="screeBox">
                            <strong>{{ $filter_attr['filter_attr_name'] }} :</strong>
                            @foreach($filter_attr['attr_list'] as $attr)
                                @if($attr['selected'])
                                    <span>{{ $attr['attr_value'] }}</span>
                                @else
                                    <a href="{{ $attr['url'] }}">{{ $attr['attr_value'] }}</a>&nbsp;
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="blank5"></div>
        @endif
        <!--组合搜索 结束-->
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
