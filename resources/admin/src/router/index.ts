import { createRouter, createWebHashHistory, useRoute } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import { useAuthStore } from '@/stores/auth'
import { decodeURIComponent2, encodeURIComponent2 } from '@/utils/urlx'

const pages = import.meta.glob('../pages/**/*.vue')
const getPathInfo = (path: string) => path.replace(/^.*\/pages\/(.+)\.vue$/, '$1')

const getRoutes = () => {
  const routes: Array<RouteRecordRaw> = []
  Object.keys(pages).forEach((file: string) => {
    let fullPathInfo = getPathInfo(file)

    if (
      fullPathInfo.search('/components') !== -1 ||
      fullPathInfo.search('/layout') !== -1 ||
      fullPathInfo.search('login') !== -1
    ) {
      return
    }

    if (fullPathInfo.endsWith('index')) {
      fullPathInfo = fullPathInfo.substring(0, fullPathInfo.length - 6)
    }

    // TODO Permission filter

    routes.push({
      path: fullPathInfo,
      name: fullPathInfo.replace('/', '.'),
      component: pages[file]
    })
  })

  return routes
}

const router = createRouter({
  history: createWebHashHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: () => import('@/layouts/App.vue'),
      children: [
        {
          path: '',
          redirect: { name: 'dashboard' }
        },
        ...getRoutes()
      ],
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/pages/login.vue'),
      meta: {
        guest: true
      }
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
