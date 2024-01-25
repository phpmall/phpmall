import type { RouteRecordRaw } from 'vue-router'

export const userRoutes: RouteRecordRaw[] = [
    {
    path: '',
    name: 'user',
    redirect: {
      name: 'user.dashboard'
    }
  },
  {
    path: 'dashboard',
    name: 'user.dashboard',
    component: () => import('@/modules/user/Dashboard.vue')
  }
]
