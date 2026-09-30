@extends('portal.layouts.portal')

@section('title', '我的购物车 - 优品商城')

@section('content')
<div class="w" style="margin-top: 20px;">
  <div style="background: #fff; border-radius: 8px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
    <h2 style="font-size: 20px; font-weight: bold; margin: 0 0 20px; color: #333;">
      全部商品 (<span id="cartTotalQty">0</span>)
    </h2>

    <!-- 表头 -->
    <div style="display: flex; align-items: center; background: #f7f7f7; padding: 12px 20px; font-size: 13px; color: #666; border-radius: 4px; margin-bottom: 15px;">
      <div style="width: 80px;"><label><input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)"> 全选</label></div>
      <div style="flex: 1;">商品信息</div>
      <div style="width: 120px; text-align: center;">单价</div>
      <div style="width: 140px; text-align: center;">数量</div>
      <div style="width: 120px; text-align: center;">小计</div>
      <div style="width: 80px; text-align: center;">操作</div>
    </div>

    <!-- 列表项容器 -->
    <div id="cartItemsList">
      <div style="text-align: center; padding: 50px; color: #888;">正在加载购物车...</div>
    </div>

    <!-- 底部结算栏 -->
    <div style="display: flex; justify-content: space-between; align-items: center; background: #fafafa; border: 1px solid #eee; border-radius: 6px; padding: 12px 20px; margin-top: 25px;">
      <div style="display: flex; gap: 20px; align-items: center; font-size: 13px;">
        <label><input type="checkbox" id="bottomSelectAll" onchange="toggleSelectAll(this)"> 全选</label>
        <a href="javascript:;" onclick="deleteSelected()" style="color: #666; text-decoration: none;">删除选中的商品</a>
      </div>

      <div style="display: flex; align-items: center; gap: 25px;">
        <div style="font-size: 14px; color: #666;">
          已选择 <strong style="color: #e1251b;" id="selectedQty">0</strong> 件商品，
          总价：<strong style="color: #e1251b; font-size: 24px;">¥<span id="selectedAmount">0.00</span></strong>
        </div>
        <button onclick="goCheckout()" id="checkoutBtn" style="height: 44px; padding: 0 35px; background: #e1251b; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer;">
          去结算 ›
        </button>
      </div>
    </div>
  </div>
</div>

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
        document.getElementById('cartItemsList').innerHTML = '<div style="text-align: center; padding: 50px; color: #999;">购物车空空如也，快去选购吧！</div>';
      }
    } catch (e) {
      document.getElementById('cartItemsList').innerHTML = '<div style="text-align: center; padding: 50px; color: #e1251b;">加载失败，请检查网络或刷新</div>';
    }
  }

  function renderCart() {
    const list = document.getElementById('cartItemsList');
    document.getElementById('cartTotalQty').innerText = cartData.total_quantity;
    document.getElementById('selectedQty').innerText = cartData.selected_quantity;
    document.getElementById('selectedAmount').innerText = formatPrice(cartData.selected_amount);

    const allSelected = cartData.items.length > 0 && cartData.items.every(i => i.is_selected);
    document.getElementById('selectAllCheckbox').checked = allSelected;
    document.getElementById('bottomSelectAll').checked = allSelected;

    if (!cartData.items || cartData.items.length === 0) {
      list.innerHTML = '<div style="text-align: center; padding: 60px; color: #999;"><span style="font-size: 48px;">🛒</span><p>购物车还是空的，去挑挑喜欢的商品吧！</p><a href="/goods" style="display: inline-block; margin-top: 10px; padding: 8px 24px; background: #e1251b; color: #fff; text-decoration: none; border-radius: 4px;">去选购 ›</a></div>';
      document.getElementById('checkoutBtn').disabled = true;
      document.getElementById('checkoutBtn').style.opacity = 0.5;
      return;
    }

    document.getElementById('checkoutBtn').disabled = cartData.selected_quantity === 0;
    document.getElementById('checkoutBtn').style.opacity = cartData.selected_quantity === 0 ? 0.5 : 1;

    list.innerHTML = cartData.items.map(item => {
      let specStr = '';
      if (item.sku_specs) {
        const specs = typeof item.sku_specs === 'string' ? JSON.parse(item.sku_specs) : item.sku_specs;
        specStr = Object.values(specs).join(' / ');
      }

      return `
        <div style="display: flex; align-items: center; padding: 18px 20px; border-bottom: 1px solid #f0f0f0; transition: background 0.2s;" onmouseover="this.style.background='#fdfdfd'" onmouseout="this.style.background='transparent'">
          <div style="width: 80px;">
            <input type="checkbox" ${item.is_selected ? 'checked' : ''} onchange="toggleItemSelect(${item.id}, this.checked)">
          </div>
          <div style="flex: 1; display: flex; gap: 15px; align-items: center;">
            <img src="${item.product_image || '/images/default.jpg'}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
            <div>
              <div style="font-size: 14px; color: #333; font-weight: 500; margin-bottom: 6px;">${item.product_title}</div>
              <div style="font-size: 12px; color: #999;">${specStr}</div>
            </div>
          </div>
          <div style="width: 120px; text-align: center; color: #444; font-size: 14px;">
            ¥${formatPrice(item.price)}
          </div>
          <div style="width: 140px; text-align: center;">
            <div style="display: inline-flex; border: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
              <button onclick="updateQty(${item.id}, ${item.quantity - 1})" style="width: 28px; height: 28px; border: none; background: #f5f5f5; cursor: pointer;">-</button>
              <input type="text" value="${item.quantity}" readonly style="width: 40px; height: 28px; text-align: center; border: none; border-left: 1px solid #ddd; border-right: 1px solid #ddd; font-size: 13px;">
              <button onclick="updateQty(${item.id}, ${item.quantity + 1})" style="width: 28px; height: 28px; border: none; background: #f5f5f5; cursor: pointer;">+</button>
            </div>
          </div>
          <div style="width: 120px; text-align: center; color: #e1251b; font-weight: bold; font-size: 15px;">
            ¥${formatPrice(item.subtotal)}
          </div>
          <div style="width: 80px; text-align: center;">
            <a href="javascript:;" onclick="removeItem(${item.id})" style="color: #999; text-decoration: none; font-size: 13px;" onmouseover="this.style.color='#e1251b'" onmouseout="this.style.color='#999'">删除</a>
          </div>
        </div>
      `;
    }).join('');
  }

  async function updateQty(cartId, qty) {
    if (qty < 1) return;
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
        showToast(json.message || '更新失败');
      }
    } catch (e) {
      showToast('网络错误');
    }
  }

  async function toggleItemSelect(cartId, isSelected) {
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
    } catch (e) {}
  }

  async function toggleSelectAll(checkbox) {
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
    } catch (e) {}
  }

  async function removeItem(cartId) {
    if (!confirm('确定从购物车移除该商品？')) return;
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
      loadCart();
      refreshCartBadge();
    } catch (e) {}
  }

  async function deleteSelected() {
    const selectedIds = cartData.items.filter(i => i.is_selected).map(i => i.id);
    if (selectedIds.length === 0) {
      showToast('请先勾选要删除的商品');
      return;
    }
    if (!confirm(`确定删除已选中的 ${selectedIds.length} 件商品？`)) return;

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
      loadCart();
      refreshCartBadge();
    } catch (e) {}
  }

  function goCheckout() {
    window.location.href = '/checkout';
  }

  document.addEventListener('DOMContentLoaded', loadCart);
</script>
@endpush
@endsection
