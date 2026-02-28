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
    <style type="text/css">
        p a {
            color: #006acd;
            text-decoration: underline;
        }
    </style>
</head>

<body>
@include('web::library/page_header')

<div class="blank"></div>
<div class="block">
    <div class="box">
        <div class="box_1">
            <h3><span>{{ $lang['system_info'] }}</span></h3>
            <div class="boxCenterList RelaArticle" align="center">
                <div style="margin:20px auto;">
                    <p style="font-size: 14px; font-weight:bold; color: red;">{{ $message }}</p>
                    <div class="blank"></div>
                    @if($virtual_card)
                        <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                            @foreach($virtual_card as $vgoods)
                                @foreach($vgoods['info'] as $card)
                                    <tr>
                                        <td bgcolor="#FFFFFF">{{ $vgoods['goods_name'] }}</td>
                                        <td bgcolor="#FFFFFF">
                                            @if($card['card_sn'])
                                                <strong>{{ $lang['card_sn'] }}:</strong>{{ $card['card_sn'] }}
                                            @endif
                                            @if($card['card_password'])
                                                <strong>{{ $lang['card_password'] }}
                                                    :</strong>{{ $card['card_password'] }}
                                            @endif
                                            @if($card['end_date'])
                                                <strong>{{ $lang['end_date'] }}:</strong>{{ $card['end_date'] }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </table>
                    @endif
                    <a href="{{ $shop_url }}">{{ $lang['back_home'] }}</a>
                </div>
            </div>
        </div>
    </div>
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
