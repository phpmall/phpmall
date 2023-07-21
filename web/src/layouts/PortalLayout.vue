<template>
<div>
  <Header />
  <RouterView />
</div>
</template>

<script setup lang="ts">
import { RouterView } from 'vue-router'
import Header from '@/pages/portal/components/Header.vue'
</script>

<style scoped lang="scss">

</style>
