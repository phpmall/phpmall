@include('admin::pageheader')
<script src="{{ asset('static/admin/js/topbar.js') }}"></script>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/listtable.js') }}"></script>
<script src="{{ asset('static/admin/js/selectzone.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>

<form action="order.php?act=operate_post" method="post" name="theForm">
    <div class="list-div" style="margin-bottom: 5px">
        <table width="100%" cellpadding="3" cellspacing="1">
            <tr>
                <th colspan="4">{{ $lang['base_info'] }}</th>
            </tr>
            <tr>
                <td width="18%">
                    <div align="right"><strong>{{ $lang['label_order_sn'] }}</strong></div>
                </td>
                <td width="34%">{{ $order['order_sn'] }}@if($order['extension_code'] === "group_buy")<a
                        href="group_buy.php?act=edit&id={{ $order['extension_id'] }}">{{ $lang['group_buy'] }}</a>@elseif($order['extension_code'] === "exchange_goods")
                        <a
                            href="exchange_goods.php?act=edit&id={{ $order['extension_id'] }}">{{ $lang['exchange_goods'] }}</a>@endif
                <td>
                    <div align="right"><strong>{{ $lang['label_order_time'] }}</strong></div>
                </td>
                <td>{{ $order['formated_add_time'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_user_name'] }}</strong></div>
                </td>
                <td>{{ $order['user_name'] ?? $lang . anonymous }}</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_how_oos'] }}</strong></div>
                </td>
                <td>{{ $order['how_oos'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_shipping'] }}</strong></div>
                </td>
                <td>
                    '.($exist_real_goods ? '@if($order['shipping_id'] > 0)
                        {{ $order['shipping_name'] }}' : '{{ $lang['require_field'] }}').'
                        @if($order['insure_fee'] > 0)
                            （{{ $lang['label_insure_fee'] }}{{ $order['formated_insure_fee'] }}）
                        @endif
                    @endif
                </td>
                <td>
                    <div align="right"><strong>{{ $lang['label_shipping_fee'] }}</strong></div>
                </td>
                <td>{{ $order['shipping_fee'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_insure_yn'] }}</strong></div>
                </td>
                <td>'.($insure_yn ? '{{ $lang['yes'] }}' : '{{ $lang['no'] }}').'</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_insure_fee'] }}</strong></div>
                </td>
                <td>{{ $order['insure_fee'] ?? 0.00 }}</td>
            </tr>
            <!--	@if($exist_real_goods)
                <tr>
                  <td><div align="right"><strong>{{ $lang['label_invoice_no'] }}</strong></div></td>
    <td colspan="3"><input name="delivery[invoice_no]" type="text" id="invoice_no" value="" size="20"/><input name="delivery_hidden" type="hidden" value="{{ $exist_real_goods }}" /></td>
  </tr>
	@endif-->
                <tr>
                    <th colspan="4">{{ $lang['consignee_info'] }}</th>
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
                <tr>
                    <td>
                        <div align="right"><strong>{{ $lang['label_postscript'] }}</strong></div>
                    </td>
                    <td colspan="3">{{ $order['postscript'] }}</td>
                </tr>
        </table>
    </div>

    <div class="list-div" style="margin-bottom: 5px">
        <table width="100%" cellpadding="3" cellspacing="1">
            <tr>
                <th colspan="9" scope="col">{{ $lang['goods_info'] }}</th>
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
                    <div align="center"><strong>{{ $lang['goods_attr'] }}</strong></div>
                </td>
                @if($suppliers_list != 0)
                    <td scope="col">
                        <div align="center"><strong>{{ $lang['suppliers_name'] }}</strong></div>
                    </td>
                @endif
                <td scope="col">
                    <div align="center"><strong>{{ $lang['storage'] }}</strong></div>
                </td>
                <td scope="col">
                    <div align="center"><strong>{{ $lang['goods_number'] }}</strong></div>
                </td>
                <td scope="col">
                    <div align="center"><strong>{{ $lang['goods_delivery'] }}</strong></div>
                </td>
                <td scope="col">
                    <div align="center"><strong>{{ $lang['goods_delivery_curr'] }}</strong></div>
                </td>
            </tr>
            @foreach($goods_list as $goods)
                <!--礼包-->
                @if($goods['goods_id'] > 0 && $goods['extension_code'] === 'package_buy')
                    <tr>
                        <td>{{ $goods['goods_name'] }}<span style="color:#FF0000;">{{ $lang['remark_package'] }}</span>
                        </td>
                        <td>{{ $goods['goods_sn'] }}</td>
                        <td>&nbsp;<!--货品货号--></td>
                        <td>&nbsp;<!--属性--></td>
                        @if($suppliers_list != 0)
                            <td>
                                <div align="right"></div>
                            </td>
                        @endif
                        <td>
                            <div align="right"></div>
                        </td>
                        <td>
                            <div align="right">{{ $goods['goods_number'] }}</div>
                        </td>
                        <td>
                            <div align="right"></div>
                        </td>
                        <td>
                            <div align="right"></div>
                        </td>
                    </tr>
                    @foreach($goods['package_goods_list'] as $package)
                        <tr>
                            <td>--&nbsp;<a href="../goods.php?id={{ $package['goods_id'] }}"
                                           target="_blank">{{ $package['goods_name'] }}</a></td>
                            <td>{{ $package['goods_sn'] }}</td>
                            <td>{{ $package['product_sn'] }}</td>
                            <td>{{ $package['goods_attr_str'] }}</td>
                            @if($suppliers_list != 0)
                                <td>
                                    <div align="right">
                                        {$suppliers_name[$package.suppliers_id]|default:$lang.restaurant}
                                    </div>
                                </td>
                            @endif
                            <td>
                                <div align="right">{{ $package['storage'] }}</div>
                            </td>
                            <td>
                                <div align="right">{{ $package['order_send_number'] }}</div>
                            </td>
                            <td>
                                <div align="right">{{ $package['sended'] }}</div>
                            </td>
                            <td>
                                <div align="right"><input
                                        name="send_number[{{ $goods['rec_id'] }}][{{ $package['g_p'] }}]"
                                        type="text" id="send_number_{{ $goods['rec_id'] }}_{{ $package['g_p'] }}"
                                        value="{{ $package['send'] }}" size="10"
                                        maxlength="11" {{ $package['readonly'] }} /></div>
                            </td>
                        </tr>
                    @endforeach

                @else
                    <tr>
                        <td>
                            @if($goods['goods_id'] > 0 && $goods['extension_code'] != 'package_buy')
                                <a href="../goods.php?id={{ $goods['goods_id'] }}"
                                   target="_blank">{{ $goods['goods_name'] }}
                                    @if($goods['brand_name'])
                                        [ {{ $goods['brand_name'] }} ]
                                    @endif
                                    '.($goods['is_gift'] ? '@if($goods['goods_price'] > 0)
                                        {{ $lang['remark_favourable'] }}' : '{{ $lang['remark_gift'] }}').'
                                    @endif
                                    @if($goods['parent_id'] > 0)
                                        {{ $lang['remark_fittings'] }}
                                    @endif</a>
                            @endif
                        </td>
                        <td>{{ $goods['goods_sn'] }}</td>
                        <td>{{ $goods['product_sn'] }}</td>
                        <td>{$goods.goods_attr|nl2br}</td>
                        @if($suppliers_list != 0)
                            <td>
                                <div align="right">{$suppliers_name[$goods.suppliers_id]|default:$lang.restaurant}</div>
                            </td>
                        @endif
                        <td>
                            <div align="right">{{ $goods['storage'] }}</div>
                        </td>
                        <td>
                            <div align="right">{{ $goods['goods_number'] }}</div>
                        </td>
                        <td>
                            <div align="right">{{ $goods['sended'] }}</div>
                        </td>
                        <td>
                            <div align="right"><input name="send_number[{{ $goods['rec_id'] }}]" type="text"
                                                      id="send_number_{{ $goods['rec_id'] }}"
                                                      value="{{ $goods['send'] }}" size="10"
                                                      maxlength="11" {{ $goods['readonly'] }} /></div>
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
    </div>

    <div class="list-div" style="margin-bottom: 5px">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th colspan="6">{{ $lang['action_info'] }}</th>
            </tr>
            @if($suppliers_list != 0)
                <tr>
                    <td>
                        <div align="right"><strong>{{ $lang['label_suppliers'] }}</strong></div>
                    </td>
                    <td colspan="5"><select name="suppliers_id" id="suppliers_id">
                            <option value="0" selected="true">{{ $lang['suppliers_no'] }}</option>
                            @foreach($suppliers_list as $suppliers)
                                <option
                                    value="{{ $suppliers['suppliers_id'] }}">{{ $suppliers['suppliers_name'] }}</option>
                            @endforeach
                        </select></td>
                </tr>
            @endif
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_action_note'] }}</strong></div>
                </td>
                <td colspan="5"><textarea name="action_note" cols="80" rows="3">{{ $action_note }}</textarea></td>
            </tr>
            <tr>
                <td colspan="6" align="center">
                    <input name="delivery_confirmed" type="submit"
                           value="{{ $lang['op_confirm'] }}{{ $lang['op_split'] }}" class="button"/>&nbsp;&nbsp;<input
                        type="button" value="{{ $lang['cancel'] }}" class="button"
                        onclick="location.href='order.php?act=info&order_id={{ $order_id }}'"/>

                    <input name="order_id" type="hidden" value="{{ $order['order_id'] }}">
                    <input name="delivery[order_sn]" type="hidden" value="{{ $order['order_sn'] }}">
                    <input name="delivery[add_time]" type="hidden" value="{{ $order['order_time'] }}">
                    <input name="delivery[user_id]" type="hidden" value="{{ $order['user_id'] }}">
                    <input name="delivery[how_oos]" type="hidden" value="{{ $order['how_oos'] }}">
                    <input name="delivery[shipping_id]" type="hidden" value="{{ $order['shipping_id'] }}">
                    <input name="delivery[shipping_fee]" type="hidden" value="{{ $order['shipping_fee'] }}">

                    <input name="delivery[consignee]" type="hidden" value="{{ $order['consignee'] }}">
                    <input name="delivery[address]" type="hidden" value="{{ $order['address'] }}">
                    <input name="delivery[country]" type="hidden" value="{{ $order['country'] }}">
                    <input name="delivery[province]" type="hidden" value="{{ $order['province'] }}">
                    <input name="delivery[city]" type="hidden" value="{{ $order['city'] }}">
                    <input name="delivery[district]" type="hidden" value="{{ $order['district'] }}">
                    <input name="delivery[sign_building]" type="hidden" value="{{ $order['sign_building'] }}">
                    <input name="delivery[email]" type="hidden" value="{{ $order['email'] }}">
                    <input name="delivery[zipcode]" type="hidden" value="{{ $order['zipcode'] }}">
                    <input name="delivery[tel]" type="hidden" value="{{ $order['tel'] }}">
                    <input name="delivery[mobile]" type="hidden" value="{{ $order['mobile'] }}">
                    <input name="delivery[best_time]" type="hidden" value="{{ $order['best_time'] }}">
                    <input name="delivery[postscript]" type="hidden" value="{{ $order['postscript'] }}">

                    <input name="delivery[how_oos]" type="hidden" value="{{ $order['how_oos'] }}">
                    <input name="delivery[insure_fee]" type="hidden" value="{{ $order['insure_fee'] }}">
                    <input name="delivery[shipping_fee]" type="hidden" value="{{ $order['shipping_fee'] }}">
                    <input name="delivery[agency_id]" type="hidden" value="{{ $order['agency_id'] }}">
                    <input name="delivery[shipping_name]" type="hidden" value="{{ $order['shipping_name'] }}">
                    <input name="operation" type="hidden" value="{{ $operation }}">
                </td>
            </tr>
        </table>
    </div>
</form>

<script type="text/javascript">

    var oldAgencyId = {{ $order['agency_id'] ?? 0 }};
</script>
@include('admin::pagefooter')
