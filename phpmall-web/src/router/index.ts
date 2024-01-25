import { createRouter, createWebHistory } from 'vue-router'
import {
  adminRoutes,
  passportRoutes,
  portalRoutes,
  sellerRoutes,
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
      meta: {
        auth: true
      }
    },
    {
      path: '/passport',
      component: () => import('@/layouts/PassportLayout.vue'),
      children: passportRoutes,
      meta: {
        guest: true
      }
    },
    {
      path: '/seller',
      component: () => import('@/layouts/SellerLayout.vue'),
      children: sellerRoutes,
      meta: {
        auth: true
      }
    },
    {
      path: '/supplier',
      component: () => import('@/layouts/SupplierLayout.vue'),
      children: supplierRoutes,
      meta: {
        auth: true
      }
    },
    {
      path: '/',
      component: () => import('@/layouts/PortalLayout.vue'),
      children: [
        {
          path: 'user',
          component: () => import('@/layouts/UserLayout.vue'),
          children: userRoutes,
          meta: {
            auth: true
          }
        },
        ...portalRoutes
      ]
    }
  ]
})

export default router
