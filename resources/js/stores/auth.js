import { defineStore } from 'pinia';
import api from '../lib/axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.user,
        isAdmin: (state) => state.user?.role === 'admin',
        isManager: (state) => state.user?.role === 'manager',
        isStaff: (state) => state.user?.role === 'staff',
        canApprove: (state) => ['manager', 'admin'].includes(state.user?.role),
    },

    actions: {
        async login(credentials) {
            await api.get('/sanctum/csrf-cookie');
            await api.post('/login', credentials);
            await this.fetchUser();
        },

        async logout() {
            await api.post('/logout');
            this.user = null;
        },

        async fetchUser() {
            const { data } = await api.get('/me');
            this.user = data.user;
        },
    },
});
