<script setup lang="ts">
import { RouterView } from 'vue-router'
import Header from '@/components/Header.vue'
</script>

<template>
  <div>
    <Header/>

    <div class="content">
        <div class="wrapper">
            <div class="wrapper-left">
                <img src="@/assets/img/login.png" alt="PHPMall Login"/>
            </div>
            <div class="wrapper-right">
              <RouterView />
            </div>
        </div>
    </div>
  </div>
</template>

<style scoped lang="scss">

</style>
