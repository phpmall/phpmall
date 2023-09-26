<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'

</script>

<template>
  <header>
    <div class="wrapper">
      <nav>
        <RouterLink to="/">Home</RouterLink>
        <RouterLink :to="{name: 'passport.login'}">Login</RouterLink>
      </nav>
    </div>
  </header>

  <RouterView />  

  <footer>
    footer
  </footer>
</template>
