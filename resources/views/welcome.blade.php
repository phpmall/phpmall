<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'PHPMall') }} - 首页</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased min-h-screen flex flex-col justify-between">
    <header class="max-w-7xl w-full mx-auto px-6 py-6 flex justify-between items-center">
        <div class="flex items-center gap-2 text-xl font-bold text-gray-900">
            <span class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center text-white font-extrabold text-base">M</span>
            <span>{{ config('app.name', 'PHPMall') }}</span>
        </div>
        <nav class="flex items-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors">
                    进入仪表盘
                </a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                    登录
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors">
                    注册
                </a>
            @endauth
        </nav>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-16 text-center">
        <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-gray-900 mb-6">
            现代化全端电商商城系统
        </h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-10">
            采用多端架构设计，覆盖 PC 商城、Uni-App 移动多端与多角色管理后台，基于 Laravel 领域驱动设计，支持高并发与高扩展性。
        </p>
        <div class="flex justify-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="px-6 py-3 text-base font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm">
                    进入控制台
                </a>
            @else
                <a href="{{ route('register') }}" class="px-6 py-3 text-base font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm">
                    立即开启体验
                </a>
                <a href="{{ route('login') }}" class="px-6 py-3 text-base font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg shadow-sm">
                    登录账号
                </a>
            @endauth
        </div>
    </main>

    <footer class="max-w-7xl w-full mx-auto px-6 py-8 text-center text-sm text-gray-500 border-t border-gray-200">
        &copy; {{ date('Y') }} {{ config('app.name', 'PHPMall') }}. All rights reserved.
    </footer>
</body>
</html>
