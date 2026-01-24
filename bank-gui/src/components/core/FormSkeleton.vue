<script setup>
const columns = 4;
const rows = 6;
</script>

<template>
  <div
    role="status"
    class="w-full space-y-0 rounded animate-pulse mt-3 mb-5"
  >
    <div
      v-for="row in rows"
      :key="row"
      class="flex justify-between items-center px-4 py-2.5"
    >
      <div v-for="col in columns" :key="col">
        <div
 class="h-2.5 bg-gray-600 rounded-full dark:bg-gray-600 w-24 mb-2"
        ></div>
        <div class="w-54 h-5 bg-gray-900 rounded dark:bg-gray-500"></div>
      </div>
      <span class="sr-only">Loading...</span>
    </div>
    <div class="flex justify-between items-center px-4 py-3">
      <div class="w-30 h-4 bg-gray-900 rounded dark:bg-gray-500"></div>
    </div>
  </div>
</template>
