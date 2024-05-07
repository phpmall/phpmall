<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import type { FormInstance, FormRules } from 'element-plus'
import { captchaService } from '@/services/portal'
import type { ILoginRequest } from '@/types/auth'
import { loginService } from '@/services/auth'

const loginFormRef = ref<FormInstance>()
const authStore = useAuthStore()

const formData = reactive<ILoginRequest>({
  username: '',
  password: '',
  captcha: '',
  uuid: ''
})

const captchaImage = ref<string>('')

onMounted(() => {
  loadCaptcha()
})

const loadCaptcha = () => {
  captchaService().then((res) => {
    captchaImage.value = res.captcha
    formData.uuid = res.uuid
  })
}

const rules = reactive<FormRules<typeof formData>>({
  username: [{ trigger: 'blur' }],
  password: [{ trigger: 'blur' }],
  captcha: [{ trigger: 'blur' }]
})

const submitForm = (formEl: FormInstance | undefined) => {
  if (!formEl) return
  formEl.validate((valid) => {
    if (valid) {
      loginService(formData).then((res) => {
        console.log(res)

        authStore.login('jwt string')
      })

      console.log('submit!')
    } else {
      console.log('error submit!')
      return false
    }
  })
}

const resetForm = (formEl: FormInstance | undefined) => {
  if (!formEl) return
  formEl.resetFields()
}
</script>

<template>
  <div class="auth">
    <div class="header">
      <div class="left">
        <RouterLink to="/">Home</RouterLink>
      </div>
      <div class="right">right</div>
    </div>
    <div class="boxes">
      <h1>login page</h1>

      <el-form
        ref="loginFormRef"
        :model="formData"
        status-icon
        :rules="rules"
        label-width="120px"
        class="loginForm"
      >
        <el-form-item label="用户名" prop="username">
          <el-input v-model="formData.username" type="text" autocomplete="off" />
        </el-form-item>
        <el-form-item label="登录密码" prop="password">
          <el-input v-model="formData.password" type="password" autocomplete="off" />
        </el-form-item>
        <el-form-item label="图片验证码" prop="captcha" v-if="captchaImage">
          <el-input v-model="formData.captcha" type="text" autocomplete="off" />
          <el-image :src="captchaImage" @click="loadCaptcha" class="captcha" />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submitForm(loginFormRef)">登 录</el-button>
          <el-button @click="resetForm(loginFormRef)">Reset</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div class="footer">footer copyright</div>
  </div>
</template>
