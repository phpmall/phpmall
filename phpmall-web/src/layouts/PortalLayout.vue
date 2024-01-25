<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <el-container>
    <el-header>
      <header>
        <div>
          <nav>
            <RouterLink to="/">Home</RouterLink>
            <RouterLink to="/about">About</RouterLink>
            <RouterLink :to="{ name: 'passport.login' }">登录</RouterLink>
            <RouterLink :to="{ name: 'user' }">会员中心</RouterLink>
          </nav>
        </div>
      </header>
    </el-header>
    <el-main>
      <RouterView />
    </el-main>
    <el-footer>Footer</el-footer>
  </el-container>
</template>

<style scoped lang="scss"></style>
