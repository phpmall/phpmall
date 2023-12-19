<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import type { FormInstance, FormRules } from 'element-plus'
import { commonCaptchaService } from '@/services/common';
import type { ILoginMobileRequest } from '@/types/auth';
import { apiAuthLoginMobileService } from '@/services/auth';

const loginFormRef = ref<FormInstance>()
const authStore = useAuthStore()

const checkAge = (rule: any, value: any, callback: any) => {
  if (!value) {
    return callback(new Error('Please input the age'))
  }
  setTimeout(() => {
    if (!Number.isInteger(value)) {
      callback(new Error('Please input digits'))
    } else {
      if (value < 18) {
        callback(new Error('Age must be greater than 18'))
      } else {
        callback()
      }
    }
  }, 1000)
}

const validatePass = (rule: any, value: any, callback: any) => {
  if (value === '') {
    callback(new Error('Please input the password'))
  } else {
    if (ruleForm.checkPass !== '') {
      if (!loginFormRef.value) return
      loginFormRef.value.validateField('checkPass', () => null)
    }
    callback()
  }
}
const validatePass2 = (rule: any, value: any, callback: any) => {
  if (value === '') {
    callback(new Error('Please input the password again'))
  } else if (value !== ruleForm.pass) {
    callback(new Error("Two inputs don't match!"))
  } else {
    callback()
  }
}

const ruleForm = reactive({
  pass: '',
  checkPass: '',
  age: ''
})

const rules = reactive<FormRules<typeof ruleForm>>({
  pass: [{ validator: validatePass, trigger: 'blur' }],
  checkPass: [{ validator: validatePass2, trigger: 'blur' }],
  age: [{ validator: checkAge, trigger: 'blur' }]
})

const submitForm = (formEl: FormInstance | undefined) => {
  if (!formEl) return
  formEl.validate((valid) => {
    if (valid) {
      const formData = ref<ILoginMobileRequest>({
        mobile: '', // 手机号码
        password: '', // 登录密码
        captcha: '', // 图片验证码
        uuid: '' // 图片验证码UUID参数
      })

      apiAuthLoginMobileService(formData.value).then((res) => {
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

onMounted(() => {
  commonCaptchaService().then((res) => {
    console.log(res);
  })
})
</script>

<template>
  <main>
    <h1>login page</h1>

    <el-form
      ref="loginFormRef"
      :model="ruleForm"
      status-icon
      :rules="rules"
      label-width="120px"
      class="loginForm"
    >
      <el-form-item label="Password" prop="pass">
        <el-input v-model="ruleForm.pass" type="password" autocomplete="off" />
      </el-form-item>
      <el-form-item label="Confirm" prop="checkPass">
        <el-input v-model="ruleForm.checkPass" type="password" autocomplete="off" />
      </el-form-item>
      <el-form-item label="Age" prop="age">
        <el-input v-model.number="ruleForm.age" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="submitForm(loginFormRef)">登 录</el-button>
        <el-button @click="resetForm(loginFormRef)">Reset</el-button>
      </el-form-item>
    </el-form>

    <RouterLink :to="{ name: 'admin.dashboard' }"> dashboard page </RouterLink>
  </main>
</template>
