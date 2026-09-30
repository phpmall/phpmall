<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', '商城 - 正品低价、品质保障、轻松购物！')</title>
  <link rel="stylesheet" href="{{ asset('static/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('static/layui/css/layui.css') }}">
  @stack('styles')
</head>
<body>
  <!-- 1. 顶部快捷导航条 -->
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
        <li><a href="{{ route('portal.order.my') }}">我的订单</a></li>
        <li class="spacer"></li>
        <li><a href="{{ route('portal.cart.view') }}">购物车</a></li>
        <li class="spacer"></li>
        <li><a href="/admin">管理后台</a></li>
      </ul>
    </div>
  </div>

  <!-- 2. 头部搜索与 Logo 区域 -->
  <header class="header-main">
    <div class="w header-flex">
      <div class="logo-box">
        <a href="{{ route('home') }}" class="logo-link">
          <div style="display: flex; align-items: center; gap: 8px; font-weight: bold; font-size: 26px; color: #e1251b;">
            <span style="background: #e1251b; color: white; padding: 2px 8px; border-radius: 4px; font-size: 20px;">MALL</span>
            <span>优品商城</span>
          </div>
        </a>
      </div>

      <div class="search-box">
        <form action="{{ route('portal.goods.list') }}" method="GET" class="search-form" id="searchForm">
          <input type="text" name="keyword" class="search-input" placeholder="搜索商品、品牌、型号..." value="{{ request('keyword', '') }}">
          <button type="submit" class="search-btn">搜索</button>
        </form>
        <div class="hotwords">
          <a href="{{ route('portal.goods.list', ['keyword' => '手机']) }}" class="highlight">智能手机</a>
          <a href="{{ route('portal.goods.list', ['keyword' => '电脑']) }}">轻薄笔记本</a>
          <a href="{{ route('portal.goods.list', ['keyword' => '耳机']) }}">降噪耳机</a>
          <a href="{{ route('portal.goods.list', ['is_hot' => 1]) }}">热销爆款</a>
        </div>
      </div>

      <div class="cart-box">
        <a href="{{ route('portal.cart.view') }}" class="cart-btn">
          <span class="cart-icon">🛒</span>
          <span class="cart-text">我的购物车</span>
          <span class="cart-badge" id="globalCartCount">0</span>
        </a>
      </div>
    </div>
  </header>

  <!-- 3. 主体内容区 -->
  <main>
    @yield('content')
  </main>

  <!-- 4. 页脚服务与保障 -->
  <footer class="footer-main" style="margin-top: 50px;">
    <div class="w">
      <div class="slogans" style="display: flex; justify-content: space-around; padding: 30px 0; border-bottom: 1px solid #dedede;">
        <div class="slogan-item" style="display: flex; align-items: center; gap: 10px;">
          <span style="font-size: 32px;">品</span>
          <div><strong>品类齐全</strong><p style="color: #999; font-size: 12px;">轻松购物 一站搞定</p></div>
        </div>
        <div class="slogan-item" style="display: flex; align-items: center; gap: 10px;">
          <span style="font-size: 32px;">快</span>
          <div><strong>极速配送</strong><p style="color: #999; font-size: 12px;">多仓直发 准时到达</p></div>
        </div>
        <div class="slogan-item" style="display: flex; align-items: center; gap: 10px;">
          <span style="font-size: 32px;">好</span>
          <div><strong>正品行货</strong><p style="color: #999; font-size: 12px;">精致服务 品质护航</p></div>
        </div>
        <div class="slogan-item" style="display: flex; align-items: center; gap: 10px;">
          <span style="font-size: 32px;">省</span>
          <div><strong>天天低价</strong><p style="color: #999; font-size: 12px;">畅选无忧 畅享优惠</p></div>
        </div>
      </div>

      <div class="footer-copy" style="text-align: center; padding: 25px 0; color: #888; font-size: 12px; line-height: 1.8;">
        <p>Copyright © 2026 商城系统 PHPMall 版权所有 | 统一采用 app/Api/Portal 驱动</p>
      </div>
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
