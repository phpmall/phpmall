<template>
  <div>
    profile page

    <ul>
      <li v-for="user in users" :key="user.id">
        {{ user.name }}
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const users = ref([])

onMounted(async () => {
  try {
    // users.value = await fetchUsers();
  } catch (error) {
    console.error(error)
  }
})
</script>

<style scoped lang="scss"></style>
