@if($related_goods)
    <div class="box">
        <div class="box_1">
            <h3><span>{{ $lang['releate_goods'] }}</span></h3>
            <div class="boxCenterList clearfix">
                @foreach($related_goods as $releated_goods_data)
                    <ul class="clearfix">
                        <li class="goodsimg"><a href="{{ $releated_goods_data['url'] }}"><img
                                    src="{{ $releated_goods_data['goods_thumb'] }}"
                                    alt="{{ $releated_goods_data['goods_name'] }}"
                                    class="B_blue"/></a></li>
                        <li>
                            <a href="{{ $releated_goods_data['url'] }}"
                               title="{{ $releated_goods_data['goods_name'] }}">{{ $releated_goods_data['short_name'] }}</a><br/>
                            @if($releated_goods_data['promote_price'] != 0)
                                {{ $lang['promote_price'] }}
                                <font class="f1">{{ $releated_goods_data['formated_promote_price'] }}</font>
                            @else
                                {{ $lang['shop_price'] }}
                                <font class="f1">{{ $releated_goods_data['shop_price'] }}</font>
                            @endif
                        </li>
                    </ul>
                @endforeach
            </div>
        </div>
    </div>
    <div class="blank5"></div>
@endif
