<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="Keywords" content="{{ $keywords }}"/>
    <meta name="Description" content="{{ $description }}"/>
    <title>{{ $page_title }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link href="{{ $ecs_css_path }}" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/global.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/compare.js') }}"></script>
</head>
<body>
@include('web::library/page_header')
<!--当前位置 start-->
<div class="block box">
    <div id="ur_here">
        @include('web::library/ur_here')
    </div>
</div>
<!--当前位置 end-->
<div class="blank"></div>
<div class="block clearfix">
    <!--left start-->
    <div class="AreaL">
        <!-- TemplateBeginEditable name="左边区域" -->
        @include('web::library/cart')
        @include('web::library/category_tree')
        @include('web::library/goods_related')
        @include('web::library/goods_fittings')
        @include('web::library/goods_article')
        @include('web::library/goods_attrlinked')
        <!-- TemplateEndEditable -->
        <!-- TemplateBeginEditable name="左边广告区域（宽200px）" -->
        <!-- TemplateEndEditable -->
        <!--AD end-->
        @include('web::library/history')
    </div>
    <!--left end-->
    <!--right start-->
    <div class="AreaR">
        @if($action == "form")
            <!--  搜索的表单 -->
            <div class="box">
                <div class="box_1">
                    <h3><span>{{ $lang['advanced_search'] }}</span></h3>
                    <div class="boxCenterList">
                        <form action="search.php" method="get" name="advancedSearchForm" id="advancedSearchForm">
                            <table border="0" align="center" cellpadding="3">
                                <tr>
                                    <td valign="top">{{ $lang['keywords'] }}：</td>
                                    <td>
                                        <input name="keywords" id="keywords" type="text" size="40" maxlength="120"
                                               class="inputBg" value="{{ $adv_val['keywords'] }}"/>
                                        <label for="sc_ds"><input type="checkbox" name="sc_ds" value="1"
                                                                  id="sc_ds" {{ $scck }} />{{ $lang['sc_ds'] }}</label>
                                        <br/>{{ $lang['searchkeywords_notice'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ $lang['category'] }}：</td>
                                    <td><select name="category" id="select" style="border:1px solid #ccc;">
                                            <option value="0">{{ $lang['all_category'] }}</option>{{ $cat_list }}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ $lang['brand'] }}：</td>
                                    <td><select name="brand" id="brand" style="border:1px solid #ccc;">
                                            <option value="0">{{ $lang['all_brand'] }}</option>
                                            {html_options options=$brand_list selected=$adv_val.brand}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ $lang['price'] }}：</td>
                                    <td><input name="min_price" type="text" id="min_price" class="inputBg"
                                               value="{{ $adv_val['min_price'] }}" size="10" maxlength="8"/>
                                        -
                                        <input name="max_price" type="text" id="max_price" class="inputBg"
                                               value="{{ $adv_val['max_price'] }}" size="10" maxlength="8"/>
                                    </td>
                                </tr>
                                @if($goods_type_list)
                                    <tr>
                                        <td>{{ $lang['extension'] }}：</td>
                                        <td><select name="goods_type" onchange="this.form.submit()"
                                                    style="border:1px solid #ccc;">
                                                <option value="0">{{ $lang['all_option'] }}</option>
                                                {html_options options=$goods_type_list selected=$goods_type_selected}
                                            </select>
                                        </td>
                                    </tr>
                                @endif
                                @if($goods_type_selected > 0)
                                    @foreach($goods_attributes as $item)
                                        @if($item['type'] == 1)
                                            <tr>
                                                <td>{{ $item['attr'] }}：</td>
                                                <td colspan="3"><input name="attr[{{ $item['id'] }}]"
                                                                       value="{{ $item['value'] }}" class="inputBg"
                                                                       type="text" size="20" maxlength="120"/></td>
                                            </tr>
                                        @endif
                                        @if($item['type'] == 2)
                                            <tr>
                                                <td>{{ $item['attr'] }}：</td>
                                                <td colspan="3"><input name="attr[{{ $item['id'] }}][from]"
                                                                       class="inputBg"
                                                                       value="{{ $item['value']['from'] }}" type="text"
                                                                       size="5" maxlength="5"/>
                                                    -
                                                    <input name="attr[{{ $item['id'] }}][to]"
                                                           value="{{ $item['value']['to'] }}" class="inputBg"
                                                           type="text" maxlength="5"/></td>
                                            </tr>
                                        @endif
                                        @if($item['type'] == 3)
                                            <tr>
                                                <td>{{ $item['attr'] }}：</td>
                                                <td colspan="3"><select name="attr[{{ $item['id'] }}]"
                                                                        style="border:1px solid #ccc;">
                                                        <option value="0">{{ $lang['all_option'] }}</option>
                                                        {html_options options=$item.options selected=$item.value}
                                                    </select></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif

                                @if($use_storage == 1)
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><label for="outstock"><input type="checkbox" name="outstock" value="1"
                                                                         id="outstock"
                                                                         @if($outstock)checked="checked"@endif/> {{ $lang['hidden_outstock'] }}
                                            </label>
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <td colspan="4" align="center"><input type="hidden" name="action" value="form"/>
                                        <input type="submit" name="Submit" class="bnt_blue_1"
                                               value="{{ $lang['button_search'] }}"/></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <div class="blank5"></div>
        @endif

        @if(isset($goods_list))
            <div class="box">
                <div class="box_1">
                    <h3>
                        <!--标题及显示方式-->
                        @if($intromode == 'best')
                            <span>{{ $lang['best_goods'] }}</span>
                        @elseif($intromode == 'new')
                            <span>{{ $lang['new_goods'] }}</span>
                        @elseif($intromode == 'hot')
                            <span>{{ $lang['hot_goods'] }}</span>
                        @elseif($intromode == 'promotion')
                            <span>{{ $lang['promotion_goods'] }}</span>
                        @else
                            <span>{{ $lang['search_result'] }}</span>
                        @endif
                        @if($goods_list)
                            <form action="search.php" method="post" class="sort" name="listform" id="form">
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
                                <select name="sort">
                                    {html_options options=$lang.sort selected=$pager.search.sort}
                                </select>
                                <select name="order">
                                    {html_options options=$lang.order selected=$pager.search.order}
                                </select>
                                <input type="image" name="imageField" src="../images/bnt_go.gif" alt="go"/>
                                <input type="hidden" name="page" value="{{ $pager['page'] }}"/>
                                <input type="hidden" name="display" value="{{ $pager['display'] }}" id="display"/>
                                @foreach($pager['search'] as $key => $item)
                                    @if($key != "sort" && $key != "order")
                                        @if($key == 'keywords')
                                            <input type="hidden" name="{{ $key }}" value="{{{ $item }}}"/>
                                        @else
                                            <input type="hidden" name="{{ $key }}" value="{{ $item }}"/>
                                        @endif
                                    @endif
                                @endforeach
                            </form>
                        @endif
                    </h3>
                    @if($goods_list)

                        <form action="compare.php" method="post" name="compareForm" id="compareForm"
                              onsubmit="return compareGoods(this);">
                            @if($pager['display'] == 'list')
                                <div class="goodsList">
                                    @foreach($goods_list as $goods)
                                        @if($goods['goods_id'])
                                            <ul class="clearfix bgcolor"'.($loop->goods_list.index mod 2 == 0 ? 'id=""'
                                            : 'id="bgcolor"').'>
                                            <li>
                                                <br>
                                                <a href="javascript:;" id="compareLink"
                                                   onClick="Compare.add({{ $goods['goods_id'] }},'{$goods.goods_name|escape:"
                                                   quotes"}','{{ $goods['type'] }}')"
                                                class="f6">{{ $lang['compare'] }}</a>
                                            </li>
                                            <li class="thumb"><a href="{{ $goods['url'] }}"><img
                                                        src="{{ $goods['goods_thumb'] }}"
                                                        alt="{{ $goods['goods_name'] }}"/></a></li>
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
                                                    {{ $lang['market_price'] }}<font
                                                        class="market">{{ $goods['market_price'] }}</font><br/>
                                                @endif
                                                @if($goods['promote_price'] != "" )
                                                    {{ $lang['promote_price'] }}<font
                                                        class="shop">{{ $goods['promote_price'] }}</font><br/>
                                                @else
                                                    {{ $lang['shop_price'] }}<font
                                                        class="shop">{{ $goods['shop_price'] }}</font><br/>
                                                @endif
                                            </li>
                                            <li class="action">
                                                <a href="javascript:collect({{ $goods['goods_id'] }});"
                                                   class="abg f6">{{ $lang['favourable_goods'] }}</a>
                                                <a href="javascript:addToCart({{ $goods['goods_id'] }})"><img
                                                        src="../images/bnt_buy_1.gif"></a>
                                            </li>
                                            </ul>
                                        @endif
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
                                                          title="{{ $goods['name'] }}">{{ $goods['goods_name'] }}</a>
                                                    </p>
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
                                                       quotes"}','{{ $goods['type'] }}')"
                                                    class="f6">{{ $lang['compare'] }}</a>
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
                                               quotes"}','{{ $goods['type'] }}')" class="f6">{{ $lang['compare'] }}</a>
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
                                                {{ $lang['market_price'] }}<font
                                                    class="market">{{ $goods['market_price'] }}</font><br/>
                                            @endif
                                            @if($goods['promote_price'] != "" )
                                                {{ $lang['promote_price'] }}<font
                                                    class="shop">{{ $goods['promote_price'] }}</font><br/>
                                            @else
                                                {{ $lang['shop_price'] }}<font
                                                    class="shop">{{ $goods['shop_price'] }}</font><br/>
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
                        </form>
                        <script type="text/javascript">
                            @foreach($lang['compare_js'] as $item => $key)
                            var {{ $key }} = "{{ $item }}";
                            @endforeach

                            @foreach($lang['compare_js'] as $item => $key)
                            @if($key != 'button_compare')
                            var {{ $key }} = "{{ $item }}";
                            @else
                            var button_compare = '';
                            @endif
                            @endforeach


                            var compare_no_goods = "{{ $lang['compare_no_goods'] }}";
                            window.onload = function () {
                                Compare.init();
                                fixpng();
                            }
                            var btn_buy = "{{ $lang['btn_buy'] }}";
                            var is_cancel = "{{ $lang['is_cancel'] }}";
                            var select_spe = "{{ $lang['select_spe'] }}";
                        </script>
                    @else
                        <div style="padding:20px 0px; text-align:center"
                             class="f5">{{ $lang['no_search_result'] }}</div>
                    @endif
                </div>
            </div>
            <div class="blank"></div>
            @include('web::library/pages')
        @endif

    </div>
    <!--right end-->
</div>
<div class="blank5"></div>
<!--帮助-->
<div class="block">
    <div class="box">
        <div class="helpTitBg clearfix">
            @include('web::library/help')
        </div>
    </div>
</div>
<div class="blank"></div>
<!--帮助-->
@include('web::library/page_footer')
</body>
</html>
