<script setup>
import { onMounted, ref } from 'vue';
import { useApprovalStore } from '../stores/approvals';
import StatusBadge from '../components/StatusBadge.vue';
import ApprovalModal from '../components/ApprovalModal.vue';

const store = useApprovalStore();
const selectedExpense = ref(null);
const showModal = ref(false);

onMounted(() => store.fetchPending());

function openApproval(expense) {
    selectedExpense.value = expense;
    showModal.value = true;
}

async function handleApprove(comment) {
    await store.approveExpense(selectedExpense.value.id, comment);
    showModal.value = false;
}

async function handleReject(comment) {
    await store.rejectExpense(selectedExpense.value.id, comment);
    showModal.value = false;
}
</script>

<template>
    <div>
        <h2 class="text-2xl font-bold mb-6">Approval Queue</h2>

        <div v-if="store.loading" class="text-gray-500">Loading...</div>

        <div v-else class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Staff</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Title</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Amount</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Category</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr v-for="expense in store.pendingExpenses" :key="expense.id">
                        <td class="px-4 py-3 text-sm">{{ expense.user?.name }}</td>
                        <td class="px-4 py-3">{{ expense.title }}</td>
                        <td class="px-4 py-3">${{ expense.amount }}</td>
                        <td class="px-4 py-3">{{ expense.category }}</td>
                        <td class="px-4 py-3">
                            <button
                                @click="openApproval(expense)"
                                class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700"
                            >
                                Review
                            </button>
                        </td>
                    </tr>
                    <tr v-if="!store.pendingExpenses.length">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">No pending approvals</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <ApprovalModal
            v-if="showModal"
            :expense="selectedExpense"
            @approve="handleApprove"
            @reject="handleReject"
            @close="showModal = false"
        />
    </div>
</template>
