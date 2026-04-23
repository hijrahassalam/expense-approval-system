<script setup>
import { ref, onMounted } from 'vue';
import api from '../lib/axios';

const data = ref(null);
const loading = ref(true);

onMounted(async () => {
    try {
        // Placeholder - will be replaced with /dashboard endpoint
        const { data: expenses } = await api.get('/expenses', { params: { per_page: 100 } });
        const all = expenses.data || [];
        data.value = {
            total: all.length,
            pending: all.filter((e) => e.status === 'submitted').length,
            approved: all.filter((e) => e.status === 'approved').length,
            rejected: all.filter((e) => e.status === 'rejected').length,
        };
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div>
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

        <div v-if="loading" class="text-gray-500">Loading...</div>

        <div v-else class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Total Expenses</p>
                <p class="text-3xl font-bold">{{ data?.total || 0 }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Pending</p>
                <p class="text-3xl font-bold text-yellow-600">{{ data?.pending || 0 }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Approved</p>
                <p class="text-3xl font-bold text-green-600">{{ data?.approved || 0 }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Rejected</p>
                <p class="text-3xl font-bold text-red-600">{{ data?.rejected || 0 }}</p>
            </div>
        </div>
    </div>
</template>
