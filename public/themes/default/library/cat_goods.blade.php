<div class="box">
    <div class="box_1">
        <h3><span><a href="{{ $goods_cat['url'] }}" class="f6">{{ $goods_cat['name'] }}</a></span></h3>
        <div class="centerPadd">
            <div class="clearfix goodsBox" style="border:none;">
                @foreach($cat_goods as $goods)
                    <div class="goodsItem">
                        <a href="{{ $goods['url'] }}"><img src="{{ $goods['thumb'] }}" alt="{{ $goods['name'] }}"
                                                           class="goodsimg"/></a><br/>
                        <p><a href="{{ $goods['url'] }}" title="{{ $goods['name'] }}">{{ $goods['short_name'] }}</a></p>
                        @if($goods['promote_price'] != "")
                            <font class="shop_s">{{ $goods['promote_price'] }}</font>
                        @else
                            <font class="shop_s">{{ $goods['shop_price'] }}</font>
                        @endif
                    </div>
                @endforeach
                <div class="more"><a href="{{ $goods_cat['url'] }}"><img src="images/more.gif"/></a></div>
            </div>
        </div>
    </div>
</div>
<div class="blank5"></div>
