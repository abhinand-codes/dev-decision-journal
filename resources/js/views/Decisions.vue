<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h2 class="text-2xl font-bold text-gray-900">Decisions</h2>
    </div>

    <!-- Error State -->
    <div v-if="store.error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
      <div class="flex">
        <div class="ml-3">
          <p class="text-sm text-red-700">{{ store.error }}</p>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="store.loading" class="flex justify-center py-12">
      <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>

    <!-- Empty State -->
    <div v-else-if="store.decisions.length === 0" class="text-center py-12 bg-white rounded-lg border border-gray-200 shadow-sm">
      <p class="text-gray-500">No decisions found. Start by documenting your first decision!</p>
    </div>

    <!-- List State -->
    <div v-else class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
      <ul class="divide-y divide-gray-200">
        <li v-for="decision in store.decisions" :key="decision.id" class="p-6 hover:bg-gray-50 transition">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-gray-900">{{ decision.title }}</h3>
              <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500">
                <span class="flex items-center">
                  <span class="font-medium mr-1 text-gray-700">Status:</span>
                  <span :class="{
                    'text-yellow-600': decision.status === 'pending',
                    'text-green-600': decision.status === 'completed',
                    'text-blue-600': decision.status === 'awaiting_review'
                  }">{{ decision.status.replace('_', ' ') }}</span>
                </span>
                <span class="flex items-center">
                  <span class="font-medium mr-1 text-gray-700">Confidence:</span>
                  {{ decision.confidence_score }}%
                </span>
                <span class="flex items-center" v-if="decision.review_date">
                  <span class="font-medium mr-1 text-gray-700">Review On:</span>
                  {{ new Date(decision.review_date).toLocaleDateString() }}
                </span>
              </div>
            </div>
          </div>
        </li>
      </ul>

      <!-- Pagination Controls -->
      <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
        <div class="text-sm text-gray-700">
          Showing <span class="font-medium">{{ store.decisions.length }}</span> results
        </div>
        <div class="flex space-x-2">
          <button 
            @click="loadPage(store.pagination.current_page - 1)" 
            :disabled="!store.pagination.prev_page_url || store.loading"
            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>
          <button 
            @click="loadPage(store.pagination.current_page + 1)" 
            :disabled="!store.pagination.next_page_url || store.loading"
            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useDecisionsStore } from '../stores/decisions';

const store = useDecisionsStore();

onMounted(() => {
  store.fetchDecisions();
});

const loadPage = (page) => {
  store.fetchDecisions({ page });
};
</script>
