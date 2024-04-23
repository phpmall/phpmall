<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <div class="auth">
    <div class="header">
      <div class="left">
        <RouterLink to="/">Home</RouterLink>
      </div>
      <div class="right">right</div>
    </div>
    <div class="boxes">
      <RouterView />
    </div>
    <div class="footer">footer copyright</div>
  </div>
</template>

<style scoped lang="scss">
.auth {
  background: lightblue;
}
</style>
