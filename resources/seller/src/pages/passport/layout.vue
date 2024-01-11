<script setup lang="ts">
import { useRouter, RouterLink } from 'vue-router';
import { useAuthStore } from '@/stores/auth'
import {
  Menu as IconMenu,
  Location,
} from '@element-plus/icons-vue'

</script>

<template>
  <el-container>
    <header>header</header>
    <RouterView></RouterView>
    <footer>footer</footer>
  </el-container>
</template>

<style scoped lang="scss">
</style>
