<!DOCTYPE HTML>
<html lang="zh-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
    <title>用户中心</title>
    <link rel="stylesheet" href="{{ asset('assets/layui/dist/css/red.css') }}">
    <link rel="stylesheet" href="{{ asset('static/common/css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('static/user/css/app.css') }}">
    <script src="{{ asset('assets/layui/dist/layui.js') }}"></script>
    <script src="{{ asset('assets/vue/dist/vue.min.js') }}"></script>
    <script src="{{ asset('static/common/js/common.js') }}"></script>
</head>
<body>
<div id="app">
    <div class="header">
        header
    </div>
    <div class="main">
        @yield('content')
    </div>
    <div class="footer">
        footer
    </div>
</div>
<script src="{{ asset('static/user/js/app.js') }}"></script>
</body>
</html>
