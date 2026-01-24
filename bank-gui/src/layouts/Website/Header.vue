<template>
  <header class="fixed w-full nav-glass z-50 shadow-sm">
    <div class="max-w-[86rem] mx-auto flex justify-between items-center py-4 px-4">
      <!-- Logo -->
      <a href="#" class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg flex items-center justify-center">
          <i class="fas fa-cube text-white"></i>
        </div>
        <span class="text-xl font-bold text-gray-800 dark:text-white">
          I mash banking
        </span>
      </a>

      <!-- Desktop -->
      <nav class="hidden lg:flex space-x-8">
        <a v-for="item in menu" :key="item" href="#" class="nav-link">
          {{ item }}
        </a>
      </nav>

      <!-- Actions -->
      <div class="flex items-center space-x-3">
        <button @click="toggleDark" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800">
          <i class="fas fa-moon dark:hidden"></i>
          <i class="fas fa-sun hidden dark:block"></i>
        </button>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue'

const menu = ['Home', 'Packages', 'About', 'Contact', 'FAQ']

const toggleDark = () => {
  document.documentElement.classList.toggle('dark')
}
</script>

<style scoped>
.nav-link {
  @apply text-gray-600 dark:text-gray-300 hover:text-gray-500 font-medium transition;
}
</style>
