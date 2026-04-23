<script setup>
defineProps({
    status: String,
});

const colors = {
    draft: 'bg-gray-100 text-gray-700',
    submitted: 'bg-yellow-100 text-yellow-700',
    approved: 'bg-green-100 text-green-700',
    rejected: 'bg-red-100 text-red-700',
};
</script>

<template>
    <span
        :class="['px-2 py-1 rounded-full text-xs font-medium', colors[status] || 'bg-gray-100 text-gray-700']"
    >
        {{ status }}
    </span>
</template>
