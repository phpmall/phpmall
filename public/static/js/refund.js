/**
 * 买家售后退换货中心交互脚本
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Toast 工具函数
  const toastEl = document.getElementById('toast');
  let toastTimer = null;
  function showToast(msg, duration = 2500) {
    if (!toastEl) return;
    toastEl.innerText = msg;
    toastEl.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      toastEl.classList.remove('show');
    }, duration);
  }

  // 2. Tab 切换
  const tabs = document.querySelectorAll('.refund-tab-item');
  const panes = document.querySelectorAll('.refund-pane');

  tabs.forEach((tab) => {
    tab.addEventListener('click', () => {
      tabs.forEach((t) => t.classList.remove('active'));
      panes.forEach((p) => p.classList.remove('active'));

      tab.classList.add('active');
      const targetId = tab.getAttribute('data-target');
      const targetPane = document.getElementById(targetId);
      if (targetPane) {
        targetPane.classList.add('active');
      }
    });
  });

  // 3. 模态框元素与打开逻辑
  const refundModal = document.getElementById('refundModal');
  const btnCloseRefundModal = document.getElementById('btnCloseRefundModal');
  const btnCancelRefundModal = document.getElementById('btnCancelRefundModal');
  const refundForm = document.getElementById('refundForm');

  const formGoodsImg = document.getElementById('formGoodsImg');
  const formGoodsTitle = document.getElementById('formGoodsTitle');
  const formGoodsPrice = document.getElementById('formGoodsPrice');
  const formOrderSn = document.getElementById('formOrderSn');
  const formRefundAmount = document.getElementById('formRefundAmount');

  let currentSelectedGoods = null;

  // 绑定“申请售后”按钮
  const applyPane = document.getElementById('pane-apply');
  if (applyPane) {
    applyPane.addEventListener('click', (e) => {
      const btn = e.target.closest('.btn-apply-refund');
      if (!btn) return;

      const card = btn.closest('.refundable-card');
      const order = card.getAttribute('data-order');
      const goods = card.getAttribute('data-goods');
      const price = card.getAttribute('data-price');
      const img = card.getAttribute('data-img');

      currentSelectedGoods = { order, goods, price, img, card };

      if (formGoodsImg) formGoodsImg.src = img;
      if (formGoodsTitle) formGoodsTitle.innerText = goods;
      if (formGoodsPrice) formGoodsPrice.innerText = `¥${price}`;
      if (formOrderSn) formOrderSn.innerText = order;
      if (formRefundAmount) {
        formRefundAmount.value = price;
        formRefundAmount.max = price;
      }

      if (refundModal) refundModal.classList.add('active');
    });
  }

  function closeModal() {
    if (refundModal) refundModal.classList.remove('active');
  }

  if (btnCloseRefundModal) btnCloseRefundModal.addEventListener('click', closeModal);
  if (btnCancelRefundModal) btnCancelRefundModal.addEventListener('click', closeModal);

  if (refundModal) {
    refundModal.addEventListener('click', (e) => {
      if (e.target === refundModal) closeModal();
    });
  }

  // 4. 服务类型 Pill 点击切换
  const pillOptions = document.querySelectorAll('.pill-option');
  let selectedServiceType = '退货退款';

  pillOptions.forEach((pill) => {
    pill.addEventListener('click', () => {
      pillOptions.forEach((p) => p.classList.remove('active'));
      pill.classList.add('active');
      selectedServiceType = pill.getAttribute('data-type');
    });
  });

  // 5. 模拟上传凭证
  const btnUploadProof = document.getElementById('btnUploadProof');
  if (btnUploadProof) {
    btnUploadProof.addEventListener('click', () => {
      showToast('📷 已选取本地实物照片并成功上传至售后凭证库');
    });
  }

  // 6. 表单提交：生成新服务单并追加到 Pane 2
  const recordsContainer = document.getElementById('recordsContainer');
  const recordCountBadge = document.getElementById('recordCountBadge');
  let recordTotal = 2;

  if (refundForm) {
    refundForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const reason = document.getElementById('formReason').value;
      const refundAmount = parseFloat(formRefundAmount.value).toFixed(2);
      const randomSn = 'AS' + new Date().getFullYear() + '0928' + Math.floor(1000 + Math.random() * 9000);

      // 创建新卡片
      const newCard = document.createElement('div');
      newCard.className = 'record-card';
      newCard.style.backgroundColor = '#fffdfd';
      newCard.innerHTML = `
        <div class="record-head">
          <div class="record-sn-tag">
            <span>服务单号：<strong>${randomSn}</strong></span>
            <span class="service-type-badge">${selectedServiceType}</span>
            <span class="time" style="color:#999;font-size:12px;">刚刚提交</span>
          </div>
          <span class="status-highlight">京东客服审核中 (预计2小时内完成)</span>
        </div>

        <div class="timeline-steps">
          <div class="step-node active">
            <div class="step-dot">1</div>
            <span class="step-title">提交申请</span>
          </div>
          <div class="step-node">
            <div class="step-dot">2</div>
            <span class="step-title">专员审核</span>
          </div>
          <div class="step-node">
            <div class="step-dot">3</div>
            <span class="step-title">快递取件</span>
          </div>
          <div class="step-node">
            <div class="step-dot">4</div>
            <span class="step-title">商家验货</span>
          </div>
          <div class="step-node">
            <div class="step-dot">5</div>
            <span class="step-title">退款到账</span>
          </div>
        </div>

        <div class="record-goods-row">
          <div class="r-mini-info">
            <img src="${currentSelectedGoods ? currentSelectedGoods.img : ''}" alt="商品">
            <div class="r-mini-desc">
              <h5>${currentSelectedGoods ? currentSelectedGoods.goods : '申请商品'}</h5>
              <p>申请退款：<strong style="color:#e1251b;">¥${refundAmount}</strong> | 原因：${reason}</p>
            </div>
          </div>
          <div class="record-btn-row">
            <button class="btn-contact-service btn-view-progress">查看进度详情</button>
            <button class="btn-contact-service btn-cancel-refund">撤销售后</button>
          </div>
        </div>
      `;

      if (recordsContainer) {
        recordsContainer.insertBefore(newCard, recordsContainer.firstChild);
      }

      // 更新计数
      recordTotal++;
      if (recordCountBadge) recordCountBadge.innerText = recordTotal;

      // 关闭模态框并重置
      closeModal();
      refundForm.reset();

      // 切换到售后记录 Pane
      const recordTab = document.querySelector('.refund-tab-item[data-target="pane-records"]');
      if (recordTab) recordTab.click();

      showToast(`✅ 售后单已受理！服务单号：${randomSn}，京东专员将极速处理`);
    });
  }

  // 7. 撤销售后与进度查看委托
  if (recordsContainer) {
    recordsContainer.addEventListener('click', (e) => {
      // 撤销
      const cancelBtn = e.target.closest('.btn-cancel-refund');
      if (cancelBtn) {
        if (confirm('确定要撤销此笔售后服务单吗？撤销后将终止退款退货履约流程。')) {
          const card = cancelBtn.closest('.record-card');
          const statusEl = card.querySelector('.status-highlight');
          if (statusEl) {
            statusEl.innerText = '已撤销关闭';
            statusEl.style.color = '#999';
          }
          cancelBtn.remove();
          showToast('此笔售后服务单已成功撤销');
        }
        return;
      }

      // 详情
      const viewBtn = e.target.closest('.btn-view-progress');
      if (viewBtn) {
        showToast('📋 正在调取全国联保维修履约工单与官方客服协商日志...');
      }
    });
  }

  // 8. 政策政策细则按钮
  const btnPolicy = document.getElementById('btnPolicy');
  if (btnPolicy) {
    btnPolicy.addEventListener('click', () => {
      showToast('📖 京东全面支持“退换无忧”与全国自营联保政策');
    });
  }

  // 9. 搜索服务单
  const btnRefundSearch = document.getElementById('btnRefundSearch');
  const refundSearchInput = document.getElementById('refundSearchInput');
  if (btnRefundSearch && refundSearchInput) {
    btnRefundSearch.addEventListener('click', () => {
      const val = refundSearchInput.value.trim();
      if (!val) {
        showToast('请输入服务单号或订单号');
        return;
      }
      showToast(`正在检索服务单号：${val}...`);
    });
  }
});
