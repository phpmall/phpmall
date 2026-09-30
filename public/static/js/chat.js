/**
 * 京东智能客服 (Joy) 交互脚本
 */

document.addEventListener('DOMContentLoaded', () => {
  const messageList = document.getElementById('messageList');
  const chatInput = document.getElementById('chatInput');
  const btnSendMsg = document.getElementById('btnSendMsg');
  const quickPills = document.getElementById('quickPills');
  const btnSendRecentOrder = document.getElementById('btnSendRecentOrder');

  // 1. 滚动到底部
  function scrollToBottom() {
    if (messageList) {
      messageList.scrollTop = messageList.scrollHeight;
    }
  }

  // 2. 添加消息到界面
  function appendMessage(text, isUser = false, isHtml = false) {
    const row = document.createElement('div');
    row.className = `msg-row ${isUser ? 'user' : 'bot'}`;

    const now = new Date();
    const timeStr = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;

    row.innerHTML = `
      <div class="msg-avatar">${isUser ? '我' : 'Joy'}</div>
      <div>
        <div class="msg-bubble">${isHtml ? text : escapeHtml(text)}</div>
        <div class="msg-time" style="${isUser ? 'text-align:right;' : ''}">${timeStr}</div>
      </div>
    `;

    messageList.appendChild(row);
    scrollToBottom();
  }

  function escapeHtml(string) {
    const div = document.createElement('div');
    div.innerText = string;
    return div.innerHTML;
  }

  // 3. 智能机器人回复策略
  function botReply(userText) {
    let reply = '';
    const q = userText.toLowerCase();

    if (q.includes('送达') || q.includes('发货') || q.includes('什么时候') || q.includes('物流')) {
      reply = `🚚 <strong>智能物流速查：</strong><br>您的订单 <code>JD2026092800101</code> (Apple iPhone 16 Pro) 已由北京智能物流中心完成分拣，正由京东特快专送配送中，预计将于 <strong>今日下午 14:00 前</strong> 准时送达您的收件地址！<br><a href="../user/order.html" style="color:#e1251b;text-decoration:underline;">点击查看完整轨迹时间轴 ›</a>`;
    } else if (q.includes('发票') || q.includes('开票')) {
      reply = `🧾 <strong>发票服务指引：</strong><br>京东全面支持电子普通发票与 13% 增值税专用发票。订单完成支付后系统已自动为您生成电子发票，您可在【我的订单】-【订单详情】中一键下载或推送到您的电子邮箱。`;
    } else if (q.includes('退') || q.includes('换货') || q.includes('售后')) {
      reply = `🔄 <strong>自营退换无忧：</strong><br>您购买的京东自营商品支持 <strong>7天无理由退货、15天免费换货</strong>。您可直接前往 <a href="../user/refund.html" style="color:#e1251b;font-weight:bold;">【客户服务-返修退换货中心】</a> 提报申请，PLUS会员享京东快递员免费上门取件！`;
    } else if (q.includes('人工') || q.includes('客服')) {
      reply = `👨‍💼 <strong>已为您接入专属人工客服：</strong><br>【工号：JD-8820 资深客服专员】已加入会话！<br>“尊敬的张先生，您好！我是本次服务专员，请问刚才的 iPhone 16 订单有什么需要帮您加急处理的吗？”`;
    } else if (q.includes('运费券') || q.includes('plus')) {
      reply = `🎟️ <strong>PLUS 会员特权：</strong><br>您的 PLUS 尊享卡每月已自动发放全品类免运费权益及 80 元无门槛优惠神券，下单时系统将自动为您勾选抵扣。`;
    } else {
      reply = `您好！我已经收到您的咨询：“${escapeHtml(userText)}”。京东为您提供 7×24 小时正品行货与自营仓配履约保障。如有复杂售后需求，您也可点击下方【转人工客服】。`;
    }

    setTimeout(() => {
      appendMessage(reply, false, true);
    }, 600);
  }

  // 4. 发送操作
  function handleSend() {
    const text = chatInput.value.trim();
    if (!text) return;

    appendMessage(text, true, false);
    chatInput.value = '';
    botReply(text);
  }

  if (btnSendMsg) btnSendMsg.addEventListener('click', handleSend);
  if (chatInput) {
    chatInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        handleSend();
      }
    });
  }

  // 5. 快捷药丸点击
  if (quickPills) {
    quickPills.addEventListener('click', (e) => {
      const pill = e.target.closest('.quick-pill');
      if (!pill) return;
      const q = pill.getAttribute('data-q');
      appendMessage(q, true, false);
      botReply(q);
    });
  }

  // 6. 发送最近订单卡片
  if (btnSendRecentOrder) {
    btnSendRecentOrder.addEventListener('click', () => {
      const cardHtml = `
        <div style="background:#fff;border:1px solid #ffd4d4;border-radius:6px;padding:8px;font-size:12px;color:#333;">
          <div style="font-weight:bold;color:#e1251b;margin-bottom:4px;">咨询订单：JD2026092800101</div>
          <div>Apple iPhone 16 Pro 256GB 原色钛金属 (¥7,999.00)</div>
        </div>
      `;
      appendMessage(cardHtml, true, true);
      botReply('这笔订单什么时候送达？');
    });
  }

  // 7. 右侧 FAQ 点击
  const faqItems = document.querySelectorAll('.faq-item');
  faqItems.forEach((item) => {
    item.addEventListener('click', () => {
      const text = item.innerText.replace('📌', '').trim();
      appendMessage(text, true, false);
      botReply(text);
    });
  });
});
