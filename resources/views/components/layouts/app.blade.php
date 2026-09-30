<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', '商城系统') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased min-h-screen flex flex-col">
    <!-- 顶部导航 -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
            <div class="flex items-center gap-6">
                <a href="/" class="flex items-center gap-2 text-xl font-bold text-gray-900">
                    <span class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center text-white font-extrabold text-base">M</span>
                    <span>{{ config('app.name', 'PHPMall') }}</span>
                </a>
                <nav class="hidden md:flex gap-4">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-600 hover:text-gray-900' }}">
                        仪表盘
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                        <a href="{{ route('profile.edit') }}" class="text-sm text-gray-500 hover:text-gray-700">设置</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-700">退出登录</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">登录</a>
                    <a href="{{ route('register') }}" class="text-sm text-red-600 font-medium hover:text-red-700">注册</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- 主体区域 -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>
</body>
</html>
