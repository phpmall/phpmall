<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{{ $lang['cp_home'] }}@if(isset($ur_here))
            - {{ $ur_here }}
        @endif</title>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="{{ asset('static/admin/styles/general.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('static/admin/styles/main.css') }}" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="{{ asset('js/transport.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/admin/js/common.js') }}"></script>

    <script type="text/javascript">
        // 这里把JS用到的所有语言都赋值到这里
        @foreach($lang['js_languages'] as $key => $item)
        var {{ $key }} = "{{ $item }}";
        @endforeach
    </script>
</head>
<body>

<h1>
    @if(isset($action_link))
        <span class="action-span"><a href="{{ $action_link['href'] }}">{{ $action_link['text'] }}</a></span>
    @endif
    @if(isset($action_link2))
        <span class="action-span"><a
                href="{{ $action_link2['href'] }}">{{ $action_link2['text'] }}</a>&nbsp;&nbsp;</span>
    @endif
    <span class="action-span1"><a href="index.php?act=main">{{ $lang['cp_home'] }}</a>&nbsp;</span>
        <span id="search_id" class="action-span1">
            @if(isset($ur_here))
            - {{ $ur_here }}
            @endif
        </span>
    <div style="clear:both"></div>
</h1>
