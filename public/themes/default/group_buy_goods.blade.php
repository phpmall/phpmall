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
                <h3><span>{{ $lang['groupbuy_goods_info'] }}</span></h3>
                <div class="boxCenterList">
                    <ul class="group clearfix">
                        <li style="margin-right:8px; text-align:center;">
                            <a href="{{ $gb_goods['url'] }}"><img src="{{ $gb_goods['goods_thumb'] }}"
                                                                  alt="{{ $gb_goods['goods_name'] }}"/></a>
                        </li>
                        <li style="width:555px; line-height:23px;">
                            {{ $lang['gb_goods_name'] }} <font class="f5">{{ $gb_goods['goods_name'] }}</font><br>
                            @if($cfg['show_goodssn'] && 0)
                                {{ $lang['goods_sn'] }} {{ $gb_goods['goods_sn'] }}<br>
                            @endif
                            @if($cfg['goods']['brand_name'] && $show_brand && 0)
                                {{ $lang['goods_brand'] }} {{ $gb_goods['brand_name'] }}<br>
                            @endif
                            @if($cfg['show_goodsweight'] && 0)
                                {{ $lang['goods_weight'] }} {{ $gb_goods['goods_weight'] }}<br>
                            @endif
                            {{ $lang['act_time'] }}：{{ $group_buy['formated_start_date'] }}
                            -- {{ $group_buy['formated_end_date'] }}<br>
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
                            <!-- @if($group_buy['deposit'] > 0)
                                保证金额-->
                                {{ $lang['gb_deposit'] }} {{ $group_buy['formated_deposit'] }}<br/>
                            @endif

                            <!--@if($group_buy['restrict_amount'] > 0)
                                限购数量-->
                                {{ $lang['gb_restrict_amount'] }} {{ $group_buy['restrict_amount'] }}<br/>
                            @endif

                            <!--@if($group_buy['gift_integral'] > 0)
                                送积分-->
                                {{ $lang['gb_gift_integral'] }} {{ $group_buy['gift_integral'] }}<br/>
                            @endif

                            <!-- @if($group_buy['status'] == 0)
                                未开始 -->
                                {{ $lang['gbs_pre_start'] }}
                                <!--

                            @elseif($group_buy['status'] == 1)
                                进行中 -->
                                <font class="f4">{{ $lang['gbs_under_way'] }}
                                    <span id="leftTime">{{ $lang['please_waiting'] }}</span></font><br/>
                                {{ $lang['gb_cur_price'] }} {{ $group_buy['formated_cur_price'] }}<br/>
                                {{ $lang['gb_valid_goods'] }} {{ $group_buy['valid_goods'] }}<br/>
                                <!--

                            @elseif($group_buy['status'] == 2)
                                已结束 -->
                                {{ $lang['gbs_finished'] }} {{ $lang['gb_cur_price'] }} {{ $group_buy['formated_cur_price'] }} {{ $lang['gb_valid_goods'] }} {{ $group_buy['valid_goods'] }}
                                <!--

                            @elseif($group_buy['status'] == 3)
                                团购成功 -->
                                {{ $lang['gbs_succeed'] }}
                                {{ $lang['gb_final_price'] }} {{ $group_buy['formated_trans_price'] }}<br/>
                                {{ $lang['gb_final_amount'] }} {{ $group_buy['trans_amount'] }}
                                <!--

                            @elseif($group_buy['status'] == 4)
                                团购失败 -->
                                {{ $lang['gbs_fail'] }}
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="blank5"></div>
        <div class="box">
            <div class="box_1">
                <h3><span>{{ $lang['properties'] }}</span></h3>
                <div class="boxCenterList">
                    <form action="group_buy.php?act=buy" method="post" name="ECS_FORMBUY" id="ECS_FORMBUY">
                        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                            <!-- @foreach($specification as $spec => $spec_key)
                                循环规格开始 -->
                                <tr>
                                    <td width="22%" bgcolor="#FFFFFF">{{ $spec['name'] }}</td>
                                    <td width="78%" bgcolor="#FFFFFF">
                                        <!-- @if($cfg['goodsattr_style'] == 1)
                                            规格显示方式：单选按钮 -->
                                            @foreach($spec['values'] as $value => $key)
                                                <label for="spec_value_{{ $value['id'] }}">
                                                    <input type="radio" name="spec_{{ $spec_key }}"
                                                           value="{{ $value['id'] }}" id="spec_value_{{ $value['id'] }}"
                                                           @if($key == 0)checked@endif />
                                                    {{ $value['label'] }} </label>
                                            @endforeach
                                            <!--

                                        @else
                                            规格显示方式：下拉列表 -->
                                            <select name="spec_{{ $spec_key }}" style="border:1px solid #ccc;">
                                                @foreach($spec['values'] as $value => $key)
                                                    <option label="{{ $value['label'] }}"
                                                            value="{{ $value['id'] }}">{{ $value['label'] }} </option>
                                                @endforeach
                                            </select>
                                            <!--

                                        @endif 规格显示方式 -->
                                    </td>
                                </tr>
                                <!--

                            @endforeach 循环规格结束 -->
                            <!-- @if($smarty['session']['user_id'] > 0)
                                如果登录了，显示购买按钮 -->
                                <tr>
                                    <td bgcolor="#FFFFFF"><strong>{{ $lang['number'] }}:</strong></td>
                                    <td bgcolor="#FFFFFF">
                                        <input name="number" type="text" class="inputBg" id="number" value="1"
                                               size="4"/>
                                        <input type="hidden" name="group_buy_id"
                                               value="{{ $group_buy['group_buy_id'] }}"/>
                                        <input type="image" src="images/bnt_buy_1.gif" style="vertical-align:middle;"/>
                                    </td>
                                </tr>
                                <!--

                            @else
                                如果没有登录，显示提示信息 -->
                                <tr>
                                    <td colspan="2" align="right" bgcolor="#FFFFFF"><br/>
                                        <font class="f_red">{{ $lang['gb_notice_login'] }}</font></td>
                                </tr>
                                <!--

                            @endif 判断登录结束 -->
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <div class="blank5"></div>
        <div class="box">
            <div class="box_1">
                <h3><span>{{ $lang['groupbuy_intro'] }}</span></h3>
                <div class="boxCenterList">
                    {{ $group_buy['group_buy_desc'] }}
                </div>
            </div>
        </div>
    </div>
    <!--right end-->
</div>
<div class="blank"></div>
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
    var gmt_end_time = "{{ $group_buy['gmt_end_date'] ?? 0 }}";
    @foreach($lang['goods_js'] as $item => $key)
    var {{ $key }} = "{{ $item }}";
    @endforeach
    var now_time = {{ $now_time }};
    <!--  -->

    onload = function () {
        try {
            onload_leftTime();
        } catch (e) {
        }
    }
    <!--  -->
</script>
</html>
