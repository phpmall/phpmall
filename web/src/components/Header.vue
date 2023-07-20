<template>
  <div>
    <nav>
<!--      <RouterLink :to="{name: 'index'}">首页</RouterLink> | -->
<!--      <RouterLink :to="{name: 'catalog'}">全部目录</RouterLink> | -->
<!--      <RouterLink :to="{name: 'category'}">分类</RouterLink> | -->
<!--      <RouterLink :to="{name: 'article'}">文章</RouterLink> | -->
<!--      <RouterLink :to="{name: 'brand'}">品牌</RouterLink> | -->
<!--      <RouterLink :to="{name: 'goods'}">商品</RouterLink> | -->
      <RouterLink to="/login">登录</RouterLink>
      <RouterLink to="/signup">免费注册</RouterLink>
    </nav>
  </div>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'

</script>

<style scoped lang="scss">

</style>
