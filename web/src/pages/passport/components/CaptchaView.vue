<template>
  <el-form :model="captchaForm" :rules="captchaRules" ref="captchaFormRef" class="captcha-form" size="large">
    <el-form-item prop="captcha" class="captcha">
      <el-input v-model="captchaForm.captcha" placeholder="请输入图片验证码"></el-input>
      <img :src="captchaImage" alt="验证码" @click="refreshCaptcha" class="captcha-image">
    </el-form-item>
    <el-form-item>
      <el-button type="primary" @click="submitForm(captchaFormRef)" class="login-btn">发送短信验证码</el-button>
    </el-form-item>
  </el-form>
</template>

<script setup lang="ts">
import {reactive, ref} from 'vue'
import type {FormInstance, FormRules} from 'element-plus'
import {captchaService} from "@/services/auth/CaptchaService";

const props = defineProps(['mobile'])

interface RuleForm {
  mobile: string
  captcha: string
  uuid: string
}

const captchaFormRef = ref<FormInstance>()
const captchaForm = reactive<RuleForm>({
  mobile: props.mobile,
  captcha: '',
  uuid: '',
})

// 定义登录表单的验证规则
const captchaRules = reactive<FormRules<RuleForm>>({
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
    captchaForm.uuid = res.uuid;
  })
};

// 初始化时获取验证码图片
refreshCaptcha();
</script>

<style scoped lang="scss">

</style>