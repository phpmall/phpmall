import type { RouteRecordRaw } from 'vue-router'

export const statCenterRoutes: RouteRecordRaw[] = [
    {
      path: 'statCenter',
      name: 'statCenter',
      component: () => import('./StatCenter.vue'),
      meta: { title: '统计管理' }
    }
  ]
