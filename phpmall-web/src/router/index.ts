import { createRouter, createWebHistory } from 'vue-router'
import {
  adminRoutes,
  passportRoutes,
  portalRoutes,
  sellerRoutes,
  storeRoutes,
  supplierRoutes,
  userRoutes
} from '@/router/modules'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/admin',
      component: () => import('@/layouts/AdminLayout.vue'),
      children: adminRoutes,
      meta: { requiresAuth: true },
    },
    {
      path: '/passport',
      component: () => import('@/layouts/PassportLayout.vue'),
      children: passportRoutes,
      meta: { requiresAuth: false },
    },
    {
      path: '/seller',
      component: () => import('@/layouts/SellerLayout.vue'),
      children: sellerRoutes,
      meta: { requiresAuth: true },
    },
    {
      path: '/store',
      component: () => import('@/layouts/StoreLayout.vue'),
      children: storeRoutes,
      meta: { requiresAuth: true },
    },
    {
      path: '/supplier',
      component: () => import('@/layouts/SupplierLayout.vue'),
      children: supplierRoutes,
      meta: { requiresAuth: true },
    },
    {
      path: '/',
      component: () => import('@/layouts/PortalLayout.vue'),
      children: [
        {
          path: 'user',
          component: () => import('@/layouts/UserLayout.vue'),
          children: userRoutes,
          meta: { requiresAuth: true },
        },
        ...portalRoutes
      ]
    }
  ]
})

export default router
