/**
 * 京东企业购 (B2B) 交互脚本
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Toast 工具函数
  const toastEl = document.getElementById('toast');
  let toastTimer = null;
  function showToast(msg, duration = 2800) {
    if (!toastEl) return;
    toastEl.innerText = msg;
    toastEl.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      toastEl.classList.remove('show');
    }, duration);
  }

  // 2. 询价单表单提交
  const b2bQuoteForm = document.getElementById('b2bQuoteForm');
  if (b2bQuoteForm) {
    b2bQuoteForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const company = document.getElementById('quoteCompany').value.trim();
      const cat = document.getElementById('quoteCat').value;
      const budget = document.getElementById('quoteBudget').value.trim();
      const contact = document.getElementById('quoteContact').value.trim();

      const quoteSn = 'BJ' + new Date().getFullYear() + Math.floor(100000 + Math.random() * 900000);

      showToast(`🎉 尊敬的【${company}】，采购单已受理！单号：${quoteSn}，华北大客户总监将安排一对一专属报价对接`);
      b2bQuoteForm.reset();
    });
  }

  // 3. 顶部提报询价单按钮平滑滚动定位
  const btnTopQuote = document.getElementById('btnTopQuote');
  const quoteCard = document.querySelector('.hero-quote-card');
  if (btnTopQuote && quoteCard) {
    btnTopQuote.addEventListener('click', (e) => {
      e.preventDefault();
      quoteCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
      quoteCard.style.transition = 'box-shadow 0.3s ease, transform 0.3s ease';
      quoteCard.style.boxShadow = '0 0 20px rgba(255, 125, 0, 0.45)';
      quoteCard.style.transform = 'scale(1.02)';
      setTimeout(() => {
        quoteCard.style.boxShadow = '';
        quoteCard.style.transform = '';
      }, 1500);
    });
  }

  // 4. 场景链接平滑跳转
  const sceneLinks = document.querySelectorAll('.scene-link');
  const sceneOffice = document.getElementById('sceneOffice');
  const sceneBenefit = document.getElementById('sceneBenefit');

  sceneLinks.forEach((link) => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const cat = link.getAttribute('data-cat');
      if (cat === 'office' && sceneOffice) {
        sceneOffice.scrollIntoView({ behavior: 'smooth', block: 'start' });
      } else if (cat === 'benefit' && sceneBenefit) {
        sceneBenefit.scrollIntoView({ behavior: 'smooth', block: 'start' });
      } else if (cat === 'finance') {
        showToast('💳 京东企业金采已为您的企业预授信 ¥500,000 额度，支持最长 90 天对公免息');
      } else {
        showToast('已切换至对应企业采购品类专区');
      }
    });
  });

  // 5. 阶梯大宗加购交互
  const batchOrderBtns = document.querySelectorAll('.btn-batch-order');
  batchOrderBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      const name = btn.getAttribute('data-name');
      const basePrice = parseFloat(btn.getAttribute('data-price'));

      const qtyStr = prompt(`正在为【${name}】制定批量采购计划，请输入采购件数/台数：`, '20');
      if (qtyStr !== null && qtyStr.trim() !== '') {
        const qty = parseInt(qtyStr, 10);
        if (!isNaN(qty) && qty > 0) {
          const total = (qty * basePrice).toLocaleString('zh-CN', { minimumFractionDigits: 2 });
          showToast(`✅ 已锁定企业阶梯出厂价！采购 ${qty} 件，预计应付总额 ¥${total}，已生成对公采购订单`);
        }
      }
    });
  });
});
