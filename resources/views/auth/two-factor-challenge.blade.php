<x-layouts.guest title="双重身份验证 - {{ config('app.name', '商城系统') }}" headerTitle="双重身份验证" mode="simple">
  @push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/register.css') }}">
  @endpush

  <main class="w register-main" style="padding: 40px 0;">
    <div class="register-card" style="max-width: 480px;">
      <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 8px; color: #333;">双重身份验证</h3>
      <p style="font-size: 13px; color: #666; margin-bottom: 24px;">请输入身份验证器提供的动态验证码或应急恢复码</p>

      @if ($errors->any())
        <div style="background: #fff2f0; border: 1px solid #ffccc7; color: #cf1322; padding: 10px 14px; border-radius: 4px; font-size: 13px; margin-bottom: 20px;">
          ⚠️ {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('two-factor.login.store') }}">
        @csrf

        <div class="form-field-group">
          <label class="form-label" for="code">动态验证码</label>
          <div class="reg-input-wrap">
            <input id="code" type="text" inputmode="numeric" name="code" autofocus autocomplete="one-time-code"
                   placeholder="请输入6位动态验证码" class="reg-input">
          </div>
        </div>

        <div class="form-field-group">
          <label class="form-label" for="recovery_code">或使用应急恢复码</label>
          <div class="reg-input-wrap">
            <input id="recovery_code" type="text" name="recovery_code" autocomplete="one-time-code"
                   placeholder="请输入应急恢复码" class="reg-input">
          </div>
        </div>

        <button type="submit" class="btn-reg-submit" style="margin-top: 10px;">
          验证并登录
        </button>
      </form>
    </div>
  </main>
</x-layouts.guest>
