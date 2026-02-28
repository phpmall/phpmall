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
    <h5><span>{{ $lang['activity_list'] }}</span></h5>
    <div class="blank"></div>
    @foreach($list as $val)
        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
            <tr>
                <th bgcolor="#ffffff">{{ $lang['label_act_name'] }}</th>
                <td colspan="3" bgcolor="#ffffff">{{ $val['act_name'] }}</td>
            </tr>
            <tr>
                <th bgcolor="#ffffff">{{ $lang['label_start_time'] }}</th>
                <td width="200" bgcolor="#ffffff">{{ $val['start_time'] }}</td>
                <th bgcolor="#ffffff">{{ $lang['label_max_amount'] }}</th>
                <td bgcolor="#ffffff">
                    @if($val['max_amount'] > 0)
                        {{ $val['max_amount'] }}
                    @else
                        {{ $lang['nolimit'] }}
                    @endif
                </td>
            </tr>
            <tr>
                <th bgcolor="#ffffff">{{ $lang['label_end_time'] }}</th>
                <td bgcolor="#ffffff">{{ $val['end_time'] }}</td>
                <th bgcolor="#ffffff">{{ $lang['label_min_amount'] }}</th>
                <td width="200" bgcolor="#ffffff">{{ $val['min_amount'] }}</td>
            </tr>
            <tr>
                <th bgcolor="#ffffff">{{ $lang['label_act_range'] }}</th>
                <td bgcolor="#ffffff">
                    {{ $val['act_range'] }}
                    @if($val['act_range'] != $lang['far_all'])
                        :<br/>
                        @foreach($val['act_range_ext'] as $ext)
                            <a href="{{ $val['program'] }}{{ $ext['id'] }}" taget="_blank" class="f6"><span
                                    class="f_user_info"><u>{{ $ext['name'] }}</u></span></a>
                        @endforeach
                    @endif
                </td>
                <th bgcolor="#ffffff">{{ $lang['label_user_rank'] }}</th>
                <td bgcolor="#ffffff">
                    @foreach($val['user_rank'] as $user)
                        {{ $user }}
                    @endforeach
                </td>
            </tr>
            <tr>
                <th bgcolor="#ffffff">{{ $lang['label_act_type'] }}</th>
                <td colspan="3" bgcolor="#ffffff">
                    {{ $val['act_type'] }}@if($val['act_type'] != $lang['fat_goods'])
                        {{ $val['act_type_ext'] }}
                    @endif
                </td>
            </tr>
            @if($val['gift'])
                <tr>
                    <td colspan="4" bgcolor="#ffffff">
                        @foreach($val['gift'] as $goods)
                            <table border="0" style="float:left;">
                                <tr>
                                    <td align="center"><a href="goods.php?id={{ $goods['id'] }}"><img
                                                src="{{ $goods['thumb'] }}"
                                                alt="{{ $goods['name'] }}"/></a></td>
                                </tr>
                                <tr>
                                    <td align="center"><a href="goods.php?id={{ $goods['id'] }}"
                                                          class="f6">{{ $goods['name'] }}</a></td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        @if($goods['price'] > 0)
                                            {{ $goods['price'] }}{{ $lang['unit_yuan'] }}
                                        @else
                                            {{ $lang['for_free'] }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </td>
                </tr>
            @endif
        </table>
        <div class="blank5"></div>
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
