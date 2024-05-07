<!DOCTYPE html>
<html lang="zh-Hans">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{ $page_title ?? '' }}</title>
    <meta name="renderer" content="webkit">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="{{ asset('assets/layui@2.6.8/css/layui.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/css/app.css') }}">
    <script type="text/javascript" src="{{ asset('assets/layui@2.6.8/layui.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vue@2.6.14/vue.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('themes/default/js/app.js') }}" defer></script>
</head>
<body>
<div class="layui-bg-blue">
    <div class="layui-container">
        <ul class="layui-nav layui-bg-blue" lay-bar="disabled">
            <li class="layui-nav-item"><a href="">产品</a></li>
            <li class="layui-nav-item"><a href="">大数据</a></li>
            <li class="layui-nav-item"><a href="javascript:;">解决方案</a>
                <dl class="layui-nav-child">
                    <dd>
                        <a href="">移动模块</a>
                    </dd>
                    <dd>
                        <a href="">后台模版</a>
                    </dd>
                    <dd>
                        <a href="">电商平台</a>
                    </dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="">社区</a></li>
        </ul>
        <ul class="layui-nav layui-bg-blue layui-layout-right" lay-bar="disabled">
            @guest
                @if (Route::has('user.register'))
                    <li class="layui-nav-item"><a href="{{ route('user.register') }}">免费注册</a></li>
                @endif
                @if (Route::has('user.login'))
                    <li class="layui-nav-item"><a href="{{ route('user.login') }}">登录</a></li>
                @endif
            @else
                <li class="layui-nav-item layui-hide layui-show-md-inline-block">
                    <a href="{{ route('user.index') }}">
                        <img src="#" class="layui-nav-img"> {{ Auth::user()->name }}
                    </a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="{{ route('user.profile') }}">个人资料</a>
                        </dd>
                        <dd>
                            <a href="{{ route('user.setting') }}">个人设置</a>
                        </dd>
                        <dd>
                            <a href="{{ route('user.logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                注销账号</a>
                            <form id="logout-form" action="{{ route('user.logout') }}" method="POST">
                                @csrf
                            </form>
                        </dd>
                    </dl>
                </li>
            @endguest
        </ul>
    </div>
</div>

<div class="layui-container">
    @yield('content')
</div>
</body>
</html>
