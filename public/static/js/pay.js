/**
 * 统一收银台交互逻辑 (pay.js)
 */

document.addEventListener('DOMContentLoaded', () => {
  initPayCountdown();
  initOrderDetailToggle();
  initPayMethodTabs();
  initSelectionOptions();
  initDoPayAction();
});

function initPayCountdown() {
  const cdEl = document.getElementById('payCountdown');
  if (!cdEl) return;

  let totalSeconds = 29 * 60 + 59;
  const timer = setInterval(() => {
    totalSeconds--;
    if (totalSeconds <= 0) {
      clearInterval(timer);
      cdEl.textContent = '00分00秒 (已超时)';
      showToast('订单支付已超时，请重新下单');
      return;
    }
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    cdEl.textContent = `${String(minutes).padStart(2, '0')}分${String(seconds).padStart(2, '0')}秒`;
  }, 1000);
}

function initOrderDetailToggle() {
  const btn = document.getElementById('btnToggleDetail');
  const drawer = document.getElementById('orderDrawer');

  btn?.addEventListener('click', () => {
    if (drawer?.classList.contains('show')) {
      drawer.classList.remove('show');
      btn.textContent = '订单详情 ▾';
    } else {
      drawer?.classList.add('show');
      btn.textContent = '收起详情 ▴';
    }
  });
}

function initPayMethodTabs() {
  const tabs = document.querySelectorAll('.method-tab');
  const panels = document.querySelectorAll('.method-panel');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const targetId = tab.getAttribute('data-tab');
      tabs.forEach(t => t.classList.remove('active'));
      panels.forEach(p => p.classList.remove('active'));

      tab.classList.add('active');
      const targetPanel = document.getElementById(targetId);
      if (targetPanel) {
        targetPanel.classList.add('active');
      }
    });
  });
}

function initSelectionOptions() {
  // 白条分期卡片切换
  const instCards = document.querySelectorAll('.inst-card');
  instCards.forEach(card => {
    card.addEventListener('click', () => {
      instCards.forEach(c => c.classList.remove('active'));
      card.classList.add('active');
    });
  });

  // 银行卡切换
  const bankRows = document.querySelectorAll('.bank-card-row');
  bankRows.forEach(row => {
    row.addEventListener('click', () => {
      bankRows.forEach(r => r.classList.remove('active'));
      row.classList.add('active');
    });
  });

  // 上传对公转账凭证
  const btnUpload = document.getElementById('btnUploadVoucher');
  btnUpload?.addEventListener('click', () => {
    showToast('已模拟上传并提交「电子银行转账回单.pdf」，等待财务自动对账');
  });
}

function initDoPayAction() {
  const btnDoPay = document.getElementById('btnDoPay');
  const modal = document.getElementById('paySuccessModal');

  btnDoPay?.addEventListener('click', () => {
    btnDoPay.textContent = '正在安全支付中...';
    btnDoPay.disabled = true;

    setTimeout(() => {
      if (modal) modal.classList.add('show');
    }, 800);
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
