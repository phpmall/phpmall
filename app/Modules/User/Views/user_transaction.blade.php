<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="Keywords" content="{{ $keywords }}"/>
    <meta name="Description" content="{{ $description }}"/>
    <title>{{ $page_title }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link href="{{ $ecs_css_path }}" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/user.js') }}"></script>
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
        <div class="box">
            <div class="box_1">
                <div class="userCenterBox">
                    @include('web::library/user_menu')
                </div>
            </div>
        </div>
    </div>
    <!--left end-->
    <!--right start-->
    <div class="AreaR">
        <div class="box">
            <div class="box_1">
                <div class="userCenterBox boxCenterList clearfix" style="_height:1%;">
                    <!-- 用户信息界面 start-->
                    @if($action == 'profile')
                        <script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
                        <script type="text/javascript">
                            @foreach($lang['profile_js'] as $item => $key)
                            var {{ $key }} = "{{ $item }}";
                            @endforeach
                        </script>
                        <h5><span>{{ $lang['profile'] }}</span></h5>
                        <div class="blank"></div>
                        <form name="formEdit" action="user.php" method="post" onSubmit="return userEdit()">
                            <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                <tr>
                                    <td width="28%" align="right" bgcolor="#FFFFFF">{{ $lang['birthday'] }}：</td>
                                    <td width="72%" align="left" bgcolor="#FFFFFF"> {html_select_date field_order=YMD
                                        prefix=birthday start_year=-60 end_year=+1 display_days=true month_format=%m
                                        day_value_format=%02d time=$profile.birthday}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="28%" align="right" bgcolor="#FFFFFF">{{ $lang['sex'] }}：</td>
                                    <td width="72%" align="left" bgcolor="#FFFFFF"><input type="radio" name="sex"
                                                                                          value="0"
                                                                                          @if($profile['sex']==0)checked="checked"@endif />
                                        {{ $lang['secrecy'] }}&nbsp;&nbsp;
                                        <input type="radio" name="sex" value="1"
                                               @if($profile['sex']==1)checked="checked"@endif />
                                        {{ $lang['male'] }}&nbsp;&nbsp;
                                        <input type="radio" name="sex" value="2"
                                               @if($profile['sex']==2)checked="checked"@endif />
                                        {{ $lang['female'] }}&nbsp;&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td width="28%" align="right" bgcolor="#FFFFFF">{{ $lang['email'] }}：</td>
                                    <td width="72%" align="left" bgcolor="#FFFFFF"><input name="email" type="text"
                                                                                          value="{{ $profile['email'] }}"
                                                                                          size="25"
                                                                                          class="inputBg"/><span
                                            style="color:#FF0000"> *</span></td>
                                </tr>
                                @foreach($extend_info_list as $field)
                                    @if($field['id'] == 6)
                                        <tr>
                                            <td width="28%" align="right"
                                                bgcolor="#FFFFFF">{{ $lang['passwd_question'] }}：
                                            </td>
                                            <td width="72%" align="left" bgcolor="#FFFFFF">
                                                <select name='sel_question'>
                                                    <option value='0'>{{ $lang['sel_question'] }}</option>
                                                    {html_options options=$passwd_questions
                                                    selected=$profile.passwd_question}
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="28%" align="right" bgcolor="#FFFFFF"
                                                @if($field['is_need'])id="passwd_quesetion"@endif>{{ $lang['passwd_answer'] }}
                                                ：
                                            </td>
                                            <td width="72%" align="left" bgcolor="#FFFFFF">
                                                <input name="passwd_answer" type="text" size="25" class="inputBg"
                                                       maxlengt='20'
                                                       value="{{ $profile['passwd_answer'] }}"/>@if($field['is_need'])
                                                    <span style="color:#FF0000"> *</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td width="28%" align="right" bgcolor="#FFFFFF"
                                                @if($field['is_need'])id="extend_field{{ $field['id'] }}i"@endif>{{ $field['reg_field_name'] }}
                                                ：
                                            </td>
                                            <td width="72%" align="left" bgcolor="#FFFFFF">
                                                <input name="extend_field{{ $field['id'] }}" type="text" class="inputBg"
                                                       value="{{ $field['content'] }}"/>@if($field['is_need'])
                                                    <span style="color:#FF0000"> *</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td colspan="2" align="center" bgcolor="#FFFFFF"><input name="act" type="hidden"
                                                                                            value="act_edit_profile"/>
                                        <input name="submit" type="submit" value="{{ $lang['confirm_edit'] }}"
                                               class="bnt_blue_1" style="border:none;"/>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <form name="formPassword" action="user.php" method="post" onSubmit="return editPassword()">
                            <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                <tr>
                                    <td width="28%" align="right" bgcolor="#FFFFFF">{{ $lang['old_password'] }}：</td>
                                    <td width="76%" align="left" bgcolor="#FFFFFF"><input name="old_password"
                                                                                          type="password" size="25"
                                                                                          class="inputBg"/></td>
                                </tr>
                                <tr>
                                    <td width="28%" align="right" bgcolor="#FFFFFF">{{ $lang['new_password'] }}：</td>
                                    <td align="left" bgcolor="#FFFFFF"><input name="new_password" type="password"
                                                                              size="25" class="inputBg"/></td>
                                </tr>
                                <tr>
                                    <td width="28%" align="right" bgcolor="#FFFFFF">{{ $lang['confirm_password'] }}：
                                    </td>
                                    <td align="left" bgcolor="#FFFFFF"><input name="comfirm_password" type="password"
                                                                              size="25" class="inputBg"/></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center" bgcolor="#FFFFFF"><input name="act" type="hidden"
                                                                                            value="act_edit_password"/>
                                        <input name="submit" type="submit" class="bnt_blue_1" style="border:none;"
                                               value="{{ $lang['confirm_edit'] }}"/>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    @endif
                    <!--#用户信息界面 end-->
                    <!-- @if($action == 'bonus')
                        用户的红包列表 start-->
                        <script type="text/javascript">
                            @foreach($lang['profile_js'] as $item => $key)
                            var {{ $key }} = "{{ $item }}";
                            @endforeach
                        </script>
                        <h5><span>{{ $lang['label_bonus'] }}</span></h5>
                        <div class="blank"></div>
                        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                            <tr>
                                <th align="center" bgcolor="#FFFFFF">{{ $lang['bonus_sn'] }}</th>
                                <th align="center" bgcolor="#FFFFFF">{{ $lang['bonus_name'] }}</th>
                                <th align="center" bgcolor="#FFFFFF">{{ $lang['bonus_amount'] }}</th>
                                <th align="center" bgcolor="#FFFFFF">{{ $lang['min_goods_amount'] }}</th>
                                <th align="center" bgcolor="#FFFFFF">{{ $lang['bonus_end_date'] }}</th>
                                <th align="center" bgcolor="#FFFFFF">{{ $lang['bonus_status'] }}</th>
                            </tr>
                            @if($bonus)
                                @foreach($bonus as $item)
                                    <tr>
                                        <td align="center" bgcolor="#FFFFFF">{{ $item['bonus_sn'] ?? N/A }}</td>
                                        <td align="center" bgcolor="#FFFFFF">{{ $item['type_name'] }}</td>
                                        <td align="center" bgcolor="#FFFFFF">{{ $item['type_money'] }}</td>
                                        <td align="center" bgcolor="#FFFFFF">{{ $item['min_goods_amount'] }}</td>
                                        <td align="center" bgcolor="#FFFFFF">{{ $item['use_enddate'] }}</td>
                                        <td align="center" bgcolor="#FFFFFF">{{ $item['status'] }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" bgcolor="#FFFFFF">{{ $lang['user_bonus_empty'] }}</td>
                                </tr>
                            @endif
                        </table>
                        <div class="blank5"></div>
                        @include('web::library/pages')
                        <div class="blank5"></div>
                        <h5><span>{{ $lang['add_bonus'] }}</span></h5>
                        <div class="blank"></div>
                        <form name="addBouns" action="user.php" method="post" onSubmit="return addBonus()">
                            <div style="padding: 15px;">
                                {{ $lang['bonus_number'] }}
                                <input name="bonus_sn" type="text" size="30" class="inputBg"/>
                                <input type="hidden" name="act" value="act_add_bonus" class="inputBg"/>
                                <input type="submit" class="bnt_blue_1" style="border:none;"
                                       value="{{ $lang['add_bonus'] }}"/>
                            </div>
                        </form>
                    @endif
                    <!--用户红包结束-->
                    <!--#订单列表界面 start-->
                    @if($action == 'order_list')
                        <h5><span>{{ $lang['label_order'] }}</span></h5>
                        <div class="blank"></div>
                        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                            <tr align="center">
                                <td bgcolor="#ffffff">{{ $lang['order_number'] }}</td>
                                <td bgcolor="#ffffff">{{ $lang['order_addtime'] }}</td>
                                <td bgcolor="#ffffff">{{ $lang['order_money'] }}</td>
                                <td bgcolor="#ffffff">{{ $lang['order_status'] }}</td>
                                <td bgcolor="#ffffff">{{ $lang['handle'] }}</td>
                            </tr>
                            @foreach($orders as $item)
                                <tr>
                                    <td align="center" bgcolor="#ffffff"><a
                                            href="user.php?act=order_detail&order_id={{ $item['order_id'] }}"
                                            class="f6">{{ $item['order_sn'] }}</a></td>
                                    <td align="center" bgcolor="#ffffff">{{ $item['order_time'] }}</td>
                                    <td align="right" bgcolor="#ffffff">{{ $item['total_fee'] }}</td>
                                    <td align="center" bgcolor="#ffffff">{{ $item['order_status'] }}</td>
                                    <td align="center" bgcolor="#ffffff"><font class="f6">{{ $item['handler'] }}</font>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="blank5"></div>
                        @include('web::library/pages')
                        <div class="blank5"></div>
                        <h5><span>{{ $lang['merge_order'] }}</span></h5>
                        <div class="blank"></div>
                        <script type="text/javascript">
                            @foreach($lang['merge_order_js'] as $item => $key)
                            var {{ $key }} = "{{ $item }}";
                            @endforeach
                        </script>
                        <form action="user.php" method="post" name="formOrder" onsubmit="return mergeOrder()">
                            <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                <tr>
                                    <td width="22%" align="right" bgcolor="#ffffff">{{ $lang['first_order'] }}:</td>
                                    <td width="12%" align="left" bgcolor="#ffffff"><select name="to_order">
                                            <option value="0">{{ $lang['select'] }}</option>

                                            {html_options options=$merge}

                                        </select></td>
                                    <td width="19%" align="right" bgcolor="#ffffff">{{ $lang['second_order'] }}:</td>
                                    <td width="11%" align="left" bgcolor="#ffffff"><select name="from_order">
                                            <option value="0">{{ $lang['select'] }}</option>

                                            {html_options options=$merge}

                                        </select></td>
                                    <td width="36%" bgcolor="#ffffff">&nbsp;<input name="act" value="merge_order"
                                                                                   type="hidden"/>
                                        <input type="submit" name="Submit" class="bnt_blue_1" style="border:none;"
                                               value="{{ $lang['merge_order'] }}"/></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#ffffff">&nbsp;</td>
                                    <td colspan="4" align="left"
                                        bgcolor="#ffffff">{{ $lang['merge_order_notice'] }}</td>
                                </tr>
                            </table>
                        </form>
                    @endif
                    <!--#订单列表界面 end-->
                    <!--#包裹状态查询界面 start-->
                    @if($action == 'track_packages')
                        <h5><span>{{ $lang['label_track_packages'] }}</span></h5>
                        <div class="blank"></div>
                        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd"
                               id="order_table">
                            <tr align="center">
                                <td bgcolor="#ffffff">{{ $lang['order_number'] }}</td>
                                <td bgcolor="#ffffff">{{ $lang['handle'] }}</td>
                            </tr>
                            @foreach($orders as $item)
                                <tr>
                                    <td align="center" bgcolor="#ffffff"><a
                                            href="user.php?act=order_detail&order_id={{ $item['order_id'] }}">{{ $item['order_sn'] }}</a>
                                    </td>
                                    <td align="center" bgcolor="#ffffff">{{ $item['query_link'] }}</td>
                                </tr>
                            @endforeach
                        </table>
                        <script type="text/javascript">
                            var query_status = '{{ $lang['query_status'] }}';
                            var ot = document.getElementById('order_table');
                            for (var i = 1; i < ot.rows.length; i++) {
                                var row = ot.rows[i];
                                var cel = row.cells[1];
                                cel.getElementsByTagName('a').item(0).innerHTML = query_status;
                            }
                        </script>
                        <div class="blank5"></div>
                        @include('web::library/pages')
                    @endif
                    <!--#包裹状态查询界面 end-->
                    <!-- ==========订单详情页面,包括：订单状态，商品列表，费用总计，收货人信息，支付方式，其它信息========== -->
                    @if($action == order_detail)
                        <h5><span>{{ $lang['order_status'] }}</span></h5>
                        <div class="blank"></div>
                        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                            <tr>
                                <td width="15%" align="right" bgcolor="#ffffff">{{ $lang['detail_order_sn'] }}：</td>
                                <td align="left" bgcolor="#ffffff">{{ $order['order_sn'] }}
                                    @if($order['extension_code'] == "group_buy")
                                        <a href="./group_buy.php?act=view&id={{ $order['extension_id'] }}"
                                           class="f6"><strong>{{ $lang['order_is_group_buy'] }}</strong></a>
                                    @elseif($order['extension_code'] == "exchange_goods")
                                        <a href="./exchange.php?act=view&id={{ $order['extension_id'] }}"
                                           class="f6"><strong>{{ $lang['order_is_exchange'] }}</strong></a>
                                    @endif
                                    <a href="user.php?act=message_list&order_id={{ $order['order_id'] }}"
                                       class="f6">[{{ $lang['business_message'] }}]</a>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" bgcolor="#ffffff">{{ $lang['detail_order_status'] }}：</td>
                                <td align="left" bgcolor="#ffffff">{{ $order['order_status'] }}
                                    &nbsp;&nbsp;&nbsp;&nbsp;{{ $order['confirm_time'] }}</td>
                            </tr>
                            <tr>
                                <td align="right" bgcolor="#ffffff">{{ $lang['detail_pay_status'] }}：</td>
                                <td align="left" bgcolor="#ffffff">{{ $order['pay_status'] }}
                                    &nbsp;&nbsp;&nbsp;&nbsp;@if($order['order_amount'] > 0)
                                        {{ $order['pay_online'] }}
                                    @endif{{ $order['pay_time'] }}</td>
                            </tr>
                            <tr>
                                <td align="right" bgcolor="#ffffff">{{ $lang['detail_shipping_status'] }}：</td>
                                <td align="left" bgcolor="#ffffff">{{ $order['shipping_status'] }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $order['shipping_time'] }}</td>
                            </tr>
                            @if($order['invoice_no'])
                                <tr>
                                    <td align="right" bgcolor="#ffffff">{{ $lang['consignment'] }}：</td>
                                    <td align="left" bgcolor="#ffffff">{{ $order['invoice_no'] }}</td>
                                </tr>
                            @endif
                            @if($order['to_buyer'])
                                <tr>
                                    <td align="right" bgcolor="#ffffff">{{ $lang['detail_to_buyer'] }}：</td>
                                    <td align="left" bgcolor="#ffffff">{{ $order['to_buyer'] }}</td>
                                </tr>
                            @endif

                            @if($virtual_card)
                                <tr>
                                    <td align="right" bgcolor="#ffffff">{{ $lang['virtual_card_info'] }}：</td>
                                    <td colspan="3" align="left" bgcolor="#ffffff">
                                        @foreach($virtual_card as $vgoods)
                                            @foreach($vgoods['info'] as $card)
                                                @if($card['card_sn'])
                                                    {{ $lang['card_sn'] }}:<span
                                                        style="color:red;">{{ $card['card_sn'] }}</span>
                                                @endif
                                                @if($card['card_password'])
                                                    {{ $lang['card_password'] }}:<span
                                                        style="color:red;">{{ $card['card_password'] }}</span>
                                                @endif
                                                @if($card['end_date'])
                                                    {{ $lang['end_date'] }}:{{ $card['end_date'] }}
                                                @endif<br/>
                                            @endforeach
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <div class="blank"></div>
                        <h5><span>{{ $lang['goods_list'] }}</span>
                            @if($allow_to_cart)
                                <a href="javascript:;" onclick="returnToCart({{ $order['order_id'] }})"
                                   class="f6">{{ $lang['return_to_cart'] }}</a>
                            @endif
                        </h5>
                        <div class="blank"></div>
                        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                            <tr>
                                <th width="23%" align="center" bgcolor="#ffffff">{{ $lang['goods_name'] }}</th>
                                <th width="29%" align="center" bgcolor="#ffffff">{{ $lang['goods_attr'] }}</th>
                                <!--<th>{{ $lang['market_price'] }}</th>-->
                                <th width="26%" align="center"
                                    bgcolor="#ffffff">{{ $lang['goods_price'] }}@if($order['extension_code'] == "group_buy")
                                        {{ $lang['gb_deposit'] }}
                                    @endif</th>
                                <th width="9%" align="center" bgcolor="#ffffff">{{ $lang['number'] }}</th>
                                <th width="20%" align="center" bgcolor="#ffffff">{{ $lang['subtotal'] }}</th>
                            </tr>
                            @foreach($goods_list as $goods)
                                <tr>
                                    <td bgcolor="#ffffff">
                                        <!-- @if($goods['goods_id'] > 0 && $goods['extension_code'] != 'package_buy')
                                            商品 -->
                                            <a href="goods.php?id={{ $goods['goods_id'] }}" target="_blank"
                                               class="f6">{{ $goods['goods_name'] }}</a>
                                            @if($goods['parent_id'] > 0)
                                                <span style="color:#FF0000">（{{ $lang['accessories'] }}）</span>
                                            @elseif($goods['is_gift'])
                                                <span style="color:#FF0000">（{{ $lang['largess'] }}）</span>
                                            @endif
                                        @elseif($goods['goods_id'] > 0 && $goods['extension_code'] == 'package_buy')
                                            <a href="javascript:void(0)" onclick="setSuitShow({{ $goods['goods_id'] }})"
                                               class="f6">{{ $goods['goods_name'] }}<span
                                                    style="color:#FF0000;">（礼包）</span></a>
                                            <div id="suit_{{ $goods['goods_id'] }}" style="display:none">
                                                @foreach($goods['package_goods_list'] as $package_goods_list)
                                                    <a href="goods.php?id={{ $package_goods_list['goods_id'] }}"
                                                       target="_blank"
                                                       class="f6">{{ $package_goods_list['goods_name'] }}</a><br/>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td align="left" bgcolor="#ffffff">{$goods.goods_attr|nl2br}</td>
                                    <!--<td align="right">{{ $goods['market_price'] }}</td>-->
                                    <td align="right" bgcolor="#ffffff">{{ $goods['goods_price'] }}</td>
                                    <td align="center" bgcolor="#ffffff">{{ $goods['goods_number'] }}</td>
                                    <td align="right" bgcolor="#ffffff">{{ $goods['subtotal'] }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="8" bgcolor="#ffffff" align="right">
                                    {{ $lang['shopping_money'] }}@if($order['extension_code'] == "group_buy")
                                        {{ $lang['gb_deposit'] }}
                                    @endif: {{ $order['formated_goods_amount'] }}
                                </td>
                            </tr>
                        </table>
                        <div class="blank"></div>
                        <h5><span>{{ $lang['fee_total'] }}</span></h5>
                        <div class="blank"></div>
                        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                            <tr>
                                <td align="right" bgcolor="#ffffff">
                                    {{ $lang['goods_all_price'] }}@if($order['extension_code'] == "group_buy")
                                        {{ $lang['gb_deposit'] }}
                                    @endif: {{ $order['formated_goods_amount'] }}
                                    <!-- @if($order['discount'] > 0)
                                        折扣 -->
                                        - {{ $lang['discount'] }}: {{ $order['formated_discount'] }}
                                    @endif
                                    @if($order['tax'] > 0)
                                        + {{ $lang['tax'] }}: {{ $order['formated_tax'] }}
                                    @endif
                                    @if($order['shipping_fee'] > 0)
                                        + {{ $lang['shipping_fee'] }}: {{ $order['formated_shipping_fee'] }}
                                    @endif
                                    @if($order['insure_fee'] > 0)
                                        + {{ $lang['insure_fee'] }}: {{ $order['formated_insure_fee'] }}
                                    @endif
                                    @if($order['pay_fee'] > 0)
                                        + {{ $lang['pay_fee'] }}: {{ $order['formated_pay_fee'] }}
                                    @endif
                                    @if($order['pack_fee'] > 0)
                                        + {{ $lang['pack_fee'] }}: {{ $order['formated_pack_fee'] }}
                                    @endif
                                    @if($order['card_fee'] > 0)
                                        + {{ $lang['card_fee'] }}: {{ $order['formated_card_fee'] }}
                                    @endif        </td>
                            </tr>
                            <tr>
                                <td align="right" bgcolor="#ffffff">
                                    @if($order['money_paid'] > 0)
                                        - {{ $lang['order_money_paid'] }}: {{ $order['formated_money_paid'] }}
                                    @endif
                                    @if($order['surplus'] > 0)
                                        - {{ $lang['use_surplus'] }}: {{ $order['formated_surplus'] }}
                                    @endif
                                    @if($order['integral_money'] > 0)
                                        - {{ $lang['use_integral'] }}: {{ $order['formated_integral_money'] }}
                                    @endif
                                    @if($order['bonus'] > 0)
                                        - {{ $lang['use_bonus'] }}: {{ $order['formated_bonus'] }}
                                    @endif        </td>
                            </tr>
                            <tr>
                                <td align="right" bgcolor="#ffffff">{{ $lang['order_amount'] }}
                                    : {{ $order['formated_order_amount'] }}
                                    @if($order['extension_code'] == "group_buy")
                                        <br/>
                                        {{ $lang['notice_gb_order_amount'] }}
                                    @endif</td>
                            </tr>
                            <!-- @if($allow_edit_surplus)
                                如果可以编辑使用余额数 -->
                                <tr>
                                    <td align="right" bgcolor="#ffffff">
                                        <form action="user.php" method="post" name="formFee"
                                              id="formFee">{{ $lang['use_more_surplus'] }}:
                                            <input name="surplus" type="text" size="8" value="0"
                                                   style="border:1px solid #ccc;"/>{{ $max_surplus }}
                                            <input type="submit" name="Submit" class="submit"
                                                   value="{{ $lang['button_submit'] }}"/>
                                            <input type="hidden" name="act" value="act_edit_surplus"/>
                                            <input type="hidden" name="order_id"
                                                   value="{{ $smarty['get']['order_id'] }}"/>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <div class="blank"></div>
                        <h5><span>{{ $lang['consignee_info'] }}</span></h5>
                        <div class="blank"></div>
                        @if($order['allow_update_address'] > 0)
                            <form action="user.php" method="post" name="formAddress" id="formAddress">
                                <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                    <tr>
                                        <td width="15%" align="right" bgcolor="#ffffff">{{ $lang['consignee_name'] }}：
                                        </td>
                                        <td width="35%" align="left" bgcolor="#ffffff"><input name="consignee"
                                                                                              type="text"
                                                                                              class="inputBg"
                                                                                              value="{{ $order['consignee'] }}"
                                                                                              size="25">
                                        </td>
                                        <td width="15%" align="right" bgcolor="#ffffff">{{ $lang['email_address'] }}：
                                        </td>
                                        <td width="35%" align="left" bgcolor="#ffffff"><input name="email" type="text"
                                                                                              class="inputBg"
                                                                                              value="{{ $order['email'] }}"
                                                                                              size="25"/>
                                        </td>
                                    </tr>
                                    @if($order['exist_real_goods'])
                                        <!-- 只有虚拟商品处理-->
                                        <tr>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['detailed_address'] }}：</td>
                                            <td align="left" bgcolor="#ffffff"><input name="address" type="text"
                                                                                      class="inputBg"
                                                                                      value="{{ $order['address'] }} "
                                                                                      size="25"/></td>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['postalcode'] }}：</td>
                                            <td align="left" bgcolor="#ffffff"><input name="zipcode" type="text"
                                                                                      class="inputBg"
                                                                                      value="{{ $order['zipcode'] }}"
                                                                                      size="25"/></td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td align="right" bgcolor="#ffffff">{{ $lang['phone'] }}：</td>
                                        <td align="left" bgcolor="#ffffff"><input name="tel" type="text" class="inputBg"
                                                                                  value="{{ $order['tel'] }}"
                                                                                  size="25"/></td>
                                        <td align="right" bgcolor="#ffffff">{{ $lang['backup_phone'] }}：</td>
                                        <td align="left" bgcolor="#ffffff"><input name="mobile" type="text"
                                                                                  class="inputBg"
                                                                                  value="{{ $order['mobile'] }}"
                                                                                  size="25"/></td>
                                    </tr>
                                    @if($order['exist_real_goods'])
                                        <!-- 只有虚拟商品处理-->
                                        <tr>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['sign_building'] }}：</td>
                                            <td align="left" bgcolor="#ffffff"><input name="sign_building"
                                                                                      class="inputBg" type="text"
                                                                                      value="{{ $order['sign_building'] }}"
                                                                                      size="25"/>
                                            </td>
                                            <td align="right" bgcolor="#ffffff">{{ $lang['deliver_goods_time'] }}：</td>
                                            <td align="left" bgcolor="#ffffff"><input name="best_time" type="text"
                                                                                      class="inputBg"
                                                                                      value="{{ $order['best_time'] }}"
                                                                                      size="25"/>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="4" align="center" bgcolor="#ffffff"><input type="hidden" name="act"
                                                                                                value="save_order_address"/>
                                            <input type="hidden" name="order_id" value="{{ $order['order_id'] }}"/>
                                            <input type="submit" class="bnt_blue_2"
                                                   value="{{ $lang['update_address'] }}"/>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        @else
                            <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                <tr>
                                    <td width="15%" align="right" bgcolor="#ffffff">{{ $lang['consignee_name'] }}：</td>
                                    <td width="35%" align="left" bgcolor="#ffffff">{{ $order['consignee'] }}</td>
                                    <td width="15%" align="right" bgcolor="#ffffff">{{ $lang['email_address'] }}：</td>
                                    <td width="35%" align="left" bgcolor="#ffffff">{{ $order['email'] }}</td>
                                </tr>
                                @if($order['exist_real_goods'])
                                    <tr>
                                        <td align="right" bgcolor="#ffffff">{{ $lang['detailed_address'] }}：</td>
                                        <td colspan="3" align="left" bgcolor="#ffffff">{{ $order['address'] }}
                                            @if($order['zipcode'])
                                                [{{ $lang['postalcode'] }}: {{ $order['zipcode'] }}]
                                            @endif</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td align="right" bgcolor="#ffffff">{{ $lang['phone'] }}：</td>
                                    <td align="left" bgcolor="#ffffff">{{ $order['tel'] }} </td>
                                    <td align="right" bgcolor="#ffffff">{{ $lang['backup_phone'] }}：</td>
                                    <td align="left" bgcolor="#ffffff">{{ $order['mobile'] }}</td>
                                </tr>
                                @if($order['exist_real_goods'])
                                    <tr>
                                        <td align="right" bgcolor="#ffffff">{{ $lang['sign_building'] }}：</td>
                                        <td align="left" bgcolor="#ffffff">{{ $order['sign_building'] }} </td>
                                        <td align="right" bgcolor="#ffffff">{{ $lang['deliver_goods_time'] }}：</td>
                                        <td align="left" bgcolor="#ffffff">{{ $order['best_time'] }}</td>
                                    </tr>
                                @endif
                            </table>
                        @endif
                        <div class="blank"></div>
                        <h5><span>{{ $lang['payment'] }}</span></h5>
                        <div class="blank"></div>
                        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                            <tr>
                                <td bgcolor="#ffffff">
                                    {{ $lang['select_payment'] }}: {{ $order['pay_name'] }}。{{ $lang['order_amount'] }}:
                                    <strong>{{ $order['formated_order_amount'] }}</strong><br/>
                                    {{ $order['pay_desc'] }}
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor="#ffffff" align="right">
                                    @if($payment_list)
                                        <form name="payment" method="post" action="user.php">
                                            {{ $lang['change_payment'] }}:
                                            <select name="pay_id">
                                                @foreach($payment_list as $payment)
                                                    <option value="{{ $payment['pay_id'] }}">
                                                        {{ $payment['pay_name'] }}({{ $lang['pay_fee'] }}
                                                        :{{ $payment['format_pay_fee'] }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="act" value="act_edit_payment"/>
                                            <input type="hidden" name="order_id" value="{{ $order['order_id'] }}"/>
                                            <input type="submit" name="Submit" class="submit"
                                                   value="{{ $lang['button_submit'] }}"/>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <div class="blank"></div>
                        <h5><span>{{ $lang['other_info'] }}</span></h5>
                        <div class="blank"></div>
                        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                            @if($order['shipping_id'] > 0)
                                <tr>
                                    <td width="15%" align="right" bgcolor="#ffffff">{{ $lang['shipping'] }}：</td>
                                    <td colspan="3" width="85%" align="left"
                                        bgcolor="#ffffff">{{ $order['shipping_name'] }}</td>
                                </tr>
                            @endif

                            <tr>
                                <td width="15%" align="right" bgcolor="#ffffff">{{ $lang['payment'] }}：</td>
                                <td colspan="3" align="left" bgcolor="#ffffff">{{ $order['pay_name'] }}</td>
                            </tr>
                            @if($order['insure_fee'] > 0)
                            @endif
                            <!-- @if($order['pack_name'])
                                是否使用包装 -->
                                <tr>
                                    <td width="15%" align="right" bgcolor="#ffffff">{{ $lang['use_pack'] }}：</td>
                                    <td colspan="3" align="left" bgcolor="#ffffff">{{ $order['pack_name'] }}</td>
                                </tr>
                                <!--

                            @endif 是否使用包装 -->
                            <!-- @if($order['card_name'])
                                是否使用贺卡 -->
                                <tr>
                                    <td width="15%" align="right" bgcolor="#ffffff">{{ $lang['use_card'] }}：</td>
                                    <td colspan="3" align="left" bgcolor="#ffffff">{{ $order['card_name'] }}</td>
                                </tr>
                            @endif
                            <!-- @if($order['card_message'])
                                是否使用贺卡 -->
                                <tr>
                                    <td width="15%" align="right" bgcolor="#ffffff">{{ $lang['bless_note'] }}：</td>
                                    <td colspan="3" align="left" bgcolor="#ffffff">{{ $order['card_message'] }}</td>
                                </tr>
                                <!--

                            @endif 是否使用贺卡 -->
                            <!-- @if($order['surplus'] > 0)
                                是否使用余额 -->
                            @endif
                            <!-- @if($order['integral'] > 0)
                                是否使用积分 -->
                                <tr>
                                    <td width="15%" align="right" bgcolor="#ffffff">{{ $lang['use_integral'] }}：</td>
                                    <td colspan="3" align="left" bgcolor="#ffffff">{{ $order['integral'] }}</td>
                                </tr>
                                <!--

                            @endif 是否使用积分 -->
                            <!-- @if($order['bonus'] > 0)
                                是否使用红包 -->
                            @endif
                            <!-- @if($order['inv_payee'] && $order['inv_content'])
                                是否开发票 -->
                                <tr>
                                    <td width="15%" align="right" bgcolor="#ffffff">{{ $lang['invoice_title'] }}：</td>
                                    <td width="36%" align="left" bgcolor="#ffffff">{{ $order['inv_payee'] }}</td>
                                    <td width="19%" align="right" bgcolor="#ffffff">{{ $lang['invoice_content'] }}：</td>
                                    <td width="25%" align="left" bgcolor="#ffffff">{{ $order['inv_content'] }}</td>
                                </tr>
                            @endif
                            <!-- @if($order['postscript'])
                                是否有订单附言 -->
                                <tr>
                                    <td width="15%" align="right" bgcolor="#ffffff">{{ $lang['order_postscript'] }}：
                                    </td>
                                    <td colspan="3" align="left" bgcolor="#ffffff">{{ $order['postscript'] }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td width="15%" align="right" bgcolor="#ffffff">{{ $lang['booking_process'] }}：</td>
                                <td colspan="3" align="left" bgcolor="#ffffff">{{ $order['how_oos_name'] }}</td>
                            </tr>
                        </table>
                    @endif
                    <!--#订单详情页 end-->
                    <!--#会员余额 start-->
                    @if($action == "account_raply" || $action == "account_log" || $action == "account_deposit" || $action == "account_detail")
                        <script type="text/javascript">
                            @foreach($lang['account_js'] as $item => $key)
                            var {{ $key }} = "{{ $item }}";
                            @endforeach
                        </script>
                        <h5><span>{{ $lang['user_balance'] }}</span></h5>
                        <div class="blank"></div>
                        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                            <tr>
                                <td align="right" bgcolor="#ffffff"><a href="user.php?act=account_deposit"
                                                                       class="f6">{{ $lang['surplus_type_0'] }}</a> | <a
                                        href="user.php?act=account_raply" class="f6">{{ $lang['surplus_type_1'] }}</a> |
                                    <a href="user.php?act=account_detail" class="f6">{{ $lang['add_surplus_log'] }}</a>
                                    | <a href="user.php?act=account_log" class="f6">{{ $lang['view_application'] }}</a>
                                </td>
                            </tr>
                        </table>
                    @endif
                    @if($action == "account_raply")
                        <form name="formSurplus" method="post" action="user.php" onSubmit="return submitSurplus()">
                            <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                <tr>
                                    <td width="15%" bgcolor="#ffffff">{{ $lang['repay_money'] }}:</td>
                                    <td bgcolor="#ffffff" align="left"><input type="text" name="amount"
                                                                              value="{{ $order['amount'] }}"
                                                                              class="inputBg" size="30"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#ffffff">{{ $lang['process_notic'] }}:</td>
                                    <td bgcolor="#ffffff" align="left"><textarea name="user_note" cols="55" rows="6"
                                                                                 style="border:1px solid #ccc;">{{ $order['user_note'] }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#ffffff" colspan="2" align="center">
                                        <input type="hidden" name="surplus_type" value="1"/>
                                        <input type="hidden" name="act" value="act_account"/>
                                        <input type="submit" name="submit" class="bnt_blue_1"
                                               value="{{ $lang['submit_request'] }}"/>
                                        <input type="reset" name="reset" class="bnt_blue_1"
                                               value="{{ $lang['button_reset'] }}"/>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    @endif
                    @if($action == "account_deposit")
                        <form name="formSurplus" method="post" action="user.php" onSubmit="return submitSurplus()">
                            <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                <tr>
                                    <td width="15%" bgcolor="#ffffff">{{ $lang['deposit_money'] }}:</td>
                                    <td align="left" bgcolor="#ffffff"><input type="text" name="amount" class="inputBg"
                                                                              value="{{ $order['amount'] }}" size="30"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#ffffff">{{ $lang['process_notic'] }}:</td>
                                    <td align="left" bgcolor="#ffffff"><textarea name="user_note" cols="55" rows="6"
                                                                                 style="border:1px solid #ccc;">{{ $order['user_note'] }}</textarea>
                                    </td>
                                </tr>
                            </table>
                            <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                <tr align="center">
                                    <td bgcolor="#ffffff" colspan="3" align="left">{{ $lang['payment'] }}:</td>
                                </tr>
                                <tr align="center">
                                    <td bgcolor="#ffffff">{{ $lang['pay_name'] }}</td>
                                    <td bgcolor="#ffffff" width="60%">{{ $lang['pay_desc'] }}</td>
                                    <td bgcolor="#ffffff" width="17%">{{ $lang['pay_fee'] }}</td>
                                </tr>
                                @foreach($payment as $list)
                                    <tr>
                                        <td bgcolor="#ffffff" align="left">
                                            <input type="radio" name="payment_id"
                                                   value="{{ $list['pay_id'] }}"/>{{ $list['pay_name'] }}</td>
                                        <td bgcolor="#ffffff" align="left">{{ $list['pay_desc'] }}</td>
                                        <td bgcolor="#ffffff" align="center">{{ $list['pay_fee'] }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td bgcolor="#ffffff" colspan="3" align="center">
                                        <input type="hidden" name="surplus_type" value="0"/>
                                        <input type="hidden" name="rec_id" value="{{ $order['id'] }}"/>
                                        <input type="hidden" name="act" value="act_account"/>
                                        <input type="submit" class="bnt_blue_1" name="submit"
                                               value="{{ $lang['submit_request'] }}"/>
                                        <input type="reset" class="bnt_blue_1" name="reset"
                                               value="{{ $lang['button_reset'] }}"/>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    @endif
                    @if($action == "act_account")
                        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                            <tr>
                                <td width="25%" align="right" bgcolor="#ffffff">{{ $lang['surplus_amount'] }}</td>
                                <td width="80%" bgcolor="#ffffff">{{ $amount }}</td>
                            </tr>
                            <tr>
                                <td align="right" bgcolor="#ffffff">{{ $lang['payment_name'] }}</td>
                                <td bgcolor="#ffffff">{{ $payment['pay_name'] }}</td>
                            </tr>
                            <tr>
                                <td align="right" bgcolor="#ffffff">{{ $lang['payment_fee'] }}</td>
                                <td bgcolor="#ffffff">{{ $pay_fee }}</td>
                            </tr>
                            <tr>
                                <td align="right" valign="middle" bgcolor="#ffffff">{{ $lang['payment_desc'] }}</td>
                                <td bgcolor="#ffffff">{{ $payment['pay_desc'] }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" bgcolor="#ffffff">{{ $payment['pay_button'] }}</td>
                            </tr>
                        </table>
                    @endif
                    @if($action == "account_detail")
                        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                            <tr align="center">
                                <td bgcolor="#ffffff">{{ $lang['process_time'] }}</td>
                                <td bgcolor="#ffffff">{{ $lang['surplus_pro_type'] }}</td>
                                <td bgcolor="#ffffff">{{ $lang['money'] }}</td>
                                <td bgcolor="#ffffff">{{ $lang['change_desc'] }}</td>
                            </tr>
                            @foreach($account_log as $item)
                                <tr>
                                    <td align="center" bgcolor="#ffffff">{{ $item['change_time'] }}</td>
                                    <td align="center" bgcolor="#ffffff">{{ $item['type'] }}</td>
                                    <td align="right" bgcolor="#ffffff">{{ $item['amount'] }}</td>
                                    <td bgcolor="#ffffff" title="{{ $item['change_desc'] }}">
                                        &nbsp;&nbsp;{{ $item['short_change_desc'] }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" align="center" bgcolor="#ffffff">
                                    <div align="right">{{ $lang['current_surplus'] }}{{ $surplus_amount }}</div>
                                </td>
                            </tr>
                        </table>
                        @include('web::library/pages')
                    @endif
                    @if($action == "account_log")
                        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                            <tr align="center">
                                <td bgcolor="#ffffff">{{ $lang['process_time'] }}</td>
                                <td bgcolor="#ffffff">{{ $lang['surplus_pro_type'] }}</td>
                                <td bgcolor="#ffffff">{{ $lang['money'] }}</td>
                                <td bgcolor="#ffffff">{{ $lang['process_notic'] }}</td>
                                <td bgcolor="#ffffff">{{ $lang['admin_notic'] }}</td>
                                <td bgcolor="#ffffff">{{ $lang['is_paid'] }}</td>
                                <td bgcolor="#ffffff">{{ $lang['handle'] }}</td>
                            </tr>
                            @foreach($account_log as $item)
                                <tr>
                                    <td align="center" bgcolor="#ffffff">{{ $item['add_time'] }}</td>
                                    <td align="left" bgcolor="#ffffff">{{ $item['type'] }}</td>
                                    <td align="right" bgcolor="#ffffff">{{ $item['amount'] }}</td>
                                    <td align="left" bgcolor="#ffffff">{{ $item['short_user_note'] }}</td>
                                    <td align="left" bgcolor="#ffffff">{{ $item['short_admin_note'] }}</td>
                                    <td align="center" bgcolor="#ffffff">{{ $item['pay_status'] }}</td>
                                    <td align="right" bgcolor="#ffffff">{{ $item['handle'] }}
                                        @if(($item['is_paid'] == 0 && $item['process_type'] == 1) || $item['handle'])
                                            <a href="user.php?act=cancel&id={{ $item['id'] }}"
                                               onclick="if (!confirm('{{ $lang['confirm_remove_account'] }}')) return false;">{{ $lang['is_cancel'] }}</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="7" align="right"
                                    bgcolor="#ffffff">{{ $lang['current_surplus'] }}{{ $surplus_amount }}</td>
                            </tr>
                        </table>
                        @include('web::library/pages')
                    @endif
                    <!--#会员余额 end-->
                    <!--#收货地址页面 -->
                    @if($action == 'address_list')
                        <h5><span>{{ $lang['consignee_info'] }}</span></h5>
                        <div class="blank"></div>

                        <script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
                        <script type="text/javascript" src="{{ asset('js/transport.js') }}"></script>
                        <script type="text/javascript" src="{{ asset('js/region.js') }}"></script>
                        <script type="text/javascript" src="{{ asset('js/shopping_flow.js') }}"></script>
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
                        @foreach($consignee_list as $consignee => $sn)
                            <form action="user.php" method="post" name="theForm" onsubmit="return checkConsignee(this)">
                                <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                    <tr>
                                        <td align="right" bgcolor="#ffffff">{{ $lang['country_province'] }}：</td>
                                        <td colspan="3" align="left" bgcolor="#ffffff"><select name="country"
                                                                                               id="selCountries_{{ $sn }}"
                                                                                               onchange="region.changed(this, 1, 'selProvinces_{{ $sn }}')">
                                                <option value="0">{{ $lang['please_select'] }}{$name_of_region[0]}
                                                </option>
                                                @foreach($country_list as $country)
                                                    <option value="{{ $country['region_id'] }}"
                                                            @if($consignee['country'] == $country['region_id'])selected@endif>{{ $country['region_name'] }}</option>
                                                @endforeach
                                            </select>
                                            <select name="province" id="selProvinces_{{ $sn }}"
                                                    onchange="region.changed(this, 2, 'selCities_{{ $sn }}')">
                                                <option value="0">{{ $lang['please_select'] }}{$name_of_region[1]}
                                                </option>
                                                @foreach($province_list[$sn] as $province)
                                                    <option value="{{ $province['region_id'] }}"
                                                            @if($consignee['province'] == $province['region_id'])selected@endif>{{ $province['region_name'] }}</option>
                                                @endforeach
                                            </select>
                                            <select name="city" id="selCities_{{ $sn }}"
                                                    onchange="region.changed(this, 3, 'selDistricts_{{ $sn }}')">
                                                <option value="0">{{ $lang['please_select'] }}{$name_of_region[2]}
                                                </option>
                                                @foreach($city_list[$sn] as $city)
                                                    <option value="{{ $city['region_id'] }}"
                                                            @if($consignee['city'] == $city['region_id'])selected@endif>{{ $city['region_name'] }}</option>
                                                @endforeach
                                            </select>
                                            <select name="district" id="selDistricts_{{ $sn }}"
                                                    @if(!$district_list['']$sn)style="display:none"@endif>
                                                <option value="0">{{ $lang['please_select'] }}{$name_of_region[3]}
                                                </option>
                                                @foreach($district_list[$sn] as $district)
                                                    <option value="{{ $district['region_id'] }}"
                                                            @if($consignee['district'] == $district['region_id'])selected@endif>{{ $district['region_name'] }}</option>
                                                @endforeach
                                            </select>
                                            {{ $lang['require_field'] }} </td>
                                    </tr>
                                    <tr>
                                        <td align="right" bgcolor="#ffffff">{{ $lang['consignee_name'] }}：</td>
                                        <td align="left" bgcolor="#ffffff"><input name="consignee" type="text"
                                                                                  class="inputBg"
                                                                                  id="consignee_{{ $sn }}"
                                                                                  value="{{ $consignee['consignee'] }}"/>
                                            {{ $lang['require_field'] }} </td>
                                        <td align="right" bgcolor="#ffffff">{{ $lang['email_address'] }}：</td>
                                        <td align="left" bgcolor="#ffffff"><input name="email" type="text"
                                                                                  class="inputBg" id="email_{{ $sn }}"
                                                                                  value="{{ $consignee['email'] }}"/>
                                            {{ $lang['require_field'] }}</td>
                                    </tr>
                                    <tr>
                                        <td align="right" bgcolor="#ffffff">{{ $lang['detailed_address'] }}：</td>
                                        <td align="left" bgcolor="#ffffff"><input name="address" type="text"
                                                                                  class="inputBg" id="address_{{ $sn }}"
                                                                                  value="{{ $consignee['address'] }}"/>
                                            {{ $lang['require_field'] }}</td>
                                        <td align="right" bgcolor="#ffffff">{{ $lang['postalcode'] }}：</td>
                                        <td align="left" bgcolor="#ffffff"><input name="zipcode" type="text"
                                                                                  class="inputBg" id="zipcode_{{ $sn }}"
                                                                                  value="{{ $consignee['zipcode'] }}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right" bgcolor="#ffffff">{{ $lang['phone'] }}：</td>
                                        <td align="left" bgcolor="#ffffff"><input name="tel" type="text" class="inputBg"
                                                                                  id="tel_{{ $sn }}"
                                                                                  value="{{ $consignee['tel'] }}"/>
                                            {{ $lang['require_field'] }}</td>
                                        <td align="right" bgcolor="#ffffff">{{ $lang['backup_phone'] }}：</td>
                                        <td align="left" bgcolor="#ffffff"><input name="mobile" type="text"
                                                                                  class="inputBg" id="mobile_{{ $sn }}"
                                                                                  value="{{ $consignee['mobile'] }}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right" bgcolor="#ffffff">{{ $lang['sign_building'] }}：</td>
                                        <td align="left" bgcolor="#ffffff"><input name="sign_building" type="text"
                                                                                  class="inputBg"
                                                                                  id="sign_building_{{ $sn }}"
                                                                                  value="{{ $consignee['sign_building'] }}"/>
                                        </td>
                                        <td align="right" bgcolor="#ffffff">{{ $lang['deliver_goods_time'] }}：</td>
                                        <td align="left" bgcolor="#ffffff"><input name="best_time" type="text"
                                                                                  class="inputBg"
                                                                                  id="best_time_{{ $sn }}"
                                                                                  value="{{ $consignee['best_time'] }}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right" bgcolor="#ffffff">&nbsp;</td>
                                        <td colspan="3" align="center"
                                            bgcolor="#ffffff">@if($consignee['consignee'] && $consignee['email'])
                                                <input type="submit" name="submit" class="bnt_blue_1"
                                                       value="{{ $lang['confirm_edit'] }}"/>
                                                <input name="button" type="button" class="bnt_blue"
                                                       onclick="if (confirm('{{ $lang['confirm_drop_address'] }}'))location.href='user.php?act=drop_consignee&id={{ $consignee['address_id'] }}'"
                                                       value="{{ $lang['drop'] }}"/>
                                            @else
                                                <input type="submit" name="submit" class="bnt_blue_2"
                                                       value="{{ $lang['add_address'] }}"/>
                                            @endif
                                            <input type="hidden" name="act" value="act_edit_address"/>
                                            <input name="address_id" type="hidden"
                                                   value="{{ $consignee['address_id'] }}"/>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        @endforeach
                    @endif
                    <!--#收货地址添加页面 -->
                    <!--* 积分兑换-->
                    @if($action == 'transform_points')
                        <h5><span>{{ $lang['transform_points'] }}</span></h5>
                        <div class="blank"></div>
                        @if($exchange_type == 'ucenter')
                            <form action="user.php" method="post" name="transForm" onsubmit="return calcredit();">
                                <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                    <tr>
                                        <th width="120" bgcolor="#FFFFFF" align="right"
                                            valign="top">{{ $lang['cur_points'] }}:
                                        </th>
                                        <td bgcolor="#FFFFFF">
                                            <label for="pay_points">{{ $lang['exchange_points']['1'] }}:</label><input
                                                type="text" size="15" id="pay_points" name="pay_points"
                                                value="{{ $shop_points['pay_points'] }}"
                                                style="border:0;border-bottom:1px solid #DADADA;"
                                                readonly="readonly"/><br/>
                                            <div class="blank"></div>
                                            <label for="rank_points">{{ $lang['exchange_points']['0'] }}:</label><input
                                                type="text" size="15" id="rank_points" name="rank_points"
                                                value="{{ $shop_points['rank_points'] }}"
                                                style="border:0;border-bottom:1px solid #DADADA;" readonly="readonly"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#FFFFFF">&nbsp;</td>
                                        <td bgcolor="#FFFFFF">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <th align="right" bgcolor="#FFFFFF"><label
                                                for="amount">{{ $lang['exchange_amount'] }}:</label></th>
                                        <td bgcolor="#FFFFFF"><input size="15" name="amount" id="amount" value="0"
                                                                     onkeyup="calcredit();" type="text"/>
                                            <select name="fromcredits" id="fromcredits" onchange="calcredit();">
                                                {html_options options=$lang.exchange_points selected=$selected_org}
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="right" bgcolor="#FFFFFF"><label
                                                for="desamount">{{ $lang['exchange_desamount'] }}:</label></th>
                                        <td bgcolor="#FFFFFF"><input type="text" name="desamount" id="desamount"
                                                                     disabled="disabled" value="0" size="15"/>
                                            <select name="tocredits" id="tocredits" onchange="calcredit();">
                                                {html_options options=$to_credits_options selected=$selected_dst}
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="right" bgcolor="#FFFFFF">{{ $lang['exchange_ratio'] }}:</th>
                                        <td bgcolor="#FFFFFF">1 <span id="orgcreditunit">{{ $orgcreditunit }}</span>
                                            <span
                                                id="orgcredittitle">{{ $orgcredittitle }}</span> {{ $lang['exchange_action'] }}
                                            <span id="descreditamount">{{ $descreditamount }}</span> <span
                                                id="descreditunit">{{ $descreditunit }}</span> <span
                                                id="descredittitle">{{ $descredittitle }}</span></td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#FFFFFF">&nbsp;</td>
                                        <td bgcolor="#FFFFFF"><input type="hidden" name="act"
                                                                     value="act_transform_ucenter_points"/><input
                                                type="submit" name="transfrom" value="{{ $lang['transform'] }}"/></td>
                                    </tr>
                                </table>
                            </form>
                            <script type="text/javascript">
                                @foreach($lang['exchange_js'] as $lang_js => $key)
                                var {{ $key }} = '{{ $lang_js }}';
                                @endforeach

                                var out_exchange_allow = new Array();
                                @foreach($out_exchange_allow as $ratio => $key)
                                    out_exchange_allow['{{ $key }}'] = '{{ $ratio }}';
                                @endforeach

                                function calcredit() {
                                    var frm = document.forms['transForm'];
                                    var src_credit = frm.fromcredits.value;
                                    var dest_credit = frm.tocredits.value;
                                    var in_credit = frm.amount.value;
                                    var org_title = frm.fromcredits[frm.fromcredits.selectedIndex].innerHTML;
                                    var dst_title = frm.tocredits[frm.tocredits.selectedIndex].innerHTML;
                                    var radio = 0;
                                    var shop_points = ['rank_points', 'pay_points'];
                                    if (parseFloat(in_credit) > parseFloat(document.getElementById(shop_points[src_credit]).value)) {
                                        alert(balance.replace('{%s}', org_title));
                                        frm.amount.value = frm.desamount.value = 0;
                                        return false;
                                    }
                                    if (typeof (out_exchange_allow[dest_credit + '|' + src_credit]) == 'string') {
                                        radio = (1 / parseFloat(out_exchange_allow[dest_credit + '|' + src_credit])).toFixed(2);
                                    }
                                    document.getElementById('orgcredittitle').innerHTML = org_title;
                                    document.getElementById('descreditamount').innerHTML = radio;
                                    document.getElementById('descredittitle').innerHTML = dst_title;
                                    if (in_credit > 0) {
                                        if (typeof (out_exchange_allow[dest_credit + '|' + src_credit]) == 'string') {
                                            frm.desamount.value = Math.floor(parseFloat(in_credit) / parseFloat(out_exchange_allow[dest_credit + '|' + src_credit]));
                                            frm.transfrom.disabled = false;
                                            return true;
                                        } else {
                                            frm.desamount.value = deny;
                                            frm.transfrom.disabled = true;
                                            return false;
                                        }
                                    } else {
                                        return false;
                                    }
                                }
                            </script>
                        @else
                            <b>{{ $lang['cur_points'] }}:</b>
                            <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                                <tr>
                                    <td width="30%" valign="top" bgcolor="#FFFFFF">
                                        <table border="0">
                                            @foreach($bbs_points as $points)
                                                <tr>
                                                    <th>{{ $points['title'] }}:</th>
                                                    <td width="120"
                                                        style="border-bottom:1px solid #DADADA;">{{ $points['value'] }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                    <td width="50%" valign="top" bgcolor="#FFFFFF">
                                        <table>
                                            <tr>
                                                <th>{{ $lang['pay_points'] }}:</th>
                                                <td width="120"
                                                    style="border-bottom:1px solid #DADADA;">{{ $shop_points['pay_points'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ $lang['rank_points'] }}:</th>
                                                <td width="120"
                                                    style="border-bottom:1px solid #DADADA;">{{ $shop_points['rank_points'] }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <br/>
                            <b>{{ $lang['rule_list'] }}:</b>
                            <ul class="point clearfix">
                                @foreach($rule_list as $rule)
                                    <li><font class="f1">·</font>"{{ $rule['from'] }}" {{ $lang['transform'] }}
                                        "{{ $rule['to'] }}" {{ $lang['rate_is'] }} {{ $rule['rate'] }}
                                @endforeach
                            </ul>

                            <form action="user.php" method="post" name="theForm">
                                <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0"
                                       style="border-collapse:collapse;border:1px solid #DADADA;">
                                    <tr style="background:#F1F1F1;">
                                        <th>{{ $lang['rule'] }}</th>
                                        <th>{{ $lang['transform_num'] }}</th>
                                        <th>{{ $lang['transform_result'] }}</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="rule_index" onchange="changeRule()">
                                                @foreach($rule_list as $rule => $key)
                                                    <option value="{{ $key }}">{{ $rule['from'] }}
                                                        ->{{ $rule['to'] }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="num" value="0" onkeyup="calPoints()"/>
                                        </td>
                                        <td><span id="ECS_RESULT">0</span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="center"><input type="hidden" name="act"
                                                                              value="act_transform_points"/><input
                                                type="submit" value="{{ $lang['transform'] }}"/></td>
                                    </tr>
                                </table>
                            </form>
                            <script type="text/javascript">
                                //<![CDATA[
                                var rule_list = new Object();
                                var invalid_input = '{{ $lang['invalid_input'] }}';
                                @foreach($rule_list as $rule => $key)
                                    rule_list['{{ $key }}'] = '{{ $rule['rate'] }}';
                                @endforeach
                                function calPoints() {
                                    var frm = document.forms['theForm'];
                                    var rule_index = frm.elements['rule_index'].value;
                                    var num = parseInt(frm.elements['num'].value);
                                    var rate = rule_list[rule_index];

                                    if (isNaN(num) || num < 0 || num != frm.elements['num'].value) {
                                        document.getElementById('ECS_RESULT').innerHTML = invalid_input;
                                        rerutn;
                                    }
                                    var arr = rate.split(':');
                                    var from = parseInt(arr[0]);
                                    var to = parseInt(arr[1]);

                                    if (from <= 0 || to <= 0) {
                                        from = 1;
                                        to = 0;
                                    }
                                    document.getElementById('ECS_RESULT').innerHTML = parseInt(num * to / from);
                                }

                                function changeRule() {
                                    document.forms['theForm'].elements['num'].value = 0;
                                    document.getElementById('ECS_RESULT').innerHTML = 0;
                                }

                                //]]>
                            </script>
                        @endif
                    @endif
                    <!--#积分兑换 -->


                </div>
            </div>
        </div>
    </div>
    <!--right end-->
</div>
<div class="blank"></div>
@include('web::library/page_footer')
</body>
<script type="text/javascript">
    @foreach($lang['clips_js'] as $item => $key)
    var {{ $key }} = "{{ $item }}";
    @endforeach
</script>
</html>
