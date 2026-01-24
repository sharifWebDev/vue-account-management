<template>
  <div class="relative">
    <button 
      @click="toggleUserMenu"
      class="flex items-center space-x-2 p-1.5 rounded-lg bg-gray-100 dark:bg-gray-700"
    >
      <div class="w-8 h-8 bg-gray-100 dark:bg-gray-900 rounded-full flex items-center justify-center">
        <span class="text-gray-600 dark:text-gray-400 text-xs font-medium">JD</span>
      </div>
      <span class="text-gray-700 dark:text-gray-300 hidden md:block text-sm">John Doe</span>
      <i class="fas fa-chevron-down text-gray-500 dark:text-gray-400 text-xs"></i>
    </button>
    
    <!-- User Dropdown -->
    <div 
      v-if="showUserMenu"
      class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 z-50 border border-gray-200 dark:border-gray-700"
    >
      <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400">Signed in as</p>
        <p class="font-medium text-gray-800 dark:text-white text-sm">john.doe@example.com</p>
      </div>
      <router-link to="/profile" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
        Profile
      </router-link>
      <router-link to="/settings" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
        Account Settings
      </router-link>
      <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
      <a href="/" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
        Back to Website
      </a>
      <button @click="logout" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
        Logout
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const showUserMenu = ref(false)

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value
}

const logout = () => {
  // Implement logout logic
  console.log('Logging out...')
  router.push('/login')
}

// Close menu when clicking outside
const handleClickOutside = (event) => {
  const dropdown = document.querySelector('[data-user-dropdown]')
  const button = document.querySelector('[data-user-button]')
  
  if (dropdown && button && !dropdown.contains(event.target) && !button.contains(event.target)) {
    showUserMenu.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>