import type { RouteRecordRaw } from 'vue-router'

export const consoleRoutes: RouteRecordRaw = {
  path: '/seller',
  component: () => import('./LayoutView.vue'),
  children: [
    {
      path: '',
      name: 'seller',
      component: () => import('./DashboardView.vue'),
      meta: { title: 'Dashboard' }
    },
  ],
  meta: { requiresAuth: true }
}
