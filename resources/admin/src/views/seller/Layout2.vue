<template>
  <el-container>
    <el-header>
      <div class="header">
        <nav>
          <RouterLink :to="{ name: 'seller.index' }">Home</RouterLink>
        </nav>
      </div>
    </el-header>
    <el-container>
      <el-aside width="200px">
        <div class="menu">
          Aside
          <ul>
            <li>
              <RouterLink :to="{ name: 'seller.order.index' }">Order</RouterLink>
            </li>
            <li>
              <RouterLink :to="{ name: 'seller.product.index' }">product</RouterLink>
            </li>
            <li>
              <RouterLink :to="{ name: 'seller.user.profile' }">user/profile</RouterLink>
            </li>
            <li>
              <RouterLink :to="{ name: 'seller.user.address.index' }">user/address</RouterLink>
            </li>
          </ul>

          <el-menu default-active="2" class="el-menu-vertical-demo" background-color="aliceblue">
          </el-menu>
        </div>
      </el-aside>
      <el-main>
        <RouterView />
      </el-main>
    </el-container>
  </el-container>
</template>

<script setup lang="ts">
import { ref, onBeforeMount, onMounted } from 'vue'
import { RouterLink, RouterView, useRoute, useRouter } from 'vue-router'
import { Document, Menu as IconMenu, Location, Setting } from '@element-plus/icons-vue'
import { useAuthStore } from '@/stores/auth'
import { fixedEncodeURIComponent } from '@/utils/url'

const menuData = ref([
  {
    id: 1,
    name: 'aaaa',
    child: [
      {
        id: 2,
        name: 'aa-1'
      }
    ]
  }
])

onMounted(() => {})
</script>

<style scoped lang="scss">
.el-header {
  --el-header-padding: 0;
}

.header {
  height: 60px;
  background-color: #0052d9;
}

.menu {
  height: calc(100vh - 60px);
  background-color: aliceblue;
}
</style>
