import { defineStore } from 'pinia';
import axios from 'axios';
import api from '../lib/axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        loading: false,
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
            await axios.get('/sanctum/csrf-cookie', { withCredentials: true });
            const { data } = await api.post('/login', credentials);
            this.user = data.user;
        },

        async logout() {
            try {
                await api.post('/logout');
            } catch {
                // ignore
            }
            this.user = null;
        },

        async fetchUser() {
            const { data } = await api.get('/me');
            this.user = data.user;
        },
    },
});
