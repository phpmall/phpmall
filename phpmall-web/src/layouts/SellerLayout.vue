<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <el-container>
      <el-header>
        seller logo
        <RouterLink to='/'>返回首页</RouterLink>
      </el-header>
      <el-container>
        <el-aside width="200px">Aside</el-aside>
        <el-main>
          <RouterView />
        </el-main>
      </el-container>
    </el-container>
</template>

<style scoped lang="scss"></style>
