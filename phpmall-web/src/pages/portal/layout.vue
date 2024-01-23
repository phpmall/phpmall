<template>
  <div class="header">
    <div class="shortcut">
      <el-row>
        <el-col :span="15" class="welcome-user">
          您好，
          <template v-if="auth.isLoggedIn()">
            <RouterLink :to="{name: 'home.index'}">会员中心</RouterLink>
            <a href="#" @click="auth.clearJwt()">注销</a>
          </template>
          <template v-else>
            <RouterLink :to="{name: 'passport.login'}">请登录</RouterLink>
            <RouterLink :to="{name: 'passport.signup'}">免费注册</RouterLink>
          </template>
        </el-col>
        <el-col :span="9" class="links">
          <ul>
            <li>
              <RouterLink :to="{name: 'home.order.index'}">我的订单</RouterLink>
            </li>
            <li>
              <a href="">用户中心</a>
              <ul class="sub">
                <li><RouterLink :to="{name: 'home.index'}">我的浏览</RouterLink></li>
                <li><RouterLink :to="{name: 'home.collect.index'}">我的收藏</RouterLink></li>
                <li><RouterLink :to="{name: 'home.enquiry.index'}">我的询价单</RouterLink></li>
              </ul>
            </li>
            <li>
              <RouterLink :to="{name: 'home.index'}">商家服务</RouterLink>
            </li>
            <li>
              <RouterLink :to="{name: 'home.index'}">网址导航</RouterLink>
            </li>
            <li>
              <RouterLink :to="{name: 'home.index'}">客户服务</RouterLink>
            </li>
            <li>
              <a href="#">收藏网址</a>
            </li>
          </ul>
        </el-col>
      </el-row>
    </div>
    <div class="main">
      header
    </div>
    <div class="menu">

    </div>
  </div>
  <div class="main-wrap">
    <RouterView/>
  </div>
  <div class="footer">
    footer
  </div>
</template>

<script setup lang="ts">
import {RouterLink, RouterView} from 'vue-router'
import {useAuthStore} from "@/stores/auth";

const auth = useAuthStore()
</script>

<style scoped lang="scss">
@import "@/assets/portal/header";
@import "@/assets/portal/footer";

.main {
  width: 1190px;
  margin: 0 auto;
}
</style>
