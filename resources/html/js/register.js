/**
 * 京东用户注册交互脚本
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Toast 工具函数
  const toastEl = document.getElementById('toast');
  let toastTimer = null;
  function showToast(msg, duration = 2800) {
    if (!toastEl) return;
    toastEl.innerText = msg;
    toastEl.className = 'toast show';
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      toastEl.className = 'toast';
    }, duration);
  }

  // 2. 元素获取
  const regForm = document.getElementById('regForm');
  const regPhone = document.getElementById('regPhone');
  const btnSendCode = document.getElementById('btnSendCode');
  const regCode = document.getElementById('regCode');
  const regPwd = document.getElementById('regPwd');
  const regConfirmPwd = document.getElementById('regConfirmPwd');
  const regUsername = document.getElementById('regUsername');
  const checkTerms = document.getElementById('checkTerms');

  const pill1 = document.getElementById('pill1');
  const pill2 = document.getElementById('pill2');
  const pill3 = document.getElementById('pill3');
  const strengthTxt = document.getElementById('strengthTxt');

  const successPanel = document.getElementById('successPanel');
  const step1 = document.getElementById('step1');
  const step2 = document.getElementById('step2');
  const step3 = document.getElementById('step3');
  const line1 = document.getElementById('line1');
  const line2 = document.getElementById('line2');

  // 3. 获取验证码倒计时
  let countdownTimer = null;
  let countdownSeconds = 60;

  if (btnSendCode) {
    btnSendCode.addEventListener('click', () => {
      const phone = regPhone.value.trim();
      if (!/^1[3-9]\d{9}$/.test(phone)) {
        showToast('⚠️ 请先输入正确的 11 位手机号码');
        regPhone.focus();
        return;
      }

      btnSendCode.disabled = true;
      countdownSeconds = 60;
      btnSendCode.innerText = `重新获取(${countdownSeconds}s)`;

      const mockCode = '888666';
      showToast(`📲 验证码已发送至 ${phone.slice(0, 3)}****${phone.slice(7)}，测试验证码：${mockCode}`);
      if (regCode) regCode.value = mockCode;

      countdownTimer = setInterval(() => {
        countdownSeconds--;
        if (countdownSeconds > 0) {
          btnSendCode.innerText = `重新获取(${countdownSeconds}s)`;
        } else {
          clearInterval(countdownTimer);
          btnSendCode.disabled = false;
          btnSendCode.innerText = '获取验证码';
        }
      }, 1000);
    });
  }

  // 4. 密码强度实时检测
  if (regPwd) {
    regPwd.addEventListener('input', () => {
      const pwd = regPwd.value;
      pill1.className = 's-pill';
      pill2.className = 's-pill';
      pill3.className = 's-pill';

      if (!pwd) {
        strengthTxt.innerText = '未输入';
        return;
      }

      let score = 0;
      if (pwd.length >= 6) score++;
      if (/[A-Z]/.test(pwd) || /[a-z]/.test(pwd)) score++;
      if (/\d/.test(pwd)) score++;
      if (/[!@#$%^&*(),.?":{}|<>]/.test(pwd)) score++;

      if (score <= 2) {
        pill1.className = 's-pill weak';
        strengthTxt.innerText = '强度：弱';
        strengthTxt.style.color = '#ff4d4f';
      } else if (score === 3) {
        pill1.className = 's-pill medium';
        pill2.className = 's-pill medium';
        strengthTxt.innerText = '强度：中';
        strengthTxt.style.color = '#faad14';
      } else {
        pill1.className = 's-pill strong';
        pill2.className = 's-pill strong';
        pill3.className = 's-pill strong';
        strengthTxt.innerText = '强度：高 (安全)';
        strengthTxt.style.color = '#52c41a';
      }
    });
  }

  // 5. 表单提交注册
  if (regForm) {
    regForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const phone = regPhone.value.trim();
      const code = regCode.value.trim();
      const pwd = regPwd.value;
      const confirmPwd = regConfirmPwd.value;
      const username = regUsername.value.trim();

      if (!/^1[3-9]\d{9}$/.test(phone)) {
        showToast('⚠️ 手机号码格式有误');
        return;
      }

      if (code.length < 4) {
        showToast('⚠️ 请输入有效的短信验证码');
        return;
      }

      if (pwd.length < 6) {
        showToast('⚠️ 登录密码长度不能少于 6 位');
        return;
      }

      if (pwd !== confirmPwd) {
        showToast('❌ 两次输入的密码不一致，请核对');
        regConfirmPwd.focus();
        return;
      }

      if (!checkTerms.checked) {
        showToast('请阅读并勾选京东用户注册协议');
        return;
      }

      // 切换到注册成功流程
      step1.className = 'step-item completed';
      line1.style.background = '#52c41a';
      step2.className = 'step-item completed';
      line2.style.background = '#52c41a';
      step3.className = 'step-item active';

      regForm.style.display = 'none';
      if (successPanel) successPanel.style.display = 'block';

      showToast(`🎉 恭喜【${username}】，账号注册成功！188元新人礼包已到账`);
    });
  }
});
