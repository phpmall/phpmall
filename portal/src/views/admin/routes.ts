import type { RouteRecordRaw } from 'vue-router'

export const adminRoutes: RouteRecordRaw = {
  path: '/admin',
  component: () => import('./Layout.vue'),
  children: [
    {
      path: '',
      name: 'admin',
      redirect: {
        name: 'admin.dashboard'
      }
    },
    {
      path: 'dashboard',
      name: 'admin.dashboard',
      component: () => import('./Dashboard.vue')
    }
  ],
  meta: { requiresAuth: true }
}
