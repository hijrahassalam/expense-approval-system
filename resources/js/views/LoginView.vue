<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

const auth = useAuthStore();
const router = useRouter();

const form = ref({
    email: '',
    password: '',
});
const error = ref('');
const loading = ref(false);

async function handleLogin() {
    loading.value = true;
    error.value = '';
    try {
        await auth.login(form.value);
        router.push({ name: 'dashboard' });
    } catch (e) {
        error.value = e.response?.data?.message || 'Login failed';
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl font-bold text-center mb-6">Expense Approval System</h1>
            <p class="text-gray-500 text-center mb-8">Sign in to your account</p>

            <div v-if="error" class="mb-4 p-3 bg-red-50 text-red-700 rounded text-sm">
                {{ error }}
            </div>

            <form @submit.prevent="handleLogin" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="admin@demo.com"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input
                        v-model="form.password"
                        type="password"
                        required
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="password"
                    />
                </div>
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50"
                >
                    {{ loading ? 'Signing in...' : 'Sign in' }}
                </button>
            </form>

            <div class="mt-6 text-sm text-gray-500">
                <p class="font-medium mb-2">Demo accounts:</p>
                <div class="grid grid-cols-2 gap-1 text-xs">
                    <span>admin@demo.com</span><span>password</span>
                    <span>manager@demo.com</span><span>password</span>
                    <span>staff@demo.com</span><span>password</span>
                </div>
            </div>
        </div>
    </div>
</template>
