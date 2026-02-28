@if($new_goods)
    @if($cat_rec_sign != 1)
        <div class="box">
            <div class="box_2 centerPadd">
                <div class="itemTit New" id="itemNew">
                    @if($cat_rec[2])
                        <h2><a href="javascript:void(0)"
                               onclick="change_tab_style('itemNew', 'h2', this);get_cat_recommend(2, 0);">{{ $lang['all_goods'] }}</a>
                        </h2>
                        @foreach($cat_rec[2] as $rec_data)
                            <h2 class="h2bg"><a href="javascript:void(0)"
                                                onclick="change_tab_style('itemNew', 'h2', this);get_cat_recommend(2, {{ $rec_data['cat_id'] }})">{{ $rec_data['cat_name'] }}</a>
                            </h2>
                        @endforeach
                    @endif
                </div>
                <div id="show_new_area" class="clearfix goodsBox">
                    @endif
                    @foreach($new_goods as $goods)
                        <div class="goodsItem">
                            <span class="news"></span>
                            <a href="{{ $goods['url'] }}"><img src="{{ $goods['thumb'] }}" alt="{{ $goods['name'] }}"
                                                               class="goodsimg"/></a><br/>
                            <p><a href="{{ $goods['url'] }}"
                                  title="{{ $goods['name'] }}">{{ $goods['short_style_name'] }}</a></p>
                            <font class="f1">
                                @if($goods['promote_price'] != "")
                                    {{ $goods['promote_price'] }}
                                @else
                                    {{ $goods['shop_price'] }}
                                @endif
                            </font>
                        </div>
                    @endforeach
                    <div class="more"><a href="../search.php?intro=new"><img src="images/more.gif"/></a></div>
                    @if($cat_rec_sign != 1)
                </div>
            </div>
        </div>
        <div class="blank5"></div>
    @endif
@endif
