import { createRouter, createWebHistory } from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', redirect: {name: 'login'} },
    { path: '/login', name: 'login', component: () => import('@/pages/Login.vue') },
    { path: '/signup', name: 'signup', component: () => import('@/pages/Signup.vue') },
    { path: '/forget', name: 'forget', component: () => import('@/pages/Forget.vue') },
    { path: '/reset', name: 'reset', component: () => import('@/pages/Reset.vue') }
  ]
})

router.beforeEach((to,from,next) => {
  NProgress.start() 
  next()
})

router.afterEach(() => {
  NProgress.done()
})

export default router
