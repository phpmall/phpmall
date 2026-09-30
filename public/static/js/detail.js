/**
 * 商品详情页交互逻辑 (detail.js)
 */

document.addEventListener('DOMContentLoaded', () => {
  initGalleryAndZoom();
  initSkuSelector();
  initStepper();
  initDetailTabs();
  initActions();
});

/* ================= 1. 画廊与放大镜 (Gallery & Magnifier) ================= */
function initGalleryAndZoom() {
  const mainBox = document.getElementById('mainImgBox');
  const mainImg = document.getElementById('mainImg');
  const lens = document.getElementById('zoomLens');
  const viewer = document.getElementById('zoomViewer');
  const largeImg = document.getElementById('zoomLargeImg');
  const thumbs = document.querySelectorAll('#thumbList li');

  if (!mainBox || !mainImg || !lens || !viewer || !largeImg) return;

  // 缩略图切换
  thumbs.forEach(thumb => {
    thumb.addEventListener('mouseenter', () => {
      thumbs.forEach(t => t.classList.remove('active'));
      thumb.classList.add('active');
      const newSrc = thumb.getAttribute('data-src');
      mainImg.src = newSrc;
      largeImg.src = newSrc.replace('w=800', 'w=1600');
    });
  });

  // 放大镜移动
  mainBox.addEventListener('mouseenter', () => {
    lens.style.display = 'block';
    viewer.style.display = 'block';
  });

  mainBox.addEventListener('mouseleave', () => {
    lens.style.display = 'none';
    viewer.style.display = 'none';
  });

  mainBox.addEventListener('mousemove', (e) => {
    const rect = mainBox.getBoundingClientRect();
    let x = e.clientX - rect.left - lens.offsetWidth / 2;
    let y = e.clientY - rect.top - lens.offsetHeight / 2;

    const maxX = mainBox.offsetWidth - lens.offsetWidth;
    const maxY = mainBox.offsetHeight - lens.offsetHeight;

    if (x < 0) x = 0;
    if (y < 0) y = 0;
    if (x > maxX) x = maxX;
    if (y > maxY) y = maxY;

    lens.style.left = `${x}px`;
    lens.style.top = `${y}px`;

    // 计算放大比例
    const ratioX = (largeImg.offsetWidth - viewer.offsetWidth) / maxX;
    const ratioY = (largeImg.offsetHeight - viewer.offsetHeight) / maxY;

    largeImg.style.left = `${-x * ratioX}px`;
    largeImg.style.top = `${-y * ratioY}px`;
  });
}

/* ================= 2. SKU 规格选择与价格联动 ================= */
function initSkuSelector() {
  const colorBtns = document.querySelectorAll('#colorOptions .opt-btn');
  const capacityBtns = document.querySelectorAll('#capacityOptions .opt-btn');
  const serviceBtns = document.querySelectorAll('#serviceOptions .opt-btn');
  const skuPrice = document.getElementById('skuPrice');
  const marketPrice = document.getElementById('marketPrice');

  function bindOptions(btns, callback) {
    btns.forEach(btn => {
      btn.addEventListener('click', () => {
        btns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        if (callback) callback(btn);
      });
    });
  }

  // 颜色选择
  bindOptions(colorBtns, (btn) => {
    const color = btn.getAttribute('data-color');
    showToast(`已切换为：${color}`);
  });

  // 容量切换联动价格
  bindOptions(capacityBtns, (btn) => {
    const price = btn.getAttribute('data-price');
    const market = btn.getAttribute('data-market');
    if (skuPrice) skuPrice.textContent = `${price}.00`;
    if (marketPrice) marketPrice.textContent = `¥${market}.00`;
    showToast(`已选择规格：${btn.getAttribute('data-capacity')}`);
  });

  // 增值服务
  bindOptions(serviceBtns);
}

/* ================= 3. 数量加减步进器 ================= */
function initStepper() {
  const buyNum = document.getElementById('buyNum');
  const btnPlus = document.getElementById('btnPlus');
  const btnMinus = document.getElementById('btnMinus');

  if (!buyNum || !btnPlus || !btnMinus) return;

  btnPlus.addEventListener('click', () => {
    let val = parseInt(buyNum.value, 10) || 1;
    if (val < 99) val++;
    buyNum.value = val;
  });

  btnMinus.addEventListener('click', () => {
    let val = parseInt(buyNum.value, 10) || 1;
    if (val > 1) val--;
    buyNum.value = val;
  });
}

/* ================= 4. 下方 Tab 切换 ================= */
function initDetailTabs() {
  const tabBtns = document.querySelectorAll('#detailTabHeader .tab-btn');
  const tabPanels = document.querySelectorAll('.tab-panel');

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.getAttribute('data-tab');
      tabBtns.forEach(b => b.classList.remove('active'));
      tabPanels.forEach(p => p.classList.remove('active'));

      btn.classList.add('active');
      const targetPanel = document.getElementById(targetId);
      if (targetPanel) {
        targetPanel.classList.add('active');
      }
    });
  });
}

/* ================= 5. 加购与关注交互 ================= */
function initActions() {
  const btnAddToCart = document.getElementById('btnAddToCart');
  const btnFollow = document.getElementById('btnFollow');
  const btnShare = document.getElementById('btnShare');
  const cartCount = document.getElementById('cartCount');
  const coupons = document.querySelectorAll('.c-badge');

  btnAddToCart?.addEventListener('click', () => {
    const buyNum = parseInt(document.getElementById('buyNum')?.value || 1, 10);
    let count = parseInt(cartCount?.textContent || 2, 10);
    count += buyNum;
    if (cartCount) {
      cartCount.textContent = count;
      cartCount.style.transform = 'scale(1.3)';
      setTimeout(() => {
        cartCount.style.transform = 'scale(1)';
      }, 200);
    }
    showToast(`已成功将 ${buyNum} 件商品加入购物车！`);
  });

  btnFollow?.addEventListener('click', () => {
    if (btnFollow.classList.contains('followed')) {
      btnFollow.classList.remove('followed');
      btnFollow.innerHTML = '<i class="icon">❤️</i> 关注商品 (12.8万)';
      showToast('已取消关注');
    } else {
      btnFollow.classList.add('followed');
      btnFollow.innerHTML = '<i class="icon" style="color:#e1251b;">❤️</i> 已关注';
      showToast('已成功关注该商品！');
    }
  });

  btnShare?.addEventListener('click', () => {
    showToast('商品链接已复制到剪贴板，快分享给好友吧！');
  });

  coupons.forEach(coupon => {
    coupon.addEventListener('click', () => {
      coupon.style.backgroundColor = '#e5e5e5';
      coupon.style.borderColor = '#ccc';
      coupon.style.color = '#888';
      coupon.textContent = '已领取';
      showToast(`优惠券领取成功！满减 ${coupon.getAttribute('data-amount')} 元`);
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
