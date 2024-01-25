import type { RouteRecordRaw } from 'vue-router'

export const supplierRoutes: RouteRecordRaw[] = [
    {
    path: '',
    name: 'supplier',
    redirect: {
      name: 'supplier.dashboard'
    }
  },
  {
    path: 'dashboard',
    name: 'supplier.dashboard',
    component: () => import('@/modules/supplier/Dashboard.vue')
  }
]
