@include('admin::pageheader')
<ul style="margin: 0pt; padding: 0pt; list-style-type: none; color: rgb(204, 0, 0);">
    <li style="border: 1px solid rgb(204, 0, 0); padding: 10px; background: rgb(255, 255, 204) none repeat scroll 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; margin-bottom: 5px;">{{ $lang['notice_edit'] }}</li>
</ul>
<div style="background-color: #F4FAFB;"></div>
<div class="list-div">
    <form action="goods_batch.php?act=update" method="post" name="theForm">
        <table cellspacing="1" cellpadding="3" width="100%">
            @if($edit_method === "each")
                <tr>
                    <th scope="col">{{ $lang['goods_sn'] }}</th>
                    <th scope="col">{{ $lang['goods_name'] }}</th>
                    <th scope="col">{{ $lang['market_price'] }}</th>
                    <th scope="col">{{ $lang['shop_price'] }}</th>
                    @foreach($rank_list as $rank)
                        <th scope="col">{{ $rank['rank_name'] }}</th>
                    @endforeach
                    <th scope="col">{{ $lang['integral'] }}</th>
                    <th scope="col">{{ $lang['give_integral'] }}</th>
                    <th scope="col">{{ $lang['goods_number'] }}</th>
                    <th scope="col">{{ $lang['brand'] }}</th>
                </tr>
                @foreach($goods_list as $goods)
                    <tr align="center">
                        <td class="first-cell" scope="row">{{ $goods['goods_sn'] }}</td>
                        <td class="first-cell" scope="row">{{ $goods['goods_name'] }}</td>
                        <td>
                            <input name="market_price[{{ $goods['goods_id'] }}]" type="text"
                                   value="{{ $goods['market_price'] }}" size="8" style="text-align:right"/></td>
                        <td>
                            <input name="shop_price[{{ $goods['goods_id'] }}]" type="text"
                                   value="{{ $goods['shop_price'] }}" size="8" style="text-align:right"/></td>
                        @foreach($rank_list as $rank)
                            <td>
                                <input name="member_price[{{ $goods['goods_id'] }}][{{ $rank['rank_id'] }}]" type="text"
                                       value="{$member_price_list[$goods.goods_id][$rank.rank_id]|default:-1}" size="8"
                                       style="text-align:right"/></td>
                        @endforeach
                        <td>
                            <input name="integral[{{ $goods['goods_id'] }}]" type="text"
                                   value="{{ $goods['integral'] }}" size="8" style="text-align:right"/></td>
                        <td><input name="give_integral[{{ $goods['goods_id'] }}]" type="text"
                                   id="give_integral[{{ $goods['goods_id'] }}]" value="{{ $goods['give_integral'] }}"
                                   size="8" style="text-align:right"/></td>
                        <td>
                            <input name="goods_number[{{ $goods['goods_id'] }}]" type="text"
                                   value="{{ $goods['goods_number'] }}" size="8" style="text-align:right"
                                   @if($goods['is_real'] === 0)readonly="readonly"@endif /></td>
                        <td><select name="brand_id[{{ $goods['goods_id'] }}]">
                                <option value="0"
                                        @if($goods['brand_id'] === 0)selected@endif>{{ $lang['select_please'] }}</option>
                                {html_options options=$brand_list selected=$goods.brand_id}</select></td>
                    </tr>
                    @if($product_exists)
                        @foreach($product_list[$goods['goods_id']] as $products)
                            <tr align="center">
                                <td class="first-cell" scope="row">{{ $products['product_sn'] }}</td>
                                <td class="first-cell" scope="row">{{ $products['goods_attr'] }}</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                @foreach($rank_list as $rank)
                                    <td>&nbsp;</td>
                                @endforeach
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>
                                    <input
                                        name="product_number[{{ $goods['goods_id'] }}][{{ $products['product_id'] }}]"
                                        type="text" value="{{ $products['product_number'] }}" size="8"
                                        style="text-align:right"/></td>
                                <td>&nbsp;</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @else
                <tr>
                    <th scope="col" colspan="102">{{ $lang['goods_name'] }}</th>
                </tr>
                <tr align="center">
                    <td colspan="102">
                        @foreach($goods_list as $goods)
                            {{ $goods['goods_name'] }},
                        @endforeach      </td>
                </tr>
                <tr>
                    <th scope="col">{{ $lang['market_price'] }}</th>
                    <th scope="col">{{ $lang['shop_price'] }}</th>
                    @foreach($rank_list as $rank)
                        <th scope="col">{{ $rank['rank_name'] }}</th>
                    @endforeach
                    <th scope="col">{{ $lang['integral'] }}</th>
                    <th scope="col">{{ $lang['give_integral'] }}</th>
                    <th scope="col">{{ $lang['goods_number'] }}</th>
                    <th scope="col">{{ $lang['brand'] }}</th>
                </tr>
                <tr align="center">
                    <td>
                        <input name="market_price" type="text" value="" size="8" style="text-align:right"/></td>
                    <td>
                        <input name="shop_price" type="text" size="8" style="text-align:right"/></td>
                    @foreach($rank_list as $rank)
                        <td>
                            <input name="member_price[{{ $rank['rank_id'] }}]" type="text" size="8"
                                   style="text-align:right"/></td>
                    @endforeach
                    <td>
                        <input name="integral" type="text" size="8" style="text-align:right"/></td>
                    <td><input name="give_integral" type="text" id="give_integral" style="text-align:right" size="8"/>
                    </td>
                    <td>
                        <input name="goods_number" type="text" size="8" style="text-align:right"/></td>
                    <td><select name="brand_id">
                            <option value="0" selected>{{ $lang['select_please'] }}</option>
                            {html_options options=$brand_list}</select></td>
                </tr>
            @endif
            <tr align="center">
                <td colspan="22" scope="row">
                    <input type="submit" name="submit" value="{{ $lang['button_submit'] }}" class="button"/>
                    <input type="reset" name="reset" value="{{ $lang['button_reset'] }}" class="button"/>
                    <input type="hidden" name="edit_method" value="{{ $edit_method }}"/>
                    @foreach($goods_list as $goods)
                        <input type="hidden" name="goods_id[]" value="{{ $goods['goods_id'] }}"/>
                    @endforeach
                    @foreach($rank_list as $rank)
                        <input type="hidden" name="rank_id[]" value="{{ $rank['rank_id'] }}"/>
                    @endforeach    </td>
            </tr>
        </table>
    </form>
</div>
@include('admin::pagefooter')
