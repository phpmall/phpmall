<x-layouts.guest title="找回密码 - {{ config('app.name', '商城系统') }}" headerTitle="找回密码" mode="simple">
  @push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/register.css') }}">
  @endpush

  <main class="w register-main" style="padding: 40px 0;">
    <div class="register-card" style="max-width: 480px;">
      <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 8px; color: #333;">找回账户密码</h3>
      <p style="font-size: 13px; color: #666; margin-bottom: 24px;">请输入您注册时绑定的电子邮箱，我们将向您发送重置链接</p>

      @if (session('status'))
        <div style="background: #f6ffed; border: 1px solid #b7eb8f; color: #389e0d; padding: 10px 14px; border-radius: 4px; font-size: 13px; margin-bottom: 20px;">
          {{ session('status') }}
        </div>
      @endif

      @if ($errors->any())
        <div style="background: #fff2f0; border: 1px solid #ffccc7; color: #cf1322; padding: 10px 14px; border-radius: 4px; font-size: 13px; margin-bottom: 20px;">
          ⚠️ {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-field-group">
          <label class="form-label" for="email">电子邮箱 <span class="req">*</span></label>
          <div class="reg-input-wrap">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   placeholder="email@example.com" class="reg-input">
          </div>
        </div>

        <button type="submit" data-test="email-password-reset-link-button" class="btn-reg-submit" style="margin-top: 10px;">
          发送密码重置链接
        </button>

        <div style="text-align: center; margin-top: 20px; font-size: 13px;">
          想起来了？<a href="{{ route('login') }}" style="color: #e1251b; text-decoration: none; font-weight: bold;">返回登录</a>
        </div>
      </form>
    </div>
  </main>
</x-layouts.guest>
