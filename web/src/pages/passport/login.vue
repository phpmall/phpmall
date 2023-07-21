<template>
  <div class="login-switch">
    <a href="javascript:void(0);">
      <img src="@/assets/auth/img/switch-qrcode.png" v-on:click="showQrCode = !showQrCode"
        v-if="!showQrCode">
      <img src="@/assets/auth/img/switch-mobile.png" v-on:click="showQrCode = !showQrCode" v-else>
    </a>
  </div>

  <div class="mobile-wrap" v-if="!showQrCode">
    <el-tabs v-model="activeName" class="login-tabs">
      <el-tab-pane label="帐号登录" name="default">
        <LoginForm />
      </el-tab-pane>
      <!-- <el-tab-pane label="短信登录" name="sms">
        TODO
      </el-tab-pane> -->
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
import { reactive, ref } from 'vue'
import LoginForm from '@/pages/passport/components/LoginForm.vue'

const showQrCode = ref(false)
const activeName = ref('default')
</script>

<style scoped lang="scss">
.login-switch {
  position: absolute;
  top: 0;
  right: 0;
  img {
    width: 80px;
  }
}

.login-other {
  text-align: center;

  a {
    font-size: 14px;
    color: red;
    text-decoration: none;
  }

  span {
    margin: 0 20px;
    color: lightgray;
  }
}
</style>
