<script setup>
import { useAuthStore } from '../../stores/auth';
import { useRouter } from 'vue-router';

const auth = useAuthStore();
const router = useRouter();

async function logout() {
    await auth.logout();
    router.push({ name: 'login' });
}
</script>

<template>
    <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <h1 class="text-xl font-bold text-gray-900">Expense System</h1>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600">{{ auth.user?.name }}</span>
            <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700">{{ auth.user?.role }}</span>
            <button @click="logout" class="text-sm text-red-600 hover:text-red-800">Logout</button>
        </div>
    </header>
</template>
