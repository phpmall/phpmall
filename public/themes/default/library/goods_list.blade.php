<div class="box">
    <div class="box_1">
        <h3>
            <span>{{ $lang['goods_list'] }}</span><a name='goods_list'></a>
            <form method="GET" class="sort" name="listform">
                {{ $lang['btn_display'] }}：
                <a href="javascript:;" onClick="javascript:display_mode('list')"><img
                        src="images/display_mode_list@if($pager['display'] == 'list')_act@endif.gif"
                        alt="{{ $lang['display']['list'] }}"></a>
                <a href="javascript:;" onClick="javascript:display_mode('grid')"><img
                        src="images/display_mode_grid@if($pager['display'] == 'grid')_act@endif.gif"
                        alt="{{ $lang['display']['grid'] }}"></a>
                <a href="javascript:;" onClick="javascript:display_mode('text')"><img
                        src="images/display_mode_text@if($pager['display'] == 'text')_act@endif.gif"
                        alt="{{ $lang['display']['text'] }}"></a>&nbsp;&nbsp;

                <a href="{{ $script_name }}.php?category={{ $category }}&display={{ $pager['display'] }}&brand={{ $brand_id }}&price_min={{ $price_min }}&price_max={{ $price_max }}&filter_attr={{ $filter_attr }}&page={{ $pager['page'] }}&sort=goods_id&order='.($pager['sort'] == 'goods_id' && $pager['order'] == 'DESC' ? 'ASC' : 'DESC').'#goods_list"><img
                        src="images/goods_id_'.($pager['sort'] == 'goods_id' ? '{{ $pager['order'] }}' : 'default').'.gif"
                        alt="{{ $lang['sort']['goods_id'] }}"></a>
                <a href="{{ $script_name }}.php?category={{ $category }}&display={{ $pager['display'] }}&brand={{ $brand_id }}&price_min={{ $price_min }}&price_max={{ $price_max }}&filter_attr={{ $filter_attr }}&page={{ $pager['page'] }}&sort=shop_price&order='.($pager['sort'] == 'shop_price' && $pager['order'] == 'ASC' ? 'DESC' : 'ASC').'#goods_list"><img
                        src="images/shop_price_'.($pager['sort'] == 'shop_price' ? '{{ $pager['order'] }}' : 'default').'.gif"
                        alt="{{ $lang['sort']['shop_price'] }}"></a>
                <a href="{{ $script_name }}.php?category={{ $category }}&display={{ $pager['display'] }}&brand={{ $brand_id }}&price_min={{ $price_min }}&price_max={{ $price_max }}&filter_attr={{ $filter_attr }}&page={{ $pager['page'] }}&sort=last_update&order='.($pager['sort'] == 'last_update' && $pager['order'] == 'DESC' ? 'ASC' : 'DESC').'#goods_list"><img
                        src="images/last_update_'.($pager['sort'] == 'last_update' ? '{{ $pager['order'] }}' : 'default').'.gif"
                        alt="{{ $lang['sort']['last_update'] }}"></a>

                <input type="hidden" name="category" value="{{ $category }}"/>
                <input type="hidden" name="display" value="{{ $pager['display'] }}" id="display"/>
                <input type="hidden" name="brand" value="{{ $brand_id }}"/>
                <input type="hidden" name="price_min" value="{{ $price_min }}"/>
                <input type="hidden" name="price_max" value="{{ $price_max }}"/>
                <input type="hidden" name="filter_attr" value="{{ $filter_attr }}"/>
                <input type="hidden" name="page" value="{{ $pager['page'] }}"/>
                <input type="hidden" name="sort" value="{{ $pager['sort'] }}"/>
                <input type="hidden" name="order" value="{{ $pager['order'] }}"/>
            </form>
        </h3>

        @if($category > 0)
            <form name="compareForm" action="compare.php" method="post" onSubmit="return compareGoods(this);">
                @endif
                @if($pager['display'] == 'list')
                    <div class="goodsList">
                        @foreach($goods_list as $goods)
                            <ul class="clearfix bgcolor"'.($loop->goods_list.index mod 2 == 0 ? 'id=""' :
                            'id="bgcolor"').'>
                            <li>
                                <br>
                                <a href="javascript:;" id="compareLink"
                                   onClick="Compare.add({{ $goods['goods_id'] }},'{$goods.goods_name|escape:"
                                   html"}','{{ $goods['type'] }}')" class="f6">比较</a>
                            </li>
                            <li class="thumb"><a href="{{ $goods['url'] }}"><img src="{{ $goods['goods_thumb'] }}"
                                                                                 alt="{{ $goods['goods_name'] }}"/></a>
                            </li>
                            <li class="goodsName">
                                <a href="{{ $goods['url'] }}" class="f6">
                                    @if($goods['goods_style_name'])
                                        {{ $goods['goods_style_name'] }}<br/>
                                    @else
                                        {{ $goods['goods_name'] }}<br/>
                                    @endif
                                </a>
                                @if($goods['goods_brief'])
                                    {{ $lang['goods_brief'] }}{{ $goods['goods_brief'] }}<br/>
                                @endif
                            </li>
                            <li>
                                @if($show_marketprice)
                                    {{ $lang['market_price'] }}<font class="market">{{ $goods['market_price'] }}</font>
                                    <br/>
                                @endif
                                @if($goods['promote_price'] != "" )
                                    {{ $lang['promote_price'] }}<font class="shop">{{ $goods['promote_price'] }}</font>
                                    <br/>
                                @else
                                    {{ $lang['shop_price'] }}<font class="shop">{{ $goods['shop_price'] }}</font><br/>
                                @endif
                            </li>
                            <li class="action">
                                <a href="javascript:collect({{ $goods['goods_id'] }});"
                                   class="abg f6">{{ $lang['favourable_goods'] }}</a>
                                <a href="javascript:addToCart({{ $goods['goods_id'] }})"><img
                                        src="../images/bnt_buy_1.gif"></a>
                            </li>
                            </ul>
                        @endforeach
                    </div>
                @elseif($pager['display'] == 'grid')
                    <div class="centerPadd">
                        <div class="clearfix goodsBox" style="border:none;">
                            @foreach($goods_list as $goods)
                                @if($goods['goods_id'])
                                    <div class="goodsItem">
                                        <a href="{{ $goods['url'] }}"><img src="{{ $goods['goods_thumb'] }}"
                                                                           alt="{{ $goods['goods_name'] }}"
                                                                           class="goodsimg"/></a><br/>
                                        <p><a href="{{ $goods['url'] }}"
                                              title="{{ $goods['name'] }}">{{ $goods['goods_name'] }}</a></p>
                                        @if($show_marketprice)
                                            {{ $lang['market_prices'] }}<font
                                                class="market_s">{{ $goods['market_price'] }}</font><br/>
                                        @endif
                                        @if($goods['promote_price'] != "" )
                                            {{ $lang['promote_price'] }}<font
                                                class="shop_s">{{ $goods['promote_price'] }}</font><br/>
                                        @else
                                            {{ $lang['shop_prices'] }}<font
                                                class="shop_s">{{ $goods['shop_price'] }}</font><br/>
                                        @endif
                                        <a href="javascript:collect({{ $goods['goods_id'] }});"
                                           class="f6">{{ $lang['btn_collect'] }}</a> |
                                        <a href="javascript:addToCart({{ $goods['goods_id'] }})"
                                           class="f6">{{ $lang['btn_buy'] }}</a> |
                                        <a href="javascript:;" id="compareLink"
                                           onClick="Compare.add({{ $goods['goods_id'] }},'{$goods.goods_name|escape:"
                                           html"}','{{ $goods['type'] }}')" class="f6">{{ $lang['compare'] }}</a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @elseif($pager['display'] == 'text')
                    <div class="goodsList">
                        @foreach($goods_list as $goods)
                            <ul class="clearfix bgcolor"'.($loop->goods_list.index mod 2 == 0 ? 'id=""' :
                            'id="bgcolor"').'>
                            <li style="margin-right:15px;">
                                <a href="javascript:;" id="compareLink"
                                   onClick="Compare.add({{ $goods['goods_id'] }},'{$goods.goods_name|escape:"
                                   html"}','{{ $goods['type'] }}')" class="f6">{{ $lang['compare'] }}</a>
                            </li>
                            <li class="goodsName">
                                <a href="{{ $goods['url'] }}" class="f6 f5">
                                    @if($goods['goods_style_name'])
                                        {{ $goods['goods_style_name'] }}<br/>
                                    @else
                                        {{ $goods['goods_name'] }}<br/>
                                    @endif
                                </a>
                                @if($goods['goods_brief'])
                                    {{ $lang['goods_brief'] }}{{ $goods['goods_brief'] }}<br/>
                                @endif
                            </li>
                            <li>
                                @if($show_marketprice)
                                    {{ $lang['market_price'] }}<font class="market">{{ $goods['market_price'] }}</font>
                                    <br/>
                                @endif
                                @if($goods['promote_price'] != "" )
                                    {{ $lang['promote_price'] }}<font class="shop">{{ $goods['promote_price'] }}</font>
                                    <br/>
                                @else
                                    {{ $lang['shop_price'] }}<font class="shop">{{ $goods['shop_price'] }}</font><br/>
                                @endif
                            </li>
                            <li class="action">
                                <a href="javascript:collect({{ $goods['goods_id'] }});"
                                   class="abg f6">{{ $lang['favourable_goods'] }}</a>
                                <a href="javascript:addToCart({{ $goods['goods_id'] }})"><img
                                        src="../images/bnt_buy_1.gif"></a>
                            </li>
                            </ul>
                        @endforeach
                    </div>
                @endif
                @if($category > 0)
            </form>
        @endif

    </div>
</div>
<div class="blank5"></div>
<script type="text/javascript">
    <!--

function selectPage(sel) {
    sel.form.submit();
}


</script>
<script type="text/javascript">
    window.onload = function () {
        Compare.init();
        fixpng();
    }
    @foreach($lang['compare_js'] as $item => $key)
    @if($key != 'button_compare')
    var {{ $key }} = "{{ $item }}";
    @else
    var button_compare = '';
    @endif
    @endforeach
    var compare_no_goods = "{{ $lang['compare_no_goods'] }}";
    var btn_buy = "{{ $lang['btn_buy'] }}";
    var is_cancel = "{{ $lang['is_cancel'] }}";
    var select_spe = "{{ $lang['select_spe'] }}";
</script>
