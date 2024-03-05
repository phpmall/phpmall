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
<body>
<div id="userBox">
    @auth
        <div>
            <a href="{{ route('user') }}">会员中心</a> |
            <a href="{{ route('logout') }}">注销</a>
        </div>
    @else
        <div>
            <a href="{{ route('signup') }}">免费注册</a> |
            <a href="{{ route('login') }}">登录</a>
        </div>
    @endauth
</div>

@yield('content')

<script>
    var vm = new Vue({
        el: '#userBox',
        data: {
            isLogin: false,
            a: 1
        },
        created: function () {
            // `this` 指向 vm 实例
            console.log('a is: ' + this.a)
        }
    })
</script>
</body>
</html>
