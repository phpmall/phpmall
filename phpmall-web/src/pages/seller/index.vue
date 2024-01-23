<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { login } from '@/services/api/auth'
import type { LoginRequest } from '@/models'

const loginRequest = ref<LoginRequest>({
  name: '',
});

// login(loginRequest.value).then(() => {
//
//});
</script>

<template>
  <main>
    Index
  </main>
</template>

<style scoped>

</style>
