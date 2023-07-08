<!DOCTYPE html>
<html lang="zh-Hans">
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $page_title ?? '运营中心' }} - 新零售电商平台系统软件</title>
<meta name="generator" content="PHPMall.Net"/>
<link rel="stylesheet" href="{{ asset('assets/layui/dist/css/blue.css') }}">
<link rel="stylesheet" href="{{ asset('static/supplier/css/app.css') }}">
@yield('style')
<script src="{{ asset('assets/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vue/dist/vue.min.js') }}"></script>
<script src="{{ asset('assets/layui/dist/layui.js') }}"></script>
</head>

<body>
@yield('content')
<script src="{{ asset('static/common/js/common.js') }}"></script>
<script src="{{ asset('static/supplier/js/app.js') }}"></script>
@yield('script')
</body>
</html>
