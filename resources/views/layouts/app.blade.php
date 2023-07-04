<!DOCTYPE html>
<html lang="zh-Hans">
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $page_title ?? 'PHPMall' }} - 全场景新零售电商平台系统软件</title>
<meta name="generator" content="PHPMall.Net"/>
<link rel="stylesheet" href="{{ asset('assets/layui/dist/css/red.css') }}">
<link rel="stylesheet" href="{{ asset('static/portal/css/app.css') }}">
@yield('style')
<script src="{{ asset('assets/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vue/dist/vue.min.js') }}"></script>
<script src="{{ asset('assets/layui/dist/layui.js') }}"></script>
</head>
<body>
<div class="app-header layui-bg-gray">
    <div class="layui-container">
        <div class="layui-row">
            <ul class="layui-nav layui-bg-gray">
                <li class="layui-nav-item">
                    <a href="{{ url('/') }}">
                        首页
                        <!--<img src="{{ asset('static/common/img/logo.png') }}" alt="PHPMall">-->
                    </a>
                </li>
                <li class="layui-nav-item">
                    <a href="/admin">运营中心</a>
                </li>
                <li class="layui-nav-item">
                    <a href="/supplier">供应商</a>
                </li>
                <li class="layui-nav-item">
                    <a href="/seller">卖家</a>
                </li>
                <li class="layui-nav-item">
                    <a href="/home">买家</a>
                </li>
                <li class="layui-nav-item">
                    <a href="/mobile">微商城</a>
                </li>

                @if (Route::has('login'))
                    @auth
                        <li class="layui-nav-item" style="float: right;">
                            <a href="{{ route('user.index') }}">控制台</a>
                        </li>
                    @else
                        @if (Route::has('register'))
                            <li class="layui-nav-item" style="float: right;">
                                <a href="{{ route('register') }}" style="color: white;">免费注册</a>
                            </li>
                        @endif
                        <li class="layui-nav-item" style="float: right;">
                            <a href="{{ route('login') }}">登录</a>
                        </li>
                    @endauth
                @endif
            </ul>
        </div>
    </div>
</div>

<div class="layui-container">
@yield('content')
</div>

<script src="{{ asset('static/common/js/jquery.SuperSlide-2.1.3.js') }}"></script>
<script src="{{ asset('static/common/js/jquery.Validform-3.0.0.min.js') }}"></script>
<script src="{{ asset('static/common/js/common.js') }}"></script>
<script src="{{ asset('static/portal/js/app.js') }}"></script>
@yield('script')
</body>
</html>
