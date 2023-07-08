@extends('admin::layouts.app')

@section('content')
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <div class="layui-logo layui-hide-xs layui-bg-black">layout demo</div>
            <!-- 头部区域（可配合layui 已有的水平导航） -->
            <ul class="layui-nav layui-layout-left">
                <!-- 移动端显示 -->
                <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-header-event="menuLeft">
                    <i class="layui-icon layui-icon-spread-left"></i>
                </li>
                <li class="layui-nav-item layui-hide-xs"><a href="javascript:;">nav 1</a></li>
                <li class="layui-nav-item layui-hide-xs"><a href="javascript:;">nav 2</a></li>
                <li class="layui-nav-item layui-hide-xs"><a href="javascript:;">nav 3</a></li>
                <li class="layui-nav-item">
                    <a href="javascript:;">nav groups</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">menu 11</a></dd>
                        <dd><a href="javascript:;">menu 22</a></dd>
                        <dd><a href="javascript:;">menu 33</a></dd>
                    </dl>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right">
                <li class="layui-nav-item layui-hide layui-show-sm-inline-block">
                    <a href="javascript:;">
                        <img src="//unpkg.com/outeres@0.0.10/img/layui/icon-v2.png" class="layui-nav-img">
                        tester
                    </a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">Your Profile</a></dd>
                        <dd><a href="javascript:;">Settings</a></dd>
                        <dd><a href="javascript:;">Sign out</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item" lay-header-event="menuRight" lay-unselect>
                    <a href="javascript:;">
                        <i class="layui-icon layui-icon-more-vertical"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="layui-side layui-bg-black">
            <div class="layui-side-scroll">
                <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
                <ul class="layui-nav layui-nav-tree" lay-filter="test">
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="" href="javascript:;">商家管理</a>
                        <dl class="layui-nav-child">
                            <dd><a href="javascript:;">menu 1</a></dd>
                            <dd><a href="javascript:;">menu 2</a></dd>
                            <dd><a href="javascript:;">menu 3</a></dd>
                            <dd><a href="javascript:;">the links</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="javascript:;">店铺管理</a>
                        <dl class="layui-nav-child">
                            <dd><a href="javascript:;">list 1</a></dd>
                            <dd><a href="javascript:;">list 2</a></dd>
                            <dd><a href="javascript:;">超链接</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item"><a href="javascript:;">商品管理</a></li>
                    <li class="layui-nav-item"><a href="javascript:;">订单管理</a></li>
                </ul>
            </div>
        </div>
        <div class="layui-body">
            <iframe src="{{ route('admin.dashboard') }}" width="100%" height="100%" frameborder="0"></iframe>
        </div>
    </div>
@endsection
