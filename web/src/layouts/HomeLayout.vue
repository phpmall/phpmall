<template>
  <el-container>
    <el-header>
      <div class="header">
        <nav>
          <RouterLink :to="{name: 'home.index'}">Home</RouterLink>
        </nav>
      </div>
    </el-header>
    <el-container>
      <el-aside  class="menu" width="200px">
          Aside
          <ul>
            <li>
              <RouterLink :to="{name: 'home.order.index'}">Order</RouterLink>
            </li>
            <li>
              <RouterLink :to="{name: 'home.profile.index'}">user/profile</RouterLink>
            </li>
            <li>
              <RouterLink :to="{name: 'home.address.index'}">user/address</RouterLink>
            </li>
          </ul>
      </el-aside>
      <el-main>
        <RouterView/>
      </el-main>
    </el-container>
  </el-container>
</template>

<script setup lang="ts">
import {onMounted} from 'vue'
import {RouterLink, RouterView} from 'vue-router'
import {useAuthStore} from '@/stores/auth';
import {fixedEncodeURIComponent} from '@/utils/url'

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
@import "@/assets/home/app.scss";

.el-header {
  --el-header-padding: 0;
}

.header {
  height: 60px;
  background-color: #df3028;
}
</style>
