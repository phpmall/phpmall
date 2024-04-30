<!DOCTYPE html>
<html lang="zh-Hans">
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $page_title ?? '' }}</title>
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/sentsin/layui@v2.6.8/dist/css/layui.css">
    <link rel="stylesheet" href="{{ skin('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/gh/sentsin/layui@v2.6.8/dist/layui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.min.js"></script>
    <!--[if lt IE 9]>
    <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div>
    <ul class="layui-nav" lay-filter="">
        <li class="layui-nav-item"><a href="">产品</a></li>
        <li class="layui-nav-item layui-this"><a href="">方案</a></li>
        <li class="layui-nav-item"><a href="">应用市场</a></li>
        <li class="layui-nav-item"><a href="javascript:;">服务与支持</a></li>
        <li class="layui-nav-item"><a href="">交流社区</a></li>
        <li class="layui-nav-item"><a href="">帮助文档</a></li>
    </ul>
</div>

@yield('content')

<div class="layui-container" style="text-align: center">footer</div>

<script src="{{ skin('js/app.js') }}"></script>
</body>
</html>
