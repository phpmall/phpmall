/**
 * 京东运营总控中台交互脚本
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Toast 工具函数
  const toastEl = document.getElementById('toast');
  let toastTimer = null;
  function showToast(msg, duration = 2600) {
    if (!toastEl) return;
    toastEl.innerText = msg;
    toastEl.className = 'toast show';
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      toastEl.className = 'toast';
    }, duration);
  }

  // 2. 查验资质模态框
  const licenseModal = document.getElementById('licenseModal');
  const btnCloseLicense = document.getElementById('btnCloseLicense');
  const licCompanyName = document.getElementById('licCompanyName');
  const licCompanyCode = document.getElementById('licCompanyCode');
  const licBoss = document.getElementById('licBoss');
  const licFund = document.getElementById('licFund');
  const licStore = document.getElementById('licStore');

  function openLicense(data) {
    if (licCompanyName) licCompanyName.innerText = data.company;
    if (licCompanyCode) licCompanyCode.innerText = data.code;
    if (licBoss) licBoss.innerText = data.boss;
    if (licFund) licFund.innerText = data.fund;
    if (licStore) licStore.innerText = data.store;
    if (licenseModal) licenseModal.classList.add('active');
  }

  function closeLicense() {
    if (licenseModal) licenseModal.classList.remove('active');
  }

  if (btnCloseLicense) btnCloseLicense.addEventListener('click', closeLicense);
  if (licenseModal) {
    licenseModal.addEventListener('click', (e) => {
      if (e.target === licenseModal) closeLicense();
    });
  }

  // 3. 资质审核表格事件委托
  const auditTbody = document.getElementById('auditTbody');
  const auditCountHead = document.getElementById('auditCountHead');
  const sidebarAuditBadge = document.getElementById('sidebarAuditBadge');
  let pendingAuditCount = 3;

  function updateAuditCount() {
    if (pendingAuditCount > 0) pendingAuditCount--;
    if (auditCountHead) auditCountHead.innerText = pendingAuditCount;
    if (sidebarAuditBadge) sidebarAuditBadge.innerText = pendingAuditCount;
  }

  if (auditTbody) {
    auditTbody.addEventListener('click', (e) => {
      // 查验资质
      const inspectBtn = e.target.closest('.btn-table-inspect');
      if (inspectBtn) {
        const company = inspectBtn.getAttribute('data-company');
        const code = inspectBtn.getAttribute('data-code');
        const boss = inspectBtn.getAttribute('data-boss');
        const fund = inspectBtn.getAttribute('data-fund');
        const store = inspectBtn.getAttribute('data-store');
        openLicense({ company, code, boss, fund, store });
        return;
      }

      // 准入通过
      const passBtn = e.target.closest('.btn-table-pass');
      if (passBtn) {
        const row = passBtn.closest('tr');
        const storeName = row.children[2].innerText;
        const actionTd = passBtn.parentElement;

        actionTd.innerHTML = `<span class="status-pill approved">✓ 准入开店授权完成</span>`;
        row.style.backgroundColor = '#f6ffed';
        updateAuditCount();
        showToast(`✅ 审核通过！已向【${storeName}】下发经营许可证与京麦管理工作台权限`);
        return;
      }

      // 驳回
      const rejectBtn = e.target.closest('.btn-table-reject');
      if (rejectBtn) {
        const reason = prompt('请输入驳回补件原因：', '营业执照经营范围与主营类目不符，请重新上传品牌授权资质');
        if (reason !== null && reason.trim() !== '') {
          const row = rejectBtn.closest('tr');
          const actionTd = rejectBtn.parentElement;
          actionTd.innerHTML = `<span class="status-pill rejected">已驳回 (待商户补件)</span>`;
          row.style.backgroundColor = '#fff1f0';
          updateAuditCount();
          showToast(`⚠️ 已驳回入驻申请，驳回通知已短信推达该企业法人`);
        }
      }
    });
  }

  // 批量通过
  const btnBatchPass = document.getElementById('btnBatchPass');
  if (btnBatchPass) {
    btnBatchPass.addEventListener('click', () => {
      const passBtns = auditTbody.querySelectorAll('.btn-table-pass');
      if (passBtns.length === 0) {
        showToast('当前暂无待审核的入驻申请');
        return;
      }
      passBtns.forEach((btn) => btn.click());
      showToast('🎉 已批量审核通过所有待审商户资质！');
    });
  }

  // 4. 商品合规巡检下架
  const inspectTbody = document.getElementById('inspectTbody');
  if (inspectTbody) {
    inspectTbody.addEventListener('click', (e) => {
      const banBtn = e.target.closest('.btn-ban-goods');
      if (banBtn) {
        const row = banBtn.closest('tr');
        const goodsTitle = row.children[0].innerText;
        const statusTd = row.children[4];

        if (banBtn.innerText.includes('下架')) {
          statusTd.innerHTML = `<span class="status-pill rejected">违规已全网下架</span>`;
          banBtn.innerText = '恢复上架';
          banBtn.style.background = '#1890ff';
          banBtn.style.color = '#fff';
          showToast(`🚨 已对【${goodsTitle.slice(0, 16)}...】执行全网强制下架封禁`);
        } else {
          statusTd.innerHTML = `<span class="status-pill normal">合规达标</span>`;
          banBtn.innerText = '违规下架';
          banBtn.style.background = '#fff';
          banBtn.style.color = '#f5222d';
          showToast(`✅ 商品已重新通过质检并恢复上架售卖`);
        }
      }
    });
  }

  // AI 智能巡检
  const btnAutoScan = document.getElementById('btnAutoScan');
  if (btnAutoScan) {
    btnAutoScan.addEventListener('click', () => {
      btnAutoScan.innerText = '正在执行全网大数据扫描...';
      btnAutoScan.disabled = true;

      setTimeout(() => {
        showToast('🛡️ AI巡检完成：已扫描 12,850 款在售商品，发现 1 项异常低价违规项已标红！');
        btnAutoScan.innerText = '运行 AI 价格异常与侵权巡检';
        btnAutoScan.disabled = false;
      }, 1200);
    });
  }

  // 5. 侧边栏平滑滚动定位
  const navSettleAudit = document.getElementById('navSettleAudit');
  const navGoodsInspect = document.getElementById('navGoodsInspect');
  const sectionSettleAudit = document.getElementById('sectionSettleAudit');
  const sectionGoodsInspect = document.getElementById('sectionGoodsInspect');

  if (navSettleAudit && sectionSettleAudit) {
    navSettleAudit.addEventListener('click', (e) => {
      e.preventDefault();
      sectionSettleAudit.scrollIntoView({ behavior: 'smooth' });
    });
  }

  if (navGoodsInspect && sectionGoodsInspect) {
    navGoodsInspect.addEventListener('click', (e) => {
      e.preventDefault();
      sectionGoodsInspect.scrollIntoView({ behavior: 'smooth' });
    });
  }
});
