<template>
  <div>
    <nav>
      <RouterLink :to="{name: 'portal.index'}">首页</RouterLink> |
      <RouterLink :to="{name: 'portal.catalog'}">全部目录</RouterLink> |
      <RouterLink :to="{name: 'portal.category'}">分类</RouterLink> |
      <RouterLink :to="{name: 'portal.article'}">文章</RouterLink> |
      <RouterLink :to="{name: 'portal.brand'}">品牌</RouterLink> |
      <RouterLink :to="{name: 'portal.goods'}">商品</RouterLink> |

      <div v-if="isLoggedIn">
        <RouterLink :to="{name: 'home.index'}">会员中心</RouterLink>
      </div>
      <div v-else>
        <RouterLink :to="{name: 'passport.login'}">登录</RouterLink>
        <RouterLink :to="{name: 'passport.signup'}">免费注册</RouterLink>
      </div>
    </nav>
    <RouterView/>
  </div>
</template>

<script setup lang="ts">
import {ref} from "vue";
import {RouterLink, RouterView} from 'vue-router'
import {useAuthStore} from "@/stores/auth";

const auth = useAuthStore()
const isLoggedIn = ref(auth.isLoggedIn())

</script>

<style scoped lang="scss">
@import "@/assets/portal/app.scss";

</style>
