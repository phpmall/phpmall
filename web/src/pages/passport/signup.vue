<script setup lang="ts">
import { ref } from 'vue'

const size = ref<'default' | 'large' | 'small'>('default')
const value1 = ref('')
</script>

<template>
  <div>
    <h1>This is an about page</h1>
    <div>{{ value1 }}</div>
    <el-date-picker
        v-model="value1"
        type="date"
        placeholder="Pick a day"
        :size="size"
      />
  </div>
</template>

<style scoped>

</style>
