import type { RouteRecordRaw } from 'vue-router'

export const managerRoutes: RouteRecordRaw = {
  path: '/admin',
  component: () => import('./Layout.vue'),
  children: [
    {
      path: '',
      name: 'manager',
      redirect: {
        name: 'manager.dashboard'
      }
    },
    {
      path: 'dashboard',
      name: 'manager.dashboard',
      component: () => import('./Dashboard.vue')
    }
  ],
  meta: { requiresAuth: true }
}
