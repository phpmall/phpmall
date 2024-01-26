import { createRouter, createWebHistory, useRoute } from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import {
  adminRoutes,
  passportRoutes,
  portalRoutes,
  sellerRoutes,
  storeRoutes,
  supplierRoutes,
  userRoutes
} from '@/router/modules'
import { useAuthStore } from '@/stores/auth'
import { decodeURIComponent2, encodeURIComponent2 } from '@/utils/urlx'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/admin',
      component: () => import('@/layouts/AdminLayout.vue'),
      children: adminRoutes,
      meta: { requiresAuth: true }
    },
    {
      path: '/passport',
      component: () => import('@/layouts/PassportLayout.vue'),
      children: passportRoutes,
      meta: { guest: true }
    },
    {
      path: '/seller',
      component: () => import('@/layouts/SellerLayout.vue'),
      children: sellerRoutes,
      meta: { requiresAuth: true }
    },
    {
      path: '/store',
      component: () => import('@/layouts/StoreLayout.vue'),
      children: storeRoutes,
      meta: { requiresAuth: true }
    },
    {
      path: '/supplier',
      component: () => import('@/layouts/SupplierLayout.vue'),
      children: supplierRoutes,
      meta: { requiresAuth: true }
    },
    {
      path: '/',
      component: () => import('@/layouts/PortalLayout.vue'),
      children: [
        {
          path: 'user',
          component: () => import('@/layouts/UserLayout.vue'),
          children: userRoutes,
          meta: { requiresAuth: true }
        },
        ...portalRoutes
      ]
    }
  ]
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
