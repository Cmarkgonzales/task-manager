import { createRouter, createWebHistory } from 'vue-router';
import Auth from '@/pages/Auth.vue';
import Dashboard from '@/pages/Dashboard.vue';
import Admin from '@/pages/Admin.vue';
import Stats from '@/pages/Stats.vue';
import NotFound from '@/pages/NotFound.vue';

const routes = [
  {
    path: '/',
    redirect: { name: 'auth' }
  },
  {
    path: '/auth',
    name: 'auth',
    component: Auth
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/statistics',
    name: 'statistics',
    component: Stats,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin',
    name: 'admin',
    component: Admin,
    meta: { requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: NotFound
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
