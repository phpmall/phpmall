<script setup lang="ts">
import { reactive, ref } from 'vue'
import type { FormInstance, FormRules } from 'element-plus'
import type { ILoginRequest } from '@/types/auth'

const loginFormRef = ref<FormInstance>()

const loginForm = reactive<ILoginRequest>({
  username: '', // 登录用户名
  password: '', // 登录密码
  captcha: '', // 图片验证码
  uuid: '' // 图片验证码UUID
})

const rules = reactive<FormRules<typeof loginForm>>({
  username: [{ required: true, trigger: 'blur' }],
  password: [{ required: true, trigger: 'blur' }],
  captcha: [{ required: true, trigger: 'blur' }]
})

const submitForm = (formEl: FormInstance | undefined) => {
  if (!formEl) return
  formEl.validate((valid) => {
    if (valid) {
      console.log('submit!')
    } else {
      console.log('error submit!')
      return false
    }
  })
}
</script>

<template>
  <div class="page">
    login page content

    <el-form ref="loginFormRef" :model="loginForm" :rules="rules">
      <el-form-item prop="username">
        <el-input v-model="loginForm.username" placeholder="登录手机号码" autocomplete="off" />
      </el-form-item>
      <el-form-item prop="password">
        <el-input
          v-model="loginForm.password"
          placeholder="登录密码"
          show-password
          autocomplete="off"
        />
      </el-form-item>
      <el-form-item prop="captcha">
        <el-input v-model="loginForm.captcha" placeholder="图片验证码" autocomplete="off" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="submitForm(loginFormRef)">Submit</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>
