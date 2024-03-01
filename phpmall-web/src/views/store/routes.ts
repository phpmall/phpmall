import type { RouteRecordRaw } from 'vue-router'

export const storeRoutes: RouteRecordRaw = {
  path: '/store',
  component: () => import('./Layout.vue'),
  children: [
    {
      path: '',
      name: 'store',
      redirect: {
        name: 'store.dashboard'
      }
    },
    {
      path: 'dashboard',
      name: 'store.dashboard',
      component: () => import('./Dashboard.vue')
    }
  ],
  meta: { requiresAuth: true }
}
