import {createRouter, createWebHistory} from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import {useAuthStore} from "@/stores/auth";
import {getRoutes} from "@/utils/routes";
import {fixedEncodeURIComponent} from "@/utils/url";
import AdminLayout from "@/layouts/AdminLayout.vue";
import HomeLayout from "@/layouts/HomeLayout.vue";
import PassportLayout from "@/layouts/PassportLayout.vue";
import PortalLayout from "@/layouts/PortalLayout.vue";
import SellerLayout from "@/layouts/SellerLayout.vue";
import SupplierLayout from "@/layouts/SupplierLayout.vue";

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
            path: '/home',
            component: HomeLayout,
            children: getRoutes('/home/'),
            meta: {requiresAuth: true}
        },
        {
            path: '/passport',
            component: PassportLayout,
            children: getRoutes('/passport/'),
            meta: {requiresAuth: false}
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
            path: '/',
            component: PortalLayout,
            children: getRoutes('/portal/')
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
