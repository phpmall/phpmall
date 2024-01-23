import {createRouter, createWebHistory} from 'vue-router'
import type {RouteRecordRaw} from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import AdminLayout from "@/pages/admin/layout.vue";
import AuthLayout from "@/pages/passport/layout.vue";
import SellerLayout from "@/pages/seller/layout.vue";
import SupplierLayout from "@/pages/supplier/layout.vue";
import UserLayout from "@/pages/home/layout.vue";
import PortalLayout from "@/pages/portal/layout.vue";
import {useAuthStore} from "@/stores/auth";
import {replaceRight} from "@/utils/str";
import {fixedEncodeURIComponent} from "@/utils/url";

const getAllRoutes = () => {
    const routes: Array<RouteRecordRaw> = []
    const pages = import.meta.glob('@/pages/**/**.vue')
    Object.keys(pages).forEach(item => {
        const matches: RegExpMatchArray = item.match(/\/pages\/(.+)\.vue/) as RegExpMatchArray
        const pathInfo: string = matches?.slice(1)[0] as string;
        if (pathInfo.search('components') === -1 && pathInfo.search('layout') === -1) {
            routes.push({
                path: `/${pathInfo}`,
                name: pathInfo.replace(/\//g, '.'),
                component: pages[item]
            })
        }
    })
    return routes
}

const getRoutes = (prefix: string, allRoutes: Array<RouteRecordRaw>) => {
    const routes: Array<RouteRecordRaw> = []
    allRoutes.forEach(item => {
        if (item.path.substring(0, prefix.length) === prefix) {
            item.path = replaceRight(item.path, '/index', '')
            item.path = item.path.substring(prefix.length)
            routes.push(item)
        }
    })
    return routes
}

const allRoutes: Array<RouteRecordRaw> = getAllRoutes()

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/admin',
            component: AdminLayout,
            children: getRoutes('/admin/', allRoutes),
            meta: {requiresAuth: true}
        },
        {
            path: '/passport',
            component: AuthLayout,
            children: getRoutes('/passport/', allRoutes),
            meta: {requiresAuth: false}
        },
        {
            path: '/seller',
            component: SellerLayout,
            children: getRoutes('/seller/', allRoutes),
            meta: {requiresAuth: true}
        },
        {
            path: '/supplier',
            component: SupplierLayout,
            children: getRoutes('/supplier/', allRoutes),
            meta: {requiresAuth: true}
        },
        {
            path: '/home',
            component: UserLayout,
            children: getRoutes('/home/', allRoutes),
            meta: {requiresAuth: true}
        },
        {
            path: '/',
            component: PortalLayout,
            children: getRoutes('/portal/', allRoutes)
        },
    ]
})

router.beforeEach((to, from, next) => {
    NProgress.start()

    // 认证检查
    const authStore = useAuthStore();
    if (to.meta.requiresAuth && !authStore.isLoggedIn()) {
        next({
            name: 'passport.login',
            query: {
                callback: fixedEncodeURIComponent(to.fullPath)
            }
        })
    } else {
        next()
    }
})

router.afterEach(() => {
    NProgress.done()
})

export default router
