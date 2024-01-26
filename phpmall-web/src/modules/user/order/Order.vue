<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <div class="page">
    user Order
    <hr />
    <RouterLink :to="{ name: 'user.order.detail', params: { id: 99 } }">订单详情</RouterLink>
  </div>
</template>

<style scoped lang="scss"></style>
