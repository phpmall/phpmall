import {createRouter, createWebHistory, useRoute} from 'vue-router'
import type {Router} from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import {useAuthStore} from '@/stores/auth'
import {decodeURIComponent2, encodeURIComponent2} from '@/utils/urlx'
import adminRoutes from '@/pages/admin/route';
import passportRoutes from '@/pages/passport/route';
import sellerRoutes from '@/pages/seller/route';
import userRoutes from '@/pages/user/route';

const router: Router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        ...adminRoutes,
        ...passportRoutes,
        ...sellerRoutes,
        ...userRoutes,
        {
            path: '/:pathMatch(.*)*',
            name: 'NotFound',
            component: () => import('@/components/NotFound/index.vue')
        }
    ]
})

router.beforeEach((to, from, next) => {
    NProgress.start()

    // 认证检查
    const authStore = useAuthStore()
    if (to.meta.guest && authStore.check()) {
        const route = useRoute()
        const {callback} = route.query
        next({path: decodeURIComponent2(callback as string)})
    } else if (to.meta.requiresAuth && !authStore.check()) {
        next({
            name: 'passport.login',
            query: {
                callback: encodeURIComponent2(to.fullPath)
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
