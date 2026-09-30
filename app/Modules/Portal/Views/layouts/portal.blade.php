<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', '商城 - 正品低价、品质保障、配送及时、轻松购物！')</title>
  <link rel="stylesheet" href="{{ asset('static/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('static/layui/css/layui.css') }}">
  @stack('styles')
</head>
<body>
  <!-- 1. 顶部快捷导航条 (Shortcut Bar) -->
  <div class="shortcut-nav">
    <div class="w container-flex">
      <div class="nav-location">
        <span class="location-icon">📍</span>
        <span class="current-city">北京</span>
      </div>
      <ul class="nav-quick-links">
        @auth
          <li class="login-item">
            <span>你好，{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline; margin-left: 8px;">
              @csrf
              <button type="submit" style="background:none; border:none; color:#999; cursor:pointer;">[退出]</button>
            </form>
          </li>
        @else
          <li class="login-item">
            <a href="{{ route('login') }}" class="highlight">你好，请登录</a>
            <a href="{{ route('register') }}" class="register-btn">免费注册</a>
          </li>
        @endauth
        <li class="spacer"></li>
        <li><a href="{{ route('portal.orders') }}">我的订单</a></li>
        <li class="spacer"></li>
        <li><a href="{{ route('portal.cart') }}">我的购物车</a></li>
        <li class="spacer"></li>
        <li><a href="{{ route('home') }}">返回首页</a></li>
        <li class="spacer"></li>
        <li><a href="/admin">管理后台</a></li>
      </ul>
    </div>
  </div>

  <!-- 2. 头部搜索与品牌展示区 (Header & Search) -->
  <header class="header">
    <div class="w header-main">
      <!-- 品牌Logo -->
      <div class="header-logo">
        <a href="{{ route('home') }}" class="logo-link">
          <div class="logo-box">
            <span class="logo-text">MALL</span>
            <span class="logo-sub">优品商城</span>
          </div>
        </a>
      </div>

      <!-- 搜索主区域 -->
      <div class="header-search-wrap">
        <form action="{{ route('portal.goods') }}" method="GET" class="search-bar" id="searchForm">
          <input type="text" class="search-input" name="keyword" placeholder="搜索商品、品牌、规格型号..." value="{{ request('keyword', '') }}" autocomplete="off">
          <button type="submit" class="search-submit-btn">
            <span>搜索</span>
          </button>
        </form>
        <div class="hot-words">
          <a href="{{ route('portal.goods', ['keyword' => '手机']) }}" class="highlight">智能手机</a>
          <a href="{{ route('portal.goods', ['keyword' => '电脑']) }}">轻薄笔记本</a>
          <a href="{{ route('portal.goods', ['keyword' => '耳机']) }}">降噪耳机</a>
          <a href="{{ route('portal.goods', ['is_hot' => 1]) }}">热销爆款</a>
          <a href="{{ route('portal.goods', ['is_new' => 1]) }}">新品首发</a>
        </div>
      </div>

      <!-- 购物车入口 -->
      <div class="header-cart" id="miniCart">
        <a href="{{ route('portal.cart') }}" class="cart-trigger">
          <span class="cart-icon">🛒</span>
          <span class="cart-text">我的购物车</span>
          <span class="cart-count" id="globalCartCount">0</span>
        </a>
      </div>
    </div>

    <!-- 主频道栏目导航条 -->
    <div class="header-channels">
      <div class="w channels-wrap">
        <div class="category-title">
          <span>全部商品分类</span>
        </div>
        <ul class="channel-nav-list">
          <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">首页</a></li>
          <li class="{{ request()->routeIs('portal.goods') && !request()->filled('is_hot') && !request()->filled('is_new') ? 'active' : '' }}"><a href="{{ route('portal.goods') }}">全部商品</a></li>
          <li class="{{ request('is_hot') ? 'active' : '' }}"><a href="{{ route('portal.goods', ['is_hot' => 1]) }}">热销榜单</a></li>
          <li class="{{ request('is_new') ? 'active' : '' }}"><a href="{{ route('portal.goods', ['is_new' => 1]) }}">新品首发</a></li>
          <li class="{{ request()->routeIs('portal.orders') ? 'active' : '' }}"><a href="{{ route('portal.orders') }}">我的订单</a></li>
        </ul>
      </div>
    </div>
  </header>

  <!-- 3. 主体内容区 -->
  <main class="portal-main-wrapper">
    @yield('content')
  </main>

  <!-- 4. 页脚服务保障与版权信息 (Footer) -->
  <footer class="footer">
    <div class="service-slogans w">
      <div class="slogan-item">
        <span class="slogan-icon duo">多</span>
        <div class="slogan-text">
          <h4>品类齐全</h4>
          <p>轻松购物 多样选择</p>
        </div>
      </div>
      <div class="slogan-item">
        <span class="slogan-icon kuai">快</span>
        <div class="slogan-text">
          <h4>极速配送</h4>
          <p>多仓直发 极速达</p>
        </div>
      </div>
      <div class="slogan-item">
        <span class="slogan-icon hao">好</span>
        <div class="slogan-text">
          <h4>正品行货</h4>
          <p>精致服务 品质护航</p>
        </div>
      </div>
      <div class="slogan-item">
        <span class="slogan-icon sheng">省</span>
        <div class="slogan-text">
          <h4>天天低价</h4>
          <p>畅选无忧 畅享实惠</p>
        </div>
      </div>
    </div>

    <div class="footer-copyright w">
      <p class="links">
        <a href="{{ route('home') }}">商城首页</a><span class="split">|</span>
        <a href="{{ route('portal.goods') }}">全部商品</a><span class="split">|</span>
        <a href="{{ route('portal.orders') }}">我的订单</a><span class="split">|</span>
        <a href="{{ route('portal.cart') }}">购物车</a><span class="split">|</span>
        <a href="/admin">管理后台</a>
      </p>
      <p class="copy">
        Copyright © 2026 商城系统 PHPMall 版权所有 | 统一采用 app/Api/Portal 驱动
      </p>
    </div>
  </footer>

  <!-- Layui 核心脚本 -->
  <script src="{{ asset('static/layui/layui.js') }}"></script>

  <script>
    // 通用轻提示函数（基于 Layui layer.msg，平滑降级）
    function showToast(msg, icon = 0, duration = 2000) {
      if (window.layer) {
        layer.msg(msg, { icon: icon, time: duration });
      } else {
        alert(msg);
      }
    }

    // 全局 Loading 加载层
    function showLoading() {
      return window.layer ? layer.load(2, { shade: [0.1, '#000'] }) : null;
    }

    function closeLoading(index) {
      if (window.layer && index !== null) {
        layer.close(index);
      }
    }

    // 格式化价格（分 -> 元）
    function formatPrice(cent) {
      return (Number(cent || 0) / 100).toFixed(2);
    }

    // 自动刷新购物车角标
    async function refreshCartBadge() {
      try {
        const res = await fetch('/api/portal/cart', {
          headers: { 'Accept': 'application/json' }
        });
        const json = await res.json();
        if (json.code === 0 && json.data) {
          const badge = document.getElementById('globalCartCount');
          if (badge) badge.innerText = json.data.total_quantity || 0;
        }
      } catch (e) {}
    }

    document.addEventListener('DOMContentLoaded', refreshCartBadge);
  </script>
  @stack('scripts')
</body>
</html>
