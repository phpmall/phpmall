@props([
    'title' => config('app.name', '商城系统'),
    'headerTitle' => '欢迎登录',
    'mode' => 'login', // login, register, simple
    'bodyClass' => '',
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? config('app.name', '商城系统') }}</title>
  <link rel="stylesheet" href="{{ asset('static/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('static/layui/css/layui.css') }}">
  @stack('styles')
</head>
<body class="{{ $bodyClass ?: ($mode === 'register' ? 'register-body' : 'login-body') }}">
  @if($mode === 'register')
    <!-- 注册页 Header -->
    <header class="register-header">
      <div class="w reg-header-inner">
        <a href="{{ route('home') }}" class="reg-logo-box">
          <div class="reg-logo-text">JD</div>
          <h2 class="reg-title">{{ $headerTitle ?? '欢迎注册' }}</h2>
        </a>
        <div class="reg-login-link">
          已有账号？<a href="{{ route('login') }}">请登录 &gt;</a>
        </div>
      </div>
    </header>
  @elseif($mode === 'login')
    <!-- 登录页 Header -->
    <header class="login-header w">
      <div class="header-left">
        <a href="{{ route('home') }}" class="logo-box">
          <span class="logo-text">JD</span>
          <span class="logo-sub">{{ config('app.name', '商城') }}</span>
        </a>
        <h2 class="welcome-title">{{ $headerTitle ?? '欢迎登录' }}</h2>
      </div>
      <div class="header-right">
        <a href="javascript:;" class="survey-link"><i>💬</i> 页面“反馈建议”</a>
      </div>
    </header>
  @else
    <!-- 通用认证 Header -->
    <header class="login-header w">
      <div class="header-left">
        <a href="{{ route('home') }}" class="logo-box">
          <span class="logo-text">JD</span>
          <span class="logo-sub">{{ config('app.name', '商城') }}</span>
        </a>
        <h2 class="welcome-title">{{ $headerTitle ?? '账户中心' }}</h2>
      </div>
      <div class="header-right">
        <a href="{{ route('home') }}" class="survey-link">返回商城首页 ›</a>
      </div>
    </header>
  @endif

  <!-- 主体内容 -->
  {{ $slot }}

  <!-- 页脚版权与链接 -->
  <footer class="footer">
    <div class="footer-copyright w">
      <p class="links">
        <a href="{{ route('home') }}">关于我们</a><span class="split">|</span>
        <a href="javascript:;">联系我们</a><span class="split">|</span>
        <a href="javascript:;">人才招聘</a><span class="split">|</span>
        <a href="javascript:;">商家入驻</a><span class="split">|</span>
        <a href="javascript:;">广告服务</a><span class="split">|</span>
        <a href="javascript:;">手机商城</a><span class="split">|</span>
        <a href="javascript:;">友情链接</a><span class="split">|</span>
        <a href="javascript:;">销售联盟</a><span class="split">|</span>
        <a href="javascript:;">隐私政策</a>
      </p>
      <p class="copy">Copyright © 2004 - 2026 {{ config('app.name', '商城系统') }} 版权所有</p>
    </div>
  </footer>

  <script src="{{ asset('static/layui/layui.js') }}"></script>
  @stack('scripts')
</body>
</html>
