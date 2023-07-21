<template>
<div>
  <div class="header">
    <div class="container">
      <a href="/">
        <img class="logo" src="@/assets/img/logo.png" alt="PHPMall Logo" />
      </a>
    </div>
  </div>
  <div class="content">
    <div class="wrapper">
      <div class="wrapper-left">
        <img src="@/assets/img/login.png" alt="PHPMall Login" />
      </div>
      <div class="wrapper-right">
        <RouterView />
      </div>
    </div>
  </div>
</div>
</template>

<script setup lang="ts">
</script>

<style scoped lang="scss">

</style>
