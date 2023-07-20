import { createRouter, createWebHashHistory, type RouteRecordRaw } from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import { replaceRight } from '@/utils/str'

const getRoutes = () => {
  const routes: Array<RouteRecordRaw> = []
  const pages = import.meta.glob('@/pages/**/**.vue')
  Object.keys(pages).forEach(item => {
      let matches = item.match(/\/pages\/(.+)\.vue/)
      let pathInfo = matches?.slice(1)[0] as string;
      pathInfo = replaceRight(pathInfo, '/index', '')
      if (pathInfo == 'index') {
        pathInfo = '';
      }
      routes.push({
          path: `/${pathInfo}`,
          component: pages[item]
      })
  })
  return routes
}

const router = createRouter({
  history: createWebHashHistory(import.meta.env.BASE_URL),
  routes: getRoutes()
})

router.beforeEach((to, from, next) => {
  NProgress.start()
  next()
})

router.afterEach(() => {
  NProgress.done()
})

export default router
