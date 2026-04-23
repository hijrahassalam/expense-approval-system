import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const routes = [
    {
        path: '/login',
        name: 'login',
        component: () => import('../views/LoginView.vue'),
        meta: { guest: true },
    },
    {
        path: '/',
        component: () => import('../components/layout/AppLayout.vue'),
        meta: { auth: true },
        children: [
            {
                path: '',
                redirect: '/dashboard',
            },
            {
                path: 'dashboard',
                name: 'dashboard',
                component: () => import('../views/DashboardView.vue'),
            },
            {
                path: 'expenses',
                name: 'expenses',
                component: () => import('../views/ExpenseListView.vue'),
            },
            {
                path: 'expenses/create',
                name: 'expenses.create',
                component: () => import('../views/ExpenseFormView.vue'),
            },
            {
                path: 'expenses/:id/edit',
                name: 'expenses.edit',
                component: () => import('../views/ExpenseFormView.vue'),
                props: true,
            },
            {
                path: 'approvals',
                name: 'approvals',
                component: () => import('../views/ApprovalQueueView.vue'),
                meta: { role: ['manager', 'admin'] },
            },
            {
                path: 'admin',
                name: 'admin',
                component: () => import('../views/AdminView.vue'),
                meta: { role: ['admin'] },
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const auth = useAuthStore();

    // Guest routes (login) — skip auth check
    if (to.meta.guest) {
        if (auth.user) {
            return next({ name: 'dashboard' });
        }
        return next();
    }

    // Protected routes — check auth
    if (!auth.user) {
        try {
            await auth.fetchUser();
        } catch {
            auth.user = null;
            return next({ name: 'login' });
        }
    }

    // Role check
    if (to.meta.role && !to.meta.role.includes(auth.user?.role)) {
        return next({ name: 'dashboard' });
    }

    next();
});

export default router;
