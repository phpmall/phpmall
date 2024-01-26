import type { RouteRecordRaw } from 'vue-router'
import NotFound from '@/modules/user/NotFound.vue'

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
  },
  {
    path: 'order',
    name: 'user.order',
    component: () => import('@/modules/user/order/Order.vue')
  },
  {
    path: 'order/detail/:id',
    name: 'user.order.detail',
    component: () => import('@/modules/user/order/OrderDetail.vue')
  },
  {
    path: ':pathMatch(.*)*',
    name: 'NotFound',
    component: NotFound
  }
]
