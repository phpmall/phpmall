import type { RouteRecordRaw } from 'vue-router'

export const passportRoutes: RouteRecordRaw[] = [
    {
    path: '',
    name: 'passport',
    redirect: {
      name: 'passport.login'
    }
  },
  {
    path: 'login',
    name: 'passport.login',
    component: () => import('@/modules/passport/Login.vue')
  }
]
