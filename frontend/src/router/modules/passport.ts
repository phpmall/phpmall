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
    component: () => import('@/modules/passport/Login.vue'),
    meta: { title: '登录' }
  },
  {
    path: 'signup',
    name: 'passport.signup',
    component: () => import('@/modules/passport/Signup.vue'),
    meta: { title: '注册新账号' }
  },
  {
    path: 'forget',
    name: 'passport.forget',
    component: () => import('@/modules/passport/Forget.vue'),
    meta: { title: '找回密码' }
  },
  {
    path: 'reset',
    name: 'passport.reset',
    component: () => import('@/modules/passport/Reset.vue'),
    meta: { title: '重设密码' }
  }
]
