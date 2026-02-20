<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-sm mb-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-900 underline decoration-indigo-500 decoration-4">Dev Decision Journal</h1>
        <div class="space-x-4">
          <router-link to="/dashboard" class="text-gray-600 hover:text-gray-900">Dashboard</router-link>
          <router-link to="/decisions" class="text-gray-600 hover:text-gray-900">Decisions</router-link>
          <router-link to="/login" class="text-gray-600 hover:text-gray-900">Login</router-link>
        </div>
      </div>
    </nav>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <router-view></router-view>
    </main>
  </div>
</template>

<script setup>
// Root component logic
</script>
