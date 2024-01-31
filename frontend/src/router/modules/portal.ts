import type { RouteRecordRaw } from 'vue-router'
import NotFound from '@/components/NotFound.vue'

export const portalRoutes: RouteRecordRaw[] = [
  {
    path: '',
    name: 'portal',
    component: () => import('@/views/Index.vue'),
    meta: { title: '首页' }
  },
  {
    path: 'about',
    name: 'portal.about',
    component: () => import('@/views/About.vue'),
    meta: { title: '关于我们' }
  },
  {
    path: ':pathMatch(.*)*',
    name: 'NotFound',
    component: NotFound,
    meta: { title: '没有找到页面' }
  }
]
