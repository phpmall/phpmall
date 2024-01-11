<script setup lang="ts">
import { useRouter, RouterLink } from 'vue-router';
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore();

const logout = () => {
  authStore.logout()
  router.push({ name: 'passport.login' })
}
</script>

<template>
  <el-container>
    <el-header class="header">
      <RouterLink :to="{ name: 'admin.dashboard' }">User logo</RouterLink>
      <div style="float: right;">
        <a href="#" @click="logout">退出</a>
      </div>
    </el-header>
    <el-container>
        Header
      <el-main>
        <RouterView></RouterView>
      </el-main>
        Footer
    </el-container>
  </el-container>
</template>

<style scoped lang="scss">
.header {
  background: #c6e2ff;
}
</style>
