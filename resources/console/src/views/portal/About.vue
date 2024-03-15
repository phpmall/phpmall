<script setup lang="ts">
import { ref } from 'vue'

const value1 = ref('')
const size = ref<'default' | 'large' | 'small'>('default')
</script>

<template>
  <div class="page">
    about page content

    <el-date-picker v-model="value1" type="date" placeholder="Pick a day" :size="size" />
  </div>
</template>
