@extends('portal.layouts.portal')

@section('title', '收银台 - 优品商城')

@section('content')
<div class="w" style="margin-top: 25px;">
  <!-- 成功提交提醒与订单摘要面板 -->
  <div style="background: #fff; border-radius: 8px; padding: 35px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 20px;">
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f0f0f0; padding-bottom: 25px;">
      <div style="display: flex; align-items: center; gap: 20px;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: #eefbf1; color: #28a745; display: flex; align-items: center; justify-content: center; font-size: 28px;">
          ✓
        </div>
        <div>
          <h2 style="font-size: 20px; font-weight: bold; color: #333; margin: 0 0 8px;">
            订单提交成功，请尽快完成支付！
          </h2>
          <p style="font-size: 13px; color: #888; margin: 0;">
            请在 <strong style="color: #e1251b;" id="countdownTimer">29分59秒</strong> 内完成支付，超时订单将自动取消
          </p>
        </div>
      </div>

      <div style="text-align: right;">
        <span style="font-size: 14px; color: #666;">应付总额：</span>
        <strong style="color: #e1251b; font-size: 32px; font-weight: 800;">
          <span style="font-size: 18px;">¥</span>{{ number_format($order->pay_amount / 100, 2) }}
        </strong>
      </div>
    </div>

    <!-- 订单关键详情展开 -->
    <div style="margin-top: 20px; font-size: 13px; color: #666; display: flex; flex-direction: column; gap: 8px;">
      <div>订单编号：<strong style="color: #333;">{{ $order->order_no }}</strong></div>
      <div>下单时间：<span>{{ $order->created_at }}</span></div>
      <div>配送方式：<span>普通快递（免运费）</span></div>
    </div>
  </div>

  <!-- 支付渠道选择 -->
  <div style="background: #fff; border-radius: 8px; padding: 35px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
    <h3 style="font-size: 16px; font-weight: bold; margin: 0 0 20px; color: #333;">选择支付方式</h3>

    <div style="display: flex; gap: 20px; margin-bottom: 35px;">
      <!-- 微信支付 -->
      <label class="pay-method-card selected" onclick="choosePayment('wechat', this)" style="border: 2px solid #e1251b; border-radius: 6px; padding: 15px 25px; display: flex; align-items: center; gap: 12px; cursor: pointer; background: #fff8f8;">
        <input type="radio" name="pay_type" value="wechat" checked style="accent-color: #e1251b;">
        <span style="font-size: 20px;">💬</span>
        <span style="font-size: 15px; font-weight: bold; color: #333;">微信支付</span>
      </label>

      <!-- 支付宝 -->
      <label class="pay-method-card" onclick="choosePayment('alipay', this)" style="border: 2px solid #eee; border-radius: 6px; padding: 15px 25px; display: flex; align-items: center; gap: 12px; cursor: pointer; background: #fff;">
        <input type="radio" name="pay_type" value="alipay" style="accent-color: #e1251b;">
        <span style="font-size: 20px;">🔵</span>
        <span style="font-size: 15px; font-weight: bold; color: #333;">支付宝支付</span>
      </label>

      <!-- 银联快捷 -->
      <label class="pay-method-card" onclick="choosePayment('unionpay', this)" style="border: 2px solid #eee; border-radius: 6px; padding: 15px 25px; display: flex; align-items: center; gap: 12px; cursor: pointer; background: #fff;">
        <input type="radio" name="pay_type" value="unionpay" style="accent-color: #e1251b;">
        <span style="font-size: 20px;">💳</span>
        <span style="font-size: 15px; font-weight: bold; color: #333;">银联快捷支付</span>
      </label>
    </div>

    <!-- 支付二维码 / 按钮操作区 -->
    <div style="border-top: 1px solid #f0f0f0; padding-top: 25px; display: flex; align-items: center; justify-content: space-between;">
      <div style="font-size: 13px; color: #999;">
        点击立即支付，将安全跳转至第三方加密收银环境
      </div>
      <button onclick="handlePay()" id="btnPay" style="padding: 12px 50px; background: #e1251b; color: white; border: none; border-radius: 4px; font-size: 18px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 12px rgba(225,37,27,0.3);">
        立即支付 ¥{{ number_format($order->pay_amount / 100, 2) }}
      </button>
    </div>
  </div>
</div>

@push('scripts')
<script>
  let selectedMethod = 'wechat';

  function choosePayment(method, el) {
    selectedMethod = method;
    document.querySelectorAll('.pay-method-card').forEach(card => {
      card.style.borderColor = '#eee';
      card.style.background = '#fff';
    });
    el.style.borderColor = '#e1251b';
    el.style.background = '#fff8f8';
  }

  // 30分钟倒计时
  let secondsLeft = 1800;
  setInterval(() => {
    if (secondsLeft <= 0) return;
    secondsLeft--;
    const m = Math.floor(secondsLeft / 60);
    const s = secondsLeft % 60;
    const timerEl = document.getElementById('countdownTimer');
    if (timerEl) {
      timerEl.innerText = `${m}分${s < 10 ? '0' : ''}${s}秒`;
    }
  }, 1000);

  function handlePay() {
    const btn = document.getElementById('btnPay');
    btn.disabled = true;
    btn.innerText = '正在调起安全支付...';

    setTimeout(() => {
      showToast('🎉 模拟支付成功！');
      setTimeout(() => {
        window.location.href = '/orders';
      }, 1200);
    }, 1000);
  }
</script>
@endpush
@endsection
