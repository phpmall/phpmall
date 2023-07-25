<template>
  <el-container>
    <el-header>
      <div class="header">
        <nav>
          <RouterLink :to="{name: 'seller.index'}">Home</RouterLink>
        </nav>
      </div>
    </el-header>
    <el-container>
      <el-aside width="200px">
        <div class="menu">
          Aside
          <ul>
            <li>
              <RouterLink :to="{name: 'seller.order.index'}">Order</RouterLink>
            </li>
            <li>
              <RouterLink :to="{name: 'seller.product.index'}">product</RouterLink>
            </li>
            <li>
              <RouterLink :to="{name: 'seller.user.profile'}">user/profile</RouterLink>
            </li>
            <li>
              <RouterLink :to="{name: 'seller.user.address.index'}">user/address</RouterLink>
            </li>
          </ul>

          <el-menu default-active="2" class="el-menu-vertical-demo" background-color="aliceblue">
            <el-sub-menu index="1">
              <template #title>
                <el-icon>
                  <location/>
                </el-icon>
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
              <el-icon>
                <icon-menu/>
              </el-icon>
              <span>Navigator Two</span>
            </el-menu-item>
            <el-menu-item index="3" disabled>
              <el-icon>
                <document/>
              </el-icon>
              <span>Navigator Three</span>
            </el-menu-item>
            <el-menu-item index="4">
              <el-icon>
                <setting/>
              </el-icon>
              <span>Navigator Four</span>
            </el-menu-item>
          </el-menu>
        </div>
      </el-aside>
      <el-main>
        <RouterView/>
      </el-main>
    </el-container>
  </el-container>
</template>

<script setup lang="ts">
import {onMounted} from 'vue';
import {RouterView, useRouter, useRoute, RouterLink} from 'vue-router'
import {useAuthStore} from '@/stores/auth';
import {fixedEncodeURIComponent} from '@/utils/url';

const router = useRouter()
const route = useRoute()

onMounted(() => {
  // 认证检查
  const authStore = useAuthStore();
  if (authStore.token == '') {
    router.push({name: 'portal.index', query: {callback: fixedEncodeURIComponent(route.fullPath)}})
    return false
  }

  // 注册页面到服务端

})
</script>

<style scoped lang="scss">
@import "@/assets/supplier/app.scss";

.el-header {
  --el-header-padding: 0;
}

.header {
  height: 60px;
  background-color: #0052D9;
}

.menu {
  height: calc(100vh - 60px);
  background-color: aliceblue;
}
</style>
