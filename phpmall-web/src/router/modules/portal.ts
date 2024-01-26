import type { RouteRecordRaw } from 'vue-router'
import NotFound from '@/components/NotFound.vue'

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
  },
  {
    path: ':pathMatch(.*)*',
    name: 'NotFound',
    component: NotFound
  }
]
