@extends('portal::layouts.portal')

@section('title', '我的订单 - 个人中心 - 优品商城')

@push('styles')
  <link rel="stylesheet" href="{{ asset('static/css/user.css') }}">
@endpush

@section('content')
<!-- 个人中心主布局 (Left Sidebar + Right Content) -->
<main class="w user-layout" style="margin-top: 20px;">
  <!-- 左侧导航栏 -->
  <aside class="user-sidebar">
    <div class="side-menu-group">
      <h3 class="group-title">订单中心</h3>
      <ul class="menu-list">
        <li class="active"><a href="{{ route('portal.orders') }}">我的订单</a></li>
      </ul>
    </div>
    <div class="side-menu-group">
      <h3 class="group-title">快捷入口</h3>
      <ul class="menu-list">
        <li><a href="{{ route('portal.cart') }}">我的购物车</a></li>
        <li><a href="{{ route('portal.goods') }}">选购商品</a></li>
      </ul>
    </div>
  </aside>

  <!-- 右侧主要内容区 -->
  <section class="user-main-content">
    <div class="order-center-card">
      <!-- 订单过滤 Tab -->
      <div class="order-tabs">
        <button class="order-tab-btn {{ empty($currentStatus) ? 'active' : '' }}" onclick="switchStatus('')">全部订单</button>
        <button class="order-tab-btn {{ $currentStatus == '10' ? 'active' : '' }}" onclick="switchStatus('10')">待付款</button>
        <button class="order-tab-btn {{ $currentStatus == '30' ? 'active' : '' }}" onclick="switchStatus('30')">待发货</button>
        <button class="order-tab-btn {{ $currentStatus == '50' ? 'active' : '' }}" onclick="switchStatus('50')">待收货</button>
        <button class="order-tab-btn {{ $currentStatus == '70' ? 'active' : '' }}" onclick="switchStatus('70')">已完成</button>
        <button class="order-tab-btn {{ $currentStatus == '80' ? 'active' : '' }}" onclick="switchStatus('80')">已取消</button>
      </div>

      <!-- 订单列表表格表头 -->
      <div class="order-thead">
        <span class="th-col col-goods">订单详情</span>
        <span class="th-col col-receiver">收货人</span>
        <span class="th-col col-amount">实付金额</span>
        <span class="th-col col-status">全部状态</span>
        <span class="th-col col-ops">操作</span>
      </div>

      <!-- 订单列表内容容器 -->
      <div class="order-list-wrap" id="orderListWrap">
        <div style="text-align: center; padding: 60px; color: #888;">正在加载我的订单...</div>
      </div>

      <!-- 分页栏 -->
      <div id="orderPagination" style="display: flex; justify-content: center; gap: 8px; margin: 30px 0;"></div>
    </div>
  </section>
</main>

@push('scripts')
<script>
  let currentStatus = '{{ $currentStatus ?? "" }}';
  let currentPage = 1;

  const STATUS_MAP = {
    10: { label: '待付款', color: '#e1251b' },
    20: { label: '已付款', color: '#28a745' },
    30: { label: '待发货', color: '#ff9900' },
    50: { label: '待收货', color: '#17a2b8' },
    70: { label: '已完成', color: '#28a745' },
    80: { label: '已取消', color: '#999999' }
  };

  async function loadOrders(page = 1) {
    currentPage = page;
    const container = document.getElementById('orderListWrap');
    container.innerHTML = '<div style="text-align: center; padding: 60px; color: #888;">正在加载订单列表...</div>';

    let url = `/api/portal/order?page=${page}&pageSize=10`;
    if (currentStatus) {
      url += `&status=${currentStatus}`;
    }

    try {
      const res = await fetch(url, {
        headers: { 'Accept': 'application/json' }
      });
      const json = await res.json();

      if (json.code === 0 && json.data) {
        renderOrderList(json.data);
      } else {
        container.innerHTML = `<div style="text-align: center; padding: 60px; color: #999;">${json.message || '加载订单失败，请登录后重试'}</div>`;
      }
    } catch (e) {
      container.innerHTML = '<div style="text-align: center; padding: 60px; color: #e1251b;">网络加载失败，请重试</div>';
    }
  }

  function renderOrderList(paginator) {
    const container = document.getElementById('orderListWrap');
    const orders = paginator.data;

    if (!orders || orders.length === 0) {
      container.innerHTML = `
        <div style="text-align: center; padding: 80px 0; color: #999;">
          <span style="font-size: 48px;">📦</span>
          <p style="margin: 15px 0;">暂无相关订单记录</p>
          <a href="/goods" style="display: inline-block; padding: 8px 24px; background: #e1251b; color: #fff; text-decoration: none; border-radius: 4px;">去选购好物 ›</a>
        </div>
      `;
      document.getElementById('orderPagination').innerHTML = '';
      return;
    }

    container.innerHTML = orders.map(order => {
      const st = STATUS_MAP[order.status] || { label: '处理中', color: '#666' };
      const items = order.items || [];

      const itemsHtml = items.map(item => {
        let specStr = '';
        if (item.sku_specs) {
          const specs = typeof item.sku_specs === 'string' ? JSON.parse(item.sku_specs) : item.sku_specs;
          specStr = Object.values(specs).join(' / ');
        }
        return `
          <div class="sub-item" style="display: flex; gap: 12px; padding: 12px 15px; border-bottom: 1px solid #f5f5f5;">
            <a href="/goods/${item.product_id}"><img src="${item.product_image || '/images/default.jpg'}" alt="${item.product_title}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;"></a>
            <div style="flex: 1;">
              <a href="/goods/${item.product_id}" style="font-size: 13px; color: #333; line-height: 1.4; display: block;">${item.product_title}</a>
              <span style="font-size: 12px; color: #999; margin-top: 3px; display: block;">${specStr || '默认规格'}</span>
            </div>
            <div style="text-align: right; width: 100px;">
              <div style="font-size: 13px; color: #333;">¥${formatPrice(item.price)}</div>
              <div style="font-size: 12px; color: #999;">x ${item.quantity}</div>
            </div>
          </div>
        `;
      }).join('');

      let actionButtons = '';
      if (order.status === 10) {
        actionButtons = `
          <a href="/pay/${order.order_no}" style="display: inline-block; padding: 5px 14px; background: #e1251b; color: white; border-radius: 3px; font-size: 12px; text-decoration: none; font-weight: bold; margin-bottom: 6px;">立即付款</a>
          <div><a href="javascript:;" onclick="cancelOrder(${order.id})" style="font-size: 12px; color: #999; text-decoration: none;">取消订单</a></div>
        `;
      } else if (order.status === 50) {
        actionButtons = `
          <button onclick="confirmReceipt(${order.id})" style="padding: 5px 14px; background: #28a745; color: white; border: none; border-radius: 3px; font-size: 12px; cursor: pointer;">确认收货</button>
        `;
      } else {
        actionButtons = `
          <a href="/goods" style="font-size: 12px; color: #666; text-decoration: none;">再次购买</a>
        `;
      }

      return `
        <div class="order-card-item" style="border: 1px solid #eee; border-radius: 6px; margin-bottom: 20px; overflow: hidden; background: #fff;">
          <div class="order-card-header" style="background: #f7f7f7; padding: 10px 20px; font-size: 12px; color: #666; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee;">
            <div style="display: flex; gap: 20px;">
              <span>下单时间：<strong>${order.created_at}</strong></span>
              <span>订单号：<strong style="color: #333;">${order.order_no}</strong></span>
            </div>
            <div style="color: #999;">优品商城自营</div>
          </div>

          <div class="order-card-body" style="display: flex; align-items: stretch;">
            <div class="goods-col-list" style="flex: 1; border-right: 1px solid #eee;">
              ${itemsHtml}
            </div>

            <div class="receiver-col" style="width: 120px; display: flex; align-items: center; justify-content: center; border-right: 1px solid #eee; padding: 15px; font-size: 13px; color: #333;">
              {{ auth()->user()?->name ?? '张三' }}
            </div>

            <div class="amount-col" style="width: 130px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-right: 1px solid #eee; padding: 15px;">
              <strong style="font-size: 15px; color: #333;">¥${formatPrice(order.pay_amount)}</strong>
              <span style="font-size: 12px; color: #999; margin-top: 3px;">(免运费)</span>
            </div>

            <div class="status-col" style="width: 120px; display: flex; align-items: center; justify-content: center; border-right: 1px solid #eee; padding: 15px;">
              <span style="color: ${st.color}; font-weight: bold; font-size: 13px;">${st.label}</span>
            </div>

            <div class="ops-col" style="width: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 15px;">
              ${actionButtons}
            </div>
          </div>
        </div>
      `;
    }).join('');

    renderPagination(paginator);
  }

  function renderPagination(paginator) {
    const el = document.getElementById('orderPagination');
    if (paginator.last_page <= 1) {
      el.innerHTML = '';
      return;
    }

    let html = '';
    if (paginator.current_page > 1) {
      html += `<button onclick="loadOrders(${paginator.current_page - 1})" style="padding: 6px 12px; border: 1px solid #ddd; background: #fff; border-radius: 4px; cursor: pointer;">‹ 上一页</button>`;
    }
    html += `<span style="padding: 6px 12px; font-size: 13px; color: #666;">第 ${paginator.current_page} / ${paginator.last_page} 页</span>`;
    if (paginator.current_page < paginator.last_page) {
      html += `<button onclick="loadOrders(${paginator.current_page + 1})" style="padding: 6px 12px; border: 1px solid #ddd; background: #fff; border-radius: 4px; cursor: pointer;">下一页 ›</button>`;
    }
    el.innerHTML = html;
  }

  function switchStatus(status) {
    currentStatus = status;
    document.querySelectorAll('.order-tabs .order-tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    loadOrders(1);
  }

  function cancelOrder(orderId) {
    const doCancel = async () => {
      const loadIdx = showLoading();
      try {
        const res = await fetch(`/api/portal/order/${orderId}/cancel`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ reason: '买家主动取消' })
        });
        const json = await res.json();
        if (json.code === 0) {
          showToast('订单已成功取消', 1);
          loadOrders(currentPage);
        } else {
          showToast(json.message || '取消失败', 2);
        }
      } catch (e) {
        showToast('网络异常', 2);
      } finally {
        closeLoading(loadIdx);
      }
    };

    if (window.layer) {
      layer.confirm('确定要取消此订单吗？', { icon: 3, title: '取消确认' }, function (index) {
        layer.close(index);
        doCancel();
      });
    } else if (confirm('确定要取消此订单吗？')) {
      doCancel();
    }
  }

  function confirmReceipt(orderId) {
    const doConfirm = async () => {
      const loadIdx = showLoading();
      try {
        const res = await fetch(`/api/portal/order/${orderId}/confirm-receipt`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });
        const json = await res.json();
        if (json.code === 0) {
          showToast('已确认收货！', 1);
          loadOrders(currentPage);
        } else {
          showToast(json.message || '操作失败', 2);
        }
      } catch (e) {
        showToast('网络异常', 2);
      } finally {
        closeLoading(loadIdx);
      }
    };

    if (window.layer) {
      layer.confirm('确认已收到货物吗？', { icon: 3, title: '收货确认' }, function (index) {
        layer.close(index);
        doConfirm();
      });
    } else if (confirm('确认已收到货物吗？')) {
      doConfirm();
    }
  }

  document.addEventListener('DOMContentLoaded', () => loadOrders(1));
</script>
@endpush
@endsection
