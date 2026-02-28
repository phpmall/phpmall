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
                <div class="blank5"></div>
                <!--相册 END-->
                <!-- TemplateBeginEditable name="商品相册下广告（宽230px）" -->
                <!-- TemplateEndEditable -->
            </div>
            <!--商品图片和相册 end-->

            <div class="textInfo">
                <form action="exchange.php?act=buy" method="post" name="ECS_FORMBUY" id="ECS_FORMBUY">
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
                        <!-- @if($cfg['show_goodssn'])
                            显示商品货号-->
                            <li class="clearfix">
                                <dd>
                                    <strong>{{ $lang['goods_sn'] }}</strong>{{ $goods['goods_sn'] }}
                                </dd>
                            </li>
                        @endif
                        <!-- @if($goods['goods_brand'] != "" && $cfg['show_brand'])
                            显示商品品牌-->
                            <li class="clearfix">
                                <dd>
                                    <strong>{{ $lang['goods_brand'] }}</strong><a
                                        href="{{ $goods['goods_brand_url'] }}">{{ $goods['goods_brand'] }}</a>
                                </dd>
                            </li>
                        @endif
                        <!-- @if($cfg['show_goodsweight'])
                            商品重量-->
                            <li class="clearfix">
                                <dd>
                                    <strong>{{ $lang['goods_weight'] }}</strong>{{ $goods['goods_weight'] }}
                                </dd>
                            </li>
                        @endif
                        <li class="clearfix">
                            <dd>
                                <strong>{{ $lang['exchange_integral'] }}</strong><font
                                    class="shop">{{ $goods['exchange_integral'] }}</font><br/>
                            </dd>
                        </li>
                        {* 开始循环所有可选属性 *}
                        <!-- @foreach($specification as $spec => $spec_key)
                            循环规格开始 -->
                            <li class="padd loop">
                                <strong>{{ $spec['name'] }}:</strong><br/>
                                <!-- @if($cfg['goodsattr_style'] == 1)
                                    规格显示方式：单选按钮 -->
                                    @foreach($spec['values'] as $value => $key)
                                        <label for="spec_value_{{ $value['id'] }}">
                                            <input type="radio" name="spec_{{ $spec_key }}" value="{{ $value['id'] }}"
                                                   id="spec_value_{{ $value['id'] }}" @if($key == 0)checked@endif />
                                            {{ $value['label'] }} </label>
                                    @endforeach
                                    <!--

                                @else
                                    规格显示方式：下拉列表 -->
                                    <select name="spec_{{ $spec_key }}" style="border:1px solid #ccc;">
                                        @foreach($spec['values'] as $value => $key)
                                            <option label="{{ $value['label'] }}"
                                                    value="{{ $value['id'] }}">{{ $value['label'] }} </option>
                                        @endforeach
                                    </select>
                                    <!--

                                @endif 规格显示方式 -->
                            </li>
                            <!--

                        @endforeach 循环规格结束 -->
                        {* 结束循环可选属性 *}
                        <li class="padd">
                            <input type="hidden" name="goods_id" value="{{ $goods['goods_id'] }}"/>
                            <input type="submit" value="{{ $lang['exchange_goods'] }}" class="bnt_blue_1"/>
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
                </div>
            </div>
        </div>

        <script type="text/javascript">
            reg("com");
        </script>

        <div class="blank"></div>
        <!--商品描述，商品属性 END-->

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
    <!--  -->
    onload = function () {
        fixpng();
    }
    <!--  -->
</script>
</html>
