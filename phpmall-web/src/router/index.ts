import { createRouter, createWebHistory } from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'index', component: () => import('@/pages/Index.vue') },
    { path: '/article', name: 'article', component: () => import('@/pages/Article.vue') },
    { path: '/brand', name: 'brand', component: () => import('@/pages/Brand.vue') },
    { path: '/catalog', name: 'catalog', component: () => import('@/pages/Catalog.vue') },
    { path: '/category', name: 'category', component: () => import('@/pages/Category.vue') },
    { path: '/goods', name: 'goods', component: () => import('@/pages/Goods.vue') }
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
