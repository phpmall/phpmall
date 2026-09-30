@extends('portal::layouts.portal')

@section('title', '订单结算页 - 优品商城')

@push('styles')
  <link rel="stylesheet" href="{{ asset('static/css/checkout.css') }}">
@endpush

@section('content')
<!-- 结算页专属流程步骤指示器 -->
<div class="w" style="margin-top: 15px; margin-bottom: 15px; display: flex; justify-content: flex-end;">
  <div class="checkout-steps">
    <div class="step-node done">
      <span class="step-num">1</span>
      <span class="step-text">1. 我的购物车</span>
    </div>
    <div class="step-line done"></div>
    <div class="step-node active">
      <span class="step-num">2</span>
      <span class="step-text">2. 填写核对订单信息</span>
    </div>
    <div class="step-line"></div>
    <div class="step-node">
      <span class="step-num">3</span>
      <span class="step-text">3. 成功提交订单</span>
    </div>
  </div>
</div>

<!-- 结算核心表单主体 -->
<main class="w checkout-main">
  <div class="checkout-box-title">填写并核对订单信息</div>
  <div class="checkout-form-container">
    
    <!-- 1. 收货人信息 -->
    <section class="checkout-section">
      <div class="sec-head">
        <h3 class="sec-title">收货人信息</h3>
      </div>
      <div class="addr-list" id="addrList">
        <div class="addr-card active">
          <div class="addr-header">
            <span class="uname">{{ auth()->user()?->name ?? '张三' }} (北京)</span>
            <span class="addr-tag">默认地址</span>
          </div>
          <p class="addr-detail">北京市 海淀区 中关村南大街1号 科技大厦8层</p>
          <p class="phone">138****8888</p>
        </div>
      </div>
    </section>

    <!-- 2. 支付方式 -->
    <section class="checkout-section">
      <div class="sec-head">
        <h3 class="sec-title">支付方式</h3>
      </div>
      <div class="pay-options" id="payOptions">
        <button class="pay-btn active" data-pay="在线支付">
          <span>在线支付</span>
          <small class="tip">支持微信 / 支付宝 / 银联</small>
        </button>
      </div>
    </section>

    <!-- 3. 送货清单与商家履约 -->
    <section class="checkout-section">
      <div class="sec-head">
        <h3 class="sec-title">送货清单</h3>
        <a href="{{ route('portal.cart') }}" class="back-cart-link">返回购物车修改 &gt;</a>
      </div>
      <div class="order-goods-panel">
        <!-- 配送时效信息 -->
        <div class="delivery-side">
          <h4>配送方式</h4>
          <div class="delivery-tag active">商城极速达 (顺丰速运)</div>
          <p class="delivery-time">预计 <strong>明天(次日) 09:00-15:00</strong> 送达</p>
          <p class="delivery-service">标准配送 · 运输保价 · 破损包赔</p>
          <div style="margin-top: 15px;">
            <label style="font-size: 12px; color: #666;">买家留言：</label>
            <input type="text" id="orderRemark" placeholder="选填，建议50字以内" style="width: 100%; padding: 6px 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 12px; margin-top: 5px;">
          </div>
        </div>

        <!-- 商品列表清单 -->
        <div class="goods-side">
          <h4>商家：优品商城自营旗舰店</h4>
          <div id="checkoutGoodsList">
            <div style="text-align: center; padding: 40px; color: #888;">正在核对商品清单及计算金额...</div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. 结算金额明细汇总 -->
    <div class="order-summary">
      <div class="sum-line">
        <span class="k">商品总件数：</span>
        <span class="v" id="goodsTotalCount">0 件</span>
      </div>
      <div class="sum-line">
        <span class="k">商品总金额：</span>
        <span class="v" id="goodsTotalAmount">¥0.00</span>
      </div>
      <div class="sum-line">
        <span class="k">运费：</span>
        <span class="v">¥0.00 (满免)</span>
      </div>
      <div class="sum-line">
        <span class="k">活动优惠：</span>
        <span class="v highlight" id="discountAmount">-¥0.00</span>
      </div>
    </div>

    <!-- 最终应付与提交卡片 -->
    <div class="final-pay-card">
      <div class="pay-amount-row">
        <span class="txt">应付总额：</span>
        <strong class="total-money" id="finalPayAmount">¥0.00</strong>
      </div>
      <div class="receiver-tip-row">
        <p>寄送至：北京市 海淀区 中关村南大街1号 科技大厦8层</p>
        <p>收货人：{{ auth()->user()?->name ?? '张三' }} 138****8888</p>
      </div>
      <button class="btn-submit-order" id="btnSubmitOrder" onclick="submitOrder()">提交订单</button>
    </div>
  </div>
</main>

@push('scripts')
<script>
  const directSkuId = {{ $directSkuId ? $directSkuId : 'null' }};
  const directQuantity = {{ $directQuantity ? $directQuantity : 1 }};
  let checkoutItems = [];

  async function initCheckout() {
    const loadIdx = showLoading();
    try {
      if (directSkuId) {
        checkoutItems = [{ sku_id: directSkuId, quantity: directQuantity }];
      } else {
        const cartRes = await fetch('/api/portal/cart', {
          headers: { 'Accept': 'application/json' }
        });
        const cartJson = await cartRes.json();
        if (cartJson.code === 0 && cartJson.data && cartJson.data.items) {
          const selected = cartJson.data.items.filter(i => i.is_selected && i.is_valid);
          if (selected.length === 0) {
            showToast('购物车中没有选中的商品', 0);
            setTimeout(() => { window.location.href = '/cart'; }, 800);
            return;
          }
          checkoutItems = selected.map(i => ({ sku_id: i.sku_id, quantity: i.quantity }));
        } else {
          showToast('获取购物车失败', 2);
          return;
        }
      }

      const previewRes = await fetch('/api/portal/order/preview', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ items: checkoutItems })
      });
      const previewJson = await previewRes.json();

      if (previewJson.code === 0 && previewJson.data) {
        renderPreview(previewJson.data);
      } else {
        document.getElementById('checkoutGoodsList').innerHTML = `<div style="text-align: center; padding: 40px; color: #e1251b;">${previewJson.message || '验价失败，请稍后重试'}</div>`;
        document.getElementById('btnSubmitOrder').disabled = true;
        document.getElementById('btnSubmitOrder').style.opacity = '0.5';
      }
    } catch (e) {
      document.getElementById('checkoutGoodsList').innerHTML = '<div style="text-align: center; padding: 40px; color: #e1251b;">网络通信异常，请刷新重试</div>';
    } finally {
      closeLoading(loadIdx);
    }
  }

  function renderPreview(data) {
    document.getElementById('goodsTotalCount').innerText = `${data.items.reduce((acc, cur) => acc + cur.quantity, 0)} 件`;
    document.getElementById('goodsTotalAmount').innerText = '¥' + formatPrice(data.total_amount);
    document.getElementById('discountAmount').innerText = '-¥' + formatPrice(data.discount_amount);
    document.getElementById('finalPayAmount').innerText = '¥' + formatPrice(data.pay_amount);

    const listHtml = data.items.map(item => {
      let specStr = '';
      if (item.sku_specs) {
        const specs = typeof item.sku_specs === 'string' ? JSON.parse(item.sku_specs) : item.sku_specs;
        specStr = Object.values(specs).join(' / ');
      }

      return `
        <div class="chk-goods-item">
          <img src="${item.product_image || '/images/default.jpg'}" alt="${item.product_title}">
          <div class="info">
            <p class="name">${item.product_title}</p>
            <p class="spec">规格：${specStr || '默认规格'}</p>
          </div>
          <div class="price">¥${formatPrice(item.price)}</div>
          <div class="count">x ${item.quantity}</div>
          <div class="stock">有货</div>
        </div>
      `;
    }).join('');

    document.getElementById('checkoutGoodsList').innerHTML = listHtml;
  }

  async function submitOrder() {
    const btn = document.getElementById('btnSubmitOrder');
    btn.disabled = true;
    btn.innerText = '正在提交...';
    const loadIdx = showLoading();

    const remark = document.getElementById('orderRemark').value.trim();

    try {
      const res = await fetch('/api/portal/order', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          items: checkoutItems,
          remark: remark,
          source: 1
        })
      });

      const json = await res.json();
      if (json.code === 0 && json.data) {
        showToast('订单提交成功，正在跳转收银台...', 1);
        setTimeout(() => {
          window.location.href = `/pay/${json.data.order_no}`;
        }, 800);
      } else {
        showToast(json.message || '下单失败，请检查商品库存或重试', 2);
        btn.disabled = false;
        btn.innerText = '提交订单';
      }
    } catch (e) {
      showToast('网络通信异常，请重试', 2);
      btn.disabled = false;
      btn.innerText = '提交订单';
    } finally {
      closeLoading(loadIdx);
    }
  }

  document.addEventListener('DOMContentLoaded', initCheckout);
</script>
@endpush
@endsection
