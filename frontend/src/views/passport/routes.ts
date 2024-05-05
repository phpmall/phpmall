import type { RouteRecordRaw } from 'vue-router'

export const authRoutes: RouteRecordRaw = {
  path: '/passport',
  component: () => import('./LayoutView.vue'),
  children: [
    {
      path: 'login',
      name: 'login',
      component: () => import('./LoginView.vue'),
      meta: { title: '登录' }
    },
    {
      path: 'signup',
      name: 'signup',
      component: () => import('./SignupView.vue'),
      meta: { title: '注册新账号' }
    },
    {
      path: 'forget',
      name: 'forget',
      component: () => import('./ForgetView.vue'),
      meta: { title: '找回密码' }
    },
    {
      path: 'reset',
      name: 'reset',
      component: () => import('./ResetView.vue'),
      meta: { title: '重设密码' }
    }
  ],
  meta: { guest: true }
}
