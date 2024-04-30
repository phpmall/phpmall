import type { RouteRecordRaw } from 'vue-router'

export const userRoutes: RouteRecordRaw = {
  path: '/user',
  component: () => import('./LayoutView.vue'),
  children: [
    {
      path: '',
      name: 'user',
      component: () => import('./DashboardView.vue'),
      meta: { title: 'Dashboard' }
    },
  ],
  meta: { requiresAuth: true }
}
