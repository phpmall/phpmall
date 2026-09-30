@extends('portal::layouts.portal')

@section('title', '商品搜索与筛选列表 - 优品商城')

@push('styles')
  <link rel="stylesheet" href="{{ asset('static/css/list.css') }}">
@endpush

@section('content')
<!-- 面包屑与结果统计 -->
<div class="w list-crumb">
  <a href="{{ route('home') }}">全部结果</a>
  <span class="sep">&gt;</span>
  <strong class="current">"{{ $keyword ?: '全部商品' }}"</strong>
  <span class="total-res" id="totalResultTxt">正在检索商品...</span>
</div>

<!-- 多维筛选器 (Selector Box) -->
<section class="w selector-box">
  <!-- 商品分类行 -->
  <div class="selector-row">
    <div class="s-key">分 类：</div>
    <div class="s-values brand-values" id="categoryFilter">
      <a href="javascript:;" onclick="filterByCategory(null)" class="b-item {{ empty($categoryId) ? 'active' : '' }}">全部</a>
      @foreach($categories as $cat)
        <a href="javascript:;" onclick="filterByCategory({{ $cat['id'] }})" class="b-item {{ $categoryId == $cat['id'] ? 'active' : '' }}">
          {{ $cat['name'] }}
        </a>
      @endforeach
    </div>
  </div>

  <!-- 价格区间 -->
  <div class="selector-row">
    <div class="s-key">价 格：</div>
    <div class="s-values">
      <a href="javascript:;" onclick="setPriceRange('', '')" class="active" id="priceAll">全部</a>
      <a href="javascript:;" onclick="setPriceRange(0, 1999)">0-1999</a>
      <a href="javascript:;" onclick="setPriceRange(2000, 3999)">2000-3999</a>
      <a href="javascript:;" onclick="setPriceRange(4000, 5999)">4000-5999</a>
      <a href="javascript:;" onclick="setPriceRange(6000, '')">6000及以上</a>
      <div class="price-input-range">
        <input type="number" id="minPriceInput" placeholder="¥"> - <input type="number" id="maxPriceInput" placeholder="¥">
        <button class="btn-price-ok" onclick="applyPriceFilter()">确定</button>
      </div>
    </div>
  </div>
</section>

<!-- 排序与过滤工具条 (Filter Bar) -->
<section class="w filter-bar">
  <div class="filter-sort-group">
    <button class="sort-btn active" id="sortDefault" onclick="setSort('sort_order', 'desc')">综合推荐</button>
    <button class="sort-btn" id="sortSales" onclick="setSort('sales', 'desc')">销量最高</button>
    <button class="sort-btn" id="sortNew" onclick="setSort('created_at', 'desc')">新品上市</button>
    <button class="sort-btn" id="sortPrice" onclick="togglePriceSort()">价格 ↕</button>
  </div>

  <div class="filter-extra-checkboxes">
    <label class="custom-checkbox">
      <input type="checkbox" checked>
      <span>商城自营 (正品直发)</span>
    </label>
    <label class="custom-checkbox">
      <input type="checkbox" checked>
      <span>仅看有货</span>
    </label>
  </div>
</section>

<!-- 商品网格展示区 -->
<main class="w list-goods-wrap">
  <div class="goods-items-grid" id="goodsList">
    <div style="grid-column: span 5; text-align: center; padding: 60px; color: #888;">正在加载商品列表中...</div>
  </div>
</main>

<!-- 分页栏 -->
<div class="w pagination-wrap" id="paginationWrap"></div>

@push('scripts')
<script>
  let currentParams = {
    keyword: "{{ $keyword }}",
    category_id: "{{ $categoryId ?? '' }}",
    sort_by: 'sort_order',
    sort_order: 'desc',
    min_price: '',
    max_price: '',
    page: 1,
    pageSize: 20
  };

  let priceOrder = 'asc';

  async function loadGoods() {
    const container = document.getElementById('goodsList');
    container.innerHTML = '<div style="grid-column: span 5; text-align: center; padding: 60px; color: #888;">正在加载商品列表中...</div>';

    const url = new URL('/api/portal/goods', window.location.origin);
    Object.keys(currentParams).forEach(k => {
      if (currentParams[k]) url.searchParams.append(k, currentParams[k]);
    });

    try {
      const res = await fetch(url.toString(), {
        headers: { 'Accept': 'application/json' }
      });
      const json = await res.json();

      if (json.code === 0 && json.data && json.data.data.length > 0) {
        document.getElementById('totalResultTxt').innerHTML = `共筛选出 <strong>${json.data.total}</strong> 件相关商品`;
        renderGoods(json.data.data);
        renderPagination(json.data.current_page, json.data.last_page);
      } else {
        document.getElementById('totalResultTxt').innerText = '未找到匹配商品';
        container.innerHTML = '<div style="grid-column: span 5; text-align: center; padding: 80px; color: #999;">抱歉，未找到匹配的商品</div>';
        document.getElementById('paginationWrap').innerHTML = '';
      }
    } catch (e) {
      container.innerHTML = '<div style="grid-column: span 5; text-align: center; padding: 80px; color: #e1251b;">数据加载失败，请重试</div>';
    }
  }

  function renderGoods(list) {
    const container = document.getElementById('goodsList');
    container.innerHTML = list.map(item => `
      <div class="gl-item">
        <div class="gl-i-wrap">
          <div class="p-img">
            <a href="/goods/${item.id}">
              ${item.main_image ? `<img src="${item.main_image}" alt="${item.title}">` : '<div style="height:210px;display:flex;align-items:center;justify-content:center;font-size:40px;">📦</div>'}
            </a>
          </div>
          <div class="p-price">
            <span class="price-val"><em>¥</em><i>${formatPrice(item.min_price)}</i></span>
            ${item.max_price && item.max_price > item.min_price ? `<span class="tag-save">至高 ¥${formatPrice(item.max_price)}</span>` : ''}
          </div>
          <div class="p-name">
            <a href="/goods/${item.id}" title="${item.title}">
              <span class="badge-zy">自营</span>
              ${item.title}
            </a>
          </div>
          <div class="p-commit">
            已有 <strong class="commit-count">${item.sales_count * 5}+</strong> 人评价
            <span class="rate-good">98%好评</span>
          </div>
          <div class="p-shop">
            <span class="shop-name">优品商城自营旗舰店</span>
            <span class="im-icon">💬</span>
          </div>
          <div class="p-icons">
            <span class="icon-zy">自营次日达</span>
            ${item.is_hot ? '<span class="icon-tag">热销爆款</span>' : ''}
          </div>
          <div class="p-operate">
            <a href="/goods/${item.id}" class="btn-quick-cart">查看详情 / 选购</a>
          </div>
        </div>
      </div>
    `).join('');
  }

  function renderPagination(current, last) {
    const wrap = document.getElementById('paginationWrap');
    if (last <= 1) {
      wrap.innerHTML = '';
      return;
    }
    let html = '';
    if (current > 1) {
      html += `<button class="p-btn" onclick="goPage(${current - 1})">&lt; 上一页</button>`;
    }
    for (let p = 1; p <= last; p++) {
      html += `<button class="p-btn ${p === current ? 'active' : ''}" onclick="goPage(${p})">${p}</button>`;
    }
    if (current < last) {
      html += `<button class="p-btn" onclick="goPage(${current + 1})">下一页 &gt;</button>`;
    }
    wrap.innerHTML = html;
  }

  function goPage(p) {
    currentParams.page = p;
    loadGoods();
    window.scrollTo({ top: 120, behavior: 'smooth' });
  }

  function filterByCategory(id) {
    currentParams.category_id = id || '';
    currentParams.page = 1;
    document.querySelectorAll('#categoryFilter .b-item').forEach(el => el.classList.remove('active'));
    event.target.classList.add('active');
    loadGoods();
  }

  function setSort(by, order) {
    currentParams.sort_by = by;
    currentParams.sort_order = order;
    currentParams.page = 1;
    document.querySelectorAll('.filter-sort-group .sort-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    loadGoods();
  }

  function togglePriceSort() {
    priceOrder = priceOrder === 'asc' ? 'desc' : 'asc';
    currentParams.sort_by = 'price';
    currentParams.sort_order = priceOrder;
    currentParams.page = 1;
    document.querySelectorAll('.filter-sort-group .sort-btn').forEach(btn => btn.classList.remove('active'));
    const btn = document.getElementById('sortPrice');
    btn.classList.add('active');
    btn.innerText = priceOrder === 'asc' ? '价格 ↑' : '价格 ↓';
    loadGoods();
  }

  function setPriceRange(min, max) {
    currentParams.min_price = min !== '' ? Math.round(Number(min) * 100) : '';
    currentParams.max_price = max !== '' ? Math.round(Number(max) * 100) : '';
    currentParams.page = 1;
    loadGoods();
  }

  function applyPriceFilter() {
    const min = document.getElementById('minPriceInput').value;
    const max = document.getElementById('maxPriceInput').value;
    setPriceRange(min, max);
  }

  document.addEventListener('DOMContentLoaded', loadGoods);
</script>
@endpush
@endsection
