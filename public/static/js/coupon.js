/**
 * 京东领券中心交互脚本
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Toast 工具函数
  const toastEl = document.getElementById('toast');
  let toastTimer = null;
  function showToast(msg, duration = 2500) {
    if (!toastEl) return;
    toastEl.innerText = msg;
    toastEl.className = 'toast show';
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      toastEl.className = 'toast';
    }, duration);
  }

  // 2. 分类 Tab 切换
  const tabs = document.querySelectorAll('.c-cat-tab');
  const cards = document.querySelectorAll('.coupon-card');

  tabs.forEach((tab) => {
    tab.addEventListener('click', () => {
      tabs.forEach((t) => t.classList.remove('active'));
      tab.classList.add('active');

      const cat = tab.getAttribute('data-cat');
      cards.forEach((card) => {
        const cardCat = card.getAttribute('data-cat');
        if (cat === 'all' || cardCat === cat) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  // 3. 领券交互委托
  const couponGrid = document.getElementById('couponGrid');
  if (couponGrid) {
    couponGrid.addEventListener('click', (e) => {
      const btn = e.target.closest('.btn-get-coupon');
      if (!btn || btn.disabled) return;

      if (btn.classList.contains('received')) {
        // 已领过去使用
        window.location.href = 'list.html';
        return;
      }

      const title = btn.getAttribute('data-title') || '专享优惠券';
      btn.innerText = '✓ 去使用 ›';
      btn.classList.add('received');

      showToast(`🎉 恭喜领取成功！【${title}】已存入您的账户，结算时自动立减`);
    });
  }
});
