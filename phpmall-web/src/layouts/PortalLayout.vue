<script setup lang="ts">
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const logout = () => {
  authStore.logout()
  router.push({ name: 'passport.login' })
}
</script>

<template>
  <el-container>
    <el-header>
      <header>
        <div>
          <nav>
            <RouterLink to="/">Home</RouterLink> | <RouterLink to="/about">About</RouterLink> |

            <template v-if="authStore.check()">
              <RouterLink :to="{ name: 'user' }">会员中心</RouterLink> |
              <a href="#" @click="logout">退出</a>
            </template>

            <template v-else>
              <RouterLink :to="{ name: 'passport.login' }">登录</RouterLink> |
              <RouterLink :to="{ name: 'passport.signup' }">免费注册</RouterLink>
            </template>
          </nav>
        </div>
      </header>
    </el-header>
    <el-main>
      <RouterView />
    </el-main>
    <el-footer>Footer</el-footer>
  </el-container>
</template>
