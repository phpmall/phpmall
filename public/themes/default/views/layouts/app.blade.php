<!DOCTYPE html>
<html lang="zh-Hans">
<head>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $pageTitle ?? '' }}</title>
<meta name="keywords" content="{{ $pageKeywords ?? '' }}" />
<meta name="description" content="{{ $pageDescription ?? '' }}" />
<link rel="stylesheet" href="https://unpkg.com/layui@2.11.4/dist/css/layui.css">
@yield('styles')
<script src="https://unpkg.com/layui@2.11.4/dist/layui.js" charset="UTF-8"></script>
<script src="https://unpkg.com/jquery@3.7.1/dist/jquery.min.js" charset="UTF-8"></script>
<script src="https://unpkg.com/vue@3.5.17/dist/vue.global.prod.js" charset="UTF-8"></script>
<script src="{{ asset('static/js/jquery.SuperSlide.2.1.3.js') }}" charset="UTF-8"></script>
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['public/themes/default/js/app.js'])
@endif
</head>
<body>
<header>
    @if (Route::has('login'))
        <nav>
            @auth
                <a href="{{ url('/dashboard') }}">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}">
                    Log in
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">
                        Register
                    </a>
                @endif
            @endauth
        </nav>
    @endif
</header>
@yield('content')
@yield('scripts')
</body>
</html>
