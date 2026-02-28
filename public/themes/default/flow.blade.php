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
    <script type="text/javascript" src="{{ asset('js/shopping_flow.js') }}"></script>
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
    @if($step == "cart")
        <!-- 购物车内容 -->

        <script type="text/javascript" src="{{ asset('js/showdiv.js') }}"></script>
        <script type="text/javascript">
            @foreach($lang['password_js'] as $item => $key)
            var {{ $key }} = "{{ $item }}";
            @endforeach
        </script>
        <div class="flowBox">
            <h6><span>{{ $lang['goods_list'] }}</span></h6>
            <form id="formCart" name="formCart" method="post" action="flow.php">
                <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                    <tr>
                        <th bgcolor="#ffffff">{{ $lang['goods_name'] }}</th>
                        <!-- @if($show_goods_attribute == 1)
                            显示商品属性 -->
                            <th bgcolor="#ffffff">{{ $lang['goods_attr'] }}</th>
                        @endif
                        <!-- @if($show_marketprice)
                            显示市场价 -->
                            <th bgcolor="#ffffff">{{ $lang['market_prices'] }}</th>
                        @endif
                        <th bgcolor="#ffffff">{{ $lang['shop_prices'] }}</th>
                        <th bgcolor="#ffffff">{{ $lang['number'] }}</th>
                        <th bgcolor="#ffffff">{{ $lang['subtotal'] }}</th>
                        <th bgcolor="#ffffff">{{ $lang['handle'] }}</th>
                    </tr>
                    @foreach($goods_list as $goods)
                        <tr>
                            <td bgcolor="#ffffff" align="center">
                                <!-- @if($goods['goods_id'] > 0 && $goods['extension_code'] != 'package_buy')
                                    商品 -->
                                    @if($show_goods_thumb == 1)
                                        <a href="goods.php?id={{ $goods['goods_id'] }}" target="_blank"
                                           class="f6">{{ $goods['goods_name'] }}</a>
                                    @elseif($show_goods_thumb == 2)
                                        <a href="goods.php?id={{ $goods['goods_id'] }}" target="_blank"><img
                                                src="{{ $goods['goods_thumb'] }}" border="0"
                                                title="{{ $goods['goods_name'] }}"/></a>
                                    @else
                                        <a href="goods.php?id={{ $goods['goods_id'] }}" target="_blank"><img
                                                src="{{ $goods['goods_thumb'] }}" border="0"
                                                title="{{ $goods['goods_name'] }}"/></a><br/>
                                        <a href="goods.php?id={{ $goods['goods_id'] }}" target="_blank"
                                           class="f6">{{ $goods['goods_name'] }}</a>
                                    @endif
                                    <!-- @if($goods['parent_id'] > 0)
                                        配件 -->
                                        <span style="color:#FF0000">（{{ $lang['accessories'] }}）</span>
                                    @endif
                                    <!-- @if($goods['is_gift'] > 0)
                                        赠品 -->
                                        <span style="color:#FF0000">（{{ $lang['largess'] }}）</span>
                                    @endif
                                @elseif($goods['goods_id'] > 0 && $goods['extension_code'] == 'package_buy')
                                    <a href="javascript:void(0)" onclick="setSuitShow({{ $goods['goods_id'] }})"
                                       class="f6">{{ $goods['goods_name'] }}<span style="color:#FF0000;">（{{ $lang['remark_package'] }}）</span></a>
                                    <div id="suit_{{ $goods['goods_id'] }}" style="display:none">
                                        @foreach($goods['package_goods_list'] as $package_goods_list)
                                            <a href="goods.php?id={{ $package_goods_list['goods_id'] }}" target="_blank"
                                               class="f6">{{ $package_goods_list['goods_name'] }}</a><br/>
                                        @endforeach
                                    </div>
                                    <!--

                                @else
                                    优惠活动 -->
                                    {{ $goods['goods_name'] }}
                                @endif
                            </td>
                            <!-- @if($show_goods_attribute == 1)
                                显示商品属性 -->
                                <td bgcolor="#ffffff">{$goods.goods_attr|nl2br}</td>
                            @endif
                            <!-- @if($show_marketprice)
                                显示市场价 -->
                                <td align="right" bgcolor="#ffffff">{{ $goods['market_price'] }}</td>
                            @endif
                            <td align="right" bgcolor="#ffffff">{{ $goods['goods_price'] }}</td>
                            <td align="right" bgcolor="#ffffff">
                                <!-- @if($goods['goods_id'] > 0 && $goods['is_gift'] == 0 && $goods['parent_id'] == 0)
                                    普通商品可修改数量 -->
                                    <input type="text" name="goods_number[{{ $goods['rec_id'] }}]"
                                           id="goods_number_{{ $goods['rec_id'] }}" value="{{ $goods['goods_number'] }}"
                                           size="4" class="inputBg" style="text-align:center "
                                           onkeydown="showdiv(this)"/>
                                @else
                                    {{ $goods['goods_number'] }}
                                @endif
                            </td>
                            <td align="right" bgcolor="#ffffff">{{ $goods['subtotal'] }}</td>
                            <td align="center" bgcolor="#ffffff">
                                <a href="javascript:if (confirm('{{ $lang['drop_goods_confirm'] }}')) location.href='flow.php?step=drop_goods&amp;id={{ $goods['rec_id'] }}'; "
                                   class="f6">{{ $lang['drop'] }}</a>
                                <!-- @if($smarty['session']['user_id'] > 0 && $goods['extension_code'] != 'package_buy')
                                    如果登录了，可以加入收藏 -->
                                    <a href="javascript:if (confirm('{{ $lang['drop_goods_confirm'] }}')) location.href='flow.php?step=drop_to_collect&amp;id={{ $goods['rec_id'] }}'; "
                                       class="f6">{{ $lang['drop_to_collect'] }}</a>
                                @endif            </td>
                        </tr>
                    @endforeach
                </table>
                <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                    <tr>
                        <td bgcolor="#ffffff">
                            @if($discount > 0)
                                {{ $your_discount }}<br/>
                            @endif
                            {{ $shopping_money }}@if($show_marketprice)
                                ，{{ $market_price_desc }}
                            @endif
                        </td>
                        <td align="right" bgcolor="#ffffff">
                            <input type="button" value="{{ $lang['clear_cart'] }}" class="bnt_blue_1"
                                   onclick="location.href='flow.php?step=clear'"/>
                            <input name="submit" type="submit" class="bnt_blue_1" value="{{ $lang['update_cart'] }}"/>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="step" value="update_cart"/>
            </form>
            <table width="99%" align="center" border="0" cellpadding="5" cellspacing="0" bgcolor="#dddddd">
                <tr>
                    <td bgcolor="#ffffff"><a href="./"><img src="images/continue.gif" alt="continue"/></a></td>
                    <td bgcolor="#ffffff" align="right"><a href="flow.php?step=checkout"><img src="images/checkout.gif"
                                                                                              alt="checkout"/></a></td>
                </tr>
            </table>
            @if($smarty['session']['user_id'] > 0)
                <script type="text/javascript" src="{{ asset('js/transport.js') }}"></script>
                <script type="text/javascript" charset="utf-8">
                    function collect_to_flow(goodsId) {
                        var goods = new Object();
                        var spec_arr = new Array();
                        var fittings_arr = new Array();
                        var number = 1;
                        goods.spec = spec_arr;
                        goods.goods_id = goodsId;
                        goods.number = number;
                        goods.parent = 0;
                        Ajax.call('flow.php?step=add_to_cart', 'goods=' + goods.toJSONString(), collect_to_flow_response, 'POST', 'JSON');
                    }

                    function collect_to_flow_response(result) {
                        if (result.error > 0) {
                            // 如果需要缺货登记，跳转
                            if (result.error == 2) {
                                if (confirm(result.message)) {
                                    location.href = 'user.php?act=add_booking&id=' + result.goods_id;
                                }
                            } else if (result.error == 6) {
                                openSpeDiv(result.message, result.goods_id);
                            } else {
                                alert(result.message);
                            }
                        } else {
                            location.href = 'flow.php';
                        }
                    }
                </script>
        </div>
        <div class="blank"></div>
    @endif

    @if($collection_goods)
        <div class="flowBox">
            <h6><span>{{ $lang['label_collection'] }}</span></h6>
            <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                @foreach($collection_goods as $goods)
                    <tr>
                        <td bgcolor="#ffffff"><a href="goods.php?id={{ $goods['goods_id'] }}"
                                                 class="f6">{{ $goods['goods_name'] }}</a></td>
                        <td bgcolor="#ffffff" align="center" width="100"><a
                                href="javascript:addToCart({{ $goods['goods_id'] }})"
                                class="f6">{{ $lang['collect_to_flow'] }}</a></td>
                    </tr>
                @endforeach
            </table>
            @endif
        </div>
        <div class="blank5"></div>
    @endif

    <!-- @if($fittings_list)
        商品配件 -->
        <script type="text/javascript" src="{{ asset('js/transport.js') }}"></script>
        <script type="text/javascript" charset="utf-8">
            function fittings_to_flow(goodsId, parentId) {
                var goods = new Object();
                var spec_arr = new Array();
                var number = 1;
                goods.spec = spec_arr;
                goods.goods_id = goodsId;
                goods.number = number;
                goods.parent = parentId;
                Ajax.call('flow.php?step=add_to_cart', 'goods=' + goods.toJSONString(), fittings_to_flow_response, 'POST', 'JSON');
            }

            function fittings_to_flow_response(result) {
                if (result.error > 0) {
                    // 如果需要缺货登记，跳转
                    if (result.error == 2) {
                        if (confirm(result.message)) {
                            location.href = 'user.php?act=add_booking&id=' + result.goods_id;
                        }
                    } else if (result.error == 6) {
                        openSpeDiv(result.message, result.goods_id, result.parent);
                    } else {
                        alert(result.message);
                    }
                } else {
                    location.href = 'flow.php';
                }
            }
        </script>
        <div class="block">
            <div class="flowBox">
                <h6><span>{{ $lang['goods_fittings'] }}</span></h6>
                <form action="flow.php" method="post">
                    <div class="flowGoodsFittings clearfix">
                        @foreach($fittings_list as $fittings)
                            <ul class="clearfix">
                                <li class="goodsimg">
                                    <a href="{{ $fittings['url'] }}" target="_blank"><img
                                            src="{{ $fittings['goods_thumb'] }}" alt="{{ $fittings['name'] }}"
                                            class="B_blue"/></a>
                                </li>
                                <li>
                                    <a href="{{ $fittings['url'] }}" target="_blank"
                                       title="{{ $fittings['goods_name'] }}"
                                       class="f6">{{ $fittings['short_name'] }}</a><br/>
                                    {{ $lang['fittings_price'] }}<font
                                        class="f1">{{ $fittings['fittings_price'] }}</font><br/>
                                    {{ $lang['parent_name'] }}{{ $fittings['parent_short_name'] }}<br/>
                                    <a href="javascript:fittings_to_flow({{ $fittings['goods_id'] }},{{ $fittings['parent_id'] }})"><img
                                            src="images/bnt_buy.gif" alt="{{ $lang['collect_to_flow'] }}"/></a>
                                </li>
                            </ul>
                            <!--

                        @endforeach 循环商品配件结束 -->
                    </div>
                </form>
            </div>
        </div>
        <div class="blank5"></div>
    @endif

    <!-- @if($favourable_list)
        优惠活动 -->
        <div class="block">
            <div class="flowBox">
                <h6><span>{{ $lang['label_favourable'] }}</span></h6>
                @foreach($favourable_list as $favourable)
                    <form action="flow.php" method="post">
                        <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                            <tr>
                                <td align="right" bgcolor="#ffffff">{{ $lang['favourable_name'] }}</td>
                                <td bgcolor="#ffffff"><strong>{{ $favourable['act_name'] }}</strong></td>
                            </tr>
                            <tr>
                                <td align="right" bgcolor="#ffffff">{{ $lang['favourable_period'] }}</td>
                                <td bgcolor="#ffffff">{{ $favourable['start_time'] }}
                                    --- {{ $favourable['end_time'] }}</td>
                            </tr>
                            <tr>
                                <td align="right" bgcolor="#ffffff">{{ $lang['favourable_range'] }}</td>
                                <td bgcolor="#ffffff">{$lang.far_ext[$favourable.act_range]}<br/>
                                    {{ $favourable['act_range_desc'] }}</td>
                            </tr>
                            <tr>
                                <td align="right" bgcolor="#ffffff">{{ $lang['favourable_amount'] }}</td>
                                <td bgcolor="#ffffff">{{ $favourable['formated_min_amount'] }}
                                    --- {{ $favourable['formated_max_amount'] }}</td>
                            </tr>
                            <tr>
                                <td align="right" bgcolor="#ffffff">{{ $lang['favourable_type'] }}</td>
                                <td bgcolor="#ffffff">
                                    <span class="STYLE1">{{ $favourable['act_type_desc'] }}</span>
                                    @if($favourable['act_type'] == 0)
                                        @foreach($favourable['gift'] as $gift)
                                            <br/>
                                            <input type="checkbox" value="{{ $gift['id'] }}" name="gift[]"/>
                                            <a href="goods.php?id={{ $gift['id'] }}" target="_blank"
                                               class="f6">{{ $gift['name'] }}</a> [{{ $gift['formated_price'] }}]
                                        @endforeach
                                    @endif          </td>
                            </tr>
                            @if($favourable['available'])
                                <tr>
                                    <td align="right" bgcolor="#ffffff">&nbsp;</td>
                                    <td align="center" bgcolor="#ffffff"><input type="image" src="images/bnt_cat.gif"
                                                                                alt="Add to cart" border="0"/></td>
                                </tr>
                            @endif
                        </table>
                        <input type="hidden" name="act_id" value="{{ $favourable['act_id'] }}"/>
                        <input type="hidden" name="step" value="add_favourable"/>
                    </form>
                    <!--

                @endforeach 循环赠品活动结束 -->
            </div>
        </div>
    @endif


    @if($step == "consignee")
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

        </script>
        <!-- 如果有收货地址，循环显示用户的收获地址 -->
        @foreach($consignee_list as $consignee => $sn)
            <form action="flow.php" method="post" name="theForm" id="theForm" onsubmit="return checkConsignee(this)">
                @include('web::Library/consignee')
            </form>
        @endforeach
    @endif

    <!-- @if($step == "checkout")
        开始订单确认界面 -->
        <form action="flow.php" method="post" name="theForm" id="theForm" onsubmit="return checkOrderForm(this)">
            <script type="text/javascript">
                var flow_no_payment = "{{ $lang['flow_no_payment'] }}";
                var flow_no_shipping = "{{ $lang['flow_no_shipping'] }}";
            </script>
            <div class="flowBox">
                <h6><span>{{ $lang['goods_list'] }}</span>@if($allow_edit_cart)
                        <a href="flow.php" class="f6">{{ $lang['modify'] }}</a>
                    @endif</h6>
                <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                    <tr>
                        <th bgcolor="#ffffff">{{ $lang['goods_name'] }}</th>
                        <th bgcolor="#ffffff">{{ $lang['goods_attr'] }}</th>
                        @if($show_marketprice)
                            <th bgcolor="#ffffff">{{ $lang['market_prices'] }}</th>
                        @endif
                        <th bgcolor="#ffffff">'.($gb_deposit ? '{{ $lang['deposit'] }}' : '{{ $lang['shop_prices'] }}
                            ').'
                        </th>
                        <th bgcolor="#ffffff">{{ $lang['number'] }}</th>
                        <th bgcolor="#ffffff">{{ $lang['subtotal'] }}</th>
                    </tr>
                    @foreach($goods_list as $goods)
                        <tr>
                            <td bgcolor="#ffffff">
                                @if($goods['goods_id'] > 0 && $goods['extension_code'] == 'package_buy')
                                    <a href="javascript:void(0)" onclick="setSuitShow({{ $goods['goods_id'] }})"
                                       class="f6">{{ $goods['goods_name'] }}<span style="color:#FF0000;">（{{ $lang['remark_package'] }}）</span></a>
                                    <div id="suit_{{ $goods['goods_id'] }}" style="display:none">
                                        @foreach($goods['package_goods_list'] as $package_goods_list)
                                            <a href="goods.php?id={{ $package_goods_list['goods_id'] }}" target="_blank"
                                               class="f6">{{ $package_goods_list['goods_name'] }}</a><br/>
                                        @endforeach
                                    </div>
                                    { else }
                                    <a href="goods.php?id={{ $goods['goods_id'] }}" target="_blank"
                                       class="f6">{{ $goods['goods_name'] }}</a>
                                    @if($goods['parent_id'] > 0)
                                        <span style="color:#FF0000">（{{ $lang['accessories'] }}）</span>
                                    @elseif($goods['is_gift'])
                                        <span style="color:#FF0000">（{{ $lang['largess'] }}）</span>
                                    @endif
                                @endif
                                @if($goods['is_shipping'])
                                    (<span style="color:#FF0000">{{ $lang['free_goods'] }}</span>)
                                @endif
                            </td>
                            <td bgcolor="#ffffff">{$goods.goods_attr|nl2br}</td>
                            @if($show_marketprice)
                                <td align="right" bgcolor="#ffffff">{{ $goods['formated_market_price'] }}</td>
                            @endif
                            <td bgcolor="#ffffff" align="right">{{ $goods['formated_goods_price'] }}</td>
                            <td bgcolor="#ffffff" align="right">{{ $goods['goods_number'] }}</td>
                            <td bgcolor="#ffffff" align="right">{{ $goods['formated_subtotal'] }}</td>
                        </tr>
                    @endforeach
                    <!-- @if(!$gb_deposit)
                        团购且有保证金时不显示 -->
                        <tr>
                            <td bgcolor="#ffffff" colspan="7">
                                @if($discount > 0)
                                    {{ $your_discount }}<br/>
                                @endif
                                {{ $shopping_money }}@if($show_marketprice)
                                    ，{{ $market_price_desc }}
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="blank"></div>
            <div class="flowBox">
                <h6><span>{{ $lang['consignee_info'] }}</span><a href="flow.php?step=consignee"
                                                                 class="f6">{{ $lang['modify'] }}</a></h6>
                <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                    <tr>
                        <td bgcolor="#ffffff">{{ $lang['consignee_name'] }}:</td>
                        <td bgcolor="#ffffff">{{ $consignee['consignee'] }}</td>
                        <td bgcolor="#ffffff">{{ $lang['email_address'] }}:</td>
                        <td bgcolor="#ffffff">{{ $consignee['email'] }}</td>
                    </tr>
                    @if($total['real_goods_count'] > 0)
                        <tr>
                            <td bgcolor="#ffffff">{{ $lang['detailed_address'] }}:</td>
                            <td bgcolor="#ffffff">{{ $consignee['address'] }} </td>
                            <td bgcolor="#ffffff">{{ $lang['postalcode'] }}:</td>
                            <td bgcolor="#ffffff">{{ $consignee['zipcode'] }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td bgcolor="#ffffff">{{ $lang['phone'] }}:</td>
                        <td bgcolor="#ffffff">{{ $consignee['tel'] }} </td>
                        <td bgcolor="#ffffff">{{ $lang['backup_phone'] }}:</td>
                        <td bgcolor="#ffffff">{{ $consignee['mobile'] }}</td>
                    </tr>
                    @if($total['real_goods_count'] > 0)
                        <tr>
                            <td bgcolor="#ffffff">{{ $lang['sign_building'] }}:</td>
                            <td bgcolor="#ffffff">{{ $consignee['sign_building'] }} </td>
                            <td bgcolor="#ffffff">{{ $lang['deliver_goods_time'] }}:</td>
                            <td bgcolor="#ffffff">{{ $consignee['best_time'] }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="blank"></div>
            @if($total['real_goods_count'] != 0)
                <div class="flowBox">
                    <h6><span>{{ $lang['shipping_method'] }}</span></h6>
                    <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd"
                           id="shippingTable">
                        <tr>
                            <th bgcolor="#ffffff" width="5%">&nbsp;</th>
                            <th bgcolor="#ffffff" width="25%">{{ $lang['name'] }}</th>
                            <th bgcolor="#ffffff">{{ $lang['describe'] }}</th>
                            <th bgcolor="#ffffff" width="15%">{{ $lang['fee'] }}</th>
                            <th bgcolor="#ffffff" width="15%">{{ $lang['free_money'] }}</th>
                            <th bgcolor="#ffffff" width="15%">{{ $lang['insure_fee'] }}</th>
                        </tr>
                        <!-- @foreach($shipping_list as $shipping)
                            循环配送方式 -->
                            <tr>
                                <td bgcolor="#ffffff" valign="top"><input name="shipping" type="radio"
                                                                          value="{{ $shipping['shipping_id'] }}"
                                                                          @if($order['shipping_id'] == $shipping['shipping_id'])checked="true"
                                                                          @endif supportCod="{{ $shipping['support_cod'] }}"
                                                                          insure="{{ $shipping['insure'] }}"
                                                                          onclick="selectShipping(this)"/>
                                </td>
                                <td bgcolor="#ffffff" valign="top"><strong>{{ $shipping['shipping_name'] }}</strong>
                                </td>
                                <td bgcolor="#ffffff" valign="top">{{ $shipping['shipping_desc'] }}</td>
                                <td bgcolor="#ffffff" align="right"
                                    valign="top">{{ $shipping['format_shipping_fee'] }}</td>
                                <td bgcolor="#ffffff" align="right" valign="top">{{ $shipping['free_money'] }}</td>
                                <td bgcolor="#ffffff" align="right" valign="top">'.($shipping['insure'] != 0 ?
                                    '{{ $shipping['insure_formated'] }}' : '{{ $lang['not_support_insure'] }}').'
                                </td>
                            </tr>
                            <!--

                        @endforeach 循环配送方式 -->
                        <tr>
                            <td colspan="6" bgcolor="#ffffff" align="right"><label for="ECS_NEEDINSURE">
                                    <input name="need_insure" id="ECS_NEEDINSURE" type="checkbox"
                                           onclick="selectInsure(this.checked)" value="1"
                                           @if($order['need_insure'])checked="true"
                                           @endif @if($insure_disabled)disabled="true"@endif />
                                    {{ $lang['need_insure'] }} </label></td>
                        </tr>
                    </table>
                </div>
                <div class="blank"></div>
            @else
                <input name="shipping" type="radio" value="-1" checked="checked" style="display:none"/>
            @endif
            @if($is_exchange_goods != 1 || $total['real_goods_count'] != 0)
                <div class="flowBox">
                    <h6><span>{{ $lang['payment_method'] }}</span></h6>
                    <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd"
                           id="paymentTable">
                        <tr>
                            <th width="5%" bgcolor="#ffffff">&nbsp;</th>
                            <th width="20%" bgcolor="#ffffff">{{ $lang['name'] }}</th>
                            <th bgcolor="#ffffff">{{ $lang['describe'] }}</th>
                            <th bgcolor="#ffffff" width="15%">{{ $lang['pay_fee'] }}</th>
                        </tr>
                        @foreach($payment_list as $payment)
                            <!-- 循环支付方式 -->
                            <tr>
                                <td valign="top" bgcolor="#ffffff"><input type="radio" name="payment"
                                                                          value="{{ $payment['pay_id'] }}"
                                                                          @if($order['pay_id'] == $payment['pay_id'])
                                                                              checked
                                                                          @endif isCod="{{ $payment['is_cod'] }}"
                                                                          onclick="selectPayment(this)"
                                                                          @if($cod_disabled && $payment['is_cod'] == "1")disabled="true"@endif/>
                                </td>
                                <td valign="top" bgcolor="#ffffff"><strong>{{ $payment['pay_name'] }}</strong></td>
                                <td valign="top" bgcolor="#ffffff">{{ $payment['pay_desc'] }}</td>
                                <td align="right" bgcolor="#ffffff" valign="top">{{ $payment['format_pay_fee'] }}</td>
                            </tr>
                            <!--

                        @endforeach 循环支付方式 -->
                    </table>
                </div>
            @else
                <input name="payment" type="radio" value="-1" checked="checked" style="display:none"/>
            @endif
            <div class="blank"></div>
            <!-- @if($pack_list)
                是否有包装 -->
                <div class="flowBox">
                    <h6><span>{{ $lang['goods_package'] }}</span></h6>
                    <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd"
                           id="packTable">
                        <tr>
                            <th width="5%" scope="col" bgcolor="#ffffff">&nbsp;</th>
                            <th width="35%" scope="col" bgcolor="#ffffff">{{ $lang['name'] }}</th>
                            <th width="22%" scope="col" bgcolor="#ffffff">{{ $lang['price'] }}</th>
                            <th width="22%" scope="col" bgcolor="#ffffff">{{ $lang['free_money'] }}</th>
                            <th scope="col" bgcolor="#ffffff">{{ $lang['img'] }}</th>
                        </tr>
                        <tr>
                            <td valign="top" bgcolor="#ffffff"><input type="radio" name="pack" value="0"
                                                                      @if($order['pack_id'] == 0)checked="true"
                                                                      @endif onclick="selectPack(this)"/></td>
                            <td valign="top" bgcolor="#ffffff"><strong>{{ $lang['no_pack'] }}</strong></td>
                            <td valign="top" bgcolor="#ffffff">&nbsp;</td>
                            <td valign="top" bgcolor="#ffffff">&nbsp;</td>
                            <td valign="top" bgcolor="#ffffff">&nbsp;</td>
                        </tr>
                        <!-- @foreach($pack_list as $pack)
                            循环包装 -->
                            <tr>
                                <td valign="top" bgcolor="#ffffff"><input type="radio" name="pack"
                                                                          value="{{ $pack['pack_id'] }}"
                                                                          @if($order['pack_id'] == $pack['pack_id'])checked="true"
                                                                          @endif onclick="selectPack(this)"/>
                                </td>
                                <td valign="top" bgcolor="#ffffff"><strong>{{ $pack['pack_name'] }}</strong></td>
                                <td valign="top" bgcolor="#ffffff" align="right">{{ $pack['format_pack_fee'] }}</td>
                                <td valign="top" bgcolor="#ffffff" align="right">{{ $pack['format_free_money'] }}</td>
                                <td valign="top" bgcolor="#ffffff" align="center">
                                    <!-- @if($pack['pack_img'])
                                        是否有图片 -->
                                        <a href="data/packimg/{{ $pack['pack_img'] }}" target="_blank"
                                           class="f6">{{ $lang['view'] }}</a>
                                    @else
                                        {{ $lang['no'] }}
                                    @endif
                                </td>
                            </tr>
                            <!--

                        @endforeach 循环包装 -->
                    </table>
                </div>
                <div class="blank"></div>
                <!--

            @endif 是否使用包装 -->

            <!-- @if($card_list)
                是否有贺卡 -->
                <div class="flowBox">
                    <h6><span>{{ $lang['goods_card'] }}</span></h6>
                    <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd"
                           id="cardTable">
                        <tr>
                            <th bgcolor="#ffffff" width="5%" scope="col">&nbsp;</th>
                            <th bgcolor="#ffffff" width="35%" scope="col">{{ $lang['name'] }}</th>
                            <th bgcolor="#ffffff" width="22%" scope="col">{{ $lang['price'] }}</th>
                            <th bgcolor="#ffffff" width="22%" scope="col">{{ $lang['free_money'] }}</th>
                            <th bgcolor="#ffffff" scope="col">{{ $lang['img'] }}</th>
                        </tr>
                        <tr>
                            <td bgcolor="#ffffff" valign="top"><input type="radio" name="card" value="0"
                                                                      @if($order['card_id'] == 0)checked="true"
                                                                      @endif onclick="selectCard(this)"/></td>
                            <td bgcolor="#ffffff" valign="top"><strong>{{ $lang['no_card'] }}</strong></td>
                            <td bgcolor="#ffffff" valign="top">&nbsp;</td>
                            <td bgcolor="#ffffff" valign="top">&nbsp;</td>
                            <td bgcolor="#ffffff" valign="top">&nbsp;</td>
                        </tr>
                        <!-- @foreach($card_list as $card)
                            循环贺卡 -->
                            <tr>
                                <td valign="top" bgcolor="#ffffff"><input type="radio" name="card"
                                                                          value="{{ $card['card_id'] }}"
                                                                          @if($order['card_id'] == $card['card_id'])checked="true"
                                                                          @endif onclick="selectCard(this)"/>
                                </td>
                                <td valign="top" bgcolor="#ffffff"><strong>{{ $card['card_name'] }}</strong></td>
                                <td valign="top" align="right" bgcolor="#ffffff">{{ $card['format_card_fee'] }}</td>
                                <td valign="top" align="right" bgcolor="#ffffff">{{ $card['format_free_money'] }}</td>
                                <td valign="top" align="center" bgcolor="#ffffff">
                                    <!-- @if($card['card_img'])
                                        是否有图片 -->
                                        <a href="data/cardimg/{{ $card['card_img'] }}" target="_blank"
                                           class="f6">{{ $lang['view'] }}</a>
                                    @else
                                        {{ $lang['no'] }}
                                    @endif
                                </td>
                            </tr>
                            <!--

                        @endforeach 循环贺卡 -->
                        <tr>
                            <td bgcolor="#ffffff"></td>
                            <td bgcolor="#ffffff" valign="top"><strong>{{ $lang['bless_note'] }}:</strong></td>
                            <td bgcolor="#ffffff" colspan="3"><textarea name="card_message" cols="60" rows="3"
                                                                        style="width:auto; border:1px solid #ccc;">{{ $order['card_message'] }}</textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="blank"></div>
                <!--

            @endif 是否使用贺卡 -->

            <div class="flowBox">
                <h6><span>{{ $lang['other_info'] }}</span></h6>
                <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                    <!-- @if($allow_use_surplus)
                        是否使用余额 -->
                        <tr>
                            <td width="20%" bgcolor="#ffffff"><strong>{{ $lang['use_surplus'] }}: </strong></td>
                            <td bgcolor="#ffffff"><input name="surplus" type="text" class="inputBg" id="ECS_SURPLUS"
                                                         size="10" value="{{ $order['surplus'] ?? 0 }}"
                                                         onblur="changeSurplus(this.value)"
                                                         @if($disable_surplus)disabled="disabled"@endif />
                                {{ $lang['your_surplus'] }}{{ $your_surplus ?? 0 }} <span id="ECS_SURPLUS_NOTICE"
                                                                                          class="notice"></span></td>
                        </tr>
                        <!--

                    @endif 是否使用余额 -->
                    <!-- @if($allow_use_integral)
                        是否使用积分 -->
                        <tr>
                            <td bgcolor="#ffffff"><strong>{{ $lang['use_integral'] }}</strong></td>
                            <td bgcolor="#ffffff"><input name="integral" type="text" class="input" id="ECS_INTEGRAL"
                                                         onblur="changeIntegral(this.value)"
                                                         value="{{ $order['integral'] ?? 0 }}" size="10"/>
                                {{ $lang['can_use_integral'] }}:{{ $your_integral ?? 0 }} {{ $points_name }}
                                ，{{ $lang['noworder_can_integral'] }}{{ $order_max_integral }}  {{ $points_name }}.
                                <span id="ECS_INTEGRAL_NOTICE" class="notice"></span></td>
                        </tr>
                        <!--

                    @endif 是否使用积分 -->
                    <!-- @if($allow_use_bonus)
                        是否使用红包 -->
                        <tr>
                            <td bgcolor="#ffffff"><strong>{{ $lang['use_bonus'] }}:</strong></td>
                            <td bgcolor="#ffffff">
                                {{ $lang['select_bonus'] }}
                                <select name="bonus" onchange="changeBonus(this.value)" id="ECS_BONUS"
                                        style="border:1px solid #ccc;">
                                    <option value="0"
                                            @if($order['bonus_id'] == 0)selected@endif>{{ $lang['please_select'] }}</option>
                                    @foreach($bonus_list as $bonus)
                                        <option value="{{ $bonus['bonus_id'] }}"
                                                @if($order['bonus_id'] == $bonus['bonus_id'])selected@endif>{{ $bonus['type_name'] }}
                                            [{{ $bonus['bonus_money_formated'] }}]
                                        </option>
                                    @endforeach
                                </select>

                                {{ $lang['input_bonus_no'] }}
                                <input name="bonus_sn" type="text" class="inputBg" size="15"
                                       value="{{ $order['bonus_sn'] }}"/>
                                <input name="validate_bonus" type="button" class="bnt_blue_1"
                                       value="{{ $lang['validate_bonus'] }}"
                                       onclick="validateBonus(document.forms['theForm'].elements['bonus_sn'].value)"
                                       style="vertical-align:middle;"/>
                            </td>
                        </tr>
                        <!--

                    @endif 是否使用红包 -->
                    <!-- @if($inv_content_list)
                        能否开发票 -->
                        <tr>
                            <td bgcolor="#ffffff"><strong>{{ $lang['invoice'] }}:</strong>
                                <input name="need_inv" type="checkbox" class="input" id="ECS_NEEDINV"
                                       onclick="changeNeedInv()" value="1"
                                       @if($order['need_inv'])checked="true"@endif />
                            </td>
                            <td bgcolor="#ffffff">
                                @if($inv_type_list)
                                    {{ $lang['invoice_type'] }}
                                    <select name="inv_type" id="ECS_INVTYPE" @if($order['need_inv'] != 1)disabled="true"
                                            @endif onchange="changeNeedInv()" style="border:1px solid #ccc;">
                                        {html_options options=$inv_type_list selected=$order.inv_type}</select>
                                @endif
                                {{ $lang['invoice_title'] }}
                                <input name="inv_payee" type="text" class="input" id="ECS_INVPAYEE" size="20"
                                       @if(!$order['need_inv'])disabled="true" @endif value="{{ $order['inv_payee'] }}"
                                       onblur="changeNeedInv()"/>
                                {{ $lang['invoice_content'] }}
                                <select name="inv_content" id="ECS_INVCONTENT"
                                        @if($order['need_inv'] != 1)disabled="true" @endif  onchange="changeNeedInv()"
                                        style="border:1px solid #ccc;">

                                    {html_options values=$inv_content_list output=$inv_content_list
                                    selected=$order.inv_content}

                                </select></td>
                        </tr>
                    @endif
                    <tr>
                        <td valign="top" bgcolor="#ffffff"><strong>{{ $lang['order_postscript'] }}:</strong></td>
                        <td bgcolor="#ffffff"><textarea name="postscript" cols="80" rows="3" id="postscript"
                                                        style="border:1px solid #ccc;">{{ $order['postscript'] }}</textarea>
                        </td>
                    </tr>
                    <!-- @if($how_oos_list)
                        是否使用缺货处理 -->
                        <tr>
                            <td bgcolor="#ffffff"><strong>{{ $lang['booking_process'] }}:</strong></td>
                            <td bgcolor="#ffffff">@foreach($how_oos_list as $how_oos_id => $how_oos_name)
                                    <label>
                                        <input name="how_oos" type="radio" value="{{ $how_oos_id }}"
                                               @if($order['how_oos'] == $how_oos_id)
                                                   checked
                                               @endif onclick="changeOOS(this)"/>
                                        {{ $how_oos_name }}</label>
                                @endforeach
                            </td>
                        </tr>
                        <!--

                    @endif 缺货处理结束 -->
                </table>
            </div>
            <div class="blank"></div>
            <div class="flowBox">
                <h6><span>{{ $lang['fee_total'] }}</span></h6>
                @include('web::Library/order_total')
                <div align="center" style="margin:8px auto;">
                    <input type="image" src="images/bnt_subOrder.gif"/>
                    <input type="hidden" name="step" value="done"/>
                </div>
            </div>
        </form>
    @endif

    @if($step == "done")
        <!-- 订单提交成功 -->
        <div class="flowBox" style="margin:30px auto 70px auto;">
            <h6 style="text-align:center; height:30px; line-height:30px;">{{ $lang['remember_order_number'] }}: <font
                    style="color:red">{{ $order['order_sn'] }}</font></h6>
            <table width="99%" align="center" border="0" cellpadding="15" cellspacing="0" bgcolor="#fff"
                   style="border:1px solid #ddd; margin:20px auto;">
                <tr>
                    <td align="center" bgcolor="#FFFFFF">
                        @if($order['shipping_name'])
                            {{ $lang['select_shipping'] }}: <strong>{{ $order['shipping_name'] }}</strong>，
                        @endif{{ $lang['select_payment'] }}:
                        <strong>{{ $order['pay_name'] }}</strong>。{{ $lang['order_amount'] }}:
                        <strong>{{ $total['amount_formated'] }}</strong>
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#FFFFFF">{{ $order['pay_desc'] }}</td>
                </tr>
                @if($pay_online)
                    <!-- 如果是线上支付则显示支付按钮 -->
                    <tr>
                        <td align="center" bgcolor="#FFFFFF">{{ $pay_online }}</td>
                    </tr>
                @endif
            </table>
            @if($virtual_card)
                <div
                    style="text-align:center;overflow:hidden;border:1px solid #E2C822;background:#FFF9D7;margin:10px;padding:10px 50px 30px;">
                    @foreach($virtual_card as $vgoods)
                        <h3 style="color:#2359B1; font-size:12px;">{{ $vgoods['goods_name'] }}</h3>
                        @foreach($vgoods['info'] as $card)
                            <ul style="list-style:none;padding:0;margin:0;clear:both">
                                @if($card['card_sn'])
                                    <li style="margin-right:50px;float:left;">
                                        <strong>{{ $lang['card_sn'] }}:</strong><span
                                            style="color:red;">{{ $card['card_sn'] }}</span>
                                    </li>
                                @endif
                                @if($card['card_password'])
                                    <li style="margin-right:50px;float:left;">
                                        <strong>{{ $lang['card_password'] }}:</strong><span
                                            style="color:red;">{{ $card['card_password'] }}</span>
                                    </li>
                                @endif
                                @if($card['end_date'])
                                    <li style="float:left;">
                                        <strong>{{ $lang['end_date'] }}:</strong>{{ $card['end_date'] }}
                                    </li>
                                @endif
                            </ul>
                        @endforeach
                    @endforeach
                </div>
            @endif
            <p style="text-align:center; margin-bottom:20px;">{{ $order_submit_back }}</p>
        </div>
    @endif
    @if($step == "login")
        <script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/user.js') }}"></script>
        <script type="text/javascript">
            @foreach($lang['flow_login_register'] as $item => $key)
            var {{ $key }} = "{{ $item }}";
            @endforeach


            function checkLoginForm(frm) {
                if (Utils.isEmpty(frm.elements['username'].value)) {
                    alert(username_not_null);
                    return false;
                }

                if (Utils.isEmpty(frm.elements['password'].value)) {
                    alert(password_not_null);
                    return false;
                }

                return true;
            }

            function checkSignupForm(frm) {
                if (Utils.isEmpty(frm.elements['username'].value)) {
                    alert(username_not_null);
                    return false;
                }

                if (Utils.trim(frm.elements['username'].value).match(/^\s*$|^c:\\con\\con$|[%,\'\*\"\s\t\<\>\&\\]/)) {
                    alert(username_invalid);
                    return false;
                }

                if (Utils.isEmpty(frm.elements['email'].value)) {
                    alert(email_not_null);
                    return false;
                }

                if (!Utils.isEmail(frm.elements['email'].value)) {
                    alert(email_invalid);
                    return false;
                }

                if (Utils.isEmpty(frm.elements['password'].value)) {
                    alert(password_not_null);
                    return false;
                }

                if (frm.elements['password'].value.length < 6) {
                    alert(password_lt_six);
                    return false;
                }

                if (frm.elements['password'].value != frm.elements['confirm_password'].value) {
                    alert(password_not_same);
                    return false;
                }
                return true;
            }

        </script>
        <!-- 开始用户登录注册界面 -->
        <div class="flowBox">
            <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                <tr>
                    <td width="50%" valign="top" bgcolor="#ffffff">
                        <h6><span>用户登录：</span></h6>
                        <form action="flow.php?step=login" method="post" name="loginForm" id="loginForm"
                              onsubmit="return checkLoginForm(this)">
                            <table width="90%" border="0" cellpadding="8" cellspacing="1" bgcolor="#B0D8FF"
                                   class="table">
                                <tr>
                                    <td bgcolor="#ffffff">
                                        <div align="right"><strong>{{ $lang['username'] }}</strong></div>
                                    </td>
                                    <td bgcolor="#ffffff"><input name="username" type="text" class="inputBg"
                                                                 id="username"/></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#ffffff">
                                        <div align="right"><strong>{{ $lang['password'] }}</strong></div>
                                    </td>
                                    <td bgcolor="#ffffff"><input name="password" class="inputBg" type="password"/></td>
                                </tr>
                                <!-- 判断是否启用验证码@if($enabled_login_captcha)
                                    -->
                                    <tr>
                                        <td bgcolor="#ffffff">
                                            <div align="right"><strong>{{ $lang['comment_captcha'] }}:</strong></div>
                                        </td>
                                        <td bgcolor="#ffffff"><input type="text" size="8" name="captcha"
                                                                     class="inputBg"/>
                                            <img src="captcha.php?is_login=1&{{ $rand }}" alt="captcha"
                                                 style="vertical-align: middle;cursor: pointer;"
                                                 onClick="this.src='captcha.php?is_login=1&'+Math.random()"/></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="2" bgcolor="#ffffff"><input type="checkbox" value="1" name="remember"
                                                                             id="remember"/><label
                                            for="remember">{{ $lang['remember'] }}</label></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#ffffff" colspan="2" align="center"><a
                                            href="user.php?act=qpassword_name"
                                            class="f6">{{ $lang['get_password_by_question'] }}</a>&nbsp;&nbsp;&nbsp;<a
                                            href="user.php?act=get_password"
                                            class="f6">{{ $lang['get_password_by_mail'] }}</a></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#ffffff" colspan="2">
                                        <div align="center">
                                            <input type="submit" class="bnt_blue" name="login"
                                                   value="{{ $lang['forthwith_login'] }}"/>
                                            <!-- @if($anonymous_buy == 1)
                                                是否允许未登录用户购物 -->
                                                <input type="button" class="bnt_blue_2"
                                                       value="{{ $lang['direct_shopping'] }}"
                                                       onclick="location.href='flow.php?step=consignee&amp;direct_shopping=1'"/>
                                            @endif
                                            <input name="act" type="hidden" value="signin"/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>

                    </td>
                    <td valign="top" bgcolor="#ffffff">
                        <h6><span>用户注册：</span></h6>
                        <form action="flow.php?step=login" method="post" name="formUser" id="registerForm"
                              onsubmit="return checkSignupForm(this)">
                            <table width="98%" border="0" cellpadding="8" cellspacing="1" bgcolor="#B0D8FF"
                                   class="table">
                                <tr>
                                    <td bgcolor="#ffffff" align="right" width="25%">
                                        <strong>{{ $lang['username'] }}</strong></td>
                                    <td bgcolor="#ffffff"><input name="username" type="text" class="inputBg"
                                                                 id="username"
                                                                 onblur="is_registered(this.value);"/><br/>
                                        <span id="username_notice" style="color:#FF0000"></span></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#ffffff" align="right"><strong>{{ $lang['email_address'] }}</strong>
                                    </td>
                                    <td bgcolor="#ffffff"><input name="email" type="text" class="inputBg" id="email"
                                                                 onblur="checkEmail(this.value);"/><br/>
                                        <span id="email_notice" style="color:#FF0000"></span></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#ffffff" align="right"><strong>{{ $lang['password'] }}</strong></td>
                                    <td bgcolor="#ffffff"><input name="password" class="inputBg" type="password"
                                                                 id="password1" onblur="check_password(this.value);"
                                                                 onkeyup="checkIntensity(this.value)"/><br/>
                                        <span style="color:#FF0000" id="password_notice"></span></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#ffffff" align="right"><strong>{{ $lang['confirm_password'] }}</strong>
                                    </td>
                                    <td bgcolor="#ffffff"><input name="confirm_password" class="inputBg" type="password"
                                                                 id="confirm_password"
                                                                 onblur="check_conform_password(this.value);"/><br/>
                                        <span style="color:#FF0000" id="conform_password_notice"></span></td>
                                </tr>
                                <!-- 判断是否启用验证码@if($enabled_register_captcha)
                                    -->
                                    <tr>
                                        <td bgcolor="#ffffff" align="right"><strong>{{ $lang['comment_captcha'] }}
                                                :</strong></td>
                                        <td bgcolor="#ffffff"><input type="text" size="8" name="captcha"
                                                                     class="inputBg"/>
                                            <img src="captcha.php?{{ $rand }}" alt="captcha"
                                                 style="vertical-align: middle;cursor: pointer;"
                                                 onClick="this.src='captcha.php?'+Math.random()"/></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="2" bgcolor="#ffffff" align="center">
                                        <input type="submit" name="Submit" class="bnt_blue_1"
                                               value="{{ $lang['forthwith_register'] }}"/>
                                        <input name="act" type="hidden" value="signup"/>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </td>
                </tr>
                @if($need_rechoose_gift)
                    <tr>
                        <td colspan="2" align="center"
                            style="border-top:1px #ccc solid; padding:5px; color:red;">{{ $lang['gift_remainder'] }}</td>
                    </tr>
                @endif
            </table>
        </div>
        <!-- 结束用户登录注册界面 -->
    @endif


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
    var process_request = "{{ $lang['process_request'] }}";
    @foreach($lang['passport_js'] as $item => $key)
    var {{ $key }} = "{{ $item }}";
    @endforeach
    var username_exist = "{{ $lang['username_exist'] }}";
    var compare_no_goods = "{{ $lang['compare_no_goods'] }}";
    var btn_buy = "{{ $lang['btn_buy'] }}";
    var is_cancel = "{{ $lang['is_cancel'] }}";
    var select_spe = "{{ $lang['select_spe'] }}";
</script>
</html>
