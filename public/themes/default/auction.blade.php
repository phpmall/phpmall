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
    <script type="text/javascript" src="{{ asset('js/lefttime.js') }}"></script>
    <script type="text/javascript">
        @foreach($lang['js_languages'] as $item => $key)
        var {{ $key }} = "{{ $item }}";
        @endforeach
    </script>
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
                <h3><span>{{ $lang['auction_goods_info'] }}</span></h3>
                <div class="boxCenterList">
                    <ul class="group clearfix">
                        <li style="margin-right:8px; text-align:center;">
                            <a href="{{ $auction_goods['url'] }}"><img src="{{ $auction_goods['goods_thumb'] }}"
                                                                       alt="{{ $auction_goods['goods_name'] }}"/></a>
                        </li>
                        <li style="width:555px; line-height:23px;">
                            <form name="theForm" action="auction.php" method="post">
                                {{ $lang['goods_name'] }}：<font
                                    class="f5">{{ $auction['goods_name'] }}</font>@if($auction['product_id'] > 0)
                                    &nbsp;[{{ $products_info }}]
                                @endif<br>
                                {{ $lang['au_current_price'] }}：{{ $auction['formated_current_price'] }}<br>
                                起止时间：{{ $auction['start_time'] }} -- {{ $auction['end_time'] }}<br>
                                {{ $lang['au_start_price'] }}：{{ $auction['formated_start_price'] }}<br>
                                {{ $lang['au_amplitude'] }}：{{ $auction['formated_amplitude'] }}<br>
                                @if($auction['end_price'] > 0)
                                    {{ $lang['au_end_price'] }}：{{ $auction['formated_end_price'] }}<br>
                                @endif
                                @if($auction['deposit'] > 0)
                                    {{ $lang['au_deposit'] }}：{{ $auction['formated_deposit'] }}<br>
                                @endif
                                <!-- @if($auction['status_no'] == 0)
                                    未开始 -->
                                    {{ $lang['au_pre_start'] }}
                                    <!--

                                @elseif($auction['status_no'] == 1)
                                    进行中 -->
                                    <font class="f4">{{ $lang['au_under_way'] }}<span
                                            id="leftTime">{{ $lang['please_waiting'] }}</span></font><br/>
                                    {{ $lang['au_i_want_bid'] }}：
                                    <input name="price" type="text" class="inputBg" id="price" size="8"/>
                                    <input name="bid" type="submit" class="bnt_blue" id="bid"
                                           value="{{ $lang['button_bid'] }}" style="vertical-align:middle;"/>
                                    <input name="act" type="hidden" value="bid"/>
                                    <input name="id" type="hidden" value="{{ $auction['act_id'] }}"/><br/>
                                    <!--

                                @else
                                    已结束 -->
                                    @if($auction['is_winner'])
                                        <span class="f_red">{{ $lang['au_is_winner'] }}</span><br/>
                                        <input name="buy" type="submit" class="bnt_blue_1"
                                               value="{{ $lang['button_buy'] }}"/>
                                        <input name="act" type="hidden" value="buy"/>
                                        <input name="id" type="hidden" value="{{ $auction['act_id'] }}"/>
                                    @else
                                        {{ $lang['au_finished'] }}
                                    @endif
                                @endif
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="blank5"></div>
        <div class="box">
            <div class="box_1">
                <h3><span>{{ $lang['activity_intro'] }}</span></h3>
                <div class="boxCenterList">
                    {$auction.act_desc|escape:html|nl2br}
                </div>
            </div>
        </div>
        <div class="blank5"></div>
        <div class="box">
            <div class="box_1">
                <h3><span>{{ $lang['bid_record'] }}</span></h3>
                <div class="boxCenterList">
                    <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                        <tr>
                            <th align="center" bgcolor="#ffffff">{{ $lang['au_bid_user'] }}</th>
                            <th align="center" bgcolor="#ffffff">{{ $lang['au_bid_price'] }}</th>
                            <th align="center" bgcolor="#ffffff">{{ $lang['au_bid_time'] }}</th>
                            <th align="center" bgcolor="#ffffff">{{ $lang['au_bid_status'] }}</th>
                        </tr>
                        @forelse($auction_log as $log)
                            <tr>
                                <td align="center" bgcolor="#ffffff">{{ $log['user_name'] }}</td>
                                <td align="center" bgcolor="#ffffff">{{ $log['formated_bid_price'] }}</td>
                                <td align="center" bgcolor="#ffffff">{{ $log['bid_time'] }}</td>
                                <td align="center" bgcolor="#ffffff">'.($loop->fe_bid_log.first ?
                                    ' {{ $lang['au_bid_ok'] }}' : '&nbsp;').'
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" align="center" bgcolor="#ffffff">{{ $lang['no_bid_log'] }}</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
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
<script type="text/javascript">
    var gmt_end_time = "{{ $auction['gmt_end_time'] ?? 0 }}";
    @foreach($lang['goods_js'] as $item => $key)
    var {{ $key }} = "{{ $item }}";
    var now_time = {{ $now_time }};
    @endforeach
    <!--  -->

    onload = function () {
        try {
            onload_leftTime(now_time);
        } catch (e) {
        }
    }
    <!--  -->
</script>
</html>
