import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'

const pageModules = import.meta.glob('@/pages/**/page.ts', {eager: true})
// @ts-ignore
const pages = Object.fromEntries(Object.entries(pageModules).map(([path, pageModule]) => [path, pageModule.default || {}]))
const componentModules = import.meta.glob('@/pages/**/index.vue')
const routes: Array<RouteRecordRaw> = Object.entries(pages).map(([pagePath, config]) => {
  const path = (pagePath.replace('/src/pages', '').replace('/page.ts', '').replace('/auth', '') || '/')
  const name = path.split('/').filter(Boolean).join('-') || 'home'
  const componentPath = pagePath.replace('page.ts', 'index.vue')
  return {
    path: path,
    name,
    component: componentModules[componentPath],
    meta: config
  }
})

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
