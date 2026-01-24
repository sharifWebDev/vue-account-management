<template>
  <tr>
    <td :colspan="colspan" class="text-center p-0">
      <div class="flex flex-col items-center justify-center w-full p-0">
        <!-- Loading State -->
        <template v-if="isLoading">
          <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full h-full flex flex-col items-center justify-center space-y-4 
          bg-gray-200/50 dark:bg-gray-800/70 rounded-lg ">
           
          <div class="p-4 bg-gray-200 rounded -lg dark:bg-gray-800 flex flex-col items-center justify-center">
          <!-- Spinner -->
          <div class="relative shaqow bg-gray-200 dark:bg-gray-800 w-16 h-16 rounded-full flex items-center justify-center">
            <div class="animate-spin rounded-full h-14 w-14 border-4 border-gray-200 dark:border-gray-700"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
              <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-gray-600"></div>
            </div>
          </div>

           <!-- Loading text -->
          <p class="mt-4 text-gray-700 dark:text-gray-300 font-medium">
            {{ loadingText || 'Loading data...' }}
          </p>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            {{ loadingSubtext || 'Please wait while we fetch your data' }}
          </p>
         </div>

          <!-- Optional: Progress bar for large datasets -->
          <div v-if="showProgress && totalItems > 100" class="mt-4 w-64">
            <div class="h-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
              <div class="h-full bg-gray-600 rounded-full animate-pulse" style="width: 60%"></div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
              Loading {{ totalItems.toLocaleString() }} {{ itemName }}...
            </p>
          </div>
          </div>
        </template>

        <!-- Empty State -->
        <template v-else>
          <div class="flex flex-col items-center justify-center py-12 px-6">
          <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
            <i :class="emptyIcon || 'fas fa-inbox text-3xl text-gray-500 dark:text-gray-400'"></i>
          </div>
          <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ emptyTitle || 'No data found' }}
          </h3>
          <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
            {{ emptyMessage || (hasSearch ? 'No data match your search criteria.' : 'Get started by creating your first data.') }}
          </p>
          
          <!-- Action Buttons -->
          <div class="flex flex-col sm:flex-row gap-3">
            <button 
              v-if="!hasSearch && showCreateButton"
              @click="$emit('create')"
              class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2"
            >
              <i class="fas fa-plus"></i>
              {{ createButtonText || 'Create New Data' }}
            </button>
            
            <button 
              v-if="hasSearch && showClearButton"
              @click="$emit('clear-search')"
              class="px-4 py-2 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 font-medium transition-colors border border-gray-300 dark:border-gray-600 rounded-lg"
            >
              {{ clearButtonText || 'Clear Search' }}
            </button>
            
            <button 
              v-if="showRefreshButton"
              @click="$emit('refresh')"
              class="px-4 py-2 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 font-medium transition-colors border border-gray-300 dark:border-gray-600 rounded-lg flex items-center justify-center gap-2"
            >
              <i class="fas fa-redo"></i>
              Refresh
            </button>
          </div>
          </div>
        </template>
      </div>
    </td>
  </tr>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  // State props
  isLoading: {
    type: Boolean,
    default: false
  },
  isEmpty: {
    type: Boolean,
    default: false
  },
  hasSearch: {
    type: Boolean,
    default: false
  },
  
  // Configuration props
  colspan: {
    type: Number,
    default: 100 // Using 100% colspan
  },
  totalItems: {
    type: Number,
    default: 0
  },
  itemName: {
    type: String,
    default: 'data'
  },
  showProgress: {
    type: Boolean,
    default: true
  },
  
  // Loading state customization
  loadingText: {
    type: String,
    default: ''
  },
  loadingSubtext: {
    type: String,
    default: ''
  },
  
  // Empty state customization
  emptyIcon: {
    type: String,
    default: ''
  },
  emptyTitle: {
    type: String,
    default: ''
  },
  emptyMessage: {
    type: String,
    default: ''
  },
  
  // Button configuration
  showCreateButton: {
    type: Boolean,
    default: true
  },
  createButtonText: {
    type: String,
    default: ''
  },
  showClearButton: {
    type: Boolean,
    default: true
  },
  clearButtonText: {
    type: String,
    default: ''
  },
  showRefreshButton: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['create', 'clear-search', 'refresh']);

// Computed properties
const shouldShowEmptyState = computed(() => {
  return props.isEmpty && !props.isLoading;
});
</script>

<style scoped>
/* Optional custom animations */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

tr {
  animation: fadeIn 0.3s ease-in-out;
}
</style>