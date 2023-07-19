<template>
  <div class="header">
    <nav>
      <RouterLink to="/">Home</RouterLink>
    </nav>
  </div>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<style scoped lang="scss">
.header {
  height: 60px;
  background-color: #0052D9;
}
</style>
