/**
 * 收货地址管理交互脚本
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

  // 2. 模态框打开与关闭
  const addrModal = document.getElementById('addrModal');
  const btnOpenAddAddr = document.getElementById('btnOpenAddAddr');
  const btnCloseAddrModal = document.getElementById('btnCloseAddrModal');
  const btnCancelAddrModal = document.getElementById('btnCancelAddrModal');
  const addrForm = document.getElementById('addrForm');
  const modalTitle = document.getElementById('modalTitle');

  const inputName = document.getElementById('inputName');
  const inputPhone = document.getElementById('inputPhone');
  const inputProvince = document.getElementById('inputProvince');
  const inputDetail = document.getElementById('inputDetail');
  const checkDefault = document.getElementById('checkDefault');

  function openModal(isEdit = false) {
    if (modalTitle) modalTitle.innerText = isEdit ? '编辑收货地址' : '新增收货地址';
    if (addrModal) addrModal.classList.add('active');
  }

  function closeModal() {
    if (addrModal) addrModal.classList.remove('active');
  }

  if (btnOpenAddAddr) {
    btnOpenAddAddr.addEventListener('click', () => {
      addrForm.reset();
      openModal(false);
    });
  }

  if (btnCloseAddrModal) btnCloseAddrModal.addEventListener('click', closeModal);
  if (btnCancelAddrModal) btnCancelAddrModal.addEventListener('click', closeModal);

  if (addrModal) {
    addrModal.addEventListener('click', (e) => {
      if (e.target === addrModal) closeModal();
    });
  }

  // 3. 地址标签选择
  const tagPills = document.querySelectorAll('.am-tag-pill');
  let selectedTag = '家';
  tagPills.forEach((pill) => {
    pill.addEventListener('click', () => {
      tagPills.forEach((p) => p.classList.remove('active'));
      pill.classList.add('active');
      selectedTag = pill.getAttribute('data-tag');
    });
  });

  // 4. 智能文本解析
  const btnSmartParse = document.getElementById('btnSmartParse');
  const smartInput = document.getElementById('smartInput');

  if (btnSmartParse && smartInput) {
    btnSmartParse.addEventListener('click', () => {
      const text = smartInput.value.trim();
      if (!text) {
        showToast('请先在输入框中粘贴整段收件地址文本');
        return;
      }

      // 提取手机号（11位）
      const phoneMatch = text.match(/1[3-9]\d{9}/);
      const phone = phoneMatch ? phoneMatch[0] : '';

      // 简单去除手机号后的文本进行切分
      let remaining = text.replace(phone, '').replace(/，|,|。/g, ' ').trim();
      const parts = remaining.split(/\s+/).filter(Boolean);

      const name = parts[0] || '收件人';
      const detail = parts.slice(1).join(' ') || remaining;

      if (inputName) inputName.value = name;
      if (inputPhone) inputPhone.value = phone;
      if (inputDetail) inputDetail.value = detail;

      openModal(false);
      showToast('⚡ 智能解析成功！已自动为您填入收件人、电话及详细地址');
    });
  }

  // 5. 保存提交地址
  const addressGrid = document.getElementById('addressGrid');
  const addrCountEl = document.getElementById('addrCount');
  let totalAddrs = 3;

  if (addrForm) {
    addrForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const name = inputName.value.trim();
      const phone = inputPhone.value.trim();
      const prov = inputProvince.value;
      const detail = inputDetail.value.trim();
      const isDef = checkDefault.checked;

      if (isDef) {
        // 清理原有默认标识
        const defCards = addressGrid.querySelectorAll('.address-card.is-default');
        defCards.forEach((c) => {
          c.classList.remove('is-default');
          const ribbon = c.querySelector('.default-ribbon');
          if (ribbon) ribbon.remove();
          const defTxt = c.querySelector('.card-bottom-actions span');
          if (defTxt) {
            defTxt.outerHTML = `<button class="btn-set-default">设为默认</button>`;
          }
        });
      }

      const card = document.createElement('div');
      card.className = `address-card ${isDef ? 'is-default' : ''}`;
      card.innerHTML = `
        ${isDef ? '<span class="default-ribbon">默认地址</span>' : ''}
        <div>
          <div class="card-recipient-line">
            <span class="name">${name}</span>
            <span class="phone">${phone}</span>
            <span class="addr-tag-pill">${selectedTag}</span>
          </div>
          <p class="card-addr-detail">
            ${prov} ${detail}
          </p>
        </div>
        <div class="card-bottom-actions">
          ${isDef ? '<span style="font-size:12px;color:#52c41a;font-weight:bold;">✓ 默认收货地址</span>' : '<button class="btn-set-default">设为默认</button>'}
          <div class="action-btns-right">
            <button class="btn-edit-addr">编辑</button>
            <button class="btn-del-addr">删除</button>
          </div>
        </div>
      `;

      if (addressGrid) {
        addressGrid.insertBefore(card, addressGrid.firstChild);
      }

      totalAddrs++;
      if (addrCountEl) addrCountEl.innerText = totalAddrs;

      closeModal();
      addrForm.reset();
      showToast('✅ 收货地址已成功保存并同步至您的常用地址簿！');
    });
  }

  // 6. 地址卡片交互委托 (设为默认/删除/编辑)
  if (addressGrid) {
    addressGrid.addEventListener('click', (e) => {
      // 设为默认
      const setDefBtn = e.target.closest('.btn-set-default');
      if (setDefBtn) {
        const card = setDefBtn.closest('.address-card');
        const prevDef = addressGrid.querySelector('.address-card.is-default');
        if (prevDef) {
          prevDef.classList.remove('is-default');
          const r = prevDef.querySelector('.default-ribbon');
          if (r) r.remove();
          const t = prevDef.querySelector('.card-bottom-actions span');
          if (t) t.outerHTML = `<button class="btn-set-default">设为默认</button>`;
        }

        card.classList.add('is-default');
        const ribbon = document.createElement('span');
        ribbon.className = 'default-ribbon';
        ribbon.innerText = '默认地址';
        card.prepend(ribbon);

        setDefBtn.outerHTML = `<span style="font-size:12px;color:#52c41a;font-weight:bold;">✓ 默认收货地址</span>`;
        showToast('已设为默认收货地址，下单时将优先为您匹配该地址');
        return;
      }

      // 删除
      const delBtn = e.target.closest('.btn-del-addr');
      if (delBtn) {
        if (confirm('确定要删除该收货地址吗？')) {
          const card = delBtn.closest('.address-card');
          card.remove();
          if (totalAddrs > 0) totalAddrs--;
          if (addrCountEl) addrCountEl.innerText = totalAddrs;
          showToast('地址已成功移除');
        }
        return;
      }

      // 编辑
      const editBtn = e.target.closest('.btn-edit-addr');
      if (editBtn) {
        const card = editBtn.closest('.address-card');
        const name = card.querySelector('.name').innerText;
        const phone = card.querySelector('.phone').innerText;
        const detail = card.querySelector('.card-addr-detail').innerText;

        if (inputName) inputName.value = name;
        if (inputPhone) inputPhone.value = phone.replace(/\*/g, '8');
        if (inputDetail) inputDetail.value = detail.trim();

        openModal(true);
      }
    });
  }
});
