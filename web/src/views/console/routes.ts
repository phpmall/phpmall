import type { RouteRecordRaw } from 'vue-router'

export const consoleRoutes: RouteRecordRaw = {
  path: '/console',
  component: () => import('./LayoutView.vue'),
  children: [
    {
      path: '',
      name: 'console',
      component: () => import('./DashboardView.vue'),
      meta: { title: 'Dashboard' }
    },
  ],
  meta: { requiresAuth: true }
}
