<!DOCTYPE html>
<html lang="zh-Hans">
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $page_title ?? '认证中心' }} - 全场景新零售电商平台系统软件</title>
<meta name="generator" content="PHPMall.Net"/>
<link rel="stylesheet" href="{{ asset('assets/layui/dist/css/red.css') }}">
<link rel="stylesheet" href="{{ asset('static/passport/css/app.css') }}">
@yield('style')
<script src="{{ asset('assets/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vue/dist/vue.min.js') }}"></script>
<script src="{{ asset('assets/layui/dist/layui.js') }}"></script>
</head>

<body>
<div class="layui-container">
    <div class="header">
        <a href="{{ url('/') }}">
            <img class="logo" src="{{ asset('static/common/img/logo.png') }}" alt="{{ config('app.name') }}"/>
        </a>
    </div>

    <div class="layui-row">
        <div class="content">
            <div class="wrapper">
                <div class="wrapper-left">
                    <img src="{{ asset('static/passport/img/login.png') }}" alt="{{ config('app.name') }}"/>
                </div>
                <div class="wrapper-right">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('static/common/js/common.js') }}"></script>
<script src="{{ asset('static/passport/js/app.js') }}"></script>
@yield('script')
</body>
</html>
