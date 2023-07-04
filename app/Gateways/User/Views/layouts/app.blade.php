<!DOCTYPE html>
<html lang="zh-Hans">
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $page_title ?? '买家中心' }} - 全场景新零售电商平台系统软件</title>
<meta name="generator" content="PHPMall.Net"/>
<link rel="stylesheet" href="{{ asset('assets/layui/dist/css/red.css') }}">
<link rel="stylesheet" href="{{ asset('static/user/css/app.css') }}">
@yield('style')
<script src="{{ asset('assets/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vue/dist/vue.min.js') }}"></script>
<script src="{{ asset('assets/layui/dist/layui.js') }}"></script>
</head>

<body>
<div>
    <div class="layui-header">
        header
    </div>
    <div class="side">
        <ul class="layui-nav layui-nav-tree" lay-filter="test">
            <li class="layui-nav-item layui-nav-itemed">
                <a class="" href="javascript:;">我的订单</a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;">menu 1</a></dd>
                    <dd><a href="javascript:;">menu 2</a></dd>
                    <dd><a href="javascript:;">menu 3</a></dd>
                    <dd><a href="javascript:;">the links</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;">我的资产</a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;">list 1</a></dd>
                    <dd><a href="javascript:;">list 2</a></dd>
                    <dd><a href="javascript:;">超链接</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="javascript:;">安全设置</a></li>
            <li class="layui-nav-item"><a href="javascript:;">其他设置</a></li>
        </ul>
    </div>
    <div class="main">
        @yield('content')
    </div>
</div>

<script src="{{ asset('static/common/js/common.js') }}"></script>
<script src="{{ asset('static/user/js/app.js') }}"></script>
@yield('script')
</body>
</html>
