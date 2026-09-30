<x-layouts.guest title="个人用户注册 - {{ config('app.name', '商城系统') }}" headerTitle="欢迎注册" mode="register">
  @push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/register.css') }}">
  @endpush

  <!-- 主体注册区域 -->
  <main class="w register-main">
    <!-- 三步流程指示条 -->
    <div class="reg-step-bar">
      <div class="step-item active" id="step1">
        <div class="step-num">1</div>
        <span class="step-text">填写账户信息</span>
      </div>
      <div class="step-line" id="line1"></div>
      <div class="step-item" id="step2">
        <div class="step-num">2</div>
        <span class="step-text">设置账户密码</span>
      </div>
      <div class="step-line" id="line2"></div>
      <div class="step-item" id="step3">
        <div class="step-num">3</div>
        <span class="step-text">注册成功</span>
      </div>
    </div>

    <!-- 核心注册表单卡片 -->
    <div class="register-card">
      @if ($errors->any())
        <div style="background: #fff2f0; border: 1px solid #ffccc7; color: #cf1322; padding: 10px 14px; border-radius: 4px; font-size: 13px; margin-bottom: 20px;">
          ⚠️ {{ $errors->first() }}
        </div>
      @endif

      <form id="regForm" method="POST" action="{{ route('register.store') }}">
        @csrf

        <!-- 用户名 -->
        <div class="form-field-group">
          <label class="form-label" for="name">设置用户名 / 姓名 <span class="req">*</span></label>
          <div class="reg-input-wrap">
            <input type="text" class="reg-input" id="name" name="name" value="{{ old('name') }}" placeholder="支持中英文、数字 (4-20位)" required autofocus autocomplete="name">
          </div>
        </div>

        <!-- 电子邮箱 -->
        <div class="form-field-group">
          <label class="form-label" for="email">电子邮箱 <span class="req">*</span></label>
          <div class="reg-input-wrap">
            <input type="email" class="reg-input" id="email" name="email" value="{{ old('email') }}" placeholder="请输入常用的电子邮箱" required autocomplete="username">
          </div>
        </div>

        <!-- 设置密码 -->
        <div class="form-field-group">
          <label class="form-label" for="password">设置登录密码 <span class="req">*</span></label>
          <div class="reg-input-wrap">
            <input type="password" class="reg-input" id="password" name="password" placeholder="8-20位，建议混合字母、数字和符号" required autocomplete="new-password" oninput="checkPwdStrength(this.value)">
          </div>
          <div class="pwd-strength-bar">
            <span class="strength-label">安全强度：</span>
            <div class="strength-pills">
              <span class="s-pill" id="pill1"></span>
              <span class="s-pill" id="pill2"></span>
              <span class="s-pill" id="pill3"></span>
            </div>
            <span style="font-size:11px;color:#888;" id="strengthTxt">未输入</span>
          </div>
        </div>

        <!-- 确认密码 -->
        <div class="form-field-group">
          <label class="form-label" for="password_confirmation">确认登录密码 <span class="req">*</span></label>
          <div class="reg-input-wrap">
            <input type="password" class="reg-input" id="password_confirmation" name="password_confirmation" placeholder="请再次输入登录密码" required autocomplete="new-password">
          </div>
        </div>

        <!-- 协议勾选 -->
        <div class="agree-terms-row">
          <input type="checkbox" id="checkTerms" required checked>
          <label for="checkTerms">
            我已阅读并同意 <a href="javascript:;">《用户注册协议》</a>、<a href="javascript:;">《隐私政策》</a> 以及 <a href="javascript:;">《网络服务使用协议》</a>
          </label>
        </div>

        <!-- 提交注册按钮 -->
        <button type="submit" data-test="register-user-button" class="btn-reg-submit" id="btnRegSubmit">立即注册</button>

        <!-- 企业用户入口 -->
        <div class="b2b-reg-tip">
          已有账号？<a href="{{ route('login') }}">请直接登录 &gt;</a>
        </div>
      </form>
    </div>
  </main>

  @push('scripts')
  <script>
    function checkPwdStrength(val) {
      const p1 = document.getElementById('pill1');
      const p2 = document.getElementById('pill2');
      const p3 = document.getElementById('pill3');
      const txt = document.getElementById('strengthTxt');

      p1.className = 's-pill';
      p2.className = 's-pill';
      p3.className = 's-pill';

      if (!val || val.length === 0) {
        txt.innerText = '未输入';
        txt.style.color = '#888';
        return;
      }

      let score = 0;
      if (val.length >= 6) score++;
      if (val.length >= 10) score++;
      if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
      if (/[^A-Za-z0-9]/.test(val)) score++;

      if (score <= 1) {
        p1.classList.add('weak');
        txt.innerText = '弱';
        txt.style.color = '#f5222d';
      } else if (score <= 3) {
        p1.classList.add('medium');
        p2.classList.add('medium');
        txt.innerText = '中等';
        txt.style.color = '#fa8c16';
      } else {
        p1.classList.add('strong');
        p2.classList.add('strong');
        p3.classList.add('strong');
        txt.innerText = '安全';
        txt.style.color = '#52c41a';
      }
    }
  </script>
  @endpush
</x-layouts.guest>
