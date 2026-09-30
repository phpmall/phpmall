/**
 * 商家入驻门户交互逻辑 (settle.js)
 */

document.addEventListener('DOMContentLoaded', () => {
  initApplyModal();
});

function initApplyModal() {
  const modal = document.getElementById('applyModal');
  const btnOpen1 = document.getElementById('btnOpenApplyModal');
  const btnOpen2 = document.getElementById('btnHeroSettle');
  const btnClose = document.getElementById('modalCloseBtn');
  const form = document.getElementById('applyForm');

  function openModal() {
    if (modal) modal.classList.add('show');
  }

  function closeModal() {
    if (modal) modal.classList.remove('show');
  }

  btnOpen1?.addEventListener('click', openModal);
  btnOpen2?.addEventListener('click', openModal);
  btnClose?.addEventListener('click', closeModal);

  modal?.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  form?.addEventListener('submit', (e) => {
    e.preventDefault();
    const company = document.getElementById('companyName')?.value.trim();
    const store = document.getElementById('targetStoreName')?.value.trim();

    showToast(`恭喜！「${company}」开店资质已通过智能极速初审，正在为您创建「${store}」...`);
    closeModal();

    setTimeout(() => {
      window.location.href = '../seller/index.html';
    }, 1500);
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
