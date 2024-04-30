import type { RouteRecordRaw } from 'vue-router'

export const commerceRoutes: RouteRecordRaw[] = [
    {
      path: '/commerce/content',
      name: 'commerce.content',
      component: () => import('./content/Content.vue'),
      meta: { title: '内容管理' }
    }
  ]
