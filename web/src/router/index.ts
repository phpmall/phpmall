import { createRouter, createWebHistory } from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'index', component: () => import('@/pages/portal/index.vue') },
    { path: '/article', name: 'article', component: () => import('@/pages/portal/article.vue') },
    { path: '/brand', name: 'brand', component: () => import('@/pages/portal/brand.vue') },
    { path: '/catalog', name: 'catalog', component: () => import('@/pages/portal/catalog.vue') },
    { path: '/category', name: 'category', component: () => import('@/pages/portal/category.vue') },
    { path: '/goods', name: 'goods', component: () => import('@/pages/portal/goods.vue') }
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
