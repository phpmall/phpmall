<x-layouts.guest title="重置密码 - {{ config('app.name', '商城系统') }}" headerTitle="重置密码" mode="simple">
  @push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/register.css') }}">
  @endpush

  <main class="w register-main" style="padding: 40px 0;">
    <div class="register-card" style="max-width: 480px;">
      <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 8px; color: #333;">设置新密码</h3>
      <p style="font-size: 13px; color: #666; margin-bottom: 24px;">请在下方输入您的新登录密码并确认</p>

      @if ($errors->any())
        <div style="background: #fff2f0; border: 1px solid #ffccc7; color: #cf1322; padding: 10px 14px; border-radius: 4px; font-size: 13px; margin-bottom: 20px;">
          ⚠️ {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') ?? $token ?? '' }}">

        <div class="form-field-group">
          <label class="form-label" for="email">电子邮箱</label>
          <div class="reg-input-wrap">
            <input id="email" type="email" name="email" value="{{ old('email', $request->email ?? $email ?? '') }}" required readonly
                   class="reg-input" style="background-color: #f5f5f5; color: #666;">
          </div>
        </div>

        <div class="form-field-group">
          <label class="form-label" for="password">新密码 <span class="req">*</span></label>
          <div class="reg-input-wrap">
            <input id="password" type="password" name="password" required autofocus autocomplete="new-password"
                   placeholder="请输入新密码" class="reg-input">
          </div>
        </div>

        <div class="form-field-group">
          <label class="form-label" for="password_confirmation">确认新密码 <span class="req">*</span></label>
          <div class="reg-input-wrap">
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   placeholder="请再次输入新密码" class="reg-input">
          </div>
        </div>

        <button type="submit" data-test="reset-password-button" class="btn-reg-submit" style="margin-top: 10px;">
          重置密码
        </button>
      </form>
    </div>
  </main>
</x-layouts.guest>
