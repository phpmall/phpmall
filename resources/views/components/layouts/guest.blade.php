<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', '商城系统') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased min-h-screen flex flex-col justify-center items-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-6">
        <a href="/" class="inline-flex items-center gap-2 text-2xl font-bold text-gray-900">
            <span class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center text-white font-extrabold text-lg">M</span>
            <span>{{ config('app.name', 'PHPMall') }}</span>
        </a>
        @if(isset($heading))
            <h2 class="mt-4 text-xl font-bold tracking-tight text-gray-900">{{ $heading }}</h2>
        @endif
        @if(isset($subheading))
            <p class="mt-2 text-sm text-gray-600">{{ $subheading }}</p>
        @endif
    </div>

    <div class="w-full sm:max-w-md px-6 py-8 bg-white border border-gray-200 shadow-sm rounded-xl">
        {{ $slot }}
    </div>
</body>
</html>
