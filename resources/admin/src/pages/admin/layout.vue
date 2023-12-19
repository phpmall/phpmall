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
  <el-container>
    <el-header class="header">
      <RouterLink :to="{ name: 'admin.dashboard' }">Admin logo</RouterLink>
      <div style="float: right;">
        <a href="#" @click="logout">退出</a>
      </div>
    </el-header>
    <el-container>
      <el-aside class="menu">
        <el-menu
        active-text-color="#ffd04b"
        background-color="#545c64"
        class="el-menu-vertical-demo"
        default-active="2"
        text-color="#fff"
      >
        <el-sub-menu index="1">
          <template #title>
            <el-icon><location /></el-icon>
            <span>Navigator One</span>
          </template>
          <el-menu-item-group title="Group One">
            <el-menu-item index="1-1">item one</el-menu-item>
            <el-menu-item index="1-2">item two</el-menu-item>
          </el-menu-item-group>
          <el-menu-item-group title="Group Two">
            <el-menu-item index="1-3">item three</el-menu-item>
          </el-menu-item-group>
          <el-sub-menu index="1-4">
            <template #title>item four</template>
            <el-menu-item index="1-4-1">item one</el-menu-item>
          </el-sub-menu>
        </el-sub-menu>
        <el-menu-item index="2">
          <el-icon><icon-menu /></el-icon>
          <span>Navigator Two</span>
        </el-menu-item>
      </el-menu>
        
      </el-aside>
      <el-main>
        <RouterView></RouterView>
      </el-main>
    </el-container>
  </el-container>
</template>

<style scoped lang="scss">
.header {
  background: #c6e2ff;
}
.menu {
  width: 200px;
  height: calc(100vh - 60px);
  background: #545c64;
}
</style>
