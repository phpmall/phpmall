<template>
  <el-container class="home">
    <el-aside class="menu">
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
</template>

<script setup lang="ts">
import {onMounted} from 'vue'
import {RouterLink, RouterView} from 'vue-router'

onMounted(() => {

})
</script>

<style scoped lang="scss">
.home {
  width: 1200px;
  margin: 15px auto;
}

.menu {
  width: 180px;
  min-height: 680px;
  background: rebeccapurple;
}
</style>
