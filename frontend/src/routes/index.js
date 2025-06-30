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
        meta: { requiresAuth: true },
    },
    {
        path: '/statistics',
        component: Stats,
        name: 'statistics',
        meta: { requiresAuth: true },
    },
    {
        path: '/admin',
        component: Admin,
        name: 'admin',
        meta: { requiresAuth: true, requiresAdmin: true },
    },
    { path: '/:pathMatch(.*)*', component: NotFound, name: 'notfound' },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

let isHydrated = false;

router.beforeEach(async (to, from, next) => {
    const auth = useAuthStore();

    if (!isHydrated && to.path !== '/auth') {
        await auth.hydrate();
        isHydrated = true;
    }

    const isAuth = auth.isAuthenticated;

    // Redirect authenticated users away from login page
    if (isAuth && to.path === '/auth') {
        return next('/dashboard');
    }

    // Block unauthenticated access to protected routes
    if (to.meta.requiresAuth && !isAuth) {
        return next('/auth');
    }

    // Block non-admin access to admin routes
    if (to.meta.requiresAdmin && !auth.user?.is_admin) {
        return next('/dashboard');
    }

    return next();
});

export default router;
