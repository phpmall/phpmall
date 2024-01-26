import type { RouteRecordRaw } from 'vue-router'

export const storeRoutes: RouteRecordRaw[] = [
  {
    path: '',
    name: 'store',
    redirect: {
      name: 'store.dashboard'
    }
  },
  {
    path: 'dashboard',
    name: 'store.dashboard',
    component: () => import('@/modules/store/Dashboard.vue')
  }
]
