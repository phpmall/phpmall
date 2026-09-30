/**
 * PLUS 会员俱乐部交互逻辑 (plus.js)
 */

document.addEventListener('DOMContentLoaded', () => {
  initPlusActions();
});

function initPlusActions() {
  const btnRenew = document.getElementById('btnRenew');
  const buyBtns = document.querySelectorAll('.btn-plus-buy');
  const privItems = document.querySelectorAll('.priv-item');

  btnRenew?.addEventListener('click', () => {
    showToast('PLUS 年卡续费成功！特权有效期已顺延 1 年至 2028-09-28');
  });

  buyBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const name = btn.getAttribute('data-name') || 'PLUS专享商品';
      showToast(`已成功将「${name}」以 PLUS 会员专属特价加入购物车！`);
      setTimeout(() => {
        window.location.href = 'cart.html';
      }, 1000);
    });
  });

  privItems.forEach(item => {
    item.addEventListener('click', () => {
      const title = item.querySelector('h4')?.textContent || '特权';
      showToast(`【${title}】为 PLUS 正式会员专属尊享权益，当前已全额激活`);
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
