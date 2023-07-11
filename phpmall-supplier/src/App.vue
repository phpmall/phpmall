<script setup lang="ts">
import { RouterView } from 'vue-router'
import zhCn from 'element-plus/dist/locale/zh-cn.mjs'
import Header from '@/components/Header.vue'
</script>

<template>
  <el-config-provider :locale="zhCn">
    <Header/>
    <RouterView />
  </el-config-provider>
</template>

<style scoped lang="scss">

</style>
