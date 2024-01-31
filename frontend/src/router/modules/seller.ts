import type { RouteRecordRaw } from 'vue-router'

export const sellerRoutes: RouteRecordRaw[] = [
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
    component: () => import('@/modules/seller/Dashboard.vue')
  }
]
