<script setup lang="ts">
import { RouterLink, RouterView, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const logout = () => {
  authStore.logout()
  router.push({ name: 'passport.login' })
}
</script>

<template>
  <!-- <el-container>
    <el-header>
      
    </el-header>
    <el-main>
      <RouterView />
    </el-main>
    <el-footer>Footer</el-footer>
  </el-container> -->

  <div class="header">
    <div class="shortcut">
      <el-row>
        <el-col :span="15" class="welcome-user">
          您好，
          <template v-if="authStore.check()">
            <RouterLink :to="{ name: 'user' }">会员中心</RouterLink>
            <a href="#" @click="logout">注销</a>
          </template>
          <template v-else>
            <RouterLink :to="{ name: 'passport.login' }">请登录</RouterLink>
            <RouterLink :to="{ name: 'passport.signup' }">免费注册</RouterLink>
          </template>
        </el-col>
        <el-col :span="9" class="links">
          <ul>
            <li>
              <RouterLink :to="{ name: 'user.order' }">我的订单</RouterLink>
            </li>
            <li>
              <a href="">用户中心</a>
              <ul class="sub">
                <li><RouterLink :to="{ name: 'user' }">我的浏览</RouterLink></li>
                <li><RouterLink :to="{ name: 'user' }">我的收藏</RouterLink></li>
                <li><RouterLink :to="{ name: 'user' }">我的询价单</RouterLink></li>
              </ul>
            </li>
            <li>
              <RouterLink :to="{ name: 'user' }">商家服务</RouterLink>
            </li>
            <li>
              <RouterLink :to="{ name: 'user' }">网址导航</RouterLink>
            </li>
            <li>
              <RouterLink :to="{ name: 'user' }">客户服务</RouterLink>
            </li>
            <li>
              <a href="#">收藏网址</a>
            </li>
          </ul>
        </el-col>
      </el-row>
    </div>
    <div class="main">
      header <RouterLink to="/">Home</RouterLink> | <RouterLink to="/about">About</RouterLink> |
    </div>
    <div class="menu">menu</div>
  </div>
  <div class="main-wrap">
    <RouterView />
  </div>
  <div class="footer">footer</div>
</template>

<style scoped lang="scss">
@import '@/assets/portal/header';
@import '@/assets/portal/footer';

.main {
  width: 1190px;
  margin: 0 auto;
}
</style>
