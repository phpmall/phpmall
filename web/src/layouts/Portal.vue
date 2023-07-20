<script setup lang="ts">
import { RouterView } from 'vue-router'
import zhCn from 'element-plus/es/locale/lang/zh-cn'
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
