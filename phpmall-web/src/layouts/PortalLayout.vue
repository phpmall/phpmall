<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <header>
    <div>
      <nav>
        <RouterLink to="/">Home</RouterLink>
        <RouterLink to="/about">About</RouterLink>
        <RouterLink :to="{name: 'passport.login'}">登录</RouterLink>
        <RouterLink :to="{name: 'user'}">会员中心</RouterLink>
      </nav>
    </div>
  </header>
  <div class="page">
    <RouterView />
  </div>
</template>

<style scoped lang="scss"></style>
