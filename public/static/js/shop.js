/**
 * 品牌旗舰店交互逻辑 (shop.js)
 */

document.addEventListener('DOMContentLoaded', () => {
  initShopHeader();
  initShopCoupons();
  initShopFilter();
  initShopAddToCart();
});

function initShopHeader() {
  const btnFollow = document.getElementById('btnShopFollow');
  const btnIm = document.getElementById('btnShopIm');
  const btnSearch = document.getElementById('btnShopSearch');
  const searchInput = document.getElementById('shopSearchInput');

  btnFollow?.addEventListener('click', () => {
    if (btnFollow.classList.contains('followed')) {
      btnFollow.classList.remove('followed');
      btnFollow.innerHTML = '<span class="icon">♥</span> <span class="txt">关注店铺</span>';
      showToast('已取消关注该店铺');
    } else {
      btnFollow.classList.add('followed');
      btnFollow.innerHTML = '<span class="icon">✔</span> <span class="txt">已关注</span>';
      showToast('关注成功！您将第一时间获悉该店铺新品与降价动态。');
    }
  });

  btnIm?.addEventListener('click', () => {
    showToast('正在为您连线「Apple自营官方旗舰店」专属在线客服...');
  });

  function doShopSearch() {
    const kw = searchInput?.value.trim();
    if (kw) {
      showToast(`正在为您筛选店内关于「${kw}」的商品...`);
    } else {
      showToast('请输入要在本店搜索的商品关键词');
    }
  }

  btnSearch?.addEventListener('click', doShopSearch);
  searchInput?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') doShopSearch();
  });
}

function initShopCoupons() {
  const couponBtns = document.querySelectorAll('.btn-get-coupon');
  couponBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      btn.textContent = '已领取';
      btn.style.backgroundColor = '#999';
      btn.disabled = true;
      showToast('店铺专享优惠券领取成功！结算时自动抵扣。');
    });
  });
}

function initShopFilter() {
  const filterBtns = document.querySelectorAll('.filter-left .f-btn');
  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      showToast(`已按「${btn.textContent.trim()}」更新店内陈列`);
    });
  });
}

function initShopAddToCart() {
  const addCartBtns = document.querySelectorAll('.btn-shop-add-cart');
  addCartBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const name = btn.getAttribute('data-name') || '商品';
      btn.textContent = '已加入 ✔';
      btn.style.backgroundColor = '#4caf50';
      btn.style.borderColor = '#4caf50';
      btn.style.color = '#fff';
      setTimeout(() => {
        btn.textContent = '加入购物车';
        btn.style.backgroundColor = '#fdf1f1';
        btn.style.borderColor = '#e1251b';
        btn.style.color = '#e1251b';
      }, 1500);
      showToast(`已将「${name}」成功加入购物车！`);
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
