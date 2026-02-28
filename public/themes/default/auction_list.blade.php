<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
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
        @include('web::library/goods_related')
        @include('web::library/goods_fittings')
        @include('web::library/goods_article')
        @include('web::library/goods_attrlinked')
        <!-- TemplateEndEditable -->
        <!-- TemplateBeginEditable name="左边广告区域（宽200px）" -->
        <!-- TemplateEndEditable -->
        <!--AD end-->
        @include('web::library/history')
    </div>
    <!--left end-->
    <!--right start-->
    <div class="AreaR">
        <!-- TemplateBeginEditable name="右边通栏广告（宽750px）" -->
        <!-- TemplateEndEditable -->
        <div class="blank5"></div>
        <div class="box">
            <div class="box_1">
                <h3><span>{{ $lang['auction_goods'] }}</span></h3>
                <div class="boxCenterList">
                    <!-- @if($auction_list)
                        如果有拍卖活动 -->
                        <!-- @foreach($auction_list as $auction)
                            循环拍卖活动开始 -->
                            <ul class="group clearfix">
                                <li style="margin-right:8px; text-align:center;">
                                    <a href="{{ $auction['url'] }}"><img src="{{ $auction['goods_thumb'] }}" border="0"
                                                                         alt="{{ $auction['goods_name'] }}"
                                                                         style="vertical-align: middle"/></a>
                                </li>
                                <li style="width:555px; line-height:23px;">
                                    {{ $lang['goods_name'] }}：<a href="{{ $auction['url'] }}"
                                                                 class="f5">{{ $auction['goods_name'] }}</a><br/>
                                    {{ $lang['act_status'] }}：
                                    @if($auction['status_no'] == 0)
                                        {{ $lang['au_pre_start'] }}<br>
                                    @elseif($auction['status_no'] == 1)
                                        {{ $lang['au_under_way_1'] }}<br>
                                    @else
                                        {{ $lang['au_finished'] }}<br>
                                    @endif
                                    {{ $lang['au_start_price'] }}：{{ $auction['formated_start_price'] }}<br>
                                    @if($auction['end_price'] > 0)
                                        {{ $lang['au_end_price'] }}：{{ $auction['formated_end_price'] }}
                                    @endif
                                </li>
                            </ul>
                        @endforeach
                    @else
                        <span
                            style="margin:2px 10px; font-size:14px; line-height:36px;">{{ $lang['no_auction'] }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="blank5"></div>
        @include('web::library/pages')
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
