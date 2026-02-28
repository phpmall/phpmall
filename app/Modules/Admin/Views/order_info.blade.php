@include('admin::pageheader')
<script src="{{ asset('static/admin/js/topbar.js') }}"></script>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/listtable.js') }}"></script>
<script src="{{ asset('static/admin/js/selectzone.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>
@if($user)
    <div id="topbar">
        <div align="right"><a href="" onclick="closebar(); return false"><img
                    src="{{ asset('static/admin/images/close.gif') }}" border="0"/></a></div>
        <table width="100%" border="0">
            <caption><strong> {{ $lang['buyer_info'] }} </strong></caption>
            <tr>
                <td> {{ $lang['email'] }} </td>
                <td><a href="mailto:{{ $user['email'] }}">{{ $user['email'] }}</a></td>
            </tr>
            <tr>
                <td> {{ $lang['user_money'] }} </td>
                <td> {{ $user['formated_user_money'] }} </td>
            </tr>
            <tr>
                <td> {{ $lang['pay_points'] }} </td>
                <td> {{ $user['pay_points'] }} </td>
            </tr>
            <tr>
                <td> {{ $lang['rank_points'] }} </td>
                <td> {{ $user['rank_points'] }} </td>
            </tr>
            <tr>
                <td> {{ $lang['rank_name'] }} </td>
                <td> {{ $user['rank_name'] }} </td>
            </tr>
            <tr>
                <td> {{ $lang['bonus_count'] }} </td>
                <td> {{ $user['bonus_count'] }} </td>
            </tr>
        </table>

        @foreach($address_list as $address)
            <table width="100%" border="0">
                <caption><strong> {{ $lang['consignee'] }} : {{ $address['consignee'] }} </strong></caption>
                <tr>
                    <td> {{ $lang['email'] }} </td>
                    <td><a href="mailto:{{ $address['email'] }}">{{ $address['email'] }}</a></td>
                </tr>
                <tr>
                    <td> {{ $lang['address'] }} </td>
                    <td> {{ $address['address'] }} </td>
                </tr>
                <tr>
                    <td> {{ $lang['zipcode'] }} </td>
                    <td> {{ $address['zipcode'] }} </td>
                </tr>
                <tr>
                    <td> {{ $lang['tel'] }} </td>
                    <td> {{ $address['tel'] }} </td>
                </tr>
                <tr>
                    <td> {{ $lang['mobile'] }} </td>
                    <td> {{ $address['mobile'] }} </td>
                </tr>
            </table>
        @endforeach
    </div>
@endif

<form action="order.php?act=operate" method="post" name="theForm">
    <div class="list-div" style="margin-bottom: 5px">
        <table width="100%" cellpadding="3" cellspacing="1">
            <tr>
                <td colspan="4">
                    <div align="center">
                        <input name="prev" type="button" class="button"
                               onClick="location.href='order.php?act=info&order_id={{ $prev_id }}';"
                               value="{{ $lang['prev'] }}" @if(!$prev_id)disabled@endif />
                        <input name="next" type="button" class="button"
                               onClick="location.href='order.php?act=info&order_id={{ $next_id }}';"
                               value="{{ $lang['next'] }}" @if(!$next_id)disabled@endif />
                        <input type="button"
                               onclick="window.open('order.php?act=info&order_id={{ $order['order_id'] }}&print=1')"
                               class="button" value="{{ $lang['print_order'] }}"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="4">{{ $lang['base_info'] }}</th>
            </tr>
            <tr>
                <td width="18%">
                    <div align="right"><strong>{{ $lang['label_order_sn'] }}</strong></div>
                </td>
                <td width="34%">{{ $order['order_sn'] }}'.($order['extension_code'] === "group_buy" ? '<a
                        href="group_buy.php?act=edit&id={{ $order['extension_id'] }}">{{ $lang['group_buy'] }}</a>' :
                    'if($order['extension_code'] === "exchange_goods")<a
                        href="exchange_goods.php?act=edit&id={{ $order['extension_id'] }}">{{ $lang['exchange_goods'] }}</a>').'
                </td>
                <td width="15%">
                    <div align="right"><strong>{{ $lang['label_order_status'] }}</strong></div>
                </td>
                <td>{{ $order['status'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_user_name'] }}</strong></div>
                </td>
                <td>{{ $order['user_name'] ?? $lang.anonymous }} @if($order['user_id'] > 0)
                        [ <a href="" onclick="staticbar();return false;">{{ $lang['display_buyer'] }}</a> ] [ <a
                            href="user_msg.php?act=add&order_id={{ $order['order_id'] }}&user_id={{ $order['user_id'] }}">{{ $lang['send_message'] }}</a>
                        ]
                    @endif</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_order_time'] }}</strong></div>
                </td>
                <td>{{ $order['formated_add_time'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_payment'] }}</strong></div>
                </td>
                <td>'.($order['pay_id'] > 0 ? '{{ $order['pay_name'] }}' : '{{ $lang['require_field'] }}').'<a
                        href="order.php?act=edit&order_id={{ $order['order_id'] }}&step=payment"
                        class="special">{{ $lang['edit'] }}</a>
                    ({{ $lang['action_note'] }}: <span
                        onclick="listTable.edit(this, 'edit_pay_note', {{ $order['order_id'] }})">'.($order['pay_note'] ? '{{ $order['pay_note'] }}' : 'N/A').'</span>)
                </td>
                <td>
                    <div align="right"><strong>{{ $lang['label_pay_time'] }}</strong></div>
                </td>
                <td>{{ $order['pay_time'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_shipping'] }}</strong></div>
                </td>
                <td>'.($exist_real_goods ? '@if($order['shipping_id'] > 0)
                        {{ $order['shipping_name'] }}' : '{{ $lang['require_field'] }}').'<a
                            href="order.php?act=edit&order_id={{ $order['order_id'] }}&step=shipping"
                            class="special">{{ $lang['edit'] }}</a>&nbsp;&nbsp;<input type="button"
                                                                                      onclick="window.open('order.php?act=info&order_id={{ $order['order_id'] }}&shipping_print=1')"
                                                                                      class="button"
                                                                                      value="{{ $lang['print_shipping'] }}"> @if($order['insure_fee'] > 0)
                            （{{ $lang['label_insure_fee'] }}{{ $order['formated_insure_fee'] }}）
                        @endif
                    @endif</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_shipping_time'] }}</strong></div>
                </td>
                <td>{{ $order['shipping_time'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_invoice_no'] }}</strong></div>
                </td>
                <td>'.($order['shipping_id']>0 && $order['shipping_status']>0 ?
                    '<span>@if($order['invoice_no']){{ $order['invoice_no'] }}' : 'N/A').'</span><a
                        href="order.php?act=edit&order_id={{ $order['order_id'] }}&step=shipping"
                        class="special">{{ $lang['edit'] }}</a>@endif</td>
                <td>
                    <div align="right"><strong>{{ $lang['from_order'] }}</strong></div>
                </td>
                <td>{{ $order['referer'] }}</td>
            </tr>
            <tr>
                <th colspan="4">{{ $lang['other_info'] }}<a
                        href="order.php?act=edit&order_id={{ $order['order_id'] }}&step=other"
                        class="special">{{ $lang['edit'] }}</a></th>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_inv_type'] }}</strong></div>
                </td>
                <td>{{ $order['inv_type'] }}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_inv_payee'] }}</strong></div>
                </td>
                <td>{{ $order['inv_payee'] }}</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_inv_content'] }}</strong></div>
                </td>
                <td>{{ $order['inv_content'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_postscript'] }}</strong></div>
                </td>
                <td colspan="3">{{ $order['postscript'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_how_oos'] }}</strong></div>
                </td>
                <td>{{ $order['how_oos'] }}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_pack'] }}</strong></div>
                </td>
                <td>{{ $order['pack_name'] }}</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_card'] }}</strong></div>
                </td>
                <td>{{ $order['card_name'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_card_message'] }}</strong></div>
                </td>
                <td colspan="3">{{ $order['card_message'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_to_buyer'] }}</strong></div>
                </td>
                <td colspan="3">{{ $order['to_buyer'] }}</td>
            </tr>
            <tr>
                <th colspan="4">{{ $lang['consignee_info'] }}<a
                        href="order.php?act=edit&order_id={{ $order['order_id'] }}&step=consignee"
                        class="special">{{ $lang['edit'] }}</a></th>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_consignee'] }}</strong></div>
                </td>
                <td>{{ $order['consignee'] }}</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_email'] }}</strong></div>
                </td>
                <td>{{ $order['email'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_address'] }}</strong></div>
                </td>
                <td>[{{ $order['region'] }}] {{ $order['address'] }}</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_zipcode'] }}</strong></div>
                </td>
                <td>{{ $order['zipcode'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_tel'] }}</strong></div>
                </td>
                <td>{{ $order['tel'] }}</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_mobile'] }}</strong></div>
                </td>
                <td>{{ $order['mobile'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_sign_building'] }}</strong></div>
                </td>
                <td>{{ $order['sign_building'] }}</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_best_time'] }}</strong></div>
                </td>
                <td>{{ $order['best_time'] }}</td>
            </tr>
        </table>
    </div>

    <div class="list-div" style="margin-bottom: 5px">
        <table width="100%" cellpadding="3" cellspacing="1">
            <tr>
                <th colspan="8" scope="col">{{ $lang['goods_info'] }}<a
                        href="order.php?act=edit&order_id={{ $order['order_id'] }}&step=goods"
                        class="special">{{ $lang['edit'] }}</a></th>
            </tr>
            <tr>
                <td scope="col">
                    <div align="center"><strong>{{ $lang['goods_name_brand'] }}</strong></div>
                </td>
                <td scope="col">
                    <div align="center"><strong>{{ $lang['goods_sn'] }}</strong></div>
                </td>
                <td scope="col">
                    <div align="center"><strong>{{ $lang['product_sn'] }}</strong></div>
                </td>
                <td scope="col">
                    <div align="center"><strong>{{ $lang['goods_price'] }}</strong></div>
                </td>
                <td scope="col">
                    <div align="center"><strong>{{ $lang['goods_number'] }}</strong></div>
                </td>
                <td scope="col">
                    <div align="center"><strong>{{ $lang['goods_attr'] }}</strong></div>
                </td>
                <td scope="col">
                    <div align="center"><strong>{{ $lang['storage'] }}</strong></div>
                </td>
                <td scope="col">
                    <div align="center"><strong>{{ $lang['subtotal'] }}</strong></div>
                </td>
            </tr>
            @foreach($goods_list as $goods)
                <tr>
                    <td>
                        @if($goods['goods_id'] > 0 && $goods['extension_code'] != 'package_buy')
                            <a href="../goods.php?id={{ $goods['goods_id'] }}"
                               target="_blank">{{ $goods['goods_name'] }} @if($goods['brand_name'])
                                    [ {{ $goods['brand_name'] }} ]
                                @endif
                                '.($goods['is_gift'] ? '@if($goods['goods_price'] > 0)
                                    {{ $lang['remark_favourable'] }}' : '{{ $lang['remark_gift'] }}').'
                                @endif
                                @if($goods['parent_id'] > 0)
                                    {{ $lang['remark_fittings'] }}
                                @endif</a>
                        @elseif($goods['goods_id'] > 0 && $goods['extension_code'] === 'package_buy')
                            <a href="javascript:void(0)"
                               onclick="setSuitShow({{ $goods['goods_id'] }})">{{ $goods['goods_name'] }}<span
                                    style="color:#FF0000;">{{ $lang['remark_package'] }}</span></a>
                            <div id="suit_{{ $goods['goods_id'] }}" style="display:none">
                                @foreach($goods['package_goods_list'] as $package_goods_list)
                                    <a href="../goods.php?id={{ $package_goods_list['goods_id'] }}"
                                       target="_blank">{{ $package_goods_list['goods_name'] }}</a><br/>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td>{{ $goods['goods_sn'] }}</td>
                    <td>{{ $goods['product_sn'] }}</td>
                    <td>
                        <div align="right">{{ $goods['formated_goods_price'] }}</div>
                    </td>
                    <td>
                        <div align="right">{{ $goods['goods_number'] }}
                        </div>
                    </td>
                    <td>{$goods.goods_attr|nl2br}</td>
                    <td>
                        <div align="right">{{ $goods['storage'] }}</div>
                    </td>
                    <td>
                        <div align="right">{{ $goods['formated_subtotal'] }}</div>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>@if($order['total_weight'])
                        <div align="right"><strong>{{ $lang['label_total_weight'] }}
                            </strong></div>
                    @endif</td>
                <td>@if($order['total_weight'])
                        <div align="right">{{ $order['total_weight'] }}
                        </div>
                    @endif</td>
                <td>&nbsp;</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_total'] }}</strong></div>
                </td>
                <td>
                    <div align="right">{{ $order['formated_goods_amount'] }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="list-div" style="margin-bottom: 5px">
        <table width="100%" cellpadding="3" cellspacing="1">
            <tr>
                <th>{{ $lang['fee_info'] }}<a href="order.php?act=edit&order_id={{ $order['order_id'] }}&step=money"
                                              class="special">{{ $lang['edit'] }}</a></th>
            </tr>
            <tr>
                <td>
                    <div align="right">{{ $lang['label_goods_amount'] }}
                        <strong>{{ $order['formated_goods_amount'] }}</strong>
                        - {{ $lang['label_discount'] }}<strong>{{ $order['formated_discount'] }}</strong>
                        + {{ $lang['label_tax'] }}<strong>{{ $order['formated_tax'] }}</strong>
                        + {{ $lang['label_shipping_fee'] }}<strong>{{ $order['formated_shipping_fee'] }}</strong>
                        + {{ $lang['label_insure_fee'] }}<strong>{{ $order['formated_insure_fee'] }}</strong>
                        + {{ $lang['label_pay_fee'] }}<strong>{{ $order['formated_pay_fee'] }}</strong>
                        + {{ $lang['label_pack_fee'] }}<strong>{{ $order['formated_pack_fee'] }}</strong>
                        + {{ $lang['label_card_fee'] }}<strong>{{ $order['formated_card_fee'] }}</strong></div>
                </td>
            <tr>
                <td>
                    <div align="right"> = {{ $lang['label_order_amount'] }}
                        <strong>{{ $order['formated_total_fee'] }}</strong></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div align="right">
                        - {{ $lang['label_money_paid'] }}<strong>{{ $order['formated_money_paid'] }}</strong>
                        - {{ $lang['label_surplus'] }} <strong>{{ $order['formated_surplus'] }}</strong>
                        - {{ $lang['label_integral'] }} <strong>{{ $order['formated_integral_money'] }}</strong>
                        - {{ $lang['label_bonus'] }} <strong>{{ $order['formated_bonus'] }}</strong>
                    </div>
                </td>
            <tr>
                <td>
                    <div align="right"> = @if($order['order_amount'] >= 0)
                            {{ $lang['label_money_dues'] }}<strong>{{ $order['formated_order_amount'] }}</strong>
                        @else
                            {{ $lang['label_money_refund'] }}<strong>{{ $order['formated_money_refund'] }}</strong>
                            <input name="refund" type="button" value="{{ $lang['refund'] }}"
                                   onclick="location.href='order.php?act=process&func=load_refund&anonymous='.($order['user_id'] <= 0 ? '1' : '0').'&order_id={{ $order['order_id'] }}&refund_amount={{ $order['money_refund'] }}'"/>
                        @endif@if($order['extension_code'] === "group_buy")
                            <br/>{{ $lang['notice_gb_order_amount'] }}
                        @endif</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="list-div" style="margin-bottom: 5px">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th colspan="6">{{ $lang['action_info'] }}</th>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_action_note'] }}</strong></div>
                </td>
                <td colspan="5"><textarea name="action_note" cols="80" rows="3"></textarea></td>
            </tr>
            <tr>
                <td>
                    <div align="right"></div>
                    <div align="right"><strong>{{ $lang['label_operable_act'] }}</strong></div>
                </td>
                <td colspan="5">@if($operable_list['confirm'])
                        <input name="confirm" type="submit" value="{{ $lang['op_confirm'] }}" class="button"/>
                    @endif @if($operable_list['pay'])
                        <input name="pay" type="submit" value="{{ $lang['op_pay'] }}" class="button"/>
                    @endif @if($operable_list['unpay'])
                        <input name="unpay" type="submit" class="button" value="{{ $lang['op_unpay'] }}"/>
                    @endif @if($operable_list['prepare'])
                        <input name="prepare" type="submit" value="{{ $lang['op_prepare'] }}" class="button"/>
                    @endif @if($operable_list['split'])
                        <input name="ship" type="submit" value="{{ $lang['op_split'] }}" class="button"/>
                    @endif @if($operable_list['unship'])
                        <input name="unship" type="submit" value="{{ $lang['op_unship'] }}" class="button"/>
                    @endif @if($operable_list['receive'])
                        <input name="receive" type="submit" value="{{ $lang['op_receive'] }}" class="button"/>
                    @endif @if($operable_list['cancel'])
                        <input name="cancel" type="submit" value="{{ $lang['op_cancel'] }}" class="button"/>
                    @endif @if($operable_list['invalid'])
                        <input name="invalid" type="submit" value="{{ $lang['op_invalid'] }}" class="button"/>
                    @endif @if($operable_list['return'])
                        <input name="return" type="submit" value="{{ $lang['op_return'] }}" class="button"/>
                    @endif @if($operable_list['to_delivery'])
                        <input name="to_delivery" type="submit" value="{{ $lang['op_to_delivery'] }}" class="button"/>
                        <input name="order_sn" type="hidden" value="{{ $order['order_sn'] }}"/>
                    @endif <input name="after_service" type="submit" value="{{ $lang['op_after_service'] }}"
                                  class="button"/>@if($operable_list['remove'])
                        <input name="remove" type="submit" value="{{ $lang['remove'] }}" class="button"
                               onClick="return window.confirm('{{ $lang['js_languages']['remove_confirm'] }}');"/>
                    @endif
                    @if($order['extension_code'] === "group_buy")
                        {{ $lang['notice_gb_ship'] }}
                    @endif
                    @if($agency_list)
                        <input name="assign" type="submit" value="{{ $lang['op_assign'] }}" class="button"
                               onclick="return assignTo(document.forms['theForm'].elements['agency_id'].value)"/>
                        <select name="agency_id">
                            <option value="0">{{ $lang['select_please'] }}</option>
                            @foreach($agency_list as $agency)
                                <option value="{{ $agency['agency_id'] }}"
                                        @if($agency['agency_id'] === $order['agency_id'])selected@endif>{{ $agency['agency_name'] }}</option>
                            @endforeach
                        </select>
                    @endif
                    <input name="order_id" type="hidden" value="{{ $smarty['request']['order_id'] }}"></td>
            </tr>
            <tr>
                <th>{{ $lang['action_user'] }}</th>
                <th>{{ $lang['action_time'] }}</th>
                <th>{{ $lang['order_status'] }}</th>
                <th>{{ $lang['pay_status'] }}</th>
                <th>{{ $lang['shipping_status'] }}</th>
                <th>{{ $lang['action_note'] }}</th>
            </tr>
            @foreach($action_list as $action)
                <tr>
                    <td>
                        <div align="center">{{ $action['action_user'] }}</div>
                    </td>
                    <td>
                        <div align="center">{{ $action['action_time'] }}</div>
                    </td>
                    <td>
                        <div align="center">{{ $action['order_status'] }}</div>
                    </td>
                    <td>
                        <div align="center">{{ $action['pay_status'] }}</div>
                    </td>
                    <td>
                        <div align="center">{{ $action['shipping_status'] }}</div>
                    </td>
                    <td>{$action.action_note|nl2br}</td>
                </tr>
            @endforeach
        </table>
    </div>
</form>

<script type="text/javascript">

    var oldAgencyId = {{ $order['agency_id'] ?? 0 }};
    /**
     * 把订单指派给某办事处
     * @param int agencyId
     */
    function assignTo(agencyId) {
        if (agencyId === 0) {
            alert(pls_select_agency);
            return false;
        }
        if (oldAgencyId != 0 && agencyId === oldAgencyId) {
            alert(pls_select_other_agency);
            return false;
        }
        return true;
    }
</script>
@include('admin::pagefooter')
