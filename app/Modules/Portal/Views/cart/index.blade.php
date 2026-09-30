@extends('portal::layouts.portal')

@section('title', '我的购物车 - 优品商城')

@push('styles')
  <link rel="stylesheet" href="{{ asset('static/css/cart.css') }}">
@endpush

@section('content')
<main class="w cart-main-content" style="margin-top: 20px;">
  <!-- 顶部状态选项卡 -->
  <div class="cart-tabs-bar">
    <div class="tab-item active">全部商品 <span class="tab-num" id="totalTabNum">0</span></div>
    <div class="deliver-tip">
      <span>配送至：<strong>北京市朝阳区</strong></span>
    </div>
  </div>

  <!-- 购物车表格列表 -->
  <div class="cart-table">
    <!-- 表头 -->
    <div class="cart-thead">
      <div class="th-chk">
        <label class="custom-checkbox">
          <input type="checkbox" id="selectAllTop" onchange="toggleSelectAll(this)">
          <span class="chk-box"></span>
          <span>全选</span>
        </label>
      </div>
      <div class="th-goods">商品清单</div>
      <div class="th-props">属性配置</div>
      <div class="th-price">单价</div>
      <div class="th-quantity">数量</div>
      <div class="th-sum">小计</div>
      <div class="th-ops">操作</div>
    </div>

    <!-- 店铺分组容器 -->
    <div class="shop-group" id="cartShopGroup">
      <div class="shop-title-bar">
        <span class="shop-name"><span class="badge-zy">自营</span> 优品商城自营旗舰店</span>
        <span class="free-shipping-tag">已免运费</span>
      </div>

      <!-- 商品行容器 -->
      <div id="cartItemsList">
        <div style="text-align: center; padding: 60px; color: #888;">正在加载购物车...</div>
      </div>
    </div>
  </div>

  <!-- 吸底结算工具条 (Sticky Float Bar) -->
  <div class="cart-floatbar" id="cartFloatBar" style="margin-top: 20px;">
    <div class="floatbar-left">
      <label class="custom-checkbox">
        <input type="checkbox" id="selectAllBottom" onchange="toggleSelectAll(this)">
        <span class="chk-box"></span>
        <span>全选</span>
      </label>
      <button class="bar-link-btn" id="batchDeleteBtn" onclick="deleteSelected()">删除选中的商品</button>
    </div>
    <div class="floatbar-right">
      <div class="selected-amount">
        已选择 <strong class="hl-count" id="selectedCount">0</strong> 件商品
      </div>
      <div class="total-price-box">
        <div class="total-row">
          <span class="txt">总价（不含运费）：</span>
          <strong class="price-val" id="totalAmount">¥0.00</strong>
        </div>
      </div>
      <a href="javascript:;" onclick="goCheckout()" class="btn-checkout" id="btnCheckout">去结算</a>
    </div>
  </div>
</main>

@push('scripts')
<script>
  let cartData = null;

  async function loadCart() {
    try {
      const res = await fetch('/api/portal/cart', {
        headers: { 'Accept': 'application/json' }
      });
      const json = await res.json();

      if (json.code === 0 && json.data) {
        cartData = json.data;
        renderCart();
      } else {
        document.getElementById('cartItemsList').innerHTML = '<div style="text-align: center; padding: 60px; color: #999;">购物车空空如也，快去选购吧！</div>';
      }
    } catch (e) {
      document.getElementById('cartItemsList').innerHTML = '<div style="text-align: center; padding: 60px; color: #e1251b;">加载失败，请检查网络或刷新</div>';
    }
  }

  function renderCart() {
    const list = document.getElementById('cartItemsList');
    document.getElementById('totalTabNum').innerText = cartData.total_quantity;
    document.getElementById('selectedCount').innerText = cartData.selected_quantity;
    document.getElementById('totalAmount').innerText = '¥' + formatPrice(cartData.selected_amount);

    const allSelected = cartData.items.length > 0 && cartData.items.every(i => i.is_selected);
    document.getElementById('selectAllTop').checked = allSelected;
    document.getElementById('selectAllBottom').checked = allSelected;

    if (!cartData.items || cartData.items.length === 0) {
      list.innerHTML = '<div style="text-align: center; padding: 80px; color: #999;"><span style="font-size: 48px;">🛒</span><p style="margin: 15px 0;">购物车还是空的，去挑选喜欢的商品吧！</p><a href="/goods" style="display: inline-block; padding: 8px 24px; background: #e1251b; color: #fff; text-decoration: none; border-radius: 4px;">去选购好物 ›</a></div>';
      document.getElementById('btnCheckout').style.pointerEvents = 'none';
      document.getElementById('btnCheckout').style.opacity = '0.5';
      return;
    }

    const canCheckout = cartData.selected_quantity > 0;
    document.getElementById('btnCheckout').style.pointerEvents = canCheckout ? 'auto' : 'none';
    document.getElementById('btnCheckout').style.opacity = canCheckout ? '1' : '0.5';

    list.innerHTML = cartData.items.map(item => {
      let specStr = '';
      if (item.sku_specs) {
        const specs = typeof item.sku_specs === 'string' ? JSON.parse(item.sku_specs) : item.sku_specs;
        specStr = Object.entries(specs).map(([k, v]) => `${k}：${v}`).join('<br>');
      }

      return `
        <div class="cart-row" data-id="${item.id}">
          <div class="cell-chk">
            <label class="custom-checkbox">
              <input type="checkbox" class="item-chk" ${item.is_selected ? 'checked' : ''} onchange="toggleItemSelect(${item.id}, this.checked)">
              <span class="chk-box"></span>
            </label>
          </div>
          <div class="cell-goods">
            <a href="/goods/${item.product_id}" class="goods-img">
              <img src="${item.product_image || '/images/default.jpg'}" alt="${item.product_title}">
            </a>
            <div class="goods-detail">
              <a href="/goods/${item.product_id}" class="title">${item.product_title}</a>
              <p class="service-tags">
                <span class="tag-zy">商城自营</span>
                <span class="tag-safe">正品保障</span>
              </p>
            </div>
          </div>
          <div class="cell-props">
            <p>${specStr || '默认规格'}</p>
          </div>
          <div class="cell-price">
            <p class="cur-price">¥${formatPrice(item.price)}</p>
          </div>
          <div class="cell-quantity">
            <div class="stepper">
              <button class="step-btn btn-minus" onclick="updateQty(${item.id}, ${item.quantity - 1})">-</button>
              <input type="text" class="step-val" value="${item.quantity}" readonly>
              <button class="step-btn btn-plus" onclick="updateQty(${item.id}, ${item.quantity + 1})">+</button>
            </div>
            <p class="stock-tip">${item.stock > 0 ? '有货' : '缺货'}</p>
          </div>
          <div class="cell-sum">
            <strong class="sum-price">¥${formatPrice(item.subtotal)}</strong>
          </div>
          <div class="cell-ops">
            <button class="btn-op btn-delete" onclick="removeItem(${item.id})">删除</button>
          </div>
        </div>
      `;
    }).join('');
  }

  async function updateQty(cartId, qty) {
    if (qty < 1) return;
    const loadIdx = showLoading();
    try {
      const res = await fetch(`/api/portal/cart/${cartId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity: qty })
      });
      const json = await res.json();
      if (json.code === 0) {
        loadCart();
        refreshCartBadge();
      } else {
        showToast(json.message || '更新失败', 2);
      }
    } catch (e) {
      showToast('网络错误', 2);
    } finally {
      closeLoading(loadIdx);
    }
  }

  async function toggleItemSelect(cartId, isSelected) {
    const loadIdx = showLoading();
    try {
      await fetch('/api/portal/cart/select', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ cart_ids: [cartId], is_selected: isSelected })
      });
      loadCart();
    } catch (e) {
    } finally {
      closeLoading(loadIdx);
    }
  }

  async function toggleSelectAll(checkbox) {
    const loadIdx = showLoading();
    try {
      await fetch('/api/portal/cart/select', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ is_selected: checkbox.checked })
      });
      loadCart();
    } catch (e) {
    } finally {
      closeLoading(loadIdx);
    }
  }

  function removeItem(cartId) {
    const doRemove = async () => {
      const loadIdx = showLoading();
      try {
        await fetch('/api/portal/cart/remove', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ cart_ids: [cartId] })
        });
        showToast('商品已从购物车移除', 1);
        loadCart();
        refreshCartBadge();
      } catch (e) {
        showToast('操作失败', 2);
      } finally {
        closeLoading(loadIdx);
      }
    };

    if (window.layer) {
      layer.confirm('确定从购物车移除该商品？', { icon: 3, title: '提示' }, function (index) {
        layer.close(index);
        doRemove();
      });
    } else if (confirm('确定从购物车移除该商品？')) {
      doRemove();
    }
  }

  function deleteSelected() {
    const selectedIds = cartData.items.filter(i => i.is_selected).map(i => i.id);
    if (selectedIds.length === 0) {
      showToast('请先勾选要删除的商品', 0);
      return;
    }

    const doDelete = async () => {
      const loadIdx = showLoading();
      try {
        await fetch('/api/portal/cart/remove', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ cart_ids: selectedIds })
        });
        showToast(`已删除选中的 ${selectedIds.length} 件商品`, 1);
        loadCart();
        refreshCartBadge();
      } catch (e) {
        showToast('操作失败', 2);
      } finally {
        closeLoading(loadIdx);
      }
    };

    if (window.layer) {
      layer.confirm(`确定删除已选中的 ${selectedIds.length} 件商品？`, { icon: 3, title: '提示' }, function (index) {
        layer.close(index);
        doDelete();
      });
    } else if (confirm(`确定删除已选中的 ${selectedIds.length} 件商品？`)) {
      doDelete();
    }
  }

  function goCheckout() {
    window.location.href = '/checkout';
  }

  document.addEventListener('DOMContentLoaded', loadCart);
</script>
@endpush
@endsection
