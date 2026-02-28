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
    <link rel="alternate" type="application/rss+xml" title="RSS|{{ $page_title }}" href="{{ $feed_url }}"/>

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
<!-- @if($cart_goods)
    如果有批发商品 -->
    <!-- 批发商品购物车 -->
    <div class="block">
        <h5><span>{{ $lang['wholesale_goods_cart'] }}</span></h5>
        <div class="blank"></div>
        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
            <tr>
                <th bgcolor="#ffffff">{{ $lang['goods_name'] }}</th>
                <th bgcolor="#ffffff">{{ $lang['goods_attr'] }}</th>
                <th bgcolor="#ffffff">{{ $lang['number'] }}</th>
                <th bgcolor="#ffffff">{{ $lang['ws_price'] }}</th>
                <th bgcolor="#ffffff">{{ $lang['ws_subtotal'] }}</th>
                <th bgcolor="#ffffff">{{ $lang['handle'] }}</th>
            </tr>
            <!-- @foreach($cart_goods as $key => $goods)
                循环批发商品开始 -->
                <tr>
                    <td bgcolor="#ffffff" align="center"><a href="{{ $goods['goods_url'] }}" target="_blank"
                                                            class="f6">{{ $goods['goods_name'] }}</a></td>
                    <td bgcolor="#ffffff" align="center">{{ $goods['goods_attr'] }}</td>
                    <td bgcolor="#ffffff" align="center">{{ $goods['goods_number'] }}</td>
                    <td bgcolor="#ffffff" align="center">{{ $goods['formated_goods_price'] }}</td>
                    <td bgcolor="#ffffff" align="center">{{ $goods['formated_subtotal'] }}</td>
                    <td bgcolor="#ffffff" align="center"><a href="wholesale.php?act=drop_goods&key={{ $key }}"
                                                            class="f6">{{ $lang['drop'] }}</a></td>
                </tr>
            @endforeach
        </table>
        <form method="post" action="wholesale.php?act=submit_order">
            <table border="0" cellpadding="5" cellspacing="1" width="100%">
                <tr>
                    <td class="f5" style="text-decoration:none;">{{ $lang['ws_remark'] }}</td>
                </tr>
                <tr>
                    <td><textarea name="remark" rows="4" class="border"
                                  style="width:99%; border:1px solid #ccc;"></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="center"><input type="submit" class="bnt_bonus" value="{{ $lang['submit'] }}"/></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="blank5"></div>
@endif

<!-- @if($wholesale_list)
    如果有批发商品 -->
    <div class="block">
        <h5><span>{{ $lang['wholesale_goods_list'] }}</span>{{ $lang['btn_display'] }}：
            <a href="javascript:void(0);" onClick="javascript:display_mode_wholesale('list')"><img align="middle"
                                                                                                   src="images/display_mode_list@if($pager['display'] == 'list')_act@endif.gif"
                                                                                                   alt="{{ $lang['display']['list'] }}"></a>
            <a href="javascript:void(0);" onClick="javascript:display_mode_wholesale('text')"><img align="middle"
                                                                                                   src="images/display_mode_text@if($pager['display'] == 'text')_act@endif.gif"
                                                                                                   alt="{{ $lang['display']['text'] }}"></a>&nbsp;&nbsp;<a
                href="wholesale.php?act=price_list" class="f6">{{ $lang['ws_price_list'] }}</a></h5>
        <div class="blank"></div>

        <table border="0" cellpadding="5" cellspacing="1" width="100%">
            <form method="post" action="wholesale.php?act=list" name="wholesale_search">
                <tr>
                    <td align="right">
                        {{ $lang['wholesale_search'] }}
                        <select name="search_category" id="search_category">
                            <option value="0">{{ $lang['all_category'] }}</option>
                            {{ $category_list }}
                        </select>
                        <input name="search_keywords" type="text" id="search_keywords" value="{{{ $search_keywords }}}"
                               style="width:110px;"/>
                        <input name="search" type="submit" value="{{ $lang['search'] }}" class="go"/>
                        <input type="hidden" name="search_display" value="{{ $pager['display'] }}" id="search_display"/>
                    </td>
                </tr>
            </form>
        </table>

        <form name="wholesale_goods" action="wholesale.php?act=add_to_cart" method="post">
            <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                <tr>
                    <th width="200" align="center" bgcolor="#ffffff">{{ $lang['goods_name'] }}</th>
                    <th width="200" align="center" bgcolor="#ffffff">{{ $lang['goods_attr'] }}</th>
                    <th width="250" align="center" bgcolor="#ffffff">{{ $lang['goods_price_ladder'] }}</th>
                    <th width="80" align="center" bgcolor="#ffffff">{{ $lang['number'] }}</th>
                    <th width="130" align="center" bgcolor="#ffffff">&nbsp;</th>
                </tr>

                <!-- @foreach($wholesale_list as $wholesale)
                    循环批发商品开始 -->
                    <tr>
                        <td bgcolor="#ffffff">@if($pager['display'] == 'list')
                                <a href="{{ $wholesale['goods_url'] }}" target="_blank"><img
                                        src="{{ $wholesale['goods_thumb'] }}" alt="{{ $wholesale['goods_name'] }}"/></a>
                            @endif<a href="{{ $wholesale['goods_url'] }}" target="_blank"
                                     class="f6">{{ $wholesale['goods_name'] }}</a></td>
                        <td bgcolor="#ffffff">

                            <table width="100%" border="0" align="center">
                                @foreach($wholesale['goods_attr'] as $property_group => $key)
                                    @foreach($property_group as $property)
                                        <tr>
                                            <td nowrap="true"
                                                style="border-bottom:2px solid #ccc;">{{ $property['name'] }}</td>
                                            <td style="border-bottom:1px solid #ccc;">{{ $property['value'] }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </table>
                        </td>

                        <td bgcolor="#ffffff">
                            @foreach($wholesale['price_ladder'] as $key => $attr_price)
                                <table width="100%" border="0" align="center" cellspacing="1" bgcolor="#547289">
                                    @if($attr_price['attr'] != '')
                                        <tr>
                                            <td align="left" nowrap="true" bgcolor="#ffffff" style="padding:5px;"
                                                colspan="2">
                                                @foreach($attr_price['attr'] as $attr_key => $attr_value)
                                                    {{ $attr_value['attr_name'] }}:{{ $attr_value['attr_val'] }}&nbsp;
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td align="left" nowrap="true" bgcolor="#ffffff"
                                            style="padding:5px;">{{ $lang['number'] }}</td>
                                        <td bgcolor="#ffffff" style="padding:5px;">{{ $lang['ladder_price'] }}</td>
                                    </tr>

                                    @foreach($attr_price['qp_list'] as $qp_list_key => $qp_list_value)
                                        <tr>
                                            <td align="left" nowrap="true" bgcolor="#ffffff"
                                                style="padding:5px;">{{ $qp_list_key }}</td>
                                            <td bgcolor="#ffffff" style="padding:5px;">{{ $qp_list_value }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                                <br/>
                            @endforeach
                        </td>
                        <td align="center" bgcolor="#ffffff" style="padding:5px;">
                            @foreach($wholesale['price_ladder'] as $key1 => $attr_price1)
                                <table width="100%" border="0" align="center" cellspacing="0" bgcolor="#547289">
                                    @if($attr_price1['attr'] != '')
                                        <tr>
                                            <td align="left" nowrap="true" bgcolor="#ffffff" style="padding:5px;"
                                                colspan="2">
                                                <input name="goods_number[{{ $wholesale['act_id'] }}][{{ $key1 }}]"
                                                       type="text" class="inputBg" value="" size="10"/>
                                                @foreach($attr_price1['attr'] as $attr_key1 => $attr_value1)
                                                    <input
                                                        name="attr_id[{{ $wholesale['act_id'] }}][{{ $key1 }}][{{ $attr_key1 }}][attr_id]"
                                                        type="hidden" value="{{ $attr_value1['attr_id'] }}"/>
                                                    <input
                                                        name="attr_id[{{ $wholesale['act_id'] }}][{{ $key1 }}][{{ $attr_key1 }}][attr_val_id]"
                                                        type="hidden" value="{{ $attr_value1['attr_val_id'] }}"/>
                                                    <input
                                                        name="attr_id[{{ $wholesale['act_id'] }}][{{ $key1 }}][{{ $attr_key1 }}][attr_name]"
                                                        type="hidden" value="{{ $attr_value1['attr_name'] }}"/>
                                                    <input
                                                        name="attr_id[{{ $wholesale['act_id'] }}][{{ $key1 }}][{{ $attr_key1 }}][attr_val]"
                                                        type="hidden" value="{{ $attr_value1['attr_val'] }}"/>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" nowrap="true" bgcolor="#ffffff" style="padding:5px;"
                                                colspan="2">&nbsp;
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td align="left" nowrap="true" bgcolor="#ffffff" style="padding:5px;"
                                                colspan="2">
                                                <input name="goods_number[{{ $wholesale['act_id'] }}]" type="text"
                                                       class="inputBg" value="" size="10"/>
                                            </td>
                                        </tr>
                                    @endif

                                    @foreach($attr_price['qp_list'] as $qp_list_key => $qp_list_value)
                                        <tr>
                                            <td align="left" nowrap="true" bgcolor="#ffffff" style="padding:5px;"
                                                colspan="2">&nbsp;
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                                <br/>
                            @endforeach

                        </td>
                        <td bgcolor="#ffffff" align="center">
                            <input name="image" type="image"
                                   onClick="this.form.elements['act_id'].value = {{ $wholesale['act_id'] }}"
                                   src="images/bnt_buy_1.gif" style="margin:8px auto;"/>
                        </td>
                    </tr>
                @endforeach

            </table>
            <input type="hidden" name="act_id" value=""/>
            <input type="hidden" name="display" value="{{ $pager['display'] }}" id="display"/>
        </form>
        <div class="blank5"></div>
        @include('web::library/pages')
    </div>
@else
    <div style="margin:2px 10px; font-size:14px; text-align:center; line-height:36px;">
        <B>{{ $lang['no_wholesale'] }}</B></div>
@endif
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
@if($search_category > 0)
    <script type="text/javascript">
        document.getElementById('search_category').value = '{{ $search_category }}';
    </script>
@endif
