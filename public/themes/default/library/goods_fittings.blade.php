@if($fittings)
    <div class="box">
        <div class="box_1">
            <h3><span>{{ $lang['accessories_releate'] }}</span></h3>
            <div class="boxCenterList clearfix">
                @foreach($fittings as $goods)
                    <ul class="clearfix">
                        <li class="goodsimg">
                            <a href="{{ $goods['url'] }}" target="_blank"><img src="{{ $goods['goods_thumb'] }}"
                                                                               alt="{{ $goods['name'] }}"
                                                                               class="B_blue"/></a>
                        </li>
                        <li>
                            <a href="{{ $goods['url'] }}" target="_blank"
                               title="{{ $goods['goods_name'] }}">{{ $goods['short_name'] }}</a><br/>
                            {{ $lang['fittings_price'] }}
                            <font class="f1">{{ $goods['fittings_price'] }}</font><br/>
                        </li>
                    </ul>
                @endforeach
            </div>
        </div>
    </div>
    <div class="blank5"></div>
@endif
