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
                <h3><span>{{ $lang['gb_goods_name'] }}</span></h3>
                <div class="boxCenterList">
                    <!-- @if($gb_list)
                        如果有团购活动 -->
                        <!-- @foreach($gb_list as $group_buy)
                            循环团购活动开始 -->
                            <ul class="group clearfix">
                                <li style="margin-right:8px; text-align:center;">
                                    <a href="{{ $group_buy['url'] }}"><img src="{{ $group_buy['goods_thumb'] }}"
                                                                           border="0"
                                                                           alt="{{ $group_buy['goods_name'] }}"
                                                                           style="vertical-align: middle"/></a>
                                </li>
                                <li style="width:555px; line-height:23px;">
                                    {{ $lang['gb_goods_name'] }}<a href="{{ $group_buy['url'] }}"
                                                                   class="f5">{{ $group_buy['goods_name'] }}</a><br/>
                                    {{ $lang['act_time'] }}：{{ $group_buy['formated_start_date'] }}
                                    -- {{ $group_buy['formated_end_date'] }}<br/>
                                    {{ $lang['gb_price_ladder'] }}<br/>
                                    <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                        <tr>
                                            <th width="29%" bgcolor="#FFFFFF">{{ $lang['gb_ladder_amount'] }}</th>
                                            <th width="71%" bgcolor="#FFFFFF">{{ $lang['gb_ladder_price'] }}</th>
                                        </tr>
                                        @foreach($group_buy['price_ladder'] as $item)
                                            <tr>
                                                <td width="29%" bgcolor="#FFFFFF">{{ $item['amount'] }}</td>
                                                <td width="71%" bgcolor="#FFFFFF">{{ $item['formated_price'] }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </li>
                            </ul>
                        @endforeach
                    @else
                        <span
                            style="margin:2px 10px; font-size:14px; line-height:36px;">{{ $lang['group_goods_empty'] }}</span>
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
