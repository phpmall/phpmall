<div class="box">
    <div class="box_2">
        <div class="top10Tit"></div>
        <div class="top10List clearfix">
            @foreach($top_goods as $goods)
                <ul class="clearfix">
                    <img src="../images/top_{{ $smarty['foreach']['top_goods']['iteration'] }}.gif" class="iteration"/>
                    @if($loop->top_goods.iteration<4)
                        <li class="topimg">
                            <a href="{{ $goods['url'] }}"><img src="{{ $goods['thumb'] }}" alt="{{ $goods['name'] }}"
                                                               class="samllimg"/></a>
                        </li>
                    @endif
                    <li @if($loop->top_goods.iteration<4)class="iteration1"@endif>
                        <a href="{{ $goods['url'] }}" title="{{ $goods['name'] }}">{{ $goods['short_name'] }}</a><br/>
                        {{ $lang['shop_price'] }}<font class="f1">{{ $goods['price'] }}</font><br/>
                    </li>
                </ul>
            @endforeach
        </div>
    </div>
</div>
<div class="blank5"></div>
