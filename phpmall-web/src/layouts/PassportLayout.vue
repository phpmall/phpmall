<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <div class="page">
    <div>
        <RouterLink to='/'>logo</RouterLink>
    </div>
    <RouterView />
  </div>
</template>

<style scoped lang="scss"></style>
