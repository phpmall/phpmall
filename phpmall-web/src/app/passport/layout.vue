<template>
  <div class="wrapper">
    <div class="header">
      <div class="container">
        <a href="/">
          <img class="logo" src="@/assets/common/img/logo.png" alt="PHPMall Logo"/>
        </a>
      </div>
    </div>
    <div class="authing">
      <RouterView/>
    </div>
  </div>
</template>

<script setup lang="ts">
</script>

<style scoped lang="scss">
.wrapper {
  background: rgba(248, 249, 255, 100) url("@/assets/passport/img/login-bg.png");
  height: 100vh;

  .header {
    height: 60px;
    line-height: 60px;
    background-color: #FFF;
    box-shadow: 0 2px 8px rgba(51, 51, 51, .08);
    z-index: 1;

    .container {
      width: 1200px;
      margin: 0 auto;

      .logo {
        height: 28px;
        vertical-align: middle;
      }
    }
  }

  .authing {
    display: flex;
    flex-direction: column;
    width: 420px;
    min-height: 520px;
    margin: 120px auto 0;
    background-color: #fff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, .05);
    border-radius: 8px;
    position: relative;
  }
}
</style>
