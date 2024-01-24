<template>
  <div class="login-switch">
    <a href="javascript:void(0);">
      <img src="@/assets/passport/img/switch-qrcode.png" v-on:click="showQrCode = !showQrCode"
           v-if="!showQrCode" alt="">
      <img src="@/assets/passport/img/switch-mobile.png" v-on:click="showQrCode = !showQrCode" v-else alt="">
    </a>
  </div>

  <div class="title">
    欢迎登录
  </div>

  <div class="mobile-wrap" v-if="!showQrCode">
    <el-tabs v-model="activeName" class="login-tabs">
      <el-tab-pane label="帐号登录" name="default">
        <el-form :model="loginForm" :rules="loginRules" ref="loginFormRef" class="login-form" size="large">
          <el-form-item prop="mobile">
            <el-input v-model="loginForm.mobile" placeholder="请输入手机号码"></el-input>
          </el-form-item>
          <el-form-item prop="password">
            <el-input v-model="loginForm.password" placeholder="请输入登录密码"
                      type="password" show-password></el-input>
          </el-form-item>
          <el-form-item prop="captcha" class="captcha">
            <el-input v-model="loginForm.captcha" placeholder="请输入图片验证码"></el-input>
            <img v-if="captchaImage !== ''" :src="captchaImage" alt="验证码"
                 @click="refreshCaptcha" class="captcha-image">
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitForm(loginFormRef)" class="login-btn">登 录</el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
      <!--<el-tab-pane label="短信登录" name="sms"></el-tab-pane>-->
    </el-tabs>

    <div class="login-other">
      <RouterLink :to="{ name: 'passport.signup' }">免费注册</RouterLink>
      <span> | </span>
      <RouterLink :to="{ name: 'passport.password.forget' }">忘记密码？</RouterLink>
    </div>
  </div>

  <div class="qrcode-wrap" v-else>
    qrcode
  </div>
</template>

<script setup lang="ts">
import {reactive, ref} from 'vue'
import type {FormInstance, FormRules} from 'element-plus'
import md5 from 'crypto-js/md5'
import {captchaService} from "@/services/auth/CaptchaService"
import {loginService} from "@/services/auth/LoginService";

const showQrCode = ref(false)
const activeName = ref('default')
const captchaImage = ref('');

interface RuleForm {
  mobile: string
  password: string
  captcha: string
  uuid: string
}

const loginFormRef = ref<FormInstance>()
const loginForm = reactive<RuleForm>({
  mobile: '',
  password: '',
  captcha: '',
  uuid: ''
})

// 获取验证码图片
const refreshCaptcha = () => {
  captchaService().then(res => {
    // 在这里发起获取验证码图片的请求，更新captchaImage的值
    captchaImage.value = res.captcha
    loginForm.uuid = res.uuid
  })
};

// 初始化时获取验证码图片
refreshCaptcha();

// 定义登录表单的验证规则
const loginRules = reactive<FormRules<RuleForm>>({
  mobile: [
    {required: true, message: '请输入手机号码', trigger: 'blur'},
    {min: 11, max: 11, message: '手机号码格式不正确', trigger: 'blur'}
  ],
  password: [
    {required: true, message: '请输入登录密码', trigger: 'blur'},
    {min: 6, message: '登录密码格式不不符合', trigger: 'blur'}
  ],
  captcha: [
    {required: true, message: '请输入图片验证码', trigger: 'blur'},
    {min: 4, max: 4, message: '图片验证码格式不正确', trigger: 'blur'}
  ]
});

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return
  await formEl.validate((valid, fields) => {
    if (valid) {
      loginForm.password = md5(loginForm.password+loginForm.mobile).toString()
      loginService(loginForm).then(res => {
        console.log(res)
      })
      console.log('submit!')
    } else {
      console.log('error submit!', fields)
    }
  })
}
</script>

<style scoped lang="scss">
.login-switch {
  display: none;
  position: absolute;
  top: 0;
  right: 0;

  img {
    width: 80px;
  }
}

.title {
  color: #282d3c;
  font-size: 28px;
  font-weight: bold;
  margin: 50px 0 20px 40px;
}

.mobile-wrap, .qrcode-wrap {
  margin: 0 40px;
}

.login-form {
  padding: 10px;

  .login-btn {
    border: none;
    background: #FF2832;
    width: 100%;
  }
}

.captcha {
  position: relative;

  .captcha-image {
    position: absolute;
    right: 1px;
    height: 38px;
    cursor: pointer;
  }
}

.login-other {
  text-align: center;

  a {
    font-size: 14px;
    color: #FF2832;
    text-decoration: none;
  }

  span {
    margin: 0 20px;
    color: lightgray;
  }
}
</style>

<style lang="scss">
.login-tabs {
  .el-tabs__item {
    color: #FF2832;
    padding: 20px;
    height: auto;
  }

  .el-tabs__active-bar {
    background-color: #FF2832;
  }

  .el-input__wrapper.is-focus {
    --el-input-focus-border-color: #FF2832;
  }
}
</style>
