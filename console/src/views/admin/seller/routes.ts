import type { RouteRecordRaw } from 'vue-router'

export const sellerRoutes: RouteRecordRaw = {
  path: '/seller',
  component: () => import('./Layout.vue'),
  children: [
    {
      path: '',
      name: 'seller',
      redirect: {
        name: 'seller.dashboard'
      }
    },
    {
      path: 'dashboard',
      name: 'seller.dashboard',
      component: () => import('./Dashboard.vue')
    }
  ],
  meta: { requiresAuth: true }
}
