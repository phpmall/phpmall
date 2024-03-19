<!DOCTYPE html>
<html lang="zh-Hans">
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{ $page_title ?? '' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="{{ asset('static/layui@2.9.3/css/layui.css') }}">
    <script type="text/javascript" src="{{ asset('static/layui@2.9.3/layui.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/vue@2.7.16/vue.min.js') }}"></script>
    @vite(['app/Portal/Assets/js/app.js'])
</head>
<body class="layout-auth">
<div class="header">
    header
</div>
<div class="content">
    @yield('content')
</div>

<div class="footer">
    footer
</div>
</body>
</html>
