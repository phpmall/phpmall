import type {RouteRecordRaw} from "vue-router";

const routes: Array<RouteRecordRaw> = [
    {
        path: '/passport',
        component: () => import('./layout.vue'),
        meta: { guest: true },
        children: [
            {
                path: '',
                redirect: {name: 'passport.login'},
            },
            {
                path: 'login',
                name: 'passport.login',
                component: () => import('./login.vue'),
            }
        ]
    },
];

export default routes;
