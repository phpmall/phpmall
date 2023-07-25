import {createRouter, createWebHistory} from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import AdminLayout from "@/pages/admin/layout.vue";
import HomeLayout from "@/pages/home/layout.vue";
import PassportLayout from "@/pages/passport/layout.vue";
import PortalLayout from "@/pages/portal/layout.vue";
import SellerLayout from "@/pages/seller/layout.vue";
import SupplierLayout from "@/pages/supplier/layout.vue";
import {useAuthStore} from "@/stores/auth";
import {getRoutes} from "@/utils/routes";
import {fixedEncodeURIComponent} from "@/utils/url";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/admin',
            component: AdminLayout,
            children: getRoutes('/admin/'),
            meta: {requiresAuth: true}
        },

        {
            path: '/seller',
            component: SellerLayout,
            children: getRoutes('/seller/'),
            meta: {requiresAuth: true}
        },
        {
            path: '/supplier',
            component: SupplierLayout,
            children: getRoutes('/supplier/'),
            meta: {requiresAuth: true}
        },
        {
            path: '/passport',
            component: PassportLayout,
            children: getRoutes('/passport/'),
            meta: {requiresAuth: false}
        },
        {
            path: '/',
            component: PortalLayout,
            children: [
                {
                    path: '/home',
                    component: HomeLayout,
                    children: getRoutes('/home/'),
                    meta: {requiresAuth: true}
                },
                ...getRoutes('/portal/')
            ]
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
