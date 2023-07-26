<template>
  <div class="title">
    免费注册
  </div>

  <div class="signup-wrap">
    <el-tabs v-model="activeName" class="signup-tabs">
      <el-tab-pane label="短信注册" name="default">
        <LoginForm/>
      </el-tab-pane>
    </el-tabs>

    <div class="signup-other">
      <RouterLink :to="{ name: 'passport.login' }">立即登录</RouterLink>
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

.signup-wrap {
  margin: 0 50px;
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
}
</style>
