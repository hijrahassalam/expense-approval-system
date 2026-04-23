<script setup>
import { useAuthStore } from '../../stores/auth';

const auth = useAuthStore();
</script>

<template>
    <aside class="w-64 bg-gray-900 text-white min-h-screen p-4">
        <nav class="space-y-2">
            <router-link
                to="/dashboard"
                class="block px-4 py-2 rounded hover:bg-gray-800"
                active-class="bg-gray-800"
            >
                Dashboard
            </router-link>
            <router-link
                to="/expenses"
                class="block px-4 py-2 rounded hover:bg-gray-800"
                active-class="bg-gray-800"
            >
                My Expenses
            </router-link>
            <router-link
                v-if="auth.canApprove"
                to="/approvals"
                class="block px-4 py-2 rounded hover:bg-gray-800"
                active-class="bg-gray-800"
            >
                Approvals
            </router-link>
            <router-link
                v-if="auth.isAdmin"
                to="/admin"
                class="block px-4 py-2 rounded hover:bg-gray-800"
                active-class="bg-gray-800"
            >
                Admin
            </router-link>
        </nav>
    </aside>
</template>
