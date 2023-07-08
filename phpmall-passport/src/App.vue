<script setup lang="ts">
import { RouterView } from 'vue-router'
import Header from '@/components/Header.vue'
</script>

<template>
  <header>
    <div>
      <Header/>
    </div>
  </header>

  <RouterView />
</template>

<style scoped>

</style>
