import {createRouter, createWebHistory} from 'vue-router'
import type {Router} from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import {getRoutes} from "@/utils/routes";
import Admin from "@/layouts/Admin.vue";
import Home from "@/layouts/Home.vue";
import Passport from "@/layouts/Passport.vue";
import Portal from "@/layouts/Portal.vue";
import Seller from "@/layouts/Seller.vue";
import Supplier from "@/layouts/Supplier.vue";

const router: Router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/admin',
            component: Admin,
            children: getRoutes('/admin/')
        },
        {
            path: '/home',
            component: Home,
            children: getRoutes('/home/')
        },
        {
            path: '/passport',
            component: Passport,
            children: getRoutes('/passport/')
        },
        {
            path: '/seller',
            component: Seller,
            children: getRoutes('/seller/')
        },
        {
            path: '/supplier',
            component: Supplier,
            children: getRoutes('/supplier/')
        },
        {
            path: '/',
            component: Portal,
            children: getRoutes('/portal/')
        },
    ]
})

router.beforeEach((to, from, next) => {
    NProgress.start()

    next()
})

router.afterEach(() => {
    NProgress.done()
})

export default router
