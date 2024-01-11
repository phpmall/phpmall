import type {RouteRecordRaw} from "vue-router";

const routes: Array<RouteRecordRaw> = [
    {
        path: '/admin',
        component: () => import('./layout.vue'),
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                redirect: {name: 'admin.dashboard'},
            },
            {
                path: 'dashboard',
                name: 'admin.dashboard',
                component: () => import('./dashboard/index.vue'),
            }
        ]
    },
];

export default routes;
