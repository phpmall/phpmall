<script setup>
import { ref } from 'vue'

const loginData = ref({
  phone: '',
  code: ''
});

const loginRules = ref({
  phone: [{ required: true, message: '请输入手机号码', trigger: 'blur' }],
  code: [{ required: true, message: '请输入验证码', trigger: 'blur' }]
});

const showBtn = ref(true);
const totalTime = ref(60);
let timer;

// 发送短信验证码
const sendCode = () => {
  // 省略短信验证码发送逻辑
  showBtn.value = false;
  timer = setInterval(() => {
    if (totalTime.value > 0) {
      totalTime.value--;
    } else {
      clearInterval(timer);
      totalTime.value = 60;
      showBtn.value = true;
    }
  }, 1000);
};

// 提交表单
const submitForm = () => {
  const valid = $refs.loginForm.validate();
  if (valid) {
    // 省略短信验证码登录逻辑
    ElMessage.success('登录成功！');
  } else {
    return false;
  }
};
</script>

<template>
<!-- 
<div class="layui-form-item">
              <div class="layui-row">
                <div class="layui-col-xs7">
                  <div class="layui-input-wrap">
                    <div class="layui-input-prefix">
                      <i class="layui-icon layui-icon-cellphone"></i>
                    </div>
                    <input type="text" name="cellphone" value="" lay-verify="phone" placeholder="手机号" lay-reqtext="请填写手机号"
                      autocomplete="off" class="layui-input" id="reg-cellphone">
                  </div>
                </div>
                <div class="layui-col-xs5">
                  <div style="margin-left: 11px;">
                    <button type="button" class="layui-btn layui-btn-fluid layui-btn-primary"
                      lay-on="reg-get-vercode">获取验证码
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="layui-form-item">
              <div class="layui-input-wrap">
                <div class="layui-input-prefix">
                  <i class="layui-icon layui-icon-vercode"></i>
                </div>
                <input type="text" name="vercode" value="" lay-verify="required" placeholder="验证码" lay-reqtext="请填写验证码"
                  autocomplete="off" class="layui-input">
              </div>
            </div> -->
  <el-form :model="loginData" :rules="loginRules" label-width="100px" class="login-form">
    <el-form-item label="手机号码" prop="phone">
      <el-input v-model="loginData.phone" placeholder="请输入手机号码"></el-input>
    </el-form-item>
    <el-form-item label="验证码" prop="code">
      <el-input v-model="loginData.code" placeholder="请输入验证码"></el-input>
      <el-button type="primary" v-if="showBtn" @click="sendCode">获取验证码</el-button>
      <el-button type="primary" v-else :disabled="true">{{ totalTime }}s</el-button>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" @click="submitForm">登录</el-button>
    </el-form-item>
  </el-form>
</template>

<style scoped>

</style>
