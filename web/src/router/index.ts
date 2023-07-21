import {createRouter, createWebHistory} from 'vue-router'
import type {Router} from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import AuthLayout from "@/layouts/AuthLayout.vue";
import ManagerLayout from "@/layouts/ManagerLayout.vue";
import PortalLayout from "@/layouts/PortalLayout.vue";
import SellerLayout from "@/layouts/SellerLayout.vue";
import SupplierLayout from "@/layouts/SupplierLayout.vue";
import UserLayout from "@/layouts/UserLayout.vue";
import {getRoutes} from "@/router/routes";

const router: Router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {path: '/admin', component: ManagerLayout, children: getRoutes('/manager/')},
        {path: '/seller', component: SellerLayout, children: getRoutes('/seller/')},
        {path: '/supplier', component: SupplierLayout, children: getRoutes('/supplier/')},
        {path: '/home', component: UserLayout, children: getRoutes('/user/')},
        {path: '/', component: AuthLayout, children: getRoutes('/auth/')},
        {path: '/', component: PortalLayout, children: getRoutes('/portal/')},
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
