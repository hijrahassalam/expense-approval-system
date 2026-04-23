<script setup>
import { ref, onMounted } from 'vue';
import { useExpenseStore } from '../stores/expenses';
import { useAuthStore } from '../stores/auth';
import StatusBadge from '../components/StatusBadge.vue';

const store = useExpenseStore();
const auth = useAuthStore();
const statusFilter = ref('');

onMounted(() => store.fetchExpenses());

function filterByStatus(status) {
    statusFilter.value = status;
    store.fetchExpenses(status ? { status } : {});
}

async function handleDelete(id) {
    if (!confirm('Delete this expense?')) return;
    await store.deleteExpense(id);
}

async function handleSubmit(id) {
    await store.submitExpense(id);
    store.fetchExpenses(statusFilter.value ? { status: statusFilter.value } : {});
}
</script>

<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">My Expenses</h2>
            <router-link
                to="/expenses/create"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
            >
                + New Expense
            </router-link>
        </div>

        <div class="flex gap-2 mb-4">
            <button
                v-for="s in ['', 'draft', 'submitted', 'approved', 'rejected']"
                :key="s"
                @click="filterByStatus(s)"
                :class="[
                    'px-3 py-1 rounded-full text-sm',
                    statusFilter === s ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
                ]"
            >
                {{ s || 'All' }}
            </button>
        </div>

        <div v-if="store.loading" class="text-gray-500">Loading...</div>

        <div v-else class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Title</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Amount</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Category</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr v-for="expense in store.expenses" :key="expense.id">
                        <td class="px-4 py-3">{{ expense.title }}</td>
                        <td class="px-4 py-3">${{ expense.amount }}</td>
                        <td class="px-4 py-3">{{ expense.category }}</td>
                        <td class="px-4 py-3">
                            <StatusBadge :status="expense.status" />
                        </td>
                        <td class="px-4 py-3 flex gap-2">
                            <router-link
                                v-if="expense.status === 'draft'"
                                :to="`/expenses/${expense.id}/edit`"
                                class="text-blue-600 hover:text-blue-800 text-sm"
                            >
                                Edit
                            </router-link>
                            <button
                                v-if="expense.status === 'draft'"
                                @click="handleSubmit(expense.id)"
                                class="text-green-600 hover:text-green-800 text-sm"
                            >
                                Submit
                            </button>
                            <button
                                v-if="expense.status === 'draft'"
                                @click="handleDelete(expense.id)"
                                class="text-red-600 hover:text-red-800 text-sm"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                    <tr v-if="!store.expenses.length">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">No expenses found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
