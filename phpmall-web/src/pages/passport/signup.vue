<template>
  <div class="title">
    免费注册
  </div>

  <div class="signup-wrap">
    <el-tabs v-model="activeName" class="signup-tabs">
      <el-tab-pane label="短信注册" name="default">
        <el-form :model="signupForm" :rules="signupRules" ref="signupFormRef" class="signup-form" size="large">
          <el-form-item prop="mobile">
            <el-input v-model="signupForm.mobile" placeholder="请输入手机号码"></el-input>
          </el-form-item>
          <el-form-item prop="code" class="send-wrap">
            <el-input v-model="signupForm.sms_code"></el-input>
            <el-button link @click="toggleCaptcha" :disabled="countdown > -1" class="send-btn">
              <template v-if="countdown === -1">发送验证码</template>
              <template v-else>重新发送({{ countdown }}s)</template>
            </el-button>
          </el-form-item>
          <el-form-item prop="password">
            <el-input type="password" v-model="signupForm.password" placeholder="请输入登录密码"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitForm(signupFormRef)" class="login-btn">注 册</el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
    </el-tabs>

    <div class="signup-other">
      <RouterLink :to="{ name: 'passport.login' }">返回登录</RouterLink>
    </div>
  </div>

  <div v-show="showCaptcha">
    <CaptchaView :mobile="signupForm.mobile"/>
  </div>
</template>

<script setup lang="ts">
import {reactive, ref} from 'vue'
import type {FormInstance, FormRules} from 'element-plus'
import CaptchaView from "@/pages/passport/components/CaptchaView.vue";

const activeName = ref('default')
const countdown = ref(-1)
const showCaptcha = ref(false)

interface RuleForm {
  mobile: string
  sms_code: string
  uuid: string
  password: string
}

const signupFormRef = ref<FormInstance>()
const signupForm = reactive<RuleForm>({
  mobile: '',
  sms_code: '',
  uuid: '',
  password: '',
})

// 定义登录表单的验证规则
const signupRules = reactive<FormRules<RuleForm>>({
  mobile: [
    {required: true, message: '请输入手机号码', trigger: 'blur'},
    {min: 11, max: 11, message: '手机号码格式不正确', trigger: 'blur'}
  ],
  sms_code: [
    {required: true, message: '请输入短信验证码', trigger: 'blur'}
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

// 显示图片验证码
const toggleCaptcha = () => {
  showCaptcha.value = !showCaptcha.value
}

// 发送短信验证码
const sendCode = () => {
  // 发送验证码逻辑，倒计时实现示例
  countdown.value = 60;
  const countdownTimer = setInterval(() => {
    if (countdown.value > 0) {
      countdown.value--;
    } else {
      clearInterval(countdownTimer);
    }
  }, 1000);
}
</script>

<style scoped lang="scss">
.title {
  color: #282d3c;
  font-size: 28px;
  font-weight: bold;
  margin: 50px 0 20px 40px;
}

.signup-wrap {
  margin: 0 40px;
}

.signup-form {
  padding: 10px;

  .send-wrap {
    position: relative;

    .send-btn {
      position: absolute;
      right: 10px;
    }
  }

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

.signup-other {
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
.signup-tabs {
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
