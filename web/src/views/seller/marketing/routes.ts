import type { RouteRecordRaw } from 'vue-router'

export const marketingRoutes: RouteRecordRaw[] = [
    {
      path: 'marketing',
      name: 'marketing',
      component: () => import('./Marketing.vue'),
      meta: { title: '营销管理' }
    }
  ]
