<script setup lang="ts">
import { useRouter, RouterLink } from 'vue-router';
import { useAuthStore } from '@/stores/auth'
import {
  Document,
  Menu as IconMenu,
  Location,
  Setting,
} from '@element-plus/icons-vue'

const router = useRouter()
const authStore = useAuthStore();

const logout = () => {
  authStore.logout()
  router.push({ name: 'passport.login' })
}
</script>

<template>
  <div class="header">
      <RouterLink :to="{ name: 'portal.index' }">portal logo</RouterLink>
      <div style="float: right;">
        <a href="#" @click="logout">退出</a>
      </div>
  </div>
  
  <div class="main">
    <RouterView></RouterView>
  </div>
  
  <div class="footer">
  footer
  </div>
</template>

<style scoped lang="scss">
.header {
  background: #c6e2ff;
}
</style>
