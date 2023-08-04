<!DOCTYPE HTML>
<html lang="zh-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
    <title>运营管理</title>
    <link rel="stylesheet" href="{{ asset('static/layui/dist/css/red.css') }}">
    <link rel="stylesheet" href="{{ asset('static/common/css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('static/manager/css/app.css') }}">
    <script src="{{ asset('static/layui/dist/layui.js') }}"></script>
    <script src="{{ asset('static/vue/dist/vue.min.js') }}"></script>
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
<script src="{{ asset('static/manager/js/app.js') }}"></script>
</body>
</html>
