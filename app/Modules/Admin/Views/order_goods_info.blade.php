<table width="100%" cellpadding="3" cellspacing="1">
    <tr>
        <td scope="col">
            <div align="center"><strong>{{ $lang['goods_name_brand'] }}</strong></div>
        </td>
        <td scope="col">
            <div align="center"><strong>{{ $lang['goods_sn'] }}</strong></div>
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
            @if($goods['goods_id'] > 0 && $goods['extension_code'] != 'package_buy')
                <td><img src="{{ $goods['goods_thumb'] }}"/><br/><a href="../goods.php?id={{ $goods['goods_id'] }}"
                                                                    target="_blank">{{ $goods['goods_name'] }} @if($goods['brand_name'])
                            [ {{ $goods['brand_name'] }} ]
                        @endif
                        '.($goods['is_gift'] ? '@if($goods['goods_price'] > 0)
                            {{ $lang['remark_favourable'] }}' : '{{ $lang['remark_gift'] }}').'
                        @endif
                        @if($goods['parent_id'] > 0)
                            {{ $lang['remark_fittings'] }}
                        @endif</a></td>
            @else
                <td>{{ $goods['goods_name'] }}{{ $lang['remark_package'] }}</td>
            @endif
            <td>{{ $goods['goods_sn'] }}</td>
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
</table>
