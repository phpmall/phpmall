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
    <script type="text/javascript" src="{{ asset('js/lefttime.js') }}"></script>
    <script type="text/javascript">
        @foreach($lang['js_languages'] as $item => $key)
        var {{ $key }} = "{{ $item }}";
        @endforeach
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
        @include('web::library/categorys')
        @include('web::library/goods_related')
        @include('web::library/goods_fittings')
        @include('web::library/goods_article')
        @include('web::library/goods_attrlinked')
        <!-- TemplateEndEditable -->
        <div class="box">
            <div class="box_1">
                <h3><span>{{ $lang['your_choice'] }}</span></h3>
                <div class="boxCenterList clearfix">
                    <ul>
                        @foreach($picks as $pick )
                            <li style="word-break:break-all;"><a href="{{ $pick['url'] }}">{{ $pick['name'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="blank5"></div>
        <!-- TemplateBeginEditable name="左边广告区域（宽200px）" -->
        <!-- TemplateEndEditable -->
        <!--AD end-->
    </div>
    <!--left end-->
    <!--right start-->
    <div class="AreaR">
        <div class="box">
            <div class="box_1">
                <h3><span>{{ $lang['pick_out'] }}</span></h3>
                <div class="boxCenterList">
                    <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                        @foreach($condition as $caption)
                            <tr>
                                <td bgcolor="#e5ecfb" style="border-bottom: 1px solid #DADADA">
                                    <img src="images/note.gif" alt="no alt"/>&nbsp;&nbsp;<strong
                                        class="f_red">{{ $caption['name'] }}</strong></td>
                            </tr>
                            @foreach($caption['cat'] as $cat)
                                <tr>
                                    <td bgcolor="#ffffff">&nbsp;&nbsp;<strong>{{ $cat['cat_name'] }}</strong></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#ffffff">&nbsp;&nbsp;
                                        @foreach($cat['list'] as $list)
                                            &nbsp;&nbsp;<a href="{{ $list['url'] }}" class="f6">{{ $list['name'] }}</a>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="blank5"></div>
        <div class="box">
            <div class="box_1">
                <h3><span>{{ $lang['search_result'] }} ({{ $count }})</span></h3>
                <div class="boxCenterList clearfix">
                    @foreach($pickout_goods as $goods)
                        <div class="goodsItem">
                            <a href="{{ $goods['url'] }}"><img src="{{ $goods['thumb'] }}" alt="{{ $goods['name'] }}"
                                                               class="goodsimg"/></a><br/>
                            <p><a href="{{ $goods['url'] }}" title="{{ $goods['name'] }}">{{ $goods['short_name'] }}</a>
                            </p>
                            <a href="javascript:addToCart({{ $goods['id'] }})"><img src="images/bnt_buy.gif"/></a> <a
                                href="javascript:collect({{ $goods['id'] }})"><img src="images/bnt_coll.gif"/></a>
                            <font class="f1">
                                @if($goods['promote_price'] != "")
                                    {{ $lang['promote_price'] }}{{ $goods['promote_price'] }}
                                @else
                                    {{ $lang['shop_price'] }}{{ $goods['shop_price'] }}
                                @endif
                            </font>
                        </div>
                    @endforeach
                    @if($count > 5)
                        <div class="more f_r" style="clear:both;"><a href="{{ $url }}"><img src="images/more.gif"/></a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <!--right end-->
</div>
<div class="blank"></div>
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
    var gmt_end_time = "{{ $group_buy['gmt_end_date'] ?? 0 }}";
    @foreach($lang['goods_js'] as $item => $key)
    var {{ $key }} = "{{ $item }}";
    @endforeach

    var btn_buy = "{{ $lang['btn_buy'] }}";
    var is_cancel = "{{ $lang['is_cancel'] }}";
    var select_spe = "{{ $lang['select_spe'] }}";
    <!--  -->

    onload = function () {
        try {
            onload_leftTime();
        } catch (e) {
        }
    }
</script>
</html>
