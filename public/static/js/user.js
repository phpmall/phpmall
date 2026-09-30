/**
 * 个人中心与订单管理交互逻辑 (user.js)
 */

document.addEventListener('DOMContentLoaded', () => {
  initOrderTabsFilter();
  initLogisticsModal();
  initOrderActions();
});

function initOrderTabsFilter() {
  const tabBtns = document.querySelectorAll('.order-tab-btn');
  const orderCards = document.querySelectorAll('.order-card-item');

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      tabBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.getAttribute('data-filter');
      orderCards.forEach(card => {
        const status = card.getAttribute('data-status');
        if (filter === 'all' || status === filter) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });
}

function initLogisticsModal() {
  const modal = document.getElementById('logisticsModal');
  const closeBtn = document.getElementById('modalCloseBtn');
  const trackBtns = document.querySelectorAll('.btn-track-logistics');
  const snSpan = document.getElementById('trackOrderSn');

  trackBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const sn = btn.getAttribute('data-sn') || 'JD2026092889104';
      if (snSpan) snSpan.textContent = sn;
      if (modal) modal.classList.add('show');
    });
  });

  closeBtn?.addEventListener('click', () => {
    modal?.classList.remove('show');
  });

  modal?.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.remove('show');
    }
  });
}

function initOrderActions() {
  const confirmBtns = document.querySelectorAll('.btn-confirm-receive');
  confirmBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      if (confirm('确认已收到商品且完好无损吗？')) {
        btn.textContent = '已收货';
        btn.disabled = true;
        btn.style.backgroundColor = '#4caf50';
        showToast('交易完成！感谢您在京东购物。');
      }
    });
  });

  const buyAgainBtns = document.querySelectorAll('.btn-buy-again');
  buyAgainBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      showToast('已为您将订单中商品再次加入购物车！');
      setTimeout(() => {
        window.location.href = 'cart.html';
      }, 1000);
    });
  });

  const commentBtns = document.querySelectorAll('.btn-to-comment');
  commentBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      showToast('已开启评价通道，感谢您的晒单分享！+20京豆已发放。');
      btn.textContent = '已评价';
      btn.disabled = true;
      btn.style.backgroundColor = '#999';
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
