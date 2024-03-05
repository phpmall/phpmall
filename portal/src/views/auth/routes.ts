import type { RouteRecordRaw } from 'vue-router'

export const authRoutes: RouteRecordRaw = {
  path: '/passport',
  component: () => import('./Layout.vue'),
  children: [
    {
      path: 'login',
      name: 'login',
      component: () => import('./Login.vue'),
      meta: { title: '登录' }
    },
    {
      path: 'signup',
      name: 'signup',
      component: () => import('./Signup.vue'),
      meta: { title: '注册新账号' }
    },
    {
      path: 'forget',
      name: 'forget',
      component: () => import('./Forget.vue'),
      meta: { title: '找回密码' }
    },
    {
      path: 'reset',
      name: 'reset',
      component: () => import('./Reset.vue'),
      meta: { title: '重设密码' }
    }
  ],
  meta: { guest: true }
}
