import type { RouteRecordRaw } from 'vue-router'

export const appStoreRoutes: RouteRecordRaw[] = [
    {
      path: '/appStore',
      name: 'appStore',
      component: () => import('./AppStore.vue'),
      meta: { title: '应用中心' }
    }
  ]
