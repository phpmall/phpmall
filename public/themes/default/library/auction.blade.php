@if($auction_list)
    <div class="box">
        <div class="box_1">
            <h3><span>{{ $lang['auction_goods'] }}</span><a href="auction.php"><img src="../images/more.gif"></a></h3>
            <div class="centerPadd">
                <div class="clearfix goodsBox" style="border:none;">
                    @foreach($auction_list as $auction)
                        <div class="goodsItem">
                            <a href="{{ $auction['url'] }}"><img src="{{ $auction['thumb'] }}"
                                                                 alt="{{ $auction['goods_name'] }}"
                                                                 class="goodsimg"/></a><br/>
                            <p><a href="{{ $auction['url'] }}"
                                  title="{{ $auction['goods_name'] }}">{{ $auction['short_style_name'] }}</a>
                            </p>
                            <font class="shop_s">{{ $auction['formated_start_price'] }}</font>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="blank5"></div>
@endif
