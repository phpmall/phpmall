import type { RouteRecordRaw } from 'vue-router'
import { createRouter, createWebHistory, useRoute } from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import { useAuthStore } from '@/stores/auth'
import { decodeURIComponent2, encodeURIComponent2 } from '@/utils/urlx'

const modules = import.meta.glob('../pages/**/*.vue')
const getPathInfo = (path: string) => path.replace(/^.*\/pages\/(.+)\.vue$/, '$1')

const getRoutes = (prefix: string) => {
  const routes: Array<RouteRecordRaw> = []
  Object.keys(modules).forEach((file: string) => {
    const fullPathInfo = getPathInfo(file)
    if (fullPathInfo.search('/components') !== -1 ||
      fullPathInfo.search('/layout') !== -1 ||
      !fullPathInfo.startsWith(prefix)) {
      return
    }

    let pathInfo = fullPathInfo.substring(prefix.length + 1)
    if (pathInfo.endsWith('index')) {
      pathInfo = pathInfo.substring(0, pathInfo.length - 6)
    }

    routes.push({
      path: pathInfo,
      name: fullPathInfo.replace('/', '.'),
      component: modules[file]
    })
  })
  return routes
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/admin',
      component: () => import('@/pages/admin/layout.vue'),
      children: getRoutes('admin'),
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/passport',
      component: () => import('@/pages/passport/layout.vue'),
      children: getRoutes('passport'),
      meta: {
        guest: true
      }
    },
    {
      path: '/seller',
      component: () => import('@/pages/seller/layout.vue'),
      children: getRoutes('seller'),
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/supplier',
      component: () => import('@/pages/supplier/layout.vue'),
      children: getRoutes('supplier'),
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/home',
      component: () => import('@/pages/user/layout.vue'),
      children: getRoutes('user'),
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/',
      component: () => import('@/pages/portal/layout.vue'),
      children: getRoutes('portal')
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'NotFound',
      component: () => import('@/components/NotFound/index.vue')
    }
  ]
})

router.beforeEach((to, from, next) => {
  NProgress.start()

  // 认证检查
  const authStore = useAuthStore()
  if (to.meta.guest && authStore.check()) {
    const route = useRoute()
    const { callback } = route.query
    next({ path: decodeURIComponent2(callback as string) })
  } else if (to.meta.requiresAuth && !authStore.check()) {
    next({
      name: 'passport.login',
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
