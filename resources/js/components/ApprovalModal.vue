<script setup>
import { ref } from 'vue';

defineProps({
    expense: Object,
});

const emit = defineEmits(['approve', 'reject', 'close']);

const comment = ref('');

function handleApprove() {
    emit('approve', comment.value);
}

function handleReject() {
    if (!comment.value.trim()) return;
    emit('reject', comment.value);
}
</script>

<template>
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
            <h3 class="text-xl font-bold mb-4">Review Expense</h3>

            <div class="space-y-3 mb-6">
                <div>
                    <p class="text-sm text-gray-500">Title</p>
                    <p class="font-medium">{{ expense.title }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Description</p>
                    <p>{{ expense.description || '—' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Amount</p>
                        <p class="font-medium">${{ expense.amount }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Category</p>
                        <p class="font-medium">{{ expense.category }}</p>
                    </div>
                </div>
                <div v-if="expense.notes">
                    <p class="text-sm text-gray-500">Notes</p>
                    <p>{{ expense.notes }}</p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Comment (required for rejection)</label>
                <textarea
                    v-model="comment"
                    rows="3"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="Add a comment..."
                />
            </div>

            <div class="flex gap-3 justify-end">
                <button
                    @click="$emit('close')"
                    class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50"
                >
                    Cancel
                </button>
                <button
                    @click="handleReject"
                    :disabled="!comment.trim()"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50"
                >
                    Reject
                </button>
                <button
                    @click="handleApprove"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                >
                    Approve
                </button>
            </div>
        </div>
    </div>
</template>
