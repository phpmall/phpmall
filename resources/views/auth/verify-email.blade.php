<x-layouts.guest title="邮箱验证 - {{ config('app.name', '商城系统') }}" headerTitle="邮箱验证" mode="simple">
  @push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/register.css') }}">
  @endpush

  <main class="w register-main" style="padding: 40px 0;">
    <div class="register-card" style="max-width: 480px; text-align: center;">
      <div style="font-size: 48px; margin-bottom: 12px;">✉️</div>
      <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 8px; color: #333;">验证您的电子邮箱</h3>
      <p style="font-size: 13px; color: #666; margin-bottom: 24px; line-height: 1.6;">请点击我们发送到您注册邮箱的验证链接以激活账号。如未收到，可点击下方按钮重新发送。</p>

      @if (session('status') == 'verification-link-sent')
        <div style="background: #f6ffed; border: 1px solid #b7eb8f; color: #389e0d; padding: 10px 14px; border-radius: 4px; font-size: 13px; margin-bottom: 20px;">
          新的验证链接已发送至您的电子邮箱，请注意查收。
        </div>
      @endif

      <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-reg-submit" style="margin-bottom: 15px;">
          重新发送验证邮件
        </button>
      </form>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="background: none; border: none; color: #999; font-size: 13px; cursor: pointer;">
          [退出登录]
        </button>
      </form>
    </div>
  </main>
</x-layouts.guest>
