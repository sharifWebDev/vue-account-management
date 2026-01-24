<template>
  <div class="alert-messages">
    <!-- Success Message -->
    <div 
      v-if="successMessage"
      class="alert alert-success flex items-center justify-between p-4 mb-4 rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30"
    >
      <div class="flex items-center">
        <i class="fas fa-check-circle text-green-600 dark:text-green-400 mr-3"></i>
        <span class="text-green-800 dark:text-green-200">{{ successMessage }}</span>
      </div>
      <button 
        @click="$emit('clearSuccess')"
        class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300"
      >
        <i class="fas fa-times"></i>
      </button>
    </div>

    <!-- Error Message -->
    <div 
      v-if="errorMessage"
      class="alert alert-error flex items-center justify-between p-4 mb-4 rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30"
    >
      <div class="flex items-center">
        <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 mr-3"></i>
        <span class="text-red-800 dark:text-red-200">{{ errorMessage }}</span>
      </div>
      <button 
        @click="$emit('clearErrors')"
        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
      >
        <i class="fas fa-times"></i>
      </button>
    </div>

    <!-- Multiple Errors -->
    <div 
      v-if="errors.length > 0"
      class="alert alert-error p-4 mb-4 rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30"
    >
      <div class="flex items-center mb-2">
        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 mr-3"></i>
        <span class="text-red-800 dark:text-red-200 font-medium">Please fix the following errors:</span>
      </div>
      <ul class="list-disc list-inside ml-7">
        <li 
          v-for="(error, index) in errors"
          :key="index"
          class="text-red-700 dark:text-red-300 text-sm mb-1"
        >
          {{ error }}
        </li>
      </ul>
      <div class="mt-3 text-right">
        <button 
          @click="$emit('clearErrors')"
          class="text-xs text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 underline"
        >
          Clear all errors
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  errors: {
    type: Array,
    default: () => []
  },
  errorMessage: {
    type: String,
    default: ""
  },
  successMessage: {
    type: String,
    default: ""
  }
});

defineEmits(['clearErrors', 'clearSuccess']);
</script>