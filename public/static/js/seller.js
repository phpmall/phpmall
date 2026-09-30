/**
 * 京麦商家工作台交互脚本
 * 负责待发货订单履约、面单打印、新商品上架、库存实时更新及各类工作台通知
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Toast 提示工具函数
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

  // 2. 待发货订单发货履约交互
  const dispatchTbody = document.getElementById('dispatchTbody');
  const alertDeliverNumEl = document.querySelector('.todo-alerts-bar .alert-item.red strong');
  const menuDeliverBadge = document.querySelector('.seller-sidebar [data-title="待发货订单管理"] .badge-num');

  let pendingDeliverCount = 3;

  if (dispatchTbody) {
    dispatchTbody.addEventListener('click', (e) => {
      const btn = e.target.closest('.btn-do-dispatch');
      if (!btn || btn.disabled) return;

      const row = btn.closest('.order-row');
      const orderSn = row.getAttribute('data-sn');
      const trackingInput = row.querySelector('.input-tracking');
      const selectExpress = row.querySelector('.select-express');

      const trackingNo = trackingInput ? trackingInput.value.trim() : '';
      const expressName = selectExpress ? selectExpress.value : '京东快递';

      if (!trackingNo) {
        showToast('⚠️ 请先输入有效的快递运单号');
        if (trackingInput) trackingInput.focus();
        return;
      }

      // 禁用输入与按钮，更新视觉状态
      btn.disabled = true;
      btn.innerText = '已出库';
      btn.style.background = '#e0e0e0';
      btn.style.color = '#888';
      btn.style.cursor = 'default';
      btn.style.borderColor = '#ccc';

      if (trackingInput) {
        trackingInput.disabled = true;
        trackingInput.style.background = '#f9f9f9';
      }
      if (selectExpress) {
        selectExpress.disabled = true;
        selectExpress.style.background = '#f9f9f9';
      }

      // 标记整行为已完成出库
      row.style.transition = 'background-color 0.3s ease';
      row.style.backgroundColor = '#f6ffed';

      // 递减待发货计数
      if (pendingDeliverCount > 0) {
        pendingDeliverCount--;
        if (alertDeliverNumEl) alertDeliverNumEl.innerText = `${pendingDeliverCount} 笔`;
        if (menuDeliverBadge) menuDeliverBadge.innerText = pendingDeliverCount;
      }

      showToast(`✅ 订单 ${orderSn} 发货成功！已指派 [${expressName} - ${trackingNo}]`);
    });
  }

  // 3. 批量打印电子面单
  const btnBatchPrint = document.getElementById('btnBatchPrint');
  if (btnBatchPrint) {
    btnBatchPrint.addEventListener('click', () => {
      btnBatchPrint.innerText = '正在调起打印机...';
      btnBatchPrint.disabled = true;

      setTimeout(() => {
        showToast('🖨️ 京东智臻电子面单组件已调用，已批量打印 3 份订单发货面单！');
        btnBatchPrint.innerText = '批量打印电子面单';
        btnBatchPrint.disabled = false;
      }, 900);
    });
  }

  // 4. 发布新商品弹窗交互
  const addGoodsModal = document.getElementById('addGoodsModal');
  const btnOpenAddGoods = document.getElementById('btnOpenAddGoods');
  const btnCloseAddGoods = document.getElementById('btnCloseAddGoods');
  const btnCancelAddGoods = document.getElementById('btnCancelAddGoods');
  const goodsForm = document.getElementById('goodsForm');
  const inventoryTbody = document.querySelector('.inventory-table tbody');
  const onSaleMenuLink = document.querySelector('.seller-sidebar [data-title="在售商品管理"]');

  function openAddModal() {
    if (addGoodsModal) {
      addGoodsModal.classList.add('active');
    }
  }

  function closeAddModal() {
    if (addGoodsModal) {
      addGoodsModal.classList.remove('active');
    }
  }

  if (btnOpenAddGoods) {
    btnOpenAddGoods.addEventListener('click', openAddModal);
  }

  if (btnCloseAddGoods) {
    btnCloseAddGoods.addEventListener('click', closeAddModal);
  }

  if (btnCancelAddGoods) {
    btnCancelAddGoods.addEventListener('click', closeAddModal);
  }

  // 点击遮罩空白区关闭
  if (addGoodsModal) {
    addGoodsModal.addEventListener('click', (e) => {
      if (e.target === addGoodsModal) {
        closeAddModal();
      }
    });
  }

  // 表单提交新增商品
  let onSaleGoodsCount = 48;
  if (goodsForm) {
    goodsForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const title = document.getElementById('newGoodsTitle').value.trim();
      const price = parseFloat(document.getElementById('newGoodsPrice').value).toFixed(2);
      const stock = parseInt(document.getElementById('newGoodsStock').value, 10) || 50;

      if (!title || isNaN(price)) {
        showToast('请完整填写商品必填项');
        return;
      }

      // 动态在库存监控表格中新增一行
      if (inventoryTbody) {
        const newTr = document.createElement('tr');
        newTr.style.backgroundColor = '#e6f7ff';
        newTr.innerHTML = `
          <td><strong>${title}</strong> <span style="display:inline-block;padding:1px 6px;border-radius:2px;background:#e6f7ff;color:#1890ff;font-size:11px;margin-left:4px;">新品</span></td>
          <td>¥${price}</td>
          <td>0 件</td>
          <td><strong>${stock} 件</strong></td>
          <td><span class="status-normal">充足</span></td>
          <td><button class="btn-table-edit">修改库存/价格</button></td>
        `;
        inventoryTbody.insertBefore(newTr, inventoryTbody.firstChild);
      }

      // 更新左侧在售计数
      onSaleGoodsCount++;
      if (onSaleMenuLink) {
        onSaleMenuLink.innerHTML = `<i>📦</i> 在售商品列表 (${onSaleGoodsCount})`;
      }

      // 重置并关闭
      goodsForm.reset();
      closeAddModal();
      showToast('🎉 新商品已通过安全质检并成功上架至前台店铺！');
    });
  }

  // 5. 待办告警横幅快捷按钮
  const btnGoDeliver = document.getElementById('btnGoDeliver');
  const btnGoRefund = document.getElementById('btnGoRefund');
  const btnGoActivity = document.getElementById('btnGoActivity');
  const dispatchSection = document.getElementById('dispatchSection');

  if (btnGoDeliver && dispatchSection) {
    btnGoDeliver.addEventListener('click', () => {
      dispatchSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
      dispatchSection.style.transition = 'box-shadow 0.3s ease';
      dispatchSection.style.boxShadow = '0 0 14px rgba(225, 37, 27, 0.35)';
      setTimeout(() => {
        dispatchSection.style.boxShadow = '';
      }, 1500);
    });
  }

  if (btnGoRefund) {
    btnGoRefund.addEventListener('click', () => {
      showToast('🔄 正在同步买家仅退款售后凭证与聊天举证记录...');
    });
  }

  if (btnGoActivity) {
    btnGoActivity.addEventListener('click', () => {
      showToast('🎯 京东年终数码大促报名入口已为您开辟，已锁定推荐位！');
    });
  }

  // 6. 快捷补货及修改库存
  if (inventoryTbody) {
    inventoryTbody.addEventListener('click', (e) => {
      const editBtn = e.target.closest('.btn-table-edit');
      if (!editBtn) return;

      const row = editBtn.closest('tr');
      const name = row.children[0].innerText;
      const stockCell = row.children[3];
      const statusCell = row.children[4];

      const addNum = prompt(`正在为【${name.slice(0, 18)}...】补货入库，请输入追加库存数量：`, '50');
      if (addNum !== null && addNum.trim() !== '') {
        const count = parseInt(addNum, 10);
        if (!isNaN(count) && count > 0) {
          stockCell.innerHTML = `<strong>${count + 10} 件</strong>`;
          statusCell.innerHTML = `<span class="status-normal">充足</span>`;
          editBtn.classList.remove('highlight-btn');
          editBtn.innerText = '修改库存/价格';
          showToast(`✅ 商品库存已成功追加 ${count} 件！`);
        }
      }
    });
  }

  // 7. 侧边栏折叠/展开与 Ant Design 手风琴交互 (单项展开模式)
  const parentItems = document.querySelectorAll('.seller-sidebar .menu-item-parent');
  parentItems.forEach((item) => {
    // 初始状态同步
    if (item.classList.contains('open')) {
      item.classList.add('ant-menu-submenu-open');
    }
    const titleEl = item.querySelector('.parent-title');
    if (titleEl) {
      titleEl.addEventListener('click', (e) => {
        e.stopPropagation();
        const willOpen = !item.classList.contains('open');

        // 手风琴模式：展开当前项时，自动折叠同级其他子菜单
        if (willOpen) {
          parentItems.forEach((other) => {
            if (other !== item) {
              other.classList.remove('open', 'ant-menu-submenu-open');
            }
          });
        }

        item.classList.toggle('open', willOpen);
        item.classList.toggle('ant-menu-submenu-open', willOpen);
      });
    }
  });

  // 二级子菜单及一级普通链接点击高亮 (AntD ant-menu-item-selected 联动)
  const allNavLinks = document.querySelectorAll('.seller-sidebar .sub-link, .seller-sidebar .nav-link');
  allNavLinks.forEach((link) => {
    link.addEventListener('click', (e) => {
      // 避免阻止新增商品弹窗
      if (link.id === 'btnOpenAddGoods') return;

      // 清除其他菜单项 active / selected
      document.querySelectorAll('.seller-sidebar li.active, .seller-sidebar .ant-menu-item-selected').forEach((el) => {
        el.classList.remove('active', 'ant-menu-item-selected');
      });

      const parentLi = link.closest('li');
      if (parentLi) {
        parentLi.classList.add('active', 'ant-menu-item-selected');
      }

      const title = link.getAttribute('data-title') || link.innerText.trim();
      showToast(`已切换至【${title}】控制台`);
    });
  });

  // Ant Design Sider 底部收起 / 展开触发条交互
  const siderTrigger = document.getElementById('siderTrigger');
  const sellerSidebar = document.getElementById('sellerSidebar');
  const triggerIcon = document.getElementById('triggerIcon');
  const triggerText = siderTrigger ? siderTrigger.querySelector('.trigger-text') : null;

  if (siderTrigger && sellerSidebar) {
    siderTrigger.addEventListener('click', () => {
      const isCollapsed = sellerSidebar.classList.toggle('ant-sider-collapsed');
      if (triggerIcon) {
        triggerIcon.innerText = isCollapsed ? '▶' : '◀';
      }
      if (triggerText) {
        triggerText.innerText = isCollapsed ? '展开' : '收起侧边栏';
      }
      showToast(isCollapsed ? '已收起侧边栏 (紧凑图标模式)' : '已展开侧边栏');
    });
  }

  // 8. 侧边栏菜单快捷搜索过滤与 Ctrl+K
  const menuSearchInput = document.getElementById('menuSearchInput');
  if (menuSearchInput) {
    menuSearchInput.addEventListener('input', (e) => {
      const keyword = e.target.value.trim().toLowerCase();
      const menuItems = document.querySelectorAll('.seller-sidebar .nav-tree > li');

      menuItems.forEach((item) => {
        if (!keyword) {
          item.style.display = '';
          const subLis = item.querySelectorAll('.submenu-list li');
          subLis.forEach((sub) => sub.style.display = '');
          return;
        }

        // 如果是带子菜单的父级
        if (item.classList.contains('menu-item-parent')) {
          let hasMatchInSub = false;
          const subLis = item.querySelectorAll('.submenu-list li');
          subLis.forEach((sub) => {
            const txt = sub.innerText.toLowerCase();
            if (txt.includes(keyword)) {
              sub.style.display = '';
              hasMatchInSub = true;
            } else {
              sub.style.display = 'none';
            }
          });

          const parentTxt = item.querySelector('.parent-title') ? item.querySelector('.parent-title').innerText.toLowerCase() : '';
          if (hasMatchInSub || parentTxt.includes(keyword)) {
            item.style.display = '';
            item.classList.add('open', 'ant-menu-submenu-open');
          } else {
            item.style.display = 'none';
          }
        } else {
          // 普通独立项
          const txt = item.innerText.toLowerCase();
          item.style.display = txt.includes(keyword) ? '' : 'none';
        }
      });
    });

    // 快捷键 Ctrl+K 聚焦
    document.addEventListener('keydown', (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault();
        menuSearchInput.focus();
        menuSearchInput.select();
      }
    });
  }

  // 9. 顶部通知与客服
  const btnNotice = document.getElementById('btnNotice');
  const btnSellerIm = document.getElementById('btnSellerIm');
  if (btnNotice) {
    btnNotice.addEventListener('click', () => {
      showToast('🔔 收到 3 条京东平台官方营商公告与类目规则变更提醒');
    });
  }
  if (btnSellerIm) {
    btnSellerIm.addEventListener('click', () => {
      showToast('🎧 正在唤起京麦客服协同工作台...');
    });
  }
});
