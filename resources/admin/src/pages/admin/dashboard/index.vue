<script setup lang="ts">
import { ref } from 'vue';
import { useRouter, RouterLink } from 'vue-router';
const router = useRouter()

const size = ref<'default' | 'large' | 'small'>('default')
const value1 = ref('')

</script>

<template>
  <main>
    {{ value1 }}

    The dashboard
    
    <el-date-picker v-model="value1" type="date" placeholder="Pick a day" :size="size" />
  </main>
</template>

<style scoped lang="scss">

</style>
