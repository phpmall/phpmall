@include('admin::pageheader')
<script src="{{ asset('static/admin/js/topbar.js') }}"></script>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/listtable.js') }}"></script>
<script src="{{ asset('static/admin/js/selectzone.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>

<div class="list-div" style="margin-bottom: 5px">
    <table width="100%" cellpadding="3" cellspacing="1">
        <form action="order.php" method="post" name="theForm">
            <tr>
                <th colspan="4">{{ $lang['base_info'] }}</th>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['delivery_sn_number'] }}</strong></div>
                </td>
                <td>{{ $delivery_order['delivery_sn'] }}</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_shipping_time'] }}</strong></div>
                </td>
                <td>{{ $delivery_order['formated_update_time'] }}</td>
            </tr>
            <tr>
                <td width="18%">
                    <div align="right"><strong>{{ $lang['label_order_sn'] }}</strong></div>
                </td>
                <td width="34%">
                    {{ $delivery_order['order_sn'] }}@if($delivery_order['extension_code'] === "group_buy")<a
                        href="group_buy.php?act=edit&id={{ $delivery_order['extension_id'] }}">{{ $lang['group_buy'] }}</a>@elseif($delivery_order['extension_code'] === "exchange_goods")
                        <a
                            href="exchange_goods.php?act=edit&id={{ $delivery_order['extension_id'] }}">{{ $lang['exchange_goods'] }}</a>@endif
                <td>
                    <div align="right"><strong>{{ $lang['label_order_time'] }}</strong></div>
                </td>
                <td>{{ $delivery_order['formated_add_time'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_user_name'] }}</strong></div>
                </td>
                <td>{{ $delivery_order['user_name'] ?? $lang . anonymous }}</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_how_oos'] }}</strong></div>
                </td>
                <td>{{ $delivery_order['how_oos'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_shipping'] }}</strong></div>
                </td>
                <td>
                    '.($exist_real_goods ? '@if($delivery_order['shipping_id'] > 0)
                        {{ $delivery_order['shipping_name'] }}' : '{{ $lang['require_field'] }}').'
                        @if($delivery_order['insure_fee'] > 0)
                            （{{ $lang['label_insure_fee'] }}{{ $delivery_order['formated_insure_fee'] }}）
                        @endif
                    @endif
                </td>
                <td>
                    <div align="right"><strong>{{ $lang['label_shipping_fee'] }}</strong></div>
                </td>
                <td>{{ $delivery_order['shipping_fee'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_insure_yn'] }}</strong></div>
                </td>
                <td>'.($insure_yn ? '{{ $lang['yes'] }}' : '{{ $lang['no'] }}').'</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_insure_fee'] }}</strong></div>
                </td>
                <td>{{ $delivery_order['insure_fee'] ?? 0.00 }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_invoice_no'] }}</strong></div>
                </td>
                <td colspan="3">@if($delivery_order['status'] != 1)
                        <input name="invoice_no" type="text" value="{{ $delivery_order['invoice_no'] }}"
                               @if($delivery_order['status'] === 0) readonly="readonly" @endif>
                    @else
                        {{ $delivery_order['invoice_no'] }}
                    @endif
                </td>
            </tr>

            <tr>
                <th colspan="4">{{ $lang['consignee_info'] }}</th>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_consignee'] }}</strong></div>
                </td>
                <td>{{ $delivery_order['consignee'] }}</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_email'] }}</strong></div>
                </td>
                <td>{{ $delivery_order['email'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_address'] }}</strong></div>
                </td>
                <td>[{{ $delivery_order['region'] }}] {{ $delivery_order['address'] }}</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_zipcode'] }}</strong></div>
                </td>
                <td>{{ $delivery_order['zipcode'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_tel'] }}</strong></div>
                </td>
                <td>{{ $delivery_order['tel'] }}</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_mobile'] }}</strong></div>
                </td>
                <td>{{ $delivery_order['mobile'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_sign_building'] }}</strong></div>
                </td>
                <td>{{ $delivery_order['sign_building'] }}</td>
                <td>
                    <div align="right"><strong>{{ $lang['label_best_time'] }}</strong></div>
                </td>
                <td>{{ $delivery_order['best_time'] }}</td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_postscript'] }}</strong></div>
                </td>
                <td colspan="3">{{ $delivery_order['postscript'] }}</td>
            </tr>
    </table>
</div>

<div class="list-div" style="margin-bottom: 5px">
    <table width="100%" cellpadding="3" cellspacing="1">
        <tr>
            <th colspan="7" scope="col">{{ $lang['goods_info'] }}</th>
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
            <td scope="col">
                <div align="center"><strong>{{ $lang['label_send_number'] }}</strong></div>
            </td>
        </tr>
        @foreach($goods_list as $goods)
            <tr>
                <td>
                    <a href="../goods.php?id={{ $goods['goods_id'] }}" target="_blank">{{ $goods['goods_name'] }}
                        @if($goods['brand_name'])[ {{ $goods['brand_name'] }} ]@endif
                </td>
                <td>
                    <div align="left">{{ $goods['goods_sn'] }}</div>
                </td>
                <td>
                    <div align="left">{{ $goods['product_sn'] }}</div>
                </td>
                <td>
                    <div align="left">{$goods.goods_attr|nl2br}</div>
                </td>
                <td>
                    <div align="left">{{ $goods['send_number'] }}</div>
                </td>
            </tr>
        @endforeach
    </table>
</div>

<div class="list-div" style="margin-bottom: 5px">
    <table cellpadding="3" cellspacing="1">
        <tr>
            <th colspan="6">{{ $lang['op_ship'] }}{{ $lang['action_info'] }}</th>
        </tr>

        <tr>
            <td>
                <div align="right"><strong>{{ $lang['action_user'] }}</strong></div>
            </td>
            <td>{{ $delivery_order['action_user'] }}</td>
            <td>
                <div align="right"><strong>{{ $lang['label_agency'] }}</strong></div>
            </td>
            <td colspan="5">{{ $delivery_order['agency_name'] }}</td>
        </tr>
        @if($delivery_order['status'] != 1)
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_action_note'] }}</strong></div>
                </td>
                <td colspan="5"><textarea name="action_note" cols="80" rows="3"></textarea></td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>{{ $lang['label_operable_act'] }}</strong></div>
                </td>
                <td colspan="6" align="left">@if($delivery_order['status'] === 2)
                        <input name="delivery_confirmed" type="submit" value="{{ $lang['op_ship'] }}"
                               class="button"/>&nbsp;&nbsp;
                    @else
                        <input name="unship" type="submit" value="{{ $lang['op_cancel_ship'] }}" class="button"/>
                    @endif
                    <input name="order_id" type="hidden" value="{{ $delivery_order['order_id'] }}">
                    <input name="delivery_id" type="hidden" value="{{ $delivery_order['delivery_id'] }}">
                    <input name="act" type="hidden" value="{{ $action_act }}">
                </td>
            </tr>
        @endif

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
            </form>
    </table>
</div>

<script type="text/javascript">

    var oldAgencyId = {{ $delivery_order['agency_id'] ?? 0 }};
</script>
@include('admin::pagefooter')
