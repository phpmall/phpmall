import {createRouter, createWebHistory} from 'vue-router'
import type {Router} from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import {getRoutes} from "@/router/routes";
import SellerLayout from "@/layouts/SellerLayout.vue";
import UserLayout from "@/layouts/UserLayout.vue";

const router: Router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {path: '/admin', children: getRoutes('/manager/')},
        {path: '/seller', component: SellerLayout, children: getRoutes('/seller/')},
        {path: '/supplier', children: getRoutes('/supplier/')},
        {path: '/home', component: UserLayout, children: getRoutes('/user/')},
        {path: '/', children: [...getRoutes('/auth/'), ...getRoutes('/portal/')]}
    ]
})

const pageTitle = window.document.title

router.beforeEach((to, from, next) => {
    NProgress.start()
    next()
    window.document.title = (to.meta.title || '') + ' - ' + pageTitle
})

router.afterEach(() => {
    NProgress.done()
})

export default router
