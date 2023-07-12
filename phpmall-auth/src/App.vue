<script setup lang="ts">
import { RouterView } from 'vue-router'
import zhCn from 'element-plus/dist/locale/zh-cn.mjs'
import Header from '@/components/Header.vue'
</script>

<template>
  <el-config-provider :locale="zhCn">
    <Header />
    <div class="content">
      <div class="wrapper">
        <div class="wrapper-left">
          <img src="@/assets/img/login.png" alt="PHPMall Login" />
       </div>
        <div class="wrapper-right">
          <RouterView />
        </div>
      </div>
    </div>
  </el-config-provider>
</template>

<style scoped lang="scss">

</style>
