<template>
  <div class="max-w-md mx-auto bg-white p-8 border border-gray-200 rounded-lg shadow-sm">
    <h2 class="text-2xl font-bold mb-6 text-gray-900">Login</h2>
    <form @submit.prevent="handleLogin" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input v-model="form.email" type="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input v-model="form.password" type="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
      </div>
      <button type="submit" :disabled="loading" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
        {{ loading ? 'Logging in...' : 'Login' }}
      </button>
      <p v-if="error" class="text-red-500 text-sm mt-2">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

const auth = useAuthStore();
const router = useRouter();

const form = reactive({
  email: '',
  password: ''
});

const loading = ref(false);
const error = ref('');

const handleLogin = async () => {
  loading.value = true;
  error.value = '';
  try {
    await auth.login(form);
    router.push('/dashboard');
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to login. Please check your credentials.';
  } finally {
    loading.value = false;
  }
};
</script>
