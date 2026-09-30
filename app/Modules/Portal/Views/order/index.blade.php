@extends('portal::layouts.portal')

@section('title', '我的订单 - 优品商城')

@section('content')
<div class="w" style="margin-top: 20px;">
  <!-- 面包屑 -->
  <div style="font-size: 13px; color: #888; margin-bottom: 15px;">
    <a href="{{ route('home') }}" style="color: #666; text-decoration: none;">首页</a> ›
    <span style="color: #333;">我的订单</span>
  </div>

  <div style="background: #fff; border-radius: 8px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
    <!-- 状态筛选 Tab -->
    <div style="display: flex; gap: 30px; border-bottom: 2px solid #f0f0f0; margin-bottom: 20px;">
      <a href="javascript:;" onclick="switchStatus('')" class="order-tab {{ empty($currentStatus) ? 'active' : '' }}" data-status="" style="padding-bottom: 12px; font-size: 16px; font-weight: bold; color: {{ empty($currentStatus) ? '#e1251b' : '#666' }}; border-bottom: 2px solid {{ empty($currentStatus) ? '#e1251b' : 'transparent' }}; text-decoration: none; margin-bottom: -2px;">
        全部订单
      </a>
      <a href="javascript:;" onclick="switchStatus('10')" class="order-tab {{ $currentStatus == '10' ? 'active' : '' }}" data-status="10" style="padding-bottom: 12px; font-size: 16px; font-weight: bold; color: {{ $currentStatus == '10' ? '#e1251b' : '#666' }}; border-bottom: 2px solid {{ $currentStatus == '10' ? '#e1251b' : 'transparent' }}; text-decoration: none; margin-bottom: -2px;">
        待付款
      </a>
      <a href="javascript:;" onclick="switchStatus('30')" class="order-tab {{ $currentStatus == '30' ? 'active' : '' }}" data-status="30" style="padding-bottom: 12px; font-size: 16px; font-weight: bold; color: {{ $currentStatus == '30' ? '#e1251b' : '#666' }}; border-bottom: 2px solid {{ $currentStatus == '30' ? '#e1251b' : 'transparent' }}; text-decoration: none; margin-bottom: -2px;">
        待发货
      </a>
      <a href="javascript:;" onclick="switchStatus('50')" class="order-tab {{ $currentStatus == '50' ? 'active' : '' }}" data-status="50" style="padding-bottom: 12px; font-size: 16px; font-weight: bold; color: {{ $currentStatus == '50' ? '#e1251b' : '#666' }}; border-bottom: 2px solid {{ $currentStatus == '50' ? '#e1251b' : 'transparent' }}; text-decoration: none; margin-bottom: -2px;">
        待收货
      </a>
      <a href="javascript:;" onclick="switchStatus('70')" class="order-tab {{ $currentStatus == '70' ? 'active' : '' }}" data-status="70" style="padding-bottom: 12px; font-size: 16px; font-weight: bold; color: {{ $currentStatus == '70' ? '#e1251b' : '#666' }}; border-bottom: 2px solid {{ $currentStatus == '70' ? '#e1251b' : 'transparent' }}; text-decoration: none; margin-bottom: -2px;">
        已完成
      </a>
      <a href="javascript:;" onclick="switchStatus('80')" class="order-tab {{ $currentStatus == '80' ? 'active' : '' }}" data-status="80" style="padding-bottom: 12px; font-size: 16px; font-weight: bold; color: {{ $currentStatus == '80' ? '#e1251b' : '#666' }}; border-bottom: 2px solid {{ $currentStatus == '80' ? '#e1251b' : 'transparent' }}; text-decoration: none; margin-bottom: -2px;">
        已取消
      </a>
    </div>

    <!-- 订单列表表头 -->
    <div style="background: #f7f7f7; padding: 12px 20px; font-size: 13px; color: #666; border-radius: 4px; display: flex; margin-bottom: 15px;">
      <div style="flex: 1;">商品详情</div>
      <div style="width: 120px; text-align: center;">单价 / 数量</div>
      <div style="width: 140px; text-align: center;">实付款</div>
      <div style="width: 120px; text-align: center;">交易状态</div>
      <div style="width: 120px; text-align: center;">交易操作</div>
    </div>

    <!-- 订单列表内容容器 -->
    <div id="orderListContainer">
      <div style="text-align: center; padding: 50px; color: #888;">正在加载我的订单...</div>
    </div>

    <!-- 分页器 -->
    <div id="orderPagination" style="display: flex; justify-content: center; gap: 10px; margin-top: 30px;"></div>
  </div>
</div>

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
    const container = document.getElementById('orderListContainer');
    container.innerHTML = '<div style="text-align: center; padding: 50px; color: #888;">正在加载订单列表...</div>';

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
        container.innerHTML = `<div style="text-align: center; padding: 50px; color: #999;">${json.message || '加载订单失败，请登录后重试'}</div>`;
      }
    } catch (e) {
      container.innerHTML = '<div style="text-align: center; padding: 50px; color: #e1251b;">网络加载失败，请重试</div>';
    }
  }

  function renderOrderList(paginator) {
    const container = document.getElementById('orderListContainer');
    const orders = paginator.data;

    if (!orders || orders.length === 0) {
      container.innerHTML = `
        <div style="text-align: center; padding: 60px 0; color: #999;">
          <span style="font-size: 48px;">📦</span>
          <p style="margin-top: 10px;">暂无相关订单记录</p>
          <a href="/goods" style="display: inline-block; margin-top: 10px; padding: 8px 24px; background: #e1251b; color: #fff; text-decoration: none; border-radius: 4px;">去选购好物 ›</a>
        </div>
      `;
      document.getElementById('orderPagination').innerHTML = '';
      return;
    }

    container.innerHTML = orders.map(order => {
      const st = STATUS_MAP[order.status] || { label: '处理中', color: '#666' };
      const items = order.items || [];

      // 渲染商品行
      const itemsHtml = items.map(item => {
        let specStr = '';
        if (item.sku_specs) {
          const specs = typeof item.sku_specs === 'string' ? JSON.parse(item.sku_specs) : item.sku_specs;
          specStr = Object.values(specs).join(' / ');
        }
        return `
          <div style="display: flex; align-items: center; padding: 12px 15px; border-bottom: 1px solid #f5f5f5;">
            <div style="flex: 1; display: flex; gap: 12px; align-items: center;">
              <img src="${item.product_image || '/images/default.jpg'}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
              <div>
                <div style="font-size: 13px; color: #333; line-height: 1.4;">${item.product_title}</div>
                <div style="font-size: 12px; color: #999; margin-top: 3px;">${specStr}</div>
              </div>
            </div>
            <div style="width: 120px; text-align: center; font-size: 13px; color: #666;">
              <div>¥${formatPrice(item.price)}</div>
              <div style="color: #999;">x ${item.quantity}</div>
            </div>
          </div>
        `;
      }).join('');

      // 操作按钮
      let actionButtons = '';
      if (order.status === 10) {
        actionButtons = `
          <a href="/pay/${order.order_no}" style="display: inline-block; padding: 5px 12px; background: #e1251b; color: white; border-radius: 3px; font-size: 12px; text-decoration: none; font-weight: bold; margin-bottom: 6px;">立即付款</a>
          <div><a href="javascript:;" onclick="cancelOrder(${order.id})" style="font-size: 12px; color: #999; text-decoration: none;">取消订单</a></div>
        `;
      } else if (order.status === 50) {
        actionButtons = `
          <button onclick="confirmReceipt(${order.id})" style="padding: 5px 12px; background: #28a745; color: white; border: none; border-radius: 3px; font-size: 12px; cursor: pointer;">确认收货</button>
        `;
      } else {
        actionButtons = `
          <a href="/goods" style="font-size: 12px; color: #666; text-decoration: none;">再次购买</a>
        `;
      }

      return `
        <div style="border: 1px solid #eee; border-radius: 6px; margin-bottom: 20px; overflow: hidden;">
          <!-- 订单头部条 -->
          <div style="background: #fafafa; padding: 10px 20px; font-size: 12px; color: #666; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee;">
            <div style="display: flex; gap: 20px;">
              <span>下单时间：<strong>${order.created_at}</strong></span>
              <span>订单号：<strong style="color: #333;">${order.order_no}</strong></span>
            </div>
            <div style="color: #999;">优品商城自营</div>
          </div>

          <!-- 订单主体表格 -->
          <div style="display: flex; align-items: stretch;">
            <!-- 商品列表区域 -->
            <div style="flex: 1; border-right: 1px solid #eee;">
              ${itemsHtml}
            </div>

            <!-- 实付总额 -->
            <div style="width: 140px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-right: 1px solid #eee; padding: 15px;">
              <strong style="font-size: 16px; color: #333;">¥${formatPrice(order.pay_amount)}</strong>
              <span style="font-size: 12px; color: #999; margin-top: 3px;">(免运费)</span>
            </div>

            <!-- 交易状态 -->
            <div style="width: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-right: 1px solid #eee; padding: 15px;">
              <span style="color: ${st.color}; font-weight: bold; font-size: 13px;">${st.label}</span>
            </div>

            <!-- 交易操作 -->
            <div style="width: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 15px;">
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
    document.querySelectorAll('.order-tab').forEach(tab => {
      const match = tab.getAttribute('data-status') === status;
      tab.style.color = match ? '#e1251b' : '#666';
      tab.style.borderBottomColor = match ? '#e1251b' : 'transparent';
    });
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
