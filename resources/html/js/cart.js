/**
 * 购物车页面交互逻辑 (cart.js)
 */

document.addEventListener('DOMContentLoaded', () => {
  initCartCalculator();
  initGuessLike();
});

function initCartCalculator() {
  const selectAllTop = document.getElementById('selectAllTop');
  const selectAllBottom = document.getElementById('selectAllBottom');
  const shopChks = document.querySelectorAll('.shop-chk');
  const itemChks = () => document.querySelectorAll('.item-chk');
  const cartRows = () => document.querySelectorAll('.cart-row');
  const selectedCountEl = document.getElementById('selectedCount');
  const totalAmountEl = document.getElementById('totalAmount');
  const totalTabNumEl = document.getElementById('totalTabNum');
  const batchDeleteBtn = document.getElementById('batchDeleteBtn');

  // 计算总金额和总件数
  function recalculate() {
    let totalCount = 0;
    let totalPrice = 0;
    const rows = cartRows();

    rows.forEach(row => {
      const chk = row.querySelector('.item-chk');
      const unitPrice = parseFloat(row.getAttribute('data-price') || 0);
      const stepVal = parseInt(row.querySelector('.step-val')?.value || 1, 10);
      const sumEl = row.querySelector('.sum-price');

      // 更新单行小计
      const rowSum = unitPrice * stepVal;
      if (sumEl) sumEl.textContent = `¥${rowSum.toFixed(2)}`;

      if (chk && chk.checked) {
        totalCount += stepVal;
        totalPrice += rowSum;
        row.classList.add('selected');
      } else {
        row.classList.remove('selected');
      }
    });

    // 满减优惠计算 (每满300减50，模拟减50)
    let finalPrice = totalPrice > 300 ? totalPrice - 50 : totalPrice;
    if (finalPrice < 0) finalPrice = 0;

    if (selectedCountEl) selectedCountEl.textContent = totalCount;
    if (totalAmountEl) totalAmountEl.textContent = `¥${finalPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    if (totalTabNumEl) totalTabNumEl.textContent = rows.length;

    // 检查是否所有单选都被勾选
    const chkList = Array.from(itemChks());
    const isAllChecked = chkList.length > 0 && chkList.every(c => c.checked);
    if (selectAllTop) selectAllTop.checked = isAllChecked;
    if (selectAllBottom) selectAllBottom.checked = isAllChecked;
    shopChks.forEach(s => s.checked = isAllChecked);
  }

  // 全选切换
  function toggleAll(checked) {
    if (selectAllTop) selectAllTop.checked = checked;
    if (selectAllBottom) selectAllBottom.checked = checked;
    shopChks.forEach(s => s.checked = checked);
    itemChks().forEach(item => item.checked = checked);
    recalculate();
  }

  selectAllTop?.addEventListener('change', (e) => toggleAll(e.target.checked));
  selectAllBottom?.addEventListener('change', (e) => toggleAll(e.target.checked));
  shopChks.forEach(s => s.addEventListener('change', (e) => toggleAll(e.target.checked)));

  // 事件委托：行内加减、单选、删除、关注
  const cartTable = document.querySelector('.cart-table');
  cartTable?.addEventListener('click', (e) => {
    const target = e.target;
    const row = target.closest('.cart-row');
    if (!row) return;

    // 数量加
    if (target.classList.contains('btn-plus')) {
      const input = row.querySelector('.step-val');
      let val = parseInt(input.value, 10) || 1;
      if (val < 99) val++;
      input.value = val;
      recalculate();
    }

    // 数量减
    if (target.classList.contains('btn-minus')) {
      const input = row.querySelector('.step-val');
      let val = parseInt(input.value, 10) || 1;
      if (val > 1) {
        val--;
        input.value = val;
        recalculate();
      }
    }

    // 单项勾选
    if (target.classList.contains('item-chk')) {
      recalculate();
    }

    // 删除
    if (target.classList.contains('btn-delete')) {
      const title = row.querySelector('.goods-detail .title')?.textContent || '该商品';
      if (confirm(`确认要从购物车中移除「${title.slice(0, 20)}...」吗？`)) {
        row.remove();
        recalculate();
        showToast('商品已从购物车删除');
      }
    }

    // 移到关注
    if (target.classList.contains('btn-fav')) {
      showToast('已成功移入「我的关注」，随时可找回！');
    }
  });

  // 批量删除选中的商品
  batchDeleteBtn?.addEventListener('click', () => {
    const selectedRows = Array.from(cartRows()).filter(r => r.querySelector('.item-chk')?.checked);
    if (!selectedRows.length) {
      showToast('请至少勾选一件要删除的商品');
      return;
    }
    if (confirm(`确定要删除选中的 ${selectedRows.length} 件商品吗？`)) {
      selectedRows.forEach(r => r.remove());
      recalculate();
      showToast(`已成功删除选中的 ${selectedRows.length} 件商品`);
    }
  });

  recalculate();
}

function initGuessLike() {
  const guessBtns = document.querySelectorAll('.btn-add-guess');
  guessBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      btn.textContent = '已加购 ✔';
      btn.style.borderColor = '#4caf50';
      btn.style.color = '#4caf50';
      setTimeout(() => {
        btn.textContent = '加入购物车';
        btn.style.borderColor = '#ddd';
        btn.style.color = '#666';
      }, 1500);
      showToast('已成功为您添加到购物车！');
    });
  });
}

function showToast(message) {
  const toast = document.getElementById('toast');
  if (!toast) return;
  toast.textContent = message;
  toast.classList.add('show');
  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => {
    toast.classList.remove('show');
  }, 2200);
}
