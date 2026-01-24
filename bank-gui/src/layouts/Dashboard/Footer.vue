<template>
  <footer class="p-4 border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800">
    <div class="flex flex-col md:flex-row justify-between items-center">
      <p class="text-xs text-gray-600 dark:text-gray-400 mb-2 md:mb-0">
        © {{ currentYear }} Banking Pro. All rights reserved.
      </p>
      <div class="flex space-x-4">
        <a 
          href="#" 
          class="text-xs text-gray-600 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-400 transition-colors"
        >
          Privacy Policy
        </a>
        <a 
          href="#" 
          class="text-xs text-gray-600 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-400 transition-colors"
        >
          Terms of Service
        </a>
        <a 
          href="#" 
          class="text-xs text-gray-600 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-400 transition-colors"
        >
          Support
        </a>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue';

const currentYear = computed(() => {
  return new Date().getFullYear();
});
</script>