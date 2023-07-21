<template>
  <div>
    <nav>
      <RouterLink :to="{name: 'portal.index'}">首页</RouterLink> |
      <RouterLink :to="{name: 'portal.catalog'}">全部目录</RouterLink> |
      <RouterLink :to="{name: 'portal.category'}">分类</RouterLink> |
      <RouterLink :to="{name: 'portal.article'}">文章</RouterLink> |
      <RouterLink :to="{name: 'portal.brand'}">品牌</RouterLink> |
      <RouterLink :to="{name: 'portal.goods'}">商品</RouterLink> |
      <RouterLink :to="{name: 'passport.login'}">登录</RouterLink>
      <RouterLink :to="{name: 'passport.signup'}">免费注册</RouterLink>
    </nav>
  </div>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'

</script>

<style scoped lang="scss">

</style>
