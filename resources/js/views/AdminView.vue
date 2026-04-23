<script setup>
import { ref, onMounted } from 'vue';
import api from '../lib/axios';
import StatusBadge from '../components/StatusBadge.vue';

const expenses = ref([]);
const loading = ref(true);
const filters = ref({
    status: '',
    user_id: '',
    from: '',
    to: '',
});

async function fetchExpenses() {
    loading.value = true;
    try {
        const params = {};
        if (filters.value.status) params.status = filters.value.status;
        if (filters.value.user_id) params.user_id = filters.value.user_id;
        if (filters.value.from) params.from = filters.value.from;
        if (filters.value.to) params.to = filters.value.to;

        const { data } = await api.get('/expenses', { params: { ...params, per_page: 100 } });
        expenses.value = data.data || [];
    } finally {
        loading.value = false;
    }
}

function exportCsv() {
    const params = new URLSearchParams();
    if (filters.value.status) params.append('status', filters.value.status);
    if (filters.value.user_id) params.append('user_id', filters.value.user_id);
    if (filters.value.from) params.append('from', filters.value.from);
    if (filters.value.to) params.append('to', filters.value.to);

    window.location.href = `/api/v1/admin/expenses/export?${params.toString()}`;
}

onMounted(fetchExpenses);
</script>

<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">Admin — All Expenses</h2>
            <button
                @click="exportCsv"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700"
            >
                Export CSV
            </button>
        </div>

        <div class="bg-white rounded-lg shadow p-4 mb-4 flex gap-4 flex-wrap">
            <select
                v-model="filters.status"
                @change="fetchExpenses"
                class="border rounded-lg px-3 py-2 text-sm"
            >
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="submitted">Submitted</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            <input
                v-model="filters.from"
                type="date"
                @change="fetchExpenses"
                class="border rounded-lg px-3 py-2 text-sm"
                placeholder="From"
            />
            <input
                v-model="filters.to"
                type="date"
                @change="fetchExpenses"
                class="border rounded-lg px-3 py-2 text-sm"
                placeholder="To"
            />
        </div>

        <div v-if="loading" class="text-gray-500">Loading...</div>

        <div v-else class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Staff</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Title</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Amount</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Category</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr v-for="expense in expenses" :key="expense.id">
                        <td class="px-4 py-3 text-sm">{{ expense.user?.name }}</td>
                        <td class="px-4 py-3">{{ expense.title }}</td>
                        <td class="px-4 py-3">${{ expense.amount }}</td>
                        <td class="px-4 py-3">{{ expense.category }}</td>
                        <td class="px-4 py-3">
                            <StatusBadge :status="expense.status" />
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ expense.created_at }}</td>
                    </tr>
                    <tr v-if="!expenses.length">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No expenses found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
