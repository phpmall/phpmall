import type { RouteRecordRaw } from 'vue-router'

export const portalRoutes: RouteRecordRaw[] = [
    {
    path: '',
    name: 'portal',
    component: () => import('@/views/Index.vue')
  },
  {
    path: 'about',
    name: 'portal.about',
    component: () => import('@/views/About.vue')
  }
]
