import type { RouteRecordRaw } from 'vue-router'
import NotFound from '@/components/NotFound/index.vue'

export const userRoutes: RouteRecordRaw = {
  path: '/user',
  component: () => import('./Layout.vue'),
  children: [
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
      component: () => import('./Dashboard.vue')
    },
    {
      path: 'order',
      name: 'user.order',
      component: () => import('./order/Order.vue')
    },
    {
      path: 'order/detail/:id',
      name: 'user.order.detail',
      component: () => import('./order/OrderDetail.vue')
    },
    {
      path: ':pathMatch(.*)*',
      name: 'NotFound',
      component: NotFound
    }
  ],
  meta: { requiresAuth: true }
}
