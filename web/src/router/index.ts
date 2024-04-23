import { createRouter, createWebHistory, useRoute } from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import {
  authRoutes,
  consoleRoutes,
  sellerRoutes,
  userRoutes
} from '@/views'
import { useAuthStore } from '@/stores/auth'
import { decodeURIComponent2, encodeURIComponent2 } from '@/utils/urlx'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [authRoutes, consoleRoutes, sellerRoutes, userRoutes]
})

router.beforeEach((to, from, next) => {
  NProgress.start()

  if (to.meta.title) {
    document.title = to.meta.title as string
  }

  const route = useRoute()
  const authStore = useAuthStore()
  if (to.meta.guest && authStore.check()) {
    const { callback } = route.query
    next({ path: decodeURIComponent2(callback as string) })
  } else if (to.meta.requiresAuth && !authStore.check()) {
    next({
      name: 'login',
      query: {
        callback: encodeURIComponent2(to.fullPath)
      }
    })
  } else {
    next()
  }
})

router.afterEach(() => {
  NProgress.done()
})

export default router
