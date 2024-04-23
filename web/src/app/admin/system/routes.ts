import type { RouteRecordRaw } from 'vue-router'

export const systemRoutes: RouteRecordRaw[] = [
    {
      path: 'system',
      name: 'system',
      component: () => import('./Setting.vue'),
      meta: { title: '系统设置' }
    }
  ]
