<script type="text/javascript" src="{{ asset('js/transport.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
<div id="ECS_ORDERTOTAL">
    <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
        @if($smarty['session']['user_id'] > 0 && ($config['use_integral'] || $config['use_bonus']))
            <tr>
                <td align="right" bgcolor="#ffffff">
                    {{ $lang['complete_acquisition'] }}
                    <!-- @if($config['use_integral'])
                        是否使用积分-->
                        <font class="f4_b">{{ $total['will_get_integral'] }}</font> {{ $points_name }}
                    @endif
                    @if($config['use_integral'] && $config['use_bonus'])
                        是否同时使用积分红包-->，{{ $lang['with_price'] }}  <!-- @endif
                            <!-- @if($config['use_bonus'])
                            是否使用红包-->
                            <font
                                class="f4_b">{{ $total['will_get_bonus'] }}</font>{{ $lang['de'] }}{{ $lang['bonus'] }}。
                        @endif    </td>
            </tr>
        @endif
        <tr>
            <td align="right" bgcolor="#ffffff">
                {{ $lang['goods_all_price'] }}: <font class="f4_b">{{ $total['goods_price_formated'] }}</font>
                <!-- @if($total['discount'] > 0)
                    折扣 -->
                    - {{ $lang['discount'] }}: <font class="f4_b">{{ $total['discount_formated'] }}</font>
                @endif
                <!-- @if($total['tax'] > 0)
                    税 -->
                    + {{ $lang['tax'] }}: <font class="f4_b">{{ $total['tax_formated'] }}</font>
                @endif
                <!-- @if($total['shipping_fee'] > 0)
                    配送费用 -->
                    + {{ $lang['shipping_fee'] }}: <font class="f4_b">{{ $total['shipping_fee_formated'] }}</font>
                @endif
                <!-- @if($total['shipping_insure'] > 0)
                    保价费用 -->
                    + {{ $lang['insure_fee'] }}: <font class="f4_b">{{ $total['shipping_insure_formated'] }}</font>
                @endif
                <!-- @if($total['pay_fee'] > 0)
                    支付费用 -->
                    + {{ $lang['pay_fee'] }}: <font class="f4_b">{{ $total['pay_fee_formated'] }}</font>
                @endif
                <!-- @if($total['pack_fee'] > 0)
                    包装费用-->
                    + {{ $lang['pack_fee'] }}: <font class="f4_b">{{ $total['pack_fee_formated'] }}</font>
                @endif
                <!-- @if($total['card_fee'] > 0)
                    贺卡费用-->
                    + {{ $lang['card_fee'] }}: <font class="f4_b">{{ $total['card_fee_formated'] }}</font>
                @endif    </td>
        </tr>
        <!-- @if($total['surplus'] > 0 || $total['integral'] > 0 || $total['bonus'] > 0)
            使用余额或积分或红包 -->
            <tr>
                <td align="right" bgcolor="#ffffff">
                    <!-- @if($total['surplus'] > 0)
                        使用余额 -->
                        - {{ $lang['use_surplus'] }}: <font class="f4_b">{{ $total['surplus_formated'] }}</font>
                    @endif
                    <!-- @if($total['integral'] > 0)
                        使用积分 -->
                        - {{ $lang['use_integral'] }}: <font class="f4_b">{{ $total['integral_formated'] }}</font>
                    @endif
                    <!-- @if($total['bonus'] > 0)
                        使用红包 -->
                        - {{ $lang['use_bonus'] }}: <font class="f4_b">{{ $total['bonus_formated'] }}</font>
                    @endif    </td>
            </tr>
            <!--

        @endif 使用余额或积分或红包 -->
        <tr>
            <td align="right" bgcolor="#ffffff"> {{ $lang['total_fee'] }}: <font
                    class="f4_b">{{ $total['amount_formated'] }}</font>
                @if($is_group_buy)
                    <br/>
                    {{ $lang['notice_gb_order_amount'] }}
                @endif
                <!--@if($total['exchange_integral'] )
                    消耗积分--><br/>
                    {{ $lang['notice_eg_integral'] }}<font class="f4_b">{{ $total['exchange_integral'] }}</font>
                @endif
            </td>
        </tr>
    </table>
</div>
