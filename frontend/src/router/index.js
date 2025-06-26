import { createRouter, createWebHistory } from 'vue-router';
import Auth from '@/pages/Auth.vue';
import Dashboard from '@/pages/Dashboard.vue';

const routes = [
    { path: '/', redirect: '/auth' },
    { path: '/auth', component: Auth },
    {
        path: '/dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
