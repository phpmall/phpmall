import { createRouter, createWebHashHistory } from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

const router = createRouter({
  history: createWebHashHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', redirect: { name: 'login' } },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/pages/Login.vue'),
      meta: { title: '登录' }
    },
    {
      path: '/signup',
      name: 'signup',
      component: () => import('@/pages/Signup.vue'),
      meta: { title: '免费注册' }
    },
    {
      path: '/password/forget',
      name: 'password.forget',
      component: () => import('@/pages/password/Forget.vue'),
      meta: { title: '找回密码' }
    },
    {
      path: '/password/reset',
      name: 'password.reset',
      component: () => import('@/pages/password/Reset.vue'),
      meta: { title: '重设密码' }
    }
  ]
})

const pageTitle = window.document.title

router.beforeEach((to, from, next) => {
  NProgress.start()

  window.document.title = to.meta.title + ' - ' + pageTitle

  next()
})

router.afterEach(() => {
  NProgress.done()
})

export default router
