@extends('portal::layouts.portal')

@section('title', '商城收银台 - 安全支付')

@push('styles')
  <link rel="stylesheet" href="{{ asset('static/css/pay.css') }}">
@endpush

@section('content')
<!-- 收银台核心交易主体 -->
<main class="w pay-main" style="margin-top: 25px;">
  <!-- 1. 订单摘要卡片 -->
  <div class="pay-order-summary">
    <div class="summary-left">
      <div class="order-primary-info">
        <span class="check-icon" style="background:#28a745;color:white;width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:50%;font-size:18px;">✔</span>
        <div class="order-info-text">
          <h3>订单提交成功，请尽快完成付款！</h3>
          <p class="order-meta">
            <span>订单号：<strong class="highlight">{{ $order->order_no }}</strong></span>
            <span class="split">|</span>
            <span>配送服务：普通快递 (自营直发)</span>
            <span class="split">|</span>
            <span>请在 <strong class="highlight" id="payCountdown">29分59秒</strong> 内完成支付</span>
          </p>
        </div>
      </div>
    </div>
    <div class="summary-right">
      <div class="amount-box">
        <span class="txt">应付金额：</span>
        <span class="yen">¥</span>
        <strong class="pay-money" id="payMoneyVal">{{ number_format($order->pay_amount / 100, 2) }}</strong>
      </div>
    </div>
  </div>

  <!-- 2. 支付方式选择面板 -->
  <div class="pay-methods-card" style="margin-top: 20px;">
    <div class="methods-tab-header">
      <button class="method-tab active" data-tab="tab-wechat" onclick="choosePayment('wechat', this)">
        <span class="tab-icon">💬</span> 微信支付
      </button>
      <button class="method-tab" data-tab="tab-alipay" onclick="choosePayment('alipay', this)">
        <span class="tab-icon">🌐</span> 支付宝支付
      </button>
      <button class="method-tab" data-tab="tab-bank" onclick="choosePayment('bank', this)">
        <span class="tab-icon">🏦</span> 银行卡快捷支付
      </button>
    </div>

    <div class="methods-tab-body">
      <!-- 微信支付面板 -->
      <div class="method-panel active" id="tab-wechat" style="display: block; padding: 30px; text-align: center;">
        <div style="font-size: 16px; font-weight: bold; margin-bottom: 15px; color: #333;" id="methodDescTitle">
          微信扫码支付
        </div>
        <div style="display: inline-block; padding: 15px; border: 1px solid #eee; border-radius: 8px; background: #fafafa;">
          <div style="font-size: 72px;">📱</div>
          <p style="font-size: 13px; color: #666; margin-top: 10px;">点击下方按钮完成安全模拟支付</p>
        </div>
      </div>
    </div>

    <!-- 底部确认支付动作区 -->
    <div class="pay-action-bottom" style="margin-top: 25px; padding: 20px; border-top: 1px solid #eee; display: flex; justify-content: flex-end; align-items: center; gap: 20px;">
      <span style="font-size: 13px; color: #999;">由金融级安全网关提供加密传输保障</span>
      <button class="btn-pay-confirm" id="btnPayConfirm" onclick="handlePay()" style="padding: 12px 40px; background: #e1251b; color: white; border: none; border-radius: 4px; font-size: 18px; font-weight: bold; cursor: pointer;">
        立即支付 ¥{{ number_format($order->pay_amount / 100, 2) }}
      </button>
    </div>
  </div>
</main>

@push('scripts')
<script>
  let selectedMethod = 'wechat';

  function choosePayment(method, el) {
    selectedMethod = method;
    document.querySelectorAll('.methods-tab-header .method-tab').forEach(btn => btn.classList.remove('active'));
    el.classList.add('active');

    const desc = {
      'wechat': '微信扫码支付',
      'alipay': '支付宝网页/扫码支付',
      'bank': '银联快捷安全支付'
    };
    document.getElementById('methodDescTitle').innerText = desc[method] || '在线安全支付';
  }

  // 30分钟倒计时
  let secondsLeft = 1800;
  setInterval(() => {
    if (secondsLeft <= 0) return;
    secondsLeft--;
    const m = Math.floor(secondsLeft / 60);
    const s = secondsLeft % 60;
    const timerEl = document.getElementById('payCountdown');
    if (timerEl) {
      timerEl.innerText = `${m}分${s < 10 ? '0' : ''}${s}秒`;
    }
  }, 1000);

  function handlePay() {
    const btn = document.getElementById('btnPayConfirm');
    btn.disabled = true;
    btn.innerText = '正在调起安全支付...';
    const loadIdx = showLoading();

    setTimeout(() => {
      closeLoading(loadIdx);
      showToast('🎉 模拟支付成功！', 1);
      setTimeout(() => {
        window.location.href = '/orders';
      }, 1000);
    }, 800);
  }
</script>
@endpush
@endsection
