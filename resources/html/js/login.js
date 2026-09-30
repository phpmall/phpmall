/**
 * 用户登录页交互逻辑 (login.js)
 */

document.addEventListener('DOMContentLoaded', () => {
  initLoginTabs();
  initLoginForm();
});

function initLoginTabs() {
  const tabBtns = document.querySelectorAll('.login-tab-head .tab-btn');
  const panels = document.querySelectorAll('.login-panel');

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.getAttribute('data-tab');
      tabBtns.forEach(b => b.classList.remove('active'));
      panels.forEach(p => p.classList.remove('active'));

      btn.classList.add('active');
      const targetPanel = document.getElementById(targetId);
      if (targetPanel) {
        targetPanel.classList.add('active');
      }
    });
  });
}

function initLoginForm() {
  const form = document.getElementById('loginForm');
  const btnRegister = document.getElementById('btnRegister');
  const oauthLinks = document.querySelectorAll('.oauth-links a');

  form?.addEventListener('submit', (e) => {
    e.preventDefault();
    const uname = document.getElementById('username')?.value.trim();
    if (!uname) {
      showToast('请输入您的账号或手机号');
      return;
    }
    showToast(`登录成功，欢迎回来 ${uname}！正在为您跳转...`);
    setTimeout(() => {
      window.location.href = 'index.html';
    }, 1200);
  });

  btnRegister?.addEventListener('click', () => {
    showToast('已进入新用户注册通道，请输入手机号验证码');
  });

  oauthLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      showToast(`正在调起 ${link.textContent.trim()} 授权登录...`);
      setTimeout(() => {
        window.location.href = 'index.html';
      }, 1200);
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
