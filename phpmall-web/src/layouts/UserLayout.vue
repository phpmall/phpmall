<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <el-container>
      <el-aside width="200px">
        user Aside
        <RouterLink :to="{name: 'user'}">会员中心</RouterLink>
        <RouterLink :to="{name: 'user.order'}">我的订单</RouterLink>
      </el-aside>
      <el-main>
        <RouterView />
      </el-main>
    </el-container>
</template>
