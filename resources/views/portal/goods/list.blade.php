@extends('portal.layouts.portal')

@section('title', '商品搜索与检索 - 优品商城')

@section('content')
<div class="w" style="margin-top: 15px;">
  <!-- 筛选条件栏 -->
  <div style="background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 15px;">
    <div style="display: flex; align-items: center; border-bottom: 1px dashed #eee; padding-bottom: 12px; margin-bottom: 12px;">
      <span style="width: 80px; color: #888; font-size: 13px;">商品分类：</span>
      <div style="display: flex; gap: 15px; flex-wrap: wrap;" id="categoryFilter">
        <a href="javascript:;" onclick="filterByCategory(null)" class="cat-pill active" style="text-decoration: none; font-size: 13px; color: #e1251b; font-weight: bold;">全部</a>
        @foreach($categories as $cat)
          <a href="javascript:;" onclick="filterByCategory({{ $cat['id'] }})" class="cat-pill" style="text-decoration: none; font-size: 13px; color: #555;">{{ $cat['name'] }}</a>
        @endforeach
      </div>
    </div>

    <!-- 排序工具栏 -->
    <div style="display: flex; justify-content: space-between; align-items: center;">
      <div style="display: flex; gap: 10px;">
        <button onclick="setSort('sort_order', 'desc')" class="sort-btn active" id="sortDefault" style="padding: 6px 14px; border: 1px solid #e1251b; background: #e1251b; color: white; border-radius: 4px; font-size: 13px; cursor: pointer;">综合排序</button>
        <button onclick="setSort('sales', 'desc')" class="sort-btn" id="sortSales" style="padding: 6px 14px; border: 1px solid #ddd; background: #fff; color: #555; border-radius: 4px; font-size: 13px; cursor: pointer;">按销量 ↓</button>
        <button onclick="togglePriceSort()" class="sort-btn" id="sortPrice" style="padding: 6px 14px; border: 1px solid #ddd; background: #fff; color: #555; border-radius: 4px; font-size: 13px; cursor: pointer;">按价格 ↕</button>
        <button onclick="setSort('created_at', 'desc')" class="sort-btn" id="sortNew" style="padding: 6px 14px; border: 1px solid #ddd; background: #fff; color: #555; border-radius: 4px; font-size: 13px; cursor: pointer;">新品优先</button>
      </div>

      <!-- 价格区间过滤 -->
      <div style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
        <span style="color: #888;">价格区间(元)：</span>
        <input type="number" id="minPriceInput" placeholder="最低" style="width: 70px; padding: 4px 6px; border: 1px solid #ddd; border-radius: 4px; font-size: 12px;">
        <span>-</span>
        <input type="number" id="maxPriceInput" placeholder="最高" style="width: 70px; padding: 4px 6px; border: 1px solid #ddd; border-radius: 4px; font-size: 12px;">
        <button onclick="applyPriceFilter()" style="padding: 4px 10px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; font-size: 12px;">确定</button>
      </div>
    </div>
  </div>

  <!-- 商品网格展示区 -->
  <div id="goodsListContainer" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px;">
    <!-- 动态通过 /api/portal/goods 异步渲染 -->
  </div>

  <!-- 分页栏 -->
  <div id="paginationBar" style="display: flex; justify-content: center; gap: 8px; margin: 30px 0;"></div>
</div>

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
    const container = document.getElementById('goodsListContainer');
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
        renderGoods(json.data.data);
        renderPagination(json.data.current_page, json.data.last_page);
      } else {
        container.innerHTML = '<div style="grid-column: span 5; text-align: center; padding: 80px; color: #999;">抱歉，未找到匹配的商品</div>';
        document.getElementById('paginationBar').innerHTML = '';
      }
    } catch (e) {
      container.innerHTML = '<div style="grid-column: span 5; text-align: center; padding: 80px; color: #e1251b;">数据加载失败，请重试</div>';
    }
  }

  function renderGoods(list) {
    const container = document.getElementById('goodsListContainer');
    container.innerHTML = list.map(item => `
      <div style="background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='none'">
        <a href="/goods/${item.id}" style="text-decoration: none; color: inherit; display: block;">
          <div style="width: 100%; height: 210px; background: #f9f9f9; display: flex; align-items: center; justify-content: center; overflow: hidden;">
            ${item.main_image ? `<img src="${item.main_image}" alt="${item.title}" style="width: 100%; height: 100%; object-fit: cover;">` : '<span style="font-size: 40px;">📦</span>'}
          </div>
          <div style="padding: 12px;">
            <div style="color: #e1251b; font-size: 18px; font-weight: bold;">
              <span style="font-size: 12px;">¥</span>${formatPrice(item.min_price)}
            </div>
            <div style="font-size: 14px; color: #333; font-weight: 500; margin: 6px 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
              ${item.title}
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 12px; color: #999;">
              <span>销量 ${item.sales_count}</span>
              ${item.is_hot ? '<span style="color: #e1251b; background: #fdf2f2; padding: 1px 4px; border-radius: 2px;">热销</span>' : ''}
            </div>
          </div>
        </a>
      </div>
    `).join('');
  }

  function renderPagination(current, last) {
    const bar = document.getElementById('paginationBar');
    if (last <= 1) {
      bar.innerHTML = '';
      return;
    }
    let html = '';
    for (let p = 1; p <= last; p++) {
      const activeStyle = p === current ? 'background: #e1251b; color: white; border-color: #e1251b;' : 'background: white; color: #555; border-color: #ddd;';
      html += `<button onclick="goPage(${p})" style="padding: 6px 12px; border: 1px solid; border-radius: 4px; cursor: pointer; ${activeStyle}">${p}</button>`;
    }
    bar.innerHTML = html;
  }

  function goPage(p) {
    currentParams.page = p;
    loadGoods();
    window.scrollTo({ top: 180, behavior: 'smooth' });
  }

  function filterByCategory(id) {
    currentParams.category_id = id || '';
    currentParams.page = 1;
    loadGoods();
  }

  function setSort(by, order) {
    currentParams.sort_by = by;
    currentParams.sort_order = order;
    currentParams.page = 1;
    loadGoods();
  }

  function togglePriceSort() {
    priceOrder = priceOrder === 'asc' ? 'desc' : 'asc';
    setSort('price', priceOrder);
    document.getElementById('sortPrice').innerText = priceOrder === 'asc' ? '价格 ↑' : '价格 ↓';
  }

  function applyPriceFilter() {
    const min = document.getElementById('minPriceInput').value;
    const max = document.getElementById('maxPriceInput').value;
    currentParams.min_price = min ? Math.round(Number(min) * 100) : '';
    currentParams.max_price = max ? Math.round(Number(max) * 100) : '';
    currentParams.page = 1;
    loadGoods();
  }

  document.addEventListener('DOMContentLoaded', loadGoods);
</script>
@endpush
@endsection
