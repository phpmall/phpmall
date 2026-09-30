/**
 * 京东 (JD.COM) 精简首页互动逻辑脚本
 */

document.addEventListener('DOMContentLoaded', () => {
  initSlider();
  initSeckillCountdown();
  initCategoryPanel();
  initLiftNav();
  initNewsTab();
  initCartAndToast();
  initSearch();
});

/* ================= 1. 大屏主轮播图 (Main Slider) ================= */
function initSlider() {
  const slides = document.querySelectorAll('.slide-item');
  const dots = document.querySelectorAll('.slider-dots .dot');
  const btnPrev = document.getElementById('sliderPrev');
  const btnNext = document.getElementById('sliderNext');
  const sliderBox = document.getElementById('mainSlider');

  if (!slides.length) return;

  let currentIndex = 0;
  let timer = null;

  function showSlide(index) {
    slides.forEach((slide, idx) => {
      slide.classList.toggle('active', idx === index);
    });
    dots.forEach((dot, idx) => {
      dot.classList.toggle('active', idx === index);
    });
    currentIndex = index;
  }

  function nextSlide() {
    let next = (currentIndex + 1) % slides.length;
    showSlide(next);
  }

  function prevSlide() {
    let prev = (currentIndex - 1 + slides.length) % slides.length;
    showSlide(prev);
  }

  function startAutoPlay() {
    stopAutoPlay();
    timer = setInterval(nextSlide, 4500);
  }

  function stopAutoPlay() {
    if (timer) clearInterval(timer);
  }

  btnNext?.addEventListener('click', () => {
    nextSlide();
    startAutoPlay();
  });

  btnPrev?.addEventListener('click', () => {
    prevSlide();
    startAutoPlay();
  });

  dots.forEach((dot, idx) => {
    dot.addEventListener('click', () => {
      showSlide(idx);
      startAutoPlay();
    });
  });

  sliderBox?.addEventListener('mouseenter', stopAutoPlay);
  sliderBox?.addEventListener('mouseleave', startAutoPlay);

  startAutoPlay();
}

/* ================= 2. 京东秒杀动态倒计时 ================= */
function initSeckillCountdown() {
  const elHour = document.getElementById('timerHour');
  const elMinute = document.getElementById('timerMinute');
  const elSecond = document.getElementById('timerSecond');
  const elRound = document.getElementById('seckillRound');

  if (!elHour || !elMinute || !elSecond) return;

  // 模拟当前场次倒计时：到今天下一个整点场
  function updateCountdown() {
    const now = new Date();
    const currentHour = now.getHours();
    const nextRoundHour = currentHour + (currentHour % 2 === 0 ? 2 : 1);
    
    if (elRound) {
      elRound.textContent = `${String(currentHour).padStart(2, '0')}:00`;
    }

    const targetTime = new Date(now);
    targetTime.setHours(nextRoundHour, 0, 0, 0);

    const diff = targetTime.getTime() - now.getTime();
    if (diff <= 0) return;

    const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
    const minutes = Math.floor((diff / (1000 * 60)) % 60);
    const seconds = Math.floor((diff / 1000) % 60);

    elHour.textContent = String(hours).padStart(2, '0');
    elMinute.textContent = String(minutes).padStart(2, '0');
    elSecond.textContent = String(seconds).padStart(2, '0');
  }

  updateCountdown();
  setInterval(updateCountdown, 1000);
}

/* ================= 3. 左侧品类导航悬浮联动面板 ================= */
function initCategoryPanel() {
  const catItems = document.querySelectorAll('.cat-list .cat-item');
  const subPanel = document.getElementById('categorySubPanel');
  const tagsHeader = document.getElementById('subTagsHeader');
  const detailGroups = document.getElementById('subDetailGroups');
  const categoryMenu = document.getElementById('categoryMenu');

  // 预置丰富品类数据
  const categoryData = {
    0: {
      tags: ['电视影音', '冰箱洗衣机', '空调特惠', '厨房大电', '净水设备', '热水器'],
      groups: [
        { key: '电视', items: ['OLED超薄', 'MiniLED', '8K超清', '游戏电视', '激光影院', '智慧屏'] },
        { key: '空调', items: ['新一级能效', '变频立式', '中央空调', '风管机', '静音节能'] },
        { key: '厨房小电', items: ['空气炸锅', '电饭煲', '破壁机', '咖啡机', '微波炉', '养生壶'] },
        { key: '生活电器', items: ['扫地机器人', '洗地机', '吸尘器', '空气净化器', '除湿机', '挂烫机'] }
      ]
    },
    1: {
      tags: ['新品手机', '以旧换新', '5G智能机', '折叠屏', '电竞游戏机', '快充配件'],
      groups: [
        { key: '热门手机', items: ['iPhone 16 Pro', '华为 Mate 70', '小米 15', '荣耀 Magic', 'vivo X200', 'OPPO Find'] },
        { key: '数码影音', items: ['无线降噪耳机', '运动蓝牙', '微单相机', '单反镜头', '运动相机', '拍立得'] },
        { key: '智能穿戴', items: ['智能手表', '健康手环', 'VR/AR眼镜', '儿童手表', '专业心率带'] }
      ]
    },
    2: {
      tags: ['轻薄本', '游戏本', '台式组装机', '机械键盘', '曲面电竞屏', 'AI电脑'],
      groups: [
        { key: '电脑整机', items: ['ThinkPad', 'ROG玩家国度', 'MacBook Air', '一体机', '迷你主机', '服务器'] },
        { key: '电脑配件', items: ['RTX 4090显卡', '英特尔酷睿', 'DDR5内存', '高速NVMe固态', '水冷散热器'] },
        { key: '办公外设', items: ['激光打印机', '投影仪', '扫描仪', '人体工学椅', '升降桌', '碎纸机'] }
      ]
    }
  };

  // 默认数据模板兜底
  function getSubData(index) {
    if (categoryData[index]) return categoryData[index];
    return {
      tags: ['品质爆款', '今日免息', '热卖榜TOP', '满减特惠', '好评精选'],
      groups: [
        { key: '热门推荐', items: ['畅销精选', '自营直发', '高口碑好物', '抢先体验', '新人立减'] },
        { key: '特色品类', items: ['大牌专区', '进口精选', '限时折扣', '正品包邮', '售后无忧'] },
        { key: '场景选购', items: ['居家生活', '送礼推荐', '办公实用', '户外运动', '精致护理'] }
      ]
    };
  }

  function renderSubPanel(index) {
    const data = getSubData(index);
    if (!tagsHeader || !detailGroups) return;

    // 渲染顶部标签
    tagsHeader.innerHTML = data.tags.map(t => `<a href="javascript:;" class="sub-tag-btn">${t} ›</a>`).join('');

    // 渲染分组内容
    detailGroups.innerHTML = data.groups.map(group => `
      <div class="sub-group-row">
        <div class="sub-group-key">${group.key} ›</div>
        <div class="sub-group-values">
          ${group.items.map(item => `<a href="javascript:;">${item}</a>`).join('')}
        </div>
      </div>
    `).join('');
  }

  let activeIndex = null;

  catItems.forEach((item, idx) => {
    item.addEventListener('mouseenter', () => {
      catItems.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      renderSubPanel(idx);
      subPanel.style.display = 'block';
      activeIndex = idx;
    });
  });

  categoryMenu?.addEventListener('mouseleave', (e) => {
    // 检查鼠标是否进入了 subPanel
    const toElem = e.relatedTarget;
    if (toElem && subPanel.contains(toElem)) return;
    subPanel.style.display = 'none';
    catItems.forEach(i => i.classList.remove('active'));
  });

  subPanel?.addEventListener('mouseleave', () => {
    subPanel.style.display = 'none';
    catItems.forEach(i => i.classList.remove('active'));
  });
}

/* ================= 4. 电梯导航 (Lift Elevator) ================= */
function initLiftNav() {
  const liftNav = document.getElementById('liftNav');
  const liftItems = document.querySelectorAll('.lift-item[data-target]');
  const btnBackTop = document.getElementById('btnBackTop');
  const btnFeedback = document.getElementById('btnFeedback');

  if (!liftNav) return;

  const targetSections = [];
  liftItems.forEach(item => {
    const targetId = item.getAttribute('data-target');
    const elem = document.getElementById(targetId);
    if (elem) {
      targetSections.push({ item, elem });
    }
  });

  // 平滑滚动点击
  targetSections.forEach(({ item, elem }) => {
    item.addEventListener('click', () => {
      const topPos = elem.getBoundingClientRect().top + window.scrollY - 70;
      window.scrollTo({
        top: topPos,
        behavior: 'smooth'
      });
    });
  });

  // 回到顶部
  btnBackTop?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  // 反馈
  btnFeedback?.addEventListener('click', () => {
    showToast('感谢您的反馈与建议！我们会不断完善页面体验。');
  });

  // 滚动监听高亮
  window.addEventListener('scroll', () => {
    const scrollPos = window.scrollY + 200;

    targetSections.forEach(({ item, elem }) => {
      const offsetTop = elem.offsetTop;
      const height = elem.offsetHeight;
      if (scrollPos >= offsetTop && scrollPos < offsetTop + height) {
        liftItems.forEach(i => i.classList.remove('active'));
        item.classList.add('active');
      }
    });
  });
}

/* ================= 5. 京东快报 Tab 切换 ================= */
function initNewsTab() {
  const tabs = document.querySelectorAll('.news-tab');
  const newsList = document.getElementById('newsList');

  const mockData = {
    news: [
      { tag: '特惠', text: '家电数码新一轮政府补贴开启预约！' },
      { tag: '热门', text: '京东物流发布春节“不打烊”服务承诺' },
      { tag: '活动', text: '大牌手机至高24期免息券限量抢' },
      { tag: '公益', text: '“暖冬计划”持续推进：爱心物资抵达' }
    ],
    notice: [
      { tag: '公告', text: '京东平台关于优化配送服务时效的通知' },
      { tag: '安全', text: '警惕新型假冒客服电信诈骗温馨提醒' },
      { tag: '升级', text: '售后上门取件免收运费服务全面升级' },
      { tag: '规则', text: '七天无理由退货细则最新补充说明' }
    ]
  };

  tabs.forEach(tab => {
    tab.addEventListener('mouseenter', () => {
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      const type = tab.getAttribute('data-tab');
      const items = mockData[type] || mockData.news;
      if (newsList) {
        newsList.innerHTML = items.map(item => `
          <li><span class="tag">${item.tag}</span><a href="javascript:;">${item.text}</a></li>
        `).join('');
      }
    });
  });
}

/* ================= 6. 加入购物车与 Toast 提示 ================= */
let cartTotalCount = 2;

function initCartAndToast() {
  const cartCountEl = document.getElementById('cartCount');
  const addButtons = document.querySelectorAll('.add-cart-btn, .sk-add-btn');

  addButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const name = btn.getAttribute('data-name') || '精选商品';
      cartTotalCount++;
      if (cartCountEl) {
        cartCountEl.textContent = cartTotalCount;
        cartCountEl.style.transform = 'scale(1.3)';
        setTimeout(() => {
          cartCountEl.style.transform = 'scale(1)';
        }, 200);
      }
      showToast(`已成功将「${name}」加入购物车！`);
    });
  });

  const couponBtns = document.querySelectorAll('.c-btn');
  couponBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      btn.textContent = '已领取';
      btn.style.backgroundColor = '#999';
      btn.disabled = true;
      showToast('优惠券领取成功！结算时自动抵扣。');
    });
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

/* ================= 7. 搜索栏与热词联动 ================= */
function initSearch() {
  const searchInput = document.getElementById('searchInput');
  const searchBtn = document.getElementById('searchBtn');
  const hotWords = document.querySelectorAll('.hot-words a');

  function doSearch(keyword) {
    if (!keyword) {
      keyword = searchInput?.placeholder || '爆款商品';
    }
    showToast(`正在为您检索：「${keyword}」...`);
  }

  searchBtn?.addEventListener('click', () => {
    doSearch(searchInput?.value.trim());
  });

  searchInput?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      window.location.href = 'list.html';
    }
  });

  hotWords.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      window.location.href = 'list.html';
    });
  });

  initProductNavigation();
}

/* ================= 8. 商品卡片跳转联动 ================= */
function initProductNavigation() {
  document.addEventListener('click', (e) => {
    // 忽略按钮、链接本身的点击
    if (e.target.closest('button') || e.target.closest('a')) return;

    // 如果点击了商品相关卡片，跳转至商品详情页
    const card = e.target.closest('.goods-card, .sk-item, .f-goods-card, .promo-card, .rank-item, .sub-item');
    if (card) {
      window.location.href = 'detail.html';
    }
  });

  const slideBtns = document.querySelectorAll('.slide-btn');
  slideBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      window.location.href = 'detail.html';
    });
  });
}
