@if($bought_goods)
    <div class="box">
        <div class="box_1">
            <h3><span class="text">{{ $lang['shopping_and_other'] }}</span></h3>
            <div class="boxCenterList clearfix ie6">
                @foreach($bought_goods as $bought_goods_data)
                    <div class="goodsItem">
                        <a href="{{ $bought_goods_data['url'] }}"><img src="{{ $bought_goods_data['goods_thumb'] }}"
                                                                       alt="{{ $bought_goods_data['goods_name'] }}"
                                                                       class="goodsimg"/></a><br/>
                        <p><a href="{{ $bought_goods_data['url'] }}"
                              title="{{ $bought_goods_data['goods_name'] }}">{{ $bought_goods_data['short_name'] }}</a>
                        </p>
                        @if($bought_goods_data['promote_price'] != 0)
                            <font class="shop_s">{{ $bought_goods_data['formated_promote_price'] }}</font>
                        @else
                            <font class="shop_s">{{ $bought_goods_data['shop_price'] }}</font>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="blank5"></div>
@endif
