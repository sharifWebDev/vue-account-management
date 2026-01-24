<template>
  <nav class="text-sm text-gray-500 align-middle justify-center my-auto">
    <ol class="flex items-center space-x-2 text-gray-500">
      <li>
        <router-link to="/dashboard" class="text-gray-600 dark:text-gray-100">
         <i class="fas fa-home"></i>  Home
        </router-link>
      </li>
      <li v-for="(crumb, index) in breadcrumbs" :key="index">
        <span v-if="index < breadcrumbs.length - 1" class="font-semibold text-gray-600 dark:text-gray-100">
          {{ crumb }} <i class="fas fa-chevron-right text-xs text-gray-400"></i>
        </span>
        <span v-else class="font-semibold text-gray-600 dark:text-gray-100">
          <i class="fas fa-chevron-right text-xs text-gray-400"></i> {{ crumb }}
        </span>
      </li>
    </ol>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const breadcrumbs = computed(() =>
  route.matched
    .map(r => r.meta.breadcrumb)
    .filter(Boolean)
)
</script>
