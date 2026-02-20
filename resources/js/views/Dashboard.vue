<template>
  <div class="bg-white p-8 border border-gray-200 rounded-lg shadow-sm">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
      <button @click="handleLogout" class="text-sm text-red-600 hover:text-red-800 font-medium">Logout</button>
    </div>
    <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-indigo-700">
            Welcome to your Decision Journal dashboard. Protected API content will appear here.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

const auth = useAuthStore();
const router = useRouter();

const handleLogout = async () => {
  try {
    await auth.logout();
    router.push('/login');
  } catch (err) {
    console.error('Logout failed', err);
  }
};
</script>
