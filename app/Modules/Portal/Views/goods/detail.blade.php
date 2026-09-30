@extends('portal::layouts.portal')

@section('title', ($detail['product']['title'] ?? '商品详情') . ' - 优品商城')

@push('styles')
  <link rel="stylesheet" href="{{ asset('static/css/detail.css') }}">
@endpush

@section('content')
<!-- 面包屑导航 -->
<nav class="breadcrumb w">
  <a href="{{ route('home') }}">首页</a>
  <span class="sep">&gt;</span>
  <a href="{{ route('portal.goods') }}">全部商品</a>
  <span class="sep">&gt;</span>
  @if(!empty($detail['category']))
    <a href="{{ route('portal.goods', ['category_id' => $detail['category']['id']]) }}">{{ $detail['category']['name'] }}</a>
    <span class="sep">&gt;</span>
  @endif
  <span class="current">{{ $detail['product']['title'] }}</span>
</nav>

<!-- 商品核心交易信息区 (Gallery + Purchase Panel) -->
<main class="w product-intro">
  <!-- 左侧：图片画廊 -->
  <div class="preview-wrap">
    <div class="main-img-box" id="mainImgBox">
      <img src="{{ $detail['product']['main_image'] ?? '' }}" alt="{{ $detail['product']['title'] }}" id="mainImg">
    </div>

    <!-- 缩略图选择栏 -->
    <div class="thumb-list-wrap">
      <div class="thumb-scroll">
        <ul class="thumb-list" id="thumbList">
          <li class="active" onclick="changeThumb('{{ $detail['product']['main_image'] ?? '' }}', this)">
            <img src="{{ $detail['product']['main_image'] ?? '' }}" alt="主图">
          </li>
          @if(!empty($detail['product']['images']))
            @php
              $images = is_string($detail['product']['images']) ? json_decode($detail['product']['images'], true) : $detail['product']['images'];
            @endphp
            @if(is_array($images))
              @foreach($images as $img)
                <li onclick="changeThumb('{{ $img }}', this)">
                  <img src="{{ $img }}" alt="图集">
                </li>
              @endforeach
            @endif
          @endif
        </ul>
      </div>
    </div>

    <!-- 底部辅助说明 -->
    <div class="preview-actions">
      <span class="item"><i class="icon">🔖</i> 商品编号：{{ 100000 + $productId }}</span>
      <span class="item"><i class="icon">🛡️</i> 正品保障 · 全国联保</span>
    </div>
  </div>

  <!-- 右侧：商品名称、价格、SKU规格选择与立即购买 -->
  <div class="item-info-wrap">
    <div class="sku-name">
      <span class="badge-zy">商城自营</span>
      <h1>{{ $detail['product']['title'] }}</h1>
    </div>
    @if(!empty($detail['product']['subtitle']))
      <p class="sku-slogan">{{ $detail['product']['subtitle'] }}</p>
    @endif

    <!-- 价格与促销区 -->
    <div class="summary-price-wrap">
      <div class="summary-top">
        <div class="price-title">限时售价</div>
        <div class="price-box">
          <span class="yen">¥</span>
          <span class="price-val" id="skuPrice">{{ number_format($detail['product']['min_price'] / 100, 2) }}</span>
        </div>
        <div class="comment-count-box">
          <p class="txt">累计销量</p>
          <p class="count">{{ $detail['product']['sales_count'] ?? 0 }} 件</p>
          <p class="rate">好评率 99%</p>
        </div>
      </div>

      <div class="summary-row">
        <span class="label">配 送</span>
        <div class="content">
          <span>至 <strong>北京市朝阳区</strong> | 现货，极速发货，次日送达</span>
        </div>
      </div>
      <div class="summary-row">
        <span class="label">保 障</span>
        <div class="content">
          <span>破损包赔 · 7天无理由退换 · 退换货免运费</span>
        </div>
      </div>
    </div>

    <!-- 规格属性选择 (SKU) -->
    <div class="choose-attrs">
      <div class="attr-group">
        <span class="dt">选择规格：</span>
        <div class="dd" id="skuList">
          @foreach($detail['skus'] as $index => $sku)
            @php
              $specs = is_string($sku['sku_specs']) ? json_decode($sku['sku_specs'], true) : $sku['sku_specs'];
              $specText = is_array($specs) ? implode(' / ', array_values($specs)) : ($sku['sku_code'] ?? '默认规格');
            @endphp
            <div 
              class="attr-item {{ $index === 0 ? 'selected' : '' }}" 
              data-sku-id="{{ $sku['id'] }}" 
              data-price="{{ $sku['price'] }}"
              data-stock="{{ $sku['stock'] }}"
              data-image="{{ $sku['image'] ?: ($detail['product']['main_image'] ?? '') }}"
              onclick="selectSku(this)">
              <span>{{ $specText }}</span>
            </div>
          @endforeach
        </div>
      </div>

      <!-- 购买数量 -->
      <div class="attr-group quantity-group">
        <span class="dt">购买数量：</span>
        <div class="dd">
          <div class="quantity-stepper">
            <button class="btn-step" onclick="changeQty(-1)">-</button>
            <input type="text" id="buyQty" value="1" readonly>
            <button class="btn-step" onclick="changeQty(1)">+</button>
          </div>
          <span class="stock-info">当前库存 <strong id="currentStock">0</strong> 件</span>
        </div>
      </div>
    </div>

    <!-- 立即购买 / 加入购物车按钮组 -->
    <div class="choose-btns">
      <button class="btn-add-cart" id="btnAddToCart" onclick="addToCart()">
        <span class="icon">🛒</span> 加入购物车
      </button>
      <button class="btn-buy-now" id="btnBuyNow" onclick="buyNow()">
        ⚡ 立即购买
      </button>
    </div>
  </div>
</main>

<!-- 下方图文详情展示 (Tab & Content) -->
<section class="w detail-content-wrap" style="margin-top: 30px; background: #fff; border-radius: 8px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
  <div style="border-bottom: 2px solid #e1251b; padding-bottom: 10px; margin-bottom: 20px;">
    <h3 style="font-size: 16px; font-weight: bold; color: #333; margin: 0;">商品介绍与规格参数</h3>
  </div>
  <div class="detail-html-content" style="line-height: 1.8; color: #444; font-size: 14px;">
    {!! $detail['product']['description'] ?? '<p>官方正品行货，全国联保，享受国家三包服务。</p>' !!}
  </div>
</section>

@push('scripts')
<script>
  let selectedSkuId = null;
  const productId = {{ $productId }};

  function selectSku(el) {
    document.querySelectorAll('#skuList .attr-item').forEach(item => {
      item.classList.remove('selected');
    });
    el.classList.add('selected');

    selectedSkuId = Number(el.getAttribute('data-sku-id'));
    const price = Number(el.getAttribute('data-price'));
    const stock = Number(el.getAttribute('data-stock'));
    const img = el.getAttribute('data-image');

    document.getElementById('skuPrice').innerText = formatPrice(price);
    document.getElementById('currentStock').innerText = stock;
    if (img) {
      document.getElementById('mainImg').src = img;
    }
  }

  function changeThumb(src, el) {
    if (!src) return;
    document.getElementById('mainImg').src = src;
    document.querySelectorAll('#thumbList li').forEach(li => li.classList.remove('active'));
    el.classList.add('active');
  }

  function changeQty(delta) {
    const input = document.getElementById('buyQty');
    let val = Number(input.value) + delta;
    if (val < 1) val = 1;
    input.value = val;
  }

  async function addToCart() {
    if (!selectedSkuId) {
      showToast('请先选择商品规格', 0);
      return;
    }
    const qty = Number(document.getElementById('buyQty').value);
    const loadIdx = showLoading();

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
        showToast('已成功加入购物车！', 1);
        refreshCartBadge();
      } else {
        showToast(json.message || '加购失败，请稍后重试', 2);
      }
    } catch (e) {
      showToast('网络错误，请稍后重试', 2);
    } finally {
      closeLoading(loadIdx);
    }
  }

  function buyNow() {
    if (!selectedSkuId) {
      showToast('请先选择商品规格', 0);
      return;
    }
    const qty = Number(document.getElementById('buyQty').value);
    window.location.href = `/checkout?sku_id=${selectedSkuId}&quantity=${qty}`;
  }

  document.addEventListener('DOMContentLoaded', () => {
    const firstSku = document.querySelector('#skuList .attr-item');
    if (firstSku) selectSku(firstSku);
  });
</script>
@endpush
@endsection
