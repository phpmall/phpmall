import type { RouteRecordRaw } from 'vue-router'

export const adminRoutes: RouteRecordRaw[] = [
  {
    path: '',
    name: 'admin',
    redirect: {
      name: 'admin.dashboard'
    }
  },
  {
    path: 'dashboard',
    name: 'admin.dashboard',
    component: () => import('@/modules/admin/Dashboard.vue')
  }
]
