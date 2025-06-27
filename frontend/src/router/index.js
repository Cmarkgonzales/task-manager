import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/store/authStore';

import Auth from '@/pages/Auth.vue';
import Dashboard from '@/pages/Dashboard.vue';
import Admin from '@/pages/Admin.vue';
import Stats from '@/pages/Stats.vue';
import NotFound from '@/pages/NotFound.vue';

const routes = [
    { path: '/', redirect: '/auth', name: 'root' },
    { path: '/auth', component: Auth, name: 'auth' },
    {
        path: '/dashboard',
        component: Dashboard,
        name: 'dashboard',
        meta: { requiresAuth: true }
    },
    {
        path: '/statistics',
        component: Stats,
        name: 'statistics',
        meta: { requiresAuth: true }
    },
    {
        path: '/admin',
        component: Admin,
        name: 'admin',
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    { path: '/:pathMatch(.*)*', component: NotFound, name: 'notfound' }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    const store = useAuthStore();

    store.hydrate();

    const isAuth = store.isAuthenticated;

    if (isAuth && (to.path === '/auth' || to.path === '/')) {
        return next('/dashboard');
    }

    if (to.meta.requiresAuth && !isAuth) {
        return next('/auth');
    }

    if (to.meta.requiresAdmin && store.user?.role !== 'admin') {
        return next('/dashboard');
    }

    next();
});

export default router;
