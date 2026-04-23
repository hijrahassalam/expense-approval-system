<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useExpenseStore } from '../stores/expenses';

const props = defineProps({
    id: String,
});

const route = useRoute();
const router = useRouter();
const store = useExpenseStore();

const isEdit = computed(() => !!props.id);
const loading = ref(false);
const error = ref('');

const form = ref({
    title: '',
    description: '',
    amount: '',
    category: '',
    notes: '',
});

const categories = ['Travel', 'Meals', 'Office Supplies', 'Software', 'Hardware', 'Other'];

onMounted(async () => {
    if (isEdit.value) {
        const expense = await store.fetchExpense(props.id);
        form.value = {
            title: expense.title,
            description: expense.description || '',
            amount: expense.amount,
            category: expense.category,
            notes: expense.notes || '',
        };
    }
});

async function handleSubmit() {
    loading.value = true;
    error.value = '';
    try {
        if (isEdit.value) {
            await store.updateExpense(props.id, form.value);
        } else {
            await store.createExpense(form.value);
        }
        router.push({ name: 'expenses' });
    } catch (e) {
        error.value = e.response?.data?.message || 'Validation failed';
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <div class="max-w-2xl">
        <h2 class="text-2xl font-bold mb-6">{{ isEdit ? 'Edit Expense' : 'New Expense' }}</h2>

        <div v-if="error" class="mb-4 p-3 bg-red-50 text-red-700 rounded text-sm">
            {{ error }}
        </div>

        <form @submit.prevent="handleSubmit" class="bg-white rounded-lg shadow p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input
                    v-model="form.title"
                    required
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea
                    v-model="form.description"
                    rows="3"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount ($)</label>
                    <input
                        v-model="form.amount"
                        type="number"
                        step="0.01"
                        min="0.01"
                        required
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select
                        v-model="form.category"
                        required
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="" disabled>Select category</option>
                        <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea
                    v-model="form.notes"
                    rows="2"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                />
            </div>
            <div class="flex gap-3">
                <button
                    type="submit"
                    :disabled="loading"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50"
                >
                    {{ loading ? 'Saving...' : (isEdit ? 'Update' : 'Create') }}
                </button>
                <router-link
                    to="/expenses"
                    class="px-6 py-2 border rounded-lg text-gray-700 hover:bg-gray-50"
                >
                    Cancel
                </router-link>
            </div>
        </form>
    </div>
</template>
