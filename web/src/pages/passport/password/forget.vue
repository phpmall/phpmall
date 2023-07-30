<template>
  <div class="title">
    忘记密码
  </div>

  <div class="forget-wrap">
    <el-tabs v-model="activeName" class="forget-tabs">
      <el-tab-pane label="通过短信找回密码" name="default">
        <el-form :model="forgetForm" :rules="forgetRules" ref="forgetFormRef" class="forget-form" size="large">
          <el-form-item prop="mobile">
            <el-input v-model="forgetForm.mobile" placeholder="请输入手机号码"></el-input>
          </el-form-item>
          <el-form-item prop="captcha" class="captcha">
            <el-input v-if="captchaImage !== ''" v-model="forgetForm.captcha" placeholder="请输入图片验证码"></el-input>
            <img :src="captchaImage" alt="验证码" @click="refreshCaptcha" class="captcha-image">
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitForm(forgetFormRef)" class="login-btn">发送短信验证码</el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
    </el-tabs>

    <div class="forget-other">
      <RouterLink :to="{ name: 'passport.login' }">立即登录</RouterLink>
      <span> | </span>
      <RouterLink :to="{ name: 'passport.signup' }">免费注册</RouterLink>
    </div>
  </div>
</template>

<script setup lang="ts">
import {reactive, ref} from 'vue'
import type {FormInstance, FormRules} from 'element-plus'
import {captchaService} from "@/services/auth/CaptchaService";

interface RuleForm {
  mobile: string
  captcha: string
  uuid: string
}

const forgetFormRef = ref<FormInstance>()
const forgetForm = reactive<RuleForm>({
  mobile: '',
  captcha: '',
  uuid: '',
})

// 定义登录表单的验证规则
const forgetRules = reactive<FormRules<RuleForm>>({
  mobile: [
    {required: true, message: '请输入手机号码', trigger: 'blur'},
    {min: 11, max: 11, message: '手机号码格式不正确', trigger: 'blur'}
  ],
  captcha: [
    {required: true, message: '请输入图片验证码', trigger: 'blur'}
  ],
  uuid: [
    {required: true, message: '请输入图片验证码UUID', trigger: 'blur'}
  ]
});

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return
  await formEl.validate((valid, fields) => {
    if (valid) {
      console.log('submit!')
    } else {
      console.log('error submit!', fields)
    }
  })
}

// 获取验证码图片
const captchaImage = ref('');
const refreshCaptcha = () => {
  captchaService().then(res => {
    // 在这里发起获取验证码图片的请求，更新captchaImage的值
    captchaImage.value = res.captcha;
  })
};

// 初始化时获取验证码图片
refreshCaptcha();

const activeName = ref('default')

</script>

<style scoped lang="scss">
.title {
  color: #282d3c;
  font-size: 28px;
  font-weight: bold;
  margin: 50px 0 20px 40px;
}

.forget-wrap {
  margin: 0 40px;
}

.forget-form {
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

.forget-other {
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
.forget-tabs {
  .el-tabs__item {
    color: #FF2832;
    padding: 20px;
    height: auto;
  }

  .el-tabs__active-bar {
    background-color: #FF2832;
  }
}
</style>
