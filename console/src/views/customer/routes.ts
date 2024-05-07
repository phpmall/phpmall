import type { RouteRecordRaw } from 'vue-router'

export const customerRoutes: RouteRecordRaw = {
  path: '/customer',
  children: [
    {
      path: '',
      name: 'customer',
      component: () => import('./Customer.vue'),
      meta: { title: '客户管理' }
    }
  ]
}
