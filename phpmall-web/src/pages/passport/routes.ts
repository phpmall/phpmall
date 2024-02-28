import type { RouteRecordRaw } from 'vue-router'

export const passportRoutes: RouteRecordRaw = {
  path: '/passport',
  component: () => import('./Layout.vue'),
  children: [
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
      component: () => import('./Login.vue'),
      meta: { title: '登录' }
    },
    {
      path: 'signup',
      name: 'passport.signup',
      component: () => import('./Signup.vue'),
      meta: { title: '注册新账号' }
    },
    {
      path: 'forget',
      name: 'passport.forget',
      component: () => import('./Forget.vue'),
      meta: { title: '找回密码' }
    },
    {
      path: 'reset',
      name: 'passport.reset',
      component: () => import('./Reset.vue'),
      meta: { title: '重设密码' }
    }
  ],
  meta: { guest: true }
}
