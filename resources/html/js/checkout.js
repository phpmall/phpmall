/**
 * 订单结算页交互逻辑 (checkout.js)
 */

document.addEventListener('DOMContentLoaded', () => {
  initAddressSelector();
  initPaymentSelector();
  initDeductions();
  initOrderSubmit();
});

function initAddressSelector() {
  const addrCards = document.querySelectorAll('.addr-card');
  const finalAddrText = document.getElementById('finalAddrText');
  const finalReceiverText = document.getElementById('finalReceiverText');
  const btnNewAddr = document.getElementById('btnNewAddr');

  addrCards.forEach(card => {
    card.addEventListener('click', () => {
      addrCards.forEach(c => c.classList.remove('active'));
      card.classList.add('active');

      const uname = card.querySelector('.uname')?.textContent || '张三';
      const detail = card.querySelector('.addr-detail')?.textContent || '';
      const phone = card.querySelector('.phone')?.textContent || '';

      if (finalAddrText) finalAddrText.textContent = detail;
      if (finalReceiverText) finalReceiverText.textContent = `${uname} ${phone}`;

      showToast(`已切换送货地址为：${uname}`);
    });
  });

  btnNewAddr?.addEventListener('click', () => {
    const newDetail = prompt('请输入新的详细收货地址：', '北京市 海淀区 科学院南路2号');
    if (newDetail) {
      showToast('新收货地址添加成功！');
    }
  });
}

function initPaymentSelector() {
  const payBtns = document.querySelectorAll('.pay-btn');
  payBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      payBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const payName = btn.getAttribute('data-pay');
      showToast(`已选择支付方式：${payName}`);
    });
  });
}

function initDeductions() {
  const couponChk = document.getElementById('couponChk');
  const beanChk = document.getElementById('beanChk');
  const couponDeductVal = document.getElementById('couponDeductVal');
  const beanDeductVal = document.getElementById('beanDeductVal');
  const finalMoney = document.getElementById('finalMoney');

  const basePrice = 10067 - 50; // 原价减活动满减 50 = 10017

  function calcFinal() {
    let total = basePrice;
    if (couponChk && couponChk.checked) {
      total -= 400;
      if (couponDeductVal) couponDeductVal.textContent = '-¥400.00';
    } else {
      if (couponDeductVal) couponDeductVal.textContent = '-¥0.00';
    }

    if (beanChk && beanChk.checked) {
      total -= 20;
      if (beanDeductVal) beanDeductVal.textContent = '-¥20.00';
    } else {
      if (beanDeductVal) beanDeductVal.textContent = '-¥0.00';
    }

    if (finalMoney) {
      finalMoney.textContent = `¥${total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    }
  }

  couponChk?.addEventListener('change', calcFinal);
  beanChk?.addEventListener('change', calcFinal);
}

function initOrderSubmit() {
  const btnSubmit = document.getElementById('btnSubmitOrder');
  const modal = document.getElementById('successModal');
  const finalMoney = document.getElementById('finalMoney');
  const modalFinalPay = document.getElementById('modalFinalPay');

  btnSubmit?.addEventListener('click', () => {
    if (modalFinalPay && finalMoney) {
      modalFinalPay.textContent = finalMoney.textContent;
    }
    if (modal) {
      modal.classList.add('show');
    }
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
