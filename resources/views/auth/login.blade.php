<x-layouts.guest title="欢迎登录 - {{ config('app.name', '商城系统') }}" headerTitle="欢迎登录" mode="login">
  @push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/login.css') }}">
  @endpush

  <!-- 登录中间大通栏背景区 -->
  <div class="login-banner-wrap">
    <div class="w login-container">
      <!-- 右侧登录浮动面板 -->
      <div class="login-box">
        <!-- 登录方式切换 Tab -->
        <div class="login-tab-head">
          <button type="button" class="tab-btn" data-tab="tab-qrcode" onclick="switchLoginTab('tab-qrcode', this)">扫码登录</button>
          <span class="split">|</span>
          <button type="button" class="tab-btn active" data-tab="tab-account" onclick="switchLoginTab('tab-account', this)">账户登录</button>
        </div>

        <!-- 1. 扫码登录 -->
        <div class="login-panel" id="tab-qrcode">
          <div class="qrcode-wrap">
            <div class="qrcode-img-box">
              <svg viewBox="0 0 100 100" class="qr-svg">
                <rect width="100" height="100" fill="#ffffff"/>
                <path d="M10,10 h24 v24 h-24 z M16,16 v12 h12 v-12 z" fill="#333"/>
                <rect x="20" y="20" width="4" height="4" fill="#e1251b"/>
                <path d="M66,10 h24 v24 h-24 z M72,16 v12 h12 v-12 z" fill="#333"/>
                <rect x="76" y="20" width="4" height="4" fill="#e1251b"/>
                <path d="M10,66 h24 v24 h-24 z M16,72 v12 h12 v-12 z" fill="#333"/>
                <rect x="20" y="76" width="4" height="4" fill="#e1251b"/>
                <rect x="42" y="14" width="6" height="6" fill="#333"/>
                <rect x="52" y="22" width="6" height="6" fill="#333"/>
                <rect x="44" y="34" width="8" height="8" fill="#e1251b"/>
                <rect x="18" y="44" width="6" height="6" fill="#333"/>
                <rect x="28" y="48" width="6" height="6" fill="#333"/>
                <rect x="64" y="44" width="6" height="6" fill="#333"/>
                <rect x="76" y="52" width="8" height="8" fill="#333"/>
                <rect x="42" y="60" width="6" height="6" fill="#333"/>
                <rect x="56" y="66" width="8" height="8" fill="#333"/>
                <rect x="68" y="74" width="6" height="6" fill="#e1251b"/>
                <rect x="44" y="82" width="8" height="8" fill="#333"/>
              </svg>
            </div>
            <p class="qrcode-tip">打开 <strong class="highlight">手机客户端</strong> 扫一扫登录</p>
            <div class="qrcode-features">
              <span>免输入</span>
              <span class="dot">·</span>
              <span>更快</span>
              <span class="dot">·</span>
              <span>更安全</span>
            </div>
          </div>
        </div>

        <!-- 2. 账号密码登录 -->
        <div class="login-panel active" id="tab-account">
          @if ($errors->any())
            <div style="background: #fff2f0; border: 1px solid #ffccc7; color: #cf1322; padding: 8px 12px; border-radius: 4px; font-size: 12px; margin-bottom: 12px;">
              ⚠️ {{ $errors->first() }}
            </div>
          @endif

          @if (session('status'))
            <div style="background: #f6ffed; border: 1px solid #b7eb8f; color: #389e0d; padding: 8px 12px; border-radius: 4px; font-size: 12px; margin-bottom: 12px;">
              {{ session('status') }}
            </div>
          @endif

          <form class="login-form" method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="input-row">
              <span class="input-icon">👤</span>
              <input type="text" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="请输入邮箱 / 用户名">
            </div>
            <div class="input-row">
              <span class="input-icon">🔒</span>
              <input type="password" name="password" id="password" required autocomplete="current-password" placeholder="请输入登录密码">
            </div>

            <div class="form-sub-links">
              <label class="custom-checkbox" style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <span>自动登录</span>
              </label>
              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">忘记密码</a>
              @endif
            </div>

            <button type="submit" data-test="login-button" class="btn-login-submit" style="border: none; width: 100%;">登 录</button>
          </form>
        </div>

        <!-- 底部快捷与注册链接 -->
        <div class="login-box-footer">
          <div class="oauth-links">
            <a href="javascript:;" title="QQ登录">🐧 QQ</a>
            <span class="pipe">|</span>
            <a href="javascript:;" title="微信登录">💬 微信</a>
            <span class="pipe">|</span>
            <a href="javascript:;" title="Apple登录">🍏 Apple</a>
          </div>
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="register-now highlight" id="btnRegister"><i>›</i> 立即注册</a>
          @endif
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    function switchLoginTab(tabId, el) {
      document.querySelectorAll('.login-tab-head .tab-btn').forEach(btn => btn.classList.remove('active'));
      document.querySelectorAll('.login-panel').forEach(panel => panel.classList.remove('active'));
      el.classList.add('active');
      const target = document.getElementById(tabId);
      if (target) target.classList.add('active');
    }
  </script>
  @endpush
</x-layouts.guest>
