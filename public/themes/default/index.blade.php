<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="Keywords" content="{{ $keywords }}"/>
    <meta name="Description" content="{{ $description }}"/>
    <!-- TemplateBeginEditable name="doctitle" -->
    <title>{{ $page_title }}</title>
    <!-- TemplateEndEditable -->
    <!-- TemplateBeginEditable name="head" -->
    <!-- TemplateEndEditable -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link href="{{ $ecs_css_path }}" rel="stylesheet" type="text/css"/>
    <link rel="alternate" type="application/rss+xml" title="RSS|{{ $page_title }}" href="{{ $feed_url }}"/>

    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
</head>

<body>
@include('web::library/page_header')
<div class="blank"></div>
<div class="block clearfix">
    <!--left start-->
    <div class="AreaL">
        <!--站内公告 start-->
        <div class="box">
            <div class="box_1">
                <h3><span>{{ $lang['shop_notice'] }}</span></h3>
                <div class="boxCenterList RelaArticle">
                    {{ $shop_notice }}
                </div>
            </div>
        </div>
        <div class="blank5"></div>
        <!--站内公告 end-->
        <!-- TemplateBeginEditable name="左边区域" -->
        @include('web::library/cart')
        @include('web::library/category_tree')
        @include('web::library/top10')
        @include('web::library/promotion_info')
        @include('web::library/order_query')
        @include('web::library/invoice_query')
        @include('web::library/vote_list')
        @include('web::library/email_list')
        <!-- TemplateEndEditable -->

    </div>
    <!--left end-->
    <!--right start-->
    <div class="AreaR">
        <!--焦点图和站内快讯 START-->
        <div class="box clearfix">
            <div class="box_1 clearfix">
                <div class="f_l" id="focus">
                    @include('web::library/index_ad')
                </div>
                <!--news-->
                <div id="mallNews" class="f_r">
                    <div class="NewsTit"></div>
                    <div class="NewsList tc">
                        <!-- TemplateBeginEditable name="站内快讯上广告位（宽：210px）" -->
                        <!-- TemplateEndEditable -->
                        @include('web::library/new_articles')
                    </div>
                </div>
                <!--news end-->
            </div>
        </div>
        <div class="blank5"></div>
        <!--焦点图和站内快讯 END-->
        <!--今日特价，品牌 start-->
        <div class="clearfix">
            <!--特价-->
            @include('web::library/recommend_promotion')
            <!--品牌-->
            <div class="box f_r brandsIe6">
                <div class="box_1 clearfix" id="brands">
                    @include('web::library/brands')
                </div>
            </div>
        </div>
        <div class="blank5"></div>
        <!-- TemplateBeginEditable name="右边主区域" -->
        @include('web::library/recommend_best')
        @include('web::library/recommend_new')
        @include('web::library/recommend_hot')
        @include('web::library/auction')
        @include('web::library/group_buy')
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
