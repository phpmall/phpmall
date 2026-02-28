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

    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script type="text/javascript">
        function $id(element) {
            return document.getElementById(element);
        }

        //切屏--是按钮，_v是内容平台，_h是内容库
        function reg(str) {
            var bt = $id(str + "_b").getElementsByTagName("h2");
            for (var i = 0; i < bt.length; i++) {
                bt[i].subj = str;
                bt[i].pai = i;
                bt[i].style.cursor = "pointer";
                bt[i].onclick = function () {
                    $id(this.subj + "_v").innerHTML = $id(this.subj + "_h").getElementsByTagName("blockquote")[this.pai].innerHTML;
                    for (var j = 0; j < $id(this.subj + "_b").getElementsByTagName("h2").length; j++) {
                        var _bt = $id(this.subj + "_b").getElementsByTagName("h2")[j];
                        var ison = j == this.pai;
                        _bt.className = (ison ? "" : "h2bg");
                    }
                }
            }
            $id(str + "_h").className = "none";
            $id(str + "_v").innerHTML = $id(str + "_h").getElementsByTagName("blockquote")[0].innerHTML;
        }

    </script>
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
        <!--商品详情start-->
        <div id="goodsInfo" class="clearfix">
            <!--商品图片和相册 start-->
            <div class="imgInfo">
                @if($pictures)
                    <a href="javascript:;"
                       onclick="window.open('gallery.php?id={{ $goods['goods_id'] }}'); return false;">
                        <img src="{{ $goods['goods_img'] }}" alt="{{ $goods['goods_name'] }}"/>
                    </a>
                @else
                    <img src="{{ $goods['goods_img'] }}" alt="{{ $goods['goods_name'] }}"/>
                @endif
                <div class="blank5"></div>
                <!--相册 START-->
                @include('web::library/goods_gallery')
                <!--相册 END-->
                <div class="blank5"></div>
                <!-- TemplateBeginEditable name="商品相册下广告（宽230px）" -->
                <!-- TemplateEndEditable -->
            </div>
            <!--商品图片和相册 end-->
            <div class="textInfo">
                <form action="javascript:addToCart({{ $goods['goods_id'] }})" method="post" name="ECS_FORMBUY"
                      id="ECS_FORMBUY">
                    <div class="clearfix">
                        <p class="f_l">{{ $goods['goods_style_name'] }}</p>
                        <p class="f_r">
                            @if($prev_good)
                                <a href="{{ $prev_good['url'] }}"><img alt="prev" src="./images/up.gif"/></a>
                            @endif
                            @if($next_good)
                                <a href="{{ $next_good['url'] }}"><img alt="next" src="./images/down.gif"/></a>
                            @endif
                        </p>
                    </div>
                    <ul>
                        @if($promotion)
                            <li class="padd">
                                <!-- @foreach($promotion as $item => $key)
                                    优惠活动-->
                                    {{ $lang['activity'] }}
                                    @if($item['type'] == "snatch")
                                        <a href="snatch.php" title="{{ $lang['snatch'] }}"
                                           style="font-weight:100; color:#006bcd; text-decoration:none;">[{{ $lang['snatch'] }}
                                            ]</a>
                                    @elseif($item['type'] == "group_buy")
                                        <a href="group_buy.php" title="{{ $lang['group_buy'] }}"
                                           style="font-weight:100; color:#006bcd; text-decoration:none;">[{{ $lang['group_buy'] }}
                                            ]</a>
                                    @elseif($item['type'] == "auction")
                                        <a href="auction.php" title="{{ $lang['auction'] }}"
                                           style="font-weight:100; color:#006bcd; text-decoration:none;">[{{ $lang['auction'] }}
                                            ]</a>
                                    @elseif($item['type'] == "favourable")
                                        <a href="activity.php" title="{{ $lang['favourable'] }}"
                                           style="font-weight:100; color:#006bcd; text-decoration:none;">[{{ $lang['favourable'] }}
                                            ]</a>
                                    @endif
                                    <a href="{{ $item['url'] }}"
                                       title="{$lang.$item.type} {{ $item['act_name'] }}{{ $item['time'] }}"
                                       style="font-weight:100; color:#006bcd;">{{ $item['act_name'] }}</a><br/>
                                @endforeach
                            </li>
                        @endif
                        <li class="clearfix">
                            <dd>
                                <!-- @if($cfg['show_goodssn'])
                                    显示商品货号-->
                                    <strong>{{ $lang['goods_sn'] }}</strong>{{ $goods['goods_sn'] }}
                                @endif
                            </dd>
                            <dd class="ddR">
                                <!-- @if($goods['goods_number'] != "" && $cfg['show_goodsnumber'])
                                    商品库存-->
                                    @if($goods['goods_number'] == 0)
                                        <strong>{{ $lang['goods_number'] }}</strong>
                                        <font color='red'>{{ $lang['stock_up'] }}</font>
                                    @else
                                        <strong>{{ $lang['goods_number'] }}</strong>
                                        {{ $goods['goods_number'] }} {{ $goods['measure_unit'] }}
                                    @endif
                                @endif
                            </dd>
                        </li>
                        <li class="clearfix">
                            <dd>
                                <!-- @if($goods['goods_brand'] != "" && $cfg['show_brand'])
                                    显示商品品牌-->
                                    <strong>{{ $lang['goods_brand'] }}</strong><a
                                        href="{{ $goods['goods_brand_url'] }}">{{ $goods['goods_brand'] }}</a>
                                @endif
                            </dd>
                            <dd class="ddR">
                                <!-- @if($cfg['show_goodsweight'])
                                    商品重量-->
                                    <strong>{{ $lang['goods_weight'] }}</strong>{{ $goods['goods_weight'] }}
                                @endif
                            </dd>
                        </li>
                        <li class="clearfix">
                            <dd>
                                <!-- @if($cfg['show_addtime'])
                                    上架时间-->
                                    <strong>{{ $lang['add_time'] }}</strong>{{ $goods['add_time'] }}
                                @endif
                            </dd>
                            <dd class="ddR">
                                <!--点击数-->
                                <strong>{{ $lang['goods_click_count'] }}：</strong>{{ $goods['click_count'] }}
                            </dd>
                        </li>
                        <li class="clearfix">
                            <dd class="ddL">
                                <!-- @if($cfg['show_marketprice'])
                                    市场价格-->
                                    <strong>{{ $lang['market_price'] }}</strong><font
                                        class="market">{{ $goods['market_price'] }}</font><br/>
                                @endif
                                <!--本店售价-->
                                <strong>{{ $lang['shop_price'] }}</strong><font class="shop"
                                                                                id="ECS_SHOPPRICE">{{ $goods['shop_price_formated'] }}</font><br/>
                                <!-- @foreach($rank_prices as $rank_price => $key)
                                    会员等级对应的价格-->
                                    <strong>{{ $rank_price['rank_name'] }}：</strong><font class="shop"
                                                                                          id="ECS_RANKPRICE_{{ $key }}">{{ $rank_price['price'] }}</font>
                                    <br/>
                                @endforeach
                            </dd>
                            <dd style="width:48%; padding-left:7px;">
                                <strong>{{ $lang['goods_rank'] }}</strong>
                                <img src="images/stars{{ $goods['comment_rank'] }}.gif"
                                     alt="comment rank {{ $goods['comment_rank'] }}"/>
                            </dd>
                        </li>

                        @if($volume_price_list )
                            <li class="padd">
                                <font class="f1">{{ $lang['volume_price'] }}：</font><br/>
                                <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#aad6ff">
                                    <tr>
                                        <td align="center" bgcolor="#FFFFFF"><strong>{{ $lang['number_to'] }}</strong>
                                        </td>
                                        <td align="center" bgcolor="#FFFFFF">
                                            <strong>{{ $lang['preferences_price'] }}</strong></td>
                                    </tr>
                                    @foreach($volume_price_list as $price_list => $price_key)
                                        <tr>
                                            <td align="center" bgcolor="#FFFFFF"
                                                class="shop">{{ $price_list['number'] }}</td>
                                            <td align="center" bgcolor="#FFFFFF"
                                                class="shop">{{ $price_list['format_price'] }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </li>
                        @endif

                        <!--@if($goods['is_promote'] && $goods['gmt_end_time'] )
                            促销-->
                            <script type="text/javascript" src="{{ asset('js/lefttime.js') }}"></script>
                            <li class="padd loop" style="margin-bottom:5px; border-bottom:1px dashed #ccc;">
                                <strong>{{ $lang['promote_price'] }}</strong><font
                                    class="shop">{{ $goods['promote_price'] }}</font><br/>
                                <strong>{{ $lang['residual_time'] }}</strong>
                                <font class="f4" id="leftTime">{{ $lang['please_waiting'] }}</font><br/>
                            </li>
                        @endif
                        <li class="clearfix">
                            <dd>
                                <strong>{{ $lang['amount'] }}：</strong><font id="ECS_GOODS_AMOUNT" class="shop"></font>
                            </dd>
                            <dd class="ddR">
                                <!-- @if($goods['give_integral'] > 0)
                                    购买此商品赠送积分-->
                                    <strong>{{ $lang['goods_give_integral'] }}</strong><font
                                        class="f4">{{ $goods['give_integral'] }} {{ $points_name }}</font>
                                @endif
                            </dd>
                        </li>
                        <!-- @if($goods['bonus_money'])
                            红包-->
                            <li class="padd loop" style="margin-bottom:5px; border-bottom:1px dashed #ccc;">
                                <strong>{{ $lang['goods_bonus'] }}</strong><font
                                    class="shop">{{ $goods['bonus_money'] }}</font><br/>
                            </li>
                        @endif
                        <li class="clearfix">
                            <dd>
                                <strong>{{ $lang['number'] }}：</strong>
                                <input name="number" type="text" id="number" value="1" size="4" onblur="changePrice()"
                                       style="border:1px solid #ccc; "/>
                            </dd>
                            <dd class="ddR">
                                <!-- @if($cfg['use_integral'])
                                    购买此商品可使用积分-->
                                    <strong>{{ $lang['goods_integral'] }}</strong><font
                                        class="f4">{{ $goods['integral'] }} {{ $points_name }}</font>
                                @endif
                            </dd>
                        </li>
                        <!-- @if($goods['is_shipping'])
                            为免运费商品则显示-->
                            <li style="height:30px;padding-top:4px;">
                                {{ $lang['goods_free_shipping'] }}<br/>
                            </li>
                        @endif
                        {* 开始循环所有可选属性 *}
                        @foreach($specification as $spec => $spec_key)
                            <li class="padd loop">
                                <strong>{{ $spec['name'] }}:</strong><br/>
                                {* 判断属性是复选还是单选 *}
                                @if($spec['attr_type'] == 1)
                                    @if($cfg['goodsattr_style'] == 1)
                                        @foreach($spec['values'] as $value => $key)
                                            <label for="spec_value_{{ $value['id'] }}">
                                                <input type="radio" name="spec_{{ $spec_key }}"
                                                       value="{{ $value['id'] }}" id="spec_value_{{ $value['id'] }}"
                                                       @if($key == 0)
                                                           checked
                                                       @endif onclick="changePrice()"/>
                                                {{ $value['label'] }} ['.($value['price'] > 0 ? '{{ $lang['plus'] }}' :
                                                'if($value['price'] < 0){{ $lang['minus'] }}').'
                                                {$value.format_price|abs}] </label><br/>
                                        @endforeach
                                        <input type="hidden" name="spec_list" value="{{ $key }}"/>
                                    @else
                                        <select name="spec_{{ $spec_key }}" onchange="changePrice()">
                                            @foreach($spec['values'] as $value => $key)
                                                <option label="{{ $value['label'] }}"
                                                        value="{{ $value['id'] }}">{{ $value['label'] }}
                                                    '.($value['price'] > 0 ? '{{ $lang['plus'] }}' : 'if($value['price']
                                                    < 0){{ $lang['minus'] }}').'@if($value['price'] != 0)
                                                        {{ $value['format_price'] }}
                                                    @endif</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="spec_list" value="{{ $key }}"/>
                                    @endif
                                @else
                                    @foreach($spec['values'] as $value => $key)
                                        <label for="spec_value_{{ $value['id'] }}">
                                            <input type="checkbox" name="spec_{{ $spec_key }}"
                                                   value="{{ $value['id'] }}" id="spec_value_{{ $value['id'] }}"
                                                   onclick="changePrice()"/>
                                            {{ $value['label'] }} ['.($value['price'] > 0 ? '{{ $lang['plus'] }}' :
                                            'if($value['price'] < 0){{ $lang['minus'] }}').' {$value.format_price|abs}]
                                        </label><br/>
                                    @endforeach
                                    <input type="hidden" name="spec_list" value="{{ $key }}"/>
                                @endif
                            </li>
                        @endforeach
                        {* 结束循环可选属性 *}
                        <li class="padd">
                            <a href="javascript:addToCart({{ $goods['goods_id'] }})"><img src="images/bnt_cat.gif"/></a>
                            <a href="javascript:collect({{ $goods['goods_id'] }})"><img
                                    src="images/bnt_colles.gif"/></a>
                            @if($affiliate['on'])
                                <a href="user.php?act=affiliate&goodsid={{ $goods['goods_id'] }}"><img
                                        src='images/bnt_recommend.gif'></a>
                            @endif
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="blank"></div>
        <!--商品详情end-->
        <!--商品描述，商品属性 START-->
        <div class="box">
            <div class="box_1">
                <h3 style="padding:0 5px;">
                    <div id="com_b" class="history clearfix">
                        <h2>{{ $lang['goods_brief'] }}</h2>
                        <h2 class="h2bg">{{ $lang['goods_attr'] }}</h2>
                        @if($package_goods_list)
                            <h2 class="h2bg" style="color:red;">{{ $lang['remark_package'] }}</h2>
                        @endif
                    </div>
                </h3>
                <div id="com_v" class="boxCenterList RelaArticle"></div>
                <div id="com_h">
                    <blockquote>
                        {{ $goods['goods_desc'] }}
                    </blockquote>

                    <blockquote>
                        <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#dddddd">
                            @foreach($properties as $property_group => $key)
                                <tr>
                                    <th colspan="2" bgcolor="#FFFFFF">{{{ $key }}}</th>
                                </tr>
                                @foreach($property_group as $property)
                                    <tr>
                                        <td bgcolor="#FFFFFF" align="left" width="30%" class="f1">
                                            [{{ $property['name'] }}]
                                        </td>
                                        <td bgcolor="#FFFFFF" align="left" width="70%">{{ $property['value'] }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </table>
                    </blockquote>

                    @if($package_goods_list)
                        <blockquote>
                            @foreach($package_goods_list as $package_goods)
                                <strong>{{ $package_goods['act_name'] }}</strong><br/>
                                <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#dddddd">
                                    <tr>
                                        <td bgcolor="#FFFFFF">
                                            @foreach($package_goods['goods_list'] as $goods_list)
                                                <a href="goods.php?id={{ $goods_list['goods_id'] }}"
                                                   target="_blank"><font
                                                        class="f1">{{ $goods_list['goods_name'] }}{{ $goods_list['goods_attr_str'] }}</font></a>
                                                &nbsp;&nbsp;X {{ $goods_list['goods_number'] }}<br/>
                                            @endforeach
                                        </td>
                                        <td bgcolor="#FFFFFF">
                                            <strong>{{ $lang['old_price'] }}</strong><font
                                                class="market">{{ $package_goods['subtotal'] }}</font><br/>
                                            <strong>{{ $lang['package_price'] }}</strong><font
                                                class="shop">{{ $package_goods['package_price'] }}</font><br/>
                                            <strong>{{ $lang['then_old_price'] }}</strong><font
                                                class="shop">{{ $package_goods['saving'] }}</font><br/>
                                        </td>
                                        <td bgcolor="#FFFFFF">
                                            <a href="javascript:addPackageToCart({{ $package_goods['act_id'] }})"
                                               style="background:transparent"><img src="images/bnt_buy_1.gif"
                                                                                   alt="{{ $lang['add_to_cart'] }}"/></a>
                                        </td>
                                    </tr>
                                </table>
                            @endforeach
                        </blockquote>
                    @endif

                </div>
            </div>
        </div>
        <script type="text/javascript">
            <!--
    reg("com");

        </script>
        <div class="blank"></div>
        <!--商品描述，商品属性 END-->
        <!-- TemplateBeginEditable name="右边可编辑区域" -->
        @include('web::library/goods_tags')
        @include('web::library/bought_goods')
        @include('web::library/bought_note_guide')
        @include('web::library/comments')
        <!-- TemplateEndEditable -->
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
<script type="text/javascript">
    var goods_id = {{ $goods_id }};
    var goodsattr_style = {{ $cfg['goodsattr_style'] ?? 1 }};
    var gmt_end_time = {{ $promote_end_time ?? 0 }};
    @foreach($lang['goods_js'] as $item => $key)
    var {{ $key }} = "{{ $item }}";
    @endforeach
    var goodsId = {{ $goods_id }};
    var now_time = {{ $now_time }};

    <!--  -->
    onload = function () {
        changePrice();
        fixpng();
        try {
            onload_leftTime();
        } catch (e) {
        }
    }

    /**
     * 点选可选属性或改变数量时修改商品价格的函数
     */
    function changePrice() {
        var attr = getSelectedAttributes(document.forms['ECS_FORMBUY']);
        var qty = document.forms['ECS_FORMBUY'].elements['number'].value;

        Ajax.call('goods.php', 'act=price&id=' + goodsId + '&attr=' + attr + '&number=' + qty, changePriceResponse, 'GET', 'JSON');
    }

    /**
     * 接收返回的信息
     */
    function changePriceResponse(res) {
        if (res.err_msg.length > 0) {
            alert(res.err_msg);
        } else {
            document.forms['ECS_FORMBUY'].elements['number'].value = res.qty;

            if (document.getElementById('ECS_GOODS_AMOUNT'))
                document.getElementById('ECS_GOODS_AMOUNT').innerHTML = res.result;
        }
    }

    <!--  -->
</script>
</html>
