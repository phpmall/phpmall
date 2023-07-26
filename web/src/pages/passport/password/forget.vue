<template>
  <div class="title">
    忘记密码
  </div>

  <div class="forget-wrap">
    <el-tabs v-model="activeName" class="forget-tabs">
      <el-tab-pane label="通过短信找回密码" name="default">
        <LoginForm/>
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
import {ref} from 'vue'
import LoginForm from '@/pages/passport/components/LoginForm.vue'

const activeName = ref('default')

</script>

<style scoped lang="scss">
.title {
  color: #282d3c;
  font-size: 28px;
  font-weight: bold;
  margin: 60px 0 20px 50px;
}

.forget-wrap {
  margin: 0 50px;
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
