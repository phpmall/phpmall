import type {RouteRecordRaw} from "vue-router";

const routes: Array<RouteRecordRaw> = [
    {
        path: '/seller',
        component: () => import('./layout.vue'),
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'seller',
                component: () => import('./index.vue'),
            }
        ]
    }

];

export default routes;
