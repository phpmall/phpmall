<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="Keywords" content="{{ $keywords }}"/>
    <meta name="Description" content="{{ $description }}"/>
    <title>{{ $page_title }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link href="{{ $ecs_css_path }}" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
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
    <h5><span>{{ $lang['package_list'] }}</span></h5>
    <div class="blank"></div>
    @foreach($list as $val)
        <a name="{{ $val['act_id'] }}"></a>
        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
            <tr>
                <th bgcolor="#ffffff">{{ $lang['package_name'] }}:</th>
                <td colspan="3" bgcolor="#ffffff">{{ $val['act_name'] }}</td>
            </tr>
            <tr>
                <th bgcolor="#ffffff">{{ $lang['start_time'] }}:</th>
                <td width="200" bgcolor="#ffffff">{{ $val['start_time'] }}</td>
                <th bgcolor="#ffffff">{{ $lang['orgtotal'] }}:</th>
                <td bgcolor="#ffffff">{{ $val['subtotal'] }}</td>
            </tr>
            <tr>
                <th bgcolor="#ffffff">{{ $lang['end_time'] }}:</th>
                <td bgcolor="#ffffff">{{ $val['end_time'] }}</td>
                <th bgcolor="#ffffff">{{ $lang['package_price'] }}:</th>
                <td bgcolor="#ffffff">{{ $val['package_price'] }}</td>
            </tr>
            <tr>
                <th bgcolor="#ffffff">{{ $lang['heart_buy'] }}:</th>
                <td bgcolor="#ffffff"><a href="javascript:addPackageToCart({{ $val['act_id'] }})"
                                         style="background:transparent"><img src="images/bnt_buy_1.gif"
                                                                             alt="{{ $lang['add_to_cart'] }}"/></a></td>
                <th bgcolor="#ffffff">{{ $lang['saving'] }}:</th>
                <td width="200" bgcolor="#ffffff">{{ $val['saving'] }}</td>
            </tr>
            <tr>
                <th bgcolor="#ffffff">{{ $lang['package_goods'] }}:</th>
                <td colspan="3" bgcolor="#ffffff">
                    @foreach($val['goods_list'] as $goods)
                        <a href="goods.php?id={{ $goods['goods_id'] }}" target="_blank" class="f6"><span
                                class="f_user_info"><u>{{ $goods['goods_name'] }}</u></span></a> &nbsp;X
                        &nbsp;{{ $goods['goods_number'] }}<br/>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th bgcolor="#ffffff">{{ $lang['desc'] }}:</th>
                <td colspan="3" bgcolor="#ffffff">{{ $val['act_desc'] }}</td>
            </tr>
        </table>
        <div class="blank5"></div><br/>
    @endforeach
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
