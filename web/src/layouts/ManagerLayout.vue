<template>
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
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { RouterView } from 'vue-router'
import Header from '@/pages/manager/components/Header.vue'
import Aside from '@/pages/manager/components/Aside.vue'
import { useAuthStore } from '@/stores/auth';
import { fixedEncodeURIComponent } from '@/utils/url';

onMounted(() => {
  // 认证检查
  const authStore = useAuthStore();
  if (authStore.token == '') {
    let callback = fixedEncodeURIComponent(window.location.href)
    // window.location.href = '/login?callback=' + callback
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
