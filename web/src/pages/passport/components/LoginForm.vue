<template>
    <el-form :model="loginForm" :rules="loginRules" ref="loginFormRef" class="login-form" size="large">
        <el-form-item prop="mobile">
            <el-input v-model="loginForm.mobile" placeholder="请输入手机号码"></el-input>
        </el-form-item>
        <el-form-item prop="password">
            <el-input type="password" v-model="loginForm.password" placeholder="请输入登录密码"></el-input>
        </el-form-item>
        <el-form-item prop="captcha" class="captcha">
            <el-input v-model="loginForm.captcha" placeholder="请输入图片验证码"></el-input>
            <img :src="captchaImage" alt="验证码" @click="refreshCaptcha" class="captcha-image">
        </el-form-item>
        <el-form-item>
            <el-button type="primary" @click="submitForm(loginFormRef)" class="login-btn">登 录</el-button>
        </el-form-item>
    </el-form>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import type { FormInstance, FormRules } from 'element-plus'

interface RuleForm {
    mobile: string
    password: string
    captcha: string
}

const loginFormRef = ref<FormInstance>()
const loginForm = reactive<RuleForm>({
    mobile: '',
    password: '',
    captcha: '',
})

// 定义登录表单的验证规则
const loginRules = reactive<FormRules<RuleForm>>({
    mobile: [
        { required: true, message: '请输入手机号码', trigger: 'blur' },
        { min: 11, max: 11, message: '手机号码格式不正确', trigger: 'blur' }
    ],
    password: [
        { required: true, message: '请输入登录密码', trigger: 'blur' }
    ],
    captcha: [
        { required: true, message: '请输入验证码', trigger: 'blur' }
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

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.resetFields()
}

// 获取验证码图片
const captchaImage = ref('');
const refreshCaptcha = () => {
    // 在这里发起获取验证码图片的请求，更新captchaImage的值
    captchaImage.value = `https://api.phpmall.net/captcha?t=?${Date.now()}`;
};

// 初始化时获取验证码图片
refreshCaptcha();
</script>

<style scoped lang="scss">
.el-tabs__item:hover,
.el-tabs__item.is-active
{
  color: #FF2832 !important;
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
</style>