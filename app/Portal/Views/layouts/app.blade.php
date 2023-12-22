<!DOCTYPE html>
<html lang="zh-Hans">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $page_title ?? '' }}</title>
    @vite(['app/Portal/Assets/ts/app.ts'])
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
