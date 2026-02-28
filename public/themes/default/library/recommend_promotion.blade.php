@if($promotion_goods)
    <div id="sales" class="f_l clearfix">
        <h1><a href="../search.php?intro=promotion"><img src="images/more.gif"/></a></h1>
        <div class="clearfix goodBox">
            @foreach($promotion_goods as $goods)
                @if($loop->promotion_foreach.index <= 3)
                    <div class="goodList">
                        <a href="{{ $goods['url'] }}"><img src="{{ $goods['thumb'] }}" border="0"
                                                           alt="{{ $goods['name'] }}"/></a><br/>
                        <p><a href="{{ $goods['url'] }}" title="{{ $goods['name'] }}">{{ $goods['short_name'] }}</a></p>
                        {{ $lang['promote_price'] }}<font class="f1">{{ $goods['promote_price'] }}</font>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif
