/**
 * 商品搜索与分类列表页交互逻辑 (list.js)
 */

document.addEventListener('DOMContentLoaded', () => {
  initSelectorFilter();
  initSorting();
  initListAddToCart();
  initPagination();
});

function initSelectorFilter() {
  const selectorRows = document.querySelectorAll('.selector-row');
  selectorRows.forEach(row => {
    const links = row.querySelectorAll('.s-values a');
    links.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        links.forEach(l => l.classList.remove('active'));
        link.classList.add('active');
        showToast(`已按条件「${link.textContent.trim()}」更新商品列表`);
      });
    });
  });

  const btnPriceOk = document.querySelector('.btn-price-ok');
  btnPriceOk?.addEventListener('click', () => {
    showToast('已应用自定义价格区间筛选');
  });
}

function initSorting() {
  const sortBtns = document.querySelectorAll('.filter-sort-group .sort-btn');
  sortBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      sortBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      showToast(`已按「${btn.textContent.trim()}」重新排序`);
    });
  });

  const checkboxes = document.querySelectorAll('.filter-extra-checkboxes input[type="checkbox"]');
  checkboxes.forEach(chk => {
    chk.addEventListener('change', () => {
      showToast('筛选条件已更新');
    });
  });
}

function initListAddToCart() {
  const addCartBtns = document.querySelectorAll('.btn-list-add-cart');
  const cartCountEl = document.querySelector('.header-cart .cart-count');

  addCartBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const name = btn.getAttribute('data-name') || '所选商品';
      if (cartCountEl) {
        let count = parseInt(cartCountEl.textContent, 10) || 0;
        count++;
        cartCountEl.textContent = count;
        cartCountEl.style.transform = 'scale(1.3)';
        setTimeout(() => {
          cartCountEl.style.transform = 'scale(1)';
        }, 200);
      }
      showToast(`已成功将「${name}」加入购物车！`);
    });
  });
}

function initPagination() {
  const pageBtns = document.querySelectorAll('.list-pagination .page-btn:not(.disabled)');
  pageBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      pageBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      window.scrollTo({ top: 300, behavior: 'smooth' });
      showToast(`正在加载第 ${btn.textContent.trim()} 页商品...`);
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
