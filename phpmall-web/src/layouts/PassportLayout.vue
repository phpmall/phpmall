<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <el-container>
      <el-header>
        <RouterLink to='/'>logo</RouterLink>
      </el-header>
      <el-main>
        <RouterView />
      </el-main>
    </el-container>
</template>

<style scoped lang="scss"></style>
