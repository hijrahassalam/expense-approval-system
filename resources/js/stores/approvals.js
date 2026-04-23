import { defineStore } from 'pinia';
import api from '../lib/axios';

export const useApprovalStore = defineStore('approvals', {
    state: () => ({
        pendingExpenses: [],
        loading: false,
    }),

    actions: {
        async fetchPending() {
            this.loading = true;
            try {
                const { data } = await api.get('/expenses', {
                    params: { status: 'submitted' },
                });
                this.pendingExpenses = data.data;
            } finally {
                this.loading = false;
            }
        },

        async approveExpense(id, comment) {
            const { data } = await api.post(`/expenses/${id}/approve`, { comment });
            this.pendingExpenses = this.pendingExpenses.filter((e) => e.id !== id);
            return data;
        },

        async rejectExpense(id, comment) {
            const { data } = await api.post(`/expenses/${id}/reject`, { comment });
            this.pendingExpenses = this.pendingExpenses.filter((e) => e.id !== id);
            return data;
        },
    },
});
