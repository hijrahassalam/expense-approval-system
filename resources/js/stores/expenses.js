import { defineStore } from 'pinia';
import api from '../lib/axios';

export const useExpenseStore = defineStore('expenses', {
    state: () => ({
        expenses: [],
        currentExpense: null,
        pagination: null,
        loading: false,
    }),

    actions: {
        async fetchExpenses(params = {}) {
            this.loading = true;
            try {
                const { data } = await api.get('/expenses', { params });
                this.expenses = data.data;
                this.pagination = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    per_page: data.per_page,
                    total: data.total,
                };
            } finally {
                this.loading = false;
            }
        },

        async fetchExpense(id) {
            const { data } = await api.get(`/expenses/${id}`);
            this.currentExpense = data;
            return data;
        },

        async createExpense(payload) {
            const { data } = await api.post('/expenses', payload);
            this.expenses.unshift(data);
            return data;
        },

        async updateExpense(id, payload) {
            const { data } = await api.patch(`/expenses/${id}`, payload);
            const index = this.expenses.findIndex((e) => e.id === id);
            if (index !== -1) this.expenses[index] = data;
            this.currentExpense = data;
            return data;
        },

        async deleteExpense(id) {
            await api.delete(`/expenses/${id}`);
            this.expenses = this.expenses.filter((e) => e.id !== id);
        },

        async submitExpense(id) {
            const { data } = await api.post(`/expenses/${id}/submit`);
            const index = this.expenses.findIndex((e) => e.id === id);
            if (index !== -1) this.expenses[index] = data;
            return data;
        },
    },
});
