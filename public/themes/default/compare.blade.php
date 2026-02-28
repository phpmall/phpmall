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

    <script type="text/javascript" src="{{ asset('js/transport.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
    <script type="text/javascript">
        function remove(id, url) {
            if (document.getCookie("compareItems") != null) {
                var obj = document.getCookie("compareItems").parseJSON();
                delete obj[id];
                var date = new Date();
                date.setTime(date.getTime() + 99999999);
                document.setCookie("compareItems", obj.toJSONString());
            }
        }

        var compare_no_goods = "{{ $lang['compare_no_goods'] }}";
        var btn_buy = "{{ $lang['btn_buy'] }}";
        var is_cancel = "{{ $lang['is_cancel'] }}";
        var select_spe = "{{ $lang['select_spe'] }}";
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
<div class="block">
    <h5><span>{{ $lang['goods_compare'] }}</span></h5>
    <div class="blank"></div>
    <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
        <tr>
            <th width="120" align="center" bgcolor="#ffffff">{{ $lang['goods_name'] }}</th>
            @foreach($goods_list as $goods)
                <td align="center" bgcolor="#ffffff"
                    @if($loop->goods_list.total > 3)width="200"@else@endif> {{ $goods['goods_name'] }}</td>
            @endforeach
        </tr>
        <tr>
            <th align="left" bgcolor="#ffffff"></th>
            @foreach($goods_list as $goods)
                <td align="center" bgcolor="#ffffff" style="padding:5px;"><a href="{{ $goods['url'] }}"><img
                            src="{{ $goods['goods_thumb'] }}" alt="{{ $goods['goods_name'] }}" class="ent_img"/></a>
                </td>
            @endforeach
        </tr>
        @if($loop->goods_list.total > 2)
            <tr>
                <td bgcolor="#ffffff">&nbsp;</td>
                @foreach($goods_list as $goods)
                    <th bgcolor="#ffffff">
                        <a href="compare.php?{{ $goods['ids'] }}"
                           onClick="return remove({{ $goods['goods_id'] }});">{{ $lang['compare_remove'] }}</a></th>
                @endforeach
            </tr>
        @endif
        <tr>
            <th align="left" bgcolor="#ffffff">&nbsp;&nbsp;{{ $lang['brand'] }}</th>
            @foreach($goods_list as $goods)
                <td bgcolor="#ffffff">&nbsp;&nbsp;{{ $goods['brand_name'] }}</td>
            @endforeach
        </tr>
        <tr>
            <th align="left" bgcolor="#ffffff">&nbsp;&nbsp;{{ $lang['shop_price'] }}</th>
            @foreach($goods_list as $goods)
                <td bgcolor="#ffffff">&nbsp;&nbsp;{{ $goods['rank_price'] }}</td>
            @endforeach
        </tr>
        <tr>
            <th align="left" bgcolor="#ffffff">&nbsp;&nbsp;{{ $lang['goods_weight'] }}</th>
            @foreach($goods_list as $goods)
                <td bgcolor="#ffffff">&nbsp;&nbsp;{{ $goods['goods_weight'] }}</td>
            @endforeach
        </tr>
        @foreach($attribute as $key => $val)
            <tr>
                <th align="left" bgcolor="#ffffff">&nbsp;&nbsp;{{ $val }}</th>
                @foreach($goods_list as $goods)
                    <td bgcolor="#ffffff">&nbsp;&nbsp;
                        @foreach($goods['properties'] as $property => $k)
                            @if($k == $key)
                                {{ $property['value'] }}
                            @endif
                        @endforeach                </td>
                @endforeach
            </tr>
        @endforeach
        <tr>
            <td align="left" bgcolor="#ffffff">&nbsp;&nbsp;<strong>{{ $lang['goods_rank'] }}</strong></td>
            @foreach($goods_list as $goods)
                <td bgcolor="#ffffff">&nbsp;&nbsp;<span class="goods-price"><img
                            src="images/stars{{ $goods['comment_rank'] }}.gif" width="64" height="12"
                            alt="comment rank {{ $goods['comment_rank'] }}"/></span><br/></td>
            @endforeach
        </tr>
        <tr>
            <td align="left" bgcolor="#ffffff">&nbsp;&nbsp;<strong>{{ $lang['brief'] }}</strong></td>
            @foreach($goods_list as $goods)
                <td bgcolor="#ffffff">&nbsp;&nbsp;<a href="{{ $goods['url'] }}"
                                                     target="_blank"> {{ $goods['goods_brief'] }}</a></td>
            @endforeach
        </tr>
        <tr>
            <td bgcolor="#ffffff">&nbsp;</td>
            @foreach($goods_list as $goods)
                <td align='center' bgcolor="#ffffff"><a href="javascript:collect({{ $goods['goods_id'] }});"><img
                            src="images/bnt_colles.gif" alt="{{ $lang['collect'] }}" style="margin:2px auto;"/></a>
                    <a href="javascript:addToCart({{ $goods['goods_id'] }})"><img src="images/bnt_cat.gif"
                                                                                  alt="{{ $lang['add_to_cart'] }}"
                                                                                  style="margin:2px auto;"/></a></td>
            @endforeach
        </tr>
    </table>
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
