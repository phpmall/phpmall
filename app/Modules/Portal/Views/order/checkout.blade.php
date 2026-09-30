@extends('portal::layouts.portal')

@section('title', '订单结算 - 优品商城')

@section('content')
<div class="w" style="margin-top: 20px;">
  <!-- 结算步骤提示 -->
  <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e1251b; padding-bottom: 15px; margin-bottom: 25px;">
    <h2 style="font-size: 22px; font-weight: bold; margin: 0; color: #333;">填写并核对订单信息</h2>
    <div style="display: flex; gap: 20px; font-size: 14px;">
      <span style="color: #999;">1. 我的购物车 ›</span>
      <strong style="color: #e1251b;">2. 填写核对订单 ›</strong>
      <span style="color: #999;">3. 成功提交订单</span>
    </div>
  </div>

  <!-- 结算卡片容器 -->
  <div style="background: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
    
    <!-- 1. 收货人信息 -->
    <div style="margin-bottom: 30px; border-bottom: 1px solid #f0f0f0; padding-bottom: 25px;">
      <h3 style="font-size: 16px; font-weight: bold; margin-bottom: 15px; color: #333; display: flex; align-items: center; gap: 8px;">
        <span style="color: #e1251b;">📍</span> 收货人信息
      </h3>
      <div style="display: flex; align-items: center; gap: 15px; background: #fffcfc; border: 1px solid #ffdede; padding: 15px 20px; border-radius: 6px;">
        <span style="background: #e1251b; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 12px;">默认地址</span>
        <strong style="color: #333; font-size: 14px;" id="addrConsignee">{{ auth()->user()?->name ?? '张三' }}</strong>
        <span style="color: #666; font-size: 14px;" id="addrPhone">138****8888</span>
        <span style="color: #444; font-size: 14px;" id="addrDetail">北京市 海淀区 中关村南大街1号科技大厦8层</span>
      </div>
    </div>

    <!-- 2. 支付方式 -->
    <div style="margin-bottom: 30px; border-bottom: 1px solid #f0f0f0; padding-bottom: 25px;">
      <h3 style="font-size: 16px; font-weight: bold; margin-bottom: 15px; color: #333; display: flex; align-items: center; gap: 8px;">
        <span style="color: #e1251b;">💳</span> 支付方式
      </h3>
      <div style="display: flex; gap: 15px;">
        <div style="border: 2px solid #e1251b; padding: 10px 20px; border-radius: 4px; background: #fff8f8; color: #e1251b; font-weight: bold; font-size: 14px; cursor: pointer;">
          在线支付（支持微信 / 支付宝）
        </div>
      </div>
    </div>

    <!-- 3. 送货清单与验价列表 -->
    <div style="margin-bottom: 30px; border-bottom: 1px solid #f0f0f0; padding-bottom: 25px;">
      <h3 style="font-size: 16px; font-weight: bold; margin-bottom: 15px; color: #333; display: flex; align-items: center; gap: 8px;">
        <span style="color: #e1251b;">📦</span> 送货清单
      </h3>

      <div style="background: #f7f7f7; padding: 12px 20px; font-size: 13px; color: #666; border-radius: 4px; display: flex; margin-bottom: 10px;">
        <div style="flex: 1;">商品名称</div>
        <div style="width: 140px; text-align: center;">单价</div>
        <div style="width: 120px; text-align: center;">数量</div>
        <div style="width: 140px; text-align: right;">小计</div>
      </div>

      <div id="checkoutGoodsList">
        <div style="text-align: center; padding: 40px; color: #999;">正在核对商品及验算价格...</div>
      </div>
    </div>

    <!-- 4. 买家留言 -->
    <div style="margin-bottom: 30px;">
      <h3 style="font-size: 14px; font-weight: bold; margin-bottom: 10px; color: #333;">买家备注 / 留言</h3>
      <input type="text" id="orderRemark" placeholder="选填：如发货要求、送达时间要求等（50字以内）" style="width: 100%; max-width: 600px; padding: 10px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
    </div>

    <!-- 5. 金额汇总与提交下单栏 -->
    <div style="background: #fafafa; border: 1px solid #eee; border-radius: 6px; padding: 25px; display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
      <div style="font-size: 14px; color: #666;">
        <span id="goodsTotalCount">0</span> 件商品，总商品金额：<span style="color: #333; font-weight: bold; width: 120px; display: inline-block; text-align: right;">¥<span id="goodsTotalAmount">0.00</span></span>
      </div>
      <div style="font-size: 14px; color: #666;">
        运费：<span style="color: #333; font-weight: bold; width: 120px; display: inline-block; text-align: right;">¥0.00</span>
      </div>
      <div style="font-size: 14px; color: #666;">
        活动优惠：<span style="color: #e1251b; font-weight: bold; width: 120px; display: inline-block; text-align: right;">- ¥<span id="discountAmount">0.00</span></span>
      </div>

      <div style="border-top: 1px solid #eee; width: 100%; margin: 10px 0;"></div>

      <div style="font-size: 15px; color: #333;">
        应付总额：<strong style="color: #e1251b; font-size: 28px;">¥<span id="finalPayAmount">0.00</span></strong>
      </div>
      
      <div style="font-size: 12px; color: #888; margin-top: 5px;">
        寄送至：北京市 海淀区 中关村南大街1号科技大厦8层 &nbsp; 收货人：{{ auth()->user()?->name ?? '张三' }} 138****8888
      </div>

      <div style="margin-top: 15px;">
        <button onclick="submitOrder()" id="btnSubmitOrder" style="padding: 12px 45px; background: #e1251b; color: white; border: none; border-radius: 4px; font-size: 18px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 12px rgba(225,37,27,0.3);">
          提交订单
        </button>
      </div>
    </div>

  </div>
</div>

@push('scripts')
<script>
  const directSkuId = {{ $directSkuId ? $directSkuId : 'null' }};
  const directQuantity = {{ $directQuantity ? $directQuantity : 1 }};
  let checkoutItems = [];

  async function initCheckout() {
    try {
      if (directSkuId) {
        // 单品立即购买链路
        checkoutItems = [{ sku_id: directSkuId, quantity: directQuantity }];
      } else {
        // 购物车批量结算链路：读取购物车已选商品
        const cartRes = await fetch('/api/portal/cart', {
          headers: { 'Accept': 'application/json' }
        });
        const cartJson = await cartRes.json();
        if (cartJson.code === 0 && cartJson.data && cartJson.data.items) {
          const selected = cartJson.data.items.filter(i => i.is_selected && i.is_valid);
          if (selected.length === 0) {
            alert('购物车中没有选中的有效商品，正在为您跳转回购物车...');
            window.location.href = '/cart';
            return;
          }
          checkoutItems = selected.map(i => ({ sku_id: i.sku_id, quantity: i.quantity }));
        } else {
          showToast('获取购物车失败');
          return;
        }
      }

      // 请求 /api/portal/order/preview 执行服务端精准验价
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
        document.getElementById('btnSubmitOrder').style.opacity = 0.5;
      }
    } catch (e) {
      document.getElementById('checkoutGoodsList').innerHTML = '<div style="text-align: center; padding: 40px; color: #e1251b;">网络异常，请刷新重试</div>';
    }
  }

  function renderPreview(data) {
    document.getElementById('goodsTotalCount').innerText = data.items.reduce((acc, cur) => acc + cur.quantity, 0);
    document.getElementById('goodsTotalAmount').innerText = formatPrice(data.total_amount);
    document.getElementById('discountAmount').innerText = formatPrice(data.discount_amount);
    document.getElementById('finalPayAmount').innerText = formatPrice(data.pay_amount);

    const listHtml = data.items.map(item => {
      let specStr = '';
      if (item.sku_specs) {
        const specs = typeof item.sku_specs === 'string' ? JSON.parse(item.sku_specs) : item.sku_specs;
        specStr = Object.values(specs).join(' / ');
      }

      return `
        <div style="display: flex; align-items: center; padding: 15px 20px; border-bottom: 1px solid #f2f2f2;">
          <div style="flex: 1; display: flex; gap: 15px; align-items: center;">
            <img src="${item.product_image || '/images/default.jpg'}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
            <div>
              <div style="font-size: 14px; color: #333; font-weight: 500; margin-bottom: 4px;">${item.product_title}</div>
              <div style="font-size: 12px; color: #999;">${specStr}</div>
            </div>
          </div>
          <div style="width: 140px; text-align: center; font-size: 14px; color: #444;">
            ¥${formatPrice(item.price)}
          </div>
          <div style="width: 120px; text-align: center; font-size: 14px; color: #444;">
            x ${item.quantity}
          </div>
          <div style="width: 140px; text-align: right; color: #e1251b; font-weight: bold; font-size: 15px;">
            ¥${formatPrice(item.subtotal)}
          </div>
        </div>
      `;
    }).join('');

    document.getElementById('checkoutGoodsList').innerHTML = listHtml;
  }

  async function submitOrder() {
    const btn = document.getElementById('btnSubmitOrder');
    btn.disabled = true;
    btn.innerText = '正在提交订单...';

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
          source: 1 // PC Web 端
        })
      });

      const json = await res.json();
      if (json.code === 0 && json.data) {
        showToast('订单提交成功，正在跳转收银台...');
        setTimeout(() => {
          window.location.href = `/pay/${json.data.order_no}`;
        }, 800);
      } else {
        showToast(json.message || '下单失败，请检查商品库存或重试');
        btn.disabled = false;
        btn.innerText = '提交订单';
      }
    } catch (e) {
      showToast('网络通信异常，请重试');
      btn.disabled = false;
      btn.innerText = '提交订单';
    }
  }

  document.addEventListener('DOMContentLoaded', initCheckout);
</script>
@endpush
@endsection
