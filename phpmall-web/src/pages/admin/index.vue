<script setup lang="ts">
import { ref } from 'vue'
const value1 = ref('')
</script>

<template>
  <main>
    Index page 
    
    <el-button>我的一个按钮</el-button>
    
    <el-date-picker
        v-model="value1"
        type="date"
        placeholder="Pick a day"
      />
  </main>
</template>

<style scoped>

</style>
