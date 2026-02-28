<!-- 开始循环属性关联的商品 @foreach($attribute_linked as $linked)-->
@if($linked['goods'])
    <div class="box">
        <div class="box_1">
            <h3><span title="{{ $linked['title'] }}">{$linked.title|truncate:11:"...":true}</span></h3>
            <div class="boxCenterList clearfix">
                @foreach($linked['goods'] as $linked_goods_data)
                    <ul class="clearfix">
                        <li class="goodsimg">
                            <a href="{{ $linked_goods_data['url'] }}" target="_blank"><img
                                    src="{{ $linked_goods_data['goods_thumb'] }}"
                                    alt="{{ $linked_goods_data['name'] }}" class="B_blue"/></a>
                        </li>
                        <li>
                            <a href="{{ $linked_goods_data['url'] }}" target="_blank"
                               title="{{ $goods['linked_goods_data_name'] }}">{{ $linked_goods_data['short_name'] }}</a><br/>
                            {{ $lang['shop_price'] }}
                            <font class="f1">{{ $linked_goods_data['shop_price'] }}</font><br/>
                        </li>
                    </ul>
                @endforeach
            </div>
        </div>
    </div>
    <div class="blank5"></div>
@endif
<!-- 结束属性关联的商品 @endforeach-->
