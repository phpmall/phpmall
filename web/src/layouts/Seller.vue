<template>
  <el-config-provider :locale="zhCn">
    <el-container>
      <el-header>
        <Header></Header>
      </el-header>
      <el-container>
        <el-aside width="200px">
          <Aside></Aside>
        </el-aside>
        <el-main>
          <RouterView />
        </el-main>
      </el-container>
    </el-container>
  </el-config-provider>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { RouterView } from 'vue-router'
import zhCn from 'element-plus/es/locale/lang/zh-cn'
import Header from '@/components/Header.vue'
import Aside from '@/components/Aside.vue'
import { useAuthStore } from '@/stores/auth';
import { fixedEncodeURIComponent } from '@/utils/url';

onMounted(() => {
  // 认证检查
  const authStore = useAuthStore();
  if (authStore.token == '') {
    let callback = fixedEncodeURIComponent(window.location.href)
    window.location.href = '/passport/#/login?callback=' + callback
    return
  }
  // 注册页面到服务端
  
})
</script>

<style scoped lang="scss">
.el-header {
  --el-header-padding: 0;
}
</style>
