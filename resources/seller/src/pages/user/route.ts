import type {RouteRecordRaw} from "vue-router";

const routes: Array<RouteRecordRaw> = [
    {
        path: '/user',
        component: () => import('./layout.vue'),
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'user',
                component: () => import('./index.vue'),
            }
        ]
    }

];

export default routes;
