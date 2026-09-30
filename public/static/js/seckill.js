/**
 * 京东秒杀大促专场脚本
 * 负责时段场次无缝切换、多维度品类筛选、秒杀倒计时与即时抢购锁定
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

  // 2. 秒杀倒计时时钟逻辑
  let totalSeconds = 1 * 3600 + 42 * 60 + 18;
  const hourEl = document.getElementById('skHour');
  const minuteEl = document.getElementById('skMinute');
  const secondEl = document.getElementById('skSecond');

  setInterval(() => {
    if (totalSeconds > 0) {
      totalSeconds--;
    } else {
      totalSeconds = 2 * 3600; // 重置下一轮
    }

    const h = Math.floor(totalSeconds / 3600);
    const m = Math.floor((totalSeconds % 3600) / 60);
    const s = totalSeconds % 60;

    if (hourEl) hourEl.innerText = String(h).padStart(2, '0');
    if (minuteEl) minuteEl.innerText = String(m).padStart(2, '0');
    if (secondEl) secondEl.innerText = String(s).padStart(2, '0');
  }, 1000);

  // 3. 场次切换
  const timelineSlots = document.querySelectorAll('.timeline-slot');
  const sessionLabel = document.getElementById('sessionLabel');
  const skGrid = document.getElementById('skGrid');

  timelineSlots.forEach((slot) => {
    slot.addEventListener('click', () => {
      timelineSlots.forEach((s) => s.classList.remove('active'));
      slot.classList.add('active');

      const time = slot.getAttribute('data-time');
      const status = slot.getAttribute('data-status');

      const buyBtns = skGrid.querySelectorAll('.btn-sk-buy, .btn-sk-remind');

      if (status === 'upcoming') {
        if (sessionLabel) sessionLabel.innerText = `距 ${time} 场开抢还剩：`;
        buyBtns.forEach((btn) => {
          btn.className = 'btn-sk-remind';
          btn.innerText = '提醒我';
          btn.disabled = false;
          btn.style = '';
        });
        showToast(`已切换至【${time} 即将开抢】场次预告`);
      } else if (status === 'ended') {
        if (sessionLabel) sessionLabel.innerText = `${time} 场已结束：`;
        buyBtns.forEach((btn) => {
          btn.className = 'btn-sk-buy';
          btn.innerText = '已结束';
          btn.disabled = true;
          btn.style.background = '#d9d9d9';
          btn.style.color = '#888';
          btn.style.cursor = 'not-allowed';
          btn.style.boxShadow = 'none';
        });
        showToast(`【${time}】场次秒杀已结束，为您展示往期热品回顾`);
      } else {
        if (sessionLabel) sessionLabel.innerText = '本场距结束还剩：';
        buyBtns.forEach((btn) => {
          btn.className = 'btn-sk-buy';
          btn.innerText = '立即抢购';
          btn.disabled = false;
          btn.style = '';
        });
        showToast(`已切换至【${time} 当前开抢中】场次`);
      }
    });
  });

  // 4. 品类快捷药丸筛选
  const catPills = document.querySelectorAll('.sk-cat-pill');
  const skCards = document.querySelectorAll('.sk-item-card');

  catPills.forEach((pill) => {
    pill.addEventListener('click', () => {
      catPills.forEach((p) => p.classList.remove('active'));
      pill.classList.add('active');

      const cat = pill.getAttribute('data-cat');
      skCards.forEach((card) => {
        const cardCat = card.getAttribute('data-cat');
        if (cat === 'all' || cardCat === cat) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  // 5. 立即抢购与提醒交互委托
  if (skGrid) {
    skGrid.addEventListener('click', (e) => {
      // 立即抢购
      const buyBtn = e.target.closest('.btn-sk-buy');
      if (buyBtn && !buyBtn.disabled) {
        const card = buyBtn.closest('.sk-item-card');
        const title = card.querySelector('.sk-title').innerText;
        buyBtn.innerText = '抢购中...';
        buyBtn.disabled = true;

        setTimeout(() => {
          showToast(`⚡ 恭喜！已锁定【${title.slice(0, 16)}...】秒杀优惠，3秒后进入收银台`);
          setTimeout(() => {
            window.location.href = 'checkout.html';
          }, 1200);
        }, 500);
        return;
      }

      // 提醒我
      const remindBtn = e.target.closest('.btn-sk-remind');
      if (remindBtn) {
        if (remindBtn.classList.contains('active')) {
          remindBtn.classList.remove('active');
          remindBtn.innerText = '提醒我';
          showToast('已取消该商品开抢提醒');
        } else {
          remindBtn.classList.add('active');
          remindBtn.innerText = '✓ 已预约';
          showToast('🔔 预约成功！开抢前 5 分钟将通过短信与京麦消息向您推送');
        }
      }
    });
  }
});
