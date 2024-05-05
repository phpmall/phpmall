<!DOCTYPE html>
<html lang="zh-Hans">
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $page_title ?? '' }}</title>
    <link rel="stylesheet" href="{{ asset('static/layui@2.9.7/css/layui.css') }}">
    <link rel="stylesheet" href="{{ asset('static/portal@1.0.0/css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('static/portal@1.0.0/css/app.css') }}">
    <script type="text/javascript" src="{{ asset('static/layui@2.9.7/layui.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/vue@2.7.16/dist/vue.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/portal@1.0.0/js/common.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/portal@1.0.0/js/app.js') }}"></script>
</head>
<body>

<div id="userBox">
    <template v-if="isLogin">
        <a href="/home">会员中心</a> |
        <a href="#logout">注销</a>
    </template>
    <template else>
        <a href="/passport/#/register">免费注册</a> |
        <a href="/passport/#/login">登录</a>
    </template>
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
