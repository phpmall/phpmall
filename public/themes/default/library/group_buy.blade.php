@if($group_buy_goods)
    <div class="box">
        <div class="box_1">
            <h3><span>{{ $lang['group_buy_goods'] }}</span><a href="group_buy.php"><img src="../images/more.gif"></a>
            </h3>
            <div class="centerPadd">
                <div class="clearfix goodsBox" style="border:none;">
                    @foreach($group_buy_goods as $goods)
                        <div class="goodsItem">
                            <a href="{{ $goods['url'] }}"><img src="{{ $goods['thumb'] }}"
                                                               alt="{{ $goods['goods_name'] }}"
                                                               class="goodsimg"/></a><br/>
                            <p><a href="{{ $goods['url'] }}"
                                  title="{{ $goods['goods_name'] }}">{{ $goods['short_style_name'] }}</a></p>
                            <font class="shop_s">{{ $goods['last_price'] }}</font>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="blank5"></div>
@endif
