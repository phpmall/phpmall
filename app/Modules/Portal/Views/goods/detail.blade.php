@extends('portal::layouts.portal')

@section('title', ($detail['product']['title'] ?? '商品详情') . ' - 优品商城')

@section('content')
<div class="w" style="margin-top: 15px;">
  <!-- 面包屑导航 -->
  <div style="font-size: 13px; color: #888; margin-bottom: 15px;">
    <a href="{{ route('home') }}" style="color: #666; text-decoration: none;">首页</a> ›
    <a href="{{ route('portal.goods.list') }}" style="color: #666; text-decoration: none;">全部商品</a> ›
    @if(!empty($detail['category']))
      <a href="{{ route('portal.goods.list', ['category_id' => $detail['category']['id']]) }}" style="color: #666; text-decoration: none;">{{ $detail['category']['name'] }}</a> ›
    @endif
    <span style="color: #333;">{{ $detail['product']['title'] }}</span>
  </div>

  <!-- 商品核心信息卡片 -->
  <div style="background: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; gap: 40px;">
    <!-- 左侧图片相册展示 -->
    <div style="width: 420px;">
      <div style="width: 420px; height: 420px; border: 1px solid #eee; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #fafafa;">
        <img id="mainProductImg" src="{{ $detail['product']['main_image'] ?? '' }}" alt="{{ $detail['product']['title'] }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
      </div>
    </div>

    <!-- 右侧购买决策区域 -->
    <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
      <div>
        <h1 style="font-size: 22px; font-weight: bold; color: #222; margin: 0 0 8px; line-height: 1.4;">
          {{ $detail['product']['title'] }}
        </h1>
        @if(!empty($detail['product']['subtitle']))
          <p style="font-size: 14px; color: #e1251b; margin: 0 0 15px;">{{ $detail['product']['subtitle'] }}</p>
        @endif

        <!-- 价格条面板 -->
        <div style="background: #fdf2f2; padding: 15px 20px; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: baseline; gap: 15px;">
          <span style="color: #888; font-size: 13px;">活动售价</span>
          <div style="color: #e1251b; font-size: 32px; font-weight: 800;">
            <span style="font-size: 18px;">¥</span><span id="currentPrice">{{ number_format($detail['product']['min_price'] / 100, 2) }}</span>
          </div>
          <span style="color: #999; font-size: 12px; margin-left: auto;">累计销量 {{ $detail['product']['sales_count'] ?? 0 }} 件</span>
        </div>

        <!-- SKU 规格选择 -->
        <div style="margin-bottom: 25px;">
          <div style="font-size: 13px; color: #666; margin-bottom: 10px;">选择规格配置：</div>
          <div id="skuOptions" style="display: flex; gap: 10px; flex-wrap: wrap;">
            @foreach($detail['skus'] as $index => $sku)
              @php
                $specs = is_string($sku['sku_specs']) ? json_decode($sku['sku_specs'], true) : $sku['sku_specs'];
                $specText = is_array($specs) ? implode(' / ', array_values($specs)) : ($sku['sku_code'] ?? '默认规格');
              @endphp
              <button 
                type="button"
                class="sku-pill {{ $index === 0 ? 'selected' : '' }}" 
                data-sku-id="{{ $sku['id'] }}" 
                data-price="{{ $sku['price'] }}"
                data-stock="{{ $sku['stock'] }}"
                data-image="{{ $sku['image'] ?: ($detail['product']['main_image'] ?? '') }}"
                onclick="selectSku(this)"
                style="padding: 8px 16px; border: 1px solid {{ $index === 0 ? '#e1251b' : '#ddd' }}; background: {{ $index === 0 ? '#fff5f5' : '#fff' }}; color: {{ $index === 0 ? '#e1251b' : '#333' }}; border-radius: 4px; font-size: 13px; cursor: pointer; transition: all 0.2s;">
                {{ $specText }}
              </button>
            @endforeach
          </div>
        </div>

        <!-- 购买数量 -->
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
          <span style="font-size: 13px; color: #666;">购买数量：</span>
          <div style="display: inline-flex; border: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
            <button onclick="changeQty(-1)" style="width: 32px; height: 32px; border: none; background: #f5f5f5; cursor: pointer; font-size: 16px;">-</button>
            <input type="number" id="buyQty" value="1" min="1" style="width: 50px; height: 32px; text-align: center; border: none; border-left: 1px solid #ddd; border-right: 1px solid #ddd; font-size: 14px;">
            <button onclick="changeQty(1)" style="width: 32px; height: 32px; border: none; background: #f5f5f5; cursor: pointer; font-size: 16px;">+</button>
          </div>
          <span style="font-size: 12px; color: #999;">当前库存 <span id="currentStock">0</span> 件</span>
        </div>
      </div>

      <!-- 购买交互按钮 -->
      <div style="display: flex; gap: 15px;">
        <button onclick="addToCart()" style="flex: 1; height: 48px; border: 1px solid #e1251b; background: #ffeded; color: #e1251b; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer;">
          🛒 加入购物车
        </button>
        <button onclick="buyNow()" style="flex: 1; height: 48px; border: none; background: #e1251b; color: white; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 12px rgba(225,37,27,0.3);">
          ⚡ 立即购买
        </button>
      </div>
    </div>
  </div>

  <!-- 商品介绍与详情 -->
  <div style="margin-top: 30px; background: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
    <h3 style="font-size: 16px; font-weight: bold; border-left: 4px solid #e1251b; padding-left: 10px; margin-bottom: 20px;">商品介绍与图文详情</h3>
    <div style="line-height: 1.8; color: #444; font-size: 14px;">
      {!! $detail['product']['description'] ?? '<p>官方正品保证，全国联保，假一赔十。</p>' !!}
    </div>
  </div>
</div>

@push('scripts')
<script>
  let selectedSkuId = null;
  const productId = {{ $productId }};

  function selectSku(el) {
    document.querySelectorAll('.sku-pill').forEach(btn => {
      btn.style.borderColor = '#ddd';
      btn.style.background = '#fff';
      btn.style.color = '#333';
    });
    el.style.borderColor = '#e1251b';
    el.style.background = '#fff5f5';
    el.style.color = '#e1251b';

    selectedSkuId = Number(el.getAttribute('data-sku-id'));
    const price = Number(el.getAttribute('data-price'));
    const stock = Number(el.getAttribute('data-stock'));
    const img = el.getAttribute('data-image');

    document.getElementById('currentPrice').innerText = formatPrice(price);
    document.getElementById('currentStock').innerText = stock;
    if (img) {
      document.getElementById('mainProductImg').src = img;
    }
  }

  function changeQty(delta) {
    const input = document.getElementById('buyQty');
    let val = Number(input.value) + delta;
    if (val < 1) val = 1;
    input.value = val;
  }

  // 异步加入购物车
  async function addToCart() {
    if (!selectedSkuId) {
      showToast('请先选择商品规格');
      return;
    }
    const qty = Number(document.getElementById('buyQty').value);

    try {
      const res = await fetch('/api/portal/cart', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ sku_id: selectedSkuId, quantity: qty })
      });
      const json = await res.json();

      if (json.code === 0) {
        showToast('已成功加入购物车！');
        refreshCartBadge();
      } else {
        showToast(json.message || '加购失败，请稍后重试');
      }
    } catch (e) {
      showToast('网络错误，请稍后重试');
    }
  }

  // 立即购买 -> 直达结算页
  function buyNow() {
    if (!selectedSkuId) {
      showToast('请先选择商品规格');
      return;
    }
    const qty = Number(document.getElementById('buyQty').value);
    window.location.href = `/checkout?sku_id=${selectedSkuId}&quantity=${qty}`;
  }

  // 初始化默认选中第一个 SKU
  document.addEventListener('DOMContentLoaded', () => {
    const firstSku = document.querySelector('.sku-pill');
    if (firstSku) selectSku(firstSku);
  });
</script>
@endpush
@endsection
