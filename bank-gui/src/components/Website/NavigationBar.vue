<template>
  <header class="fixed w-full nav-glass z-50 shadow-sm">
    <div class="container mx-auto">
      <div class="lg:max-w-[86rem] mx-auto flex justify-between items-center py-4 px-4">
        <!-- Logo -->
        <router-link to="/" class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-lg flex items-center justify-center shadow-md">
            <i class="fas fa-cube text-white text-xl"></i>
          </div>
          <span class="text-xl font-bold text-gray-800 dark:text-white">Banking<span class="bg-gradient-to-r from-cyan-500 to-cyan-600 bg-clip-text text-transparent"> </span></span>
        </router-link>
        
        <!-- Desktop Menu -->
        <nav class="hidden lg:flex items-center space-x-8">
          <a href="#home" class="text-gray-600 dark:text-gray-300 hover:text-cyan-500 transition font-medium">Home</a>
          <a href="#packages" class="text-gray-600 dark:text-gray-300 hover:text-cyan-500 transition font-medium">Packages</a>
          <a href="#about" class="text-gray-600 dark:text-gray-300 hover:text-cyan-500 transition font-medium">About</a>
          <a href="#contact" class="text-gray-600 dark:text-gray-300 hover:text-cyan-500 transition font-medium">Contact</a>
          <a href="#faq" class="text-gray-600 dark:text-gray-300 hover:text-cyan-500 transition font-medium">FAQ</a>
            </nav>
        
        <!-- Dark Mode Toggle & Mobile Menu Button -->
        <div class="flex items-center space-x-4">
           
        </div>
      </div>
      
      <!-- Mobile Menu -->
      <div v-show="mobileMenuOpen" class="lg:hidden py-4 border-t border-gray-200 dark:border-gray-700 px-4">
        <div class="flex flex-col space-y-4">
          <a href="#home" class="text-gray-600 dark:text-gray-300 hover:text-cyan-500 transition font-medium py-2">Home</a>
         </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useGlobalDarkMode } from '@/utils/useDarkMode.js';

const { isDarkMode, toggleDarkMode } = useGlobalDarkMode();
const mobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
};

const showAuthModal = () => {
  const event = new CustomEvent('show-auth-modal');
  window.dispatchEvent(event);
  mobileMenuOpen.value = false;
};

// Close mobile menu when clicking outside
onMounted(() => {
  const handleClickOutside = (event) => {
    const nav = document.querySelector('header');
    if (mobileMenuOpen.value && nav && !nav.contains(event.target)) {
      mobileMenuOpen.value = false;
    }
  };
  
  document.addEventListener('click', handleClickOutside);
  
  onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
  });
});
</script>