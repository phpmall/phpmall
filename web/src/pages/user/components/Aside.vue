<template>
  <div class="menu">
    Aside
    <ul>
      <li>
        <RouterLink :to="{name: 'user.order.index'}">Order</RouterLink>
      </li>
      <li>
        <RouterLink :to="{name: 'user.profile.index'}">user/profile</RouterLink>
      </li>
      <li>
        <RouterLink :to="{name: 'user.address.index'}">user/address</RouterLink>
      </li>
    </ul>

  </div>
</template>

<script setup lang="ts">
import {RouterLink} from 'vue-router'
</script>

<style scoped lang="scss">
.menu {
  height: calc(100vh - 60px);
  background-color: aliceblue;
}
</style>