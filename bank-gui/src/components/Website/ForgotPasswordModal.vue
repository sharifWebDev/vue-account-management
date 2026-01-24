<template>
  <div 
    class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 z-50 transition-all duration-300"
    :class="modalClasses"
    @click.self="$emit('close')"
  >
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-md transform transition-all duration-300 shadow-2xl relative overflow-hidden" :class="contentClasses">
      <!-- Gradient Top Border -->
      <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-gray-500 via-purple-500 to-pink-500"></div>
      
      <div class="text-center mb-6">
        <div class="w-16 h-16 bg-gradient-to-r from-gray-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
          <i class="fas fa-key text-white text-2xl"></i>
        </div>
        <h2 class="text-2xl font-bold bg-gradient-to-r from-gray-600 to-purple-600 bg-clip-text text-transparent">
          Reset Password
        </h2>
        <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">Enter your email to receive a reset link</p>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-5">
        <div class="space-y-2">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
          <div class="relative">
            <input 
              v-model="email"
              type="email" 
              class="w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:text-white transition-all duration-300 pl-11" 
              placeholder="your@email.com" 
              required
            />
            <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
          </div>
        </div>
        
        <button 
          type="submit" 
          :disabled="isSubmitting"
          class="w-full bg-gradient-to-r from-gray-500 to-purple-600 text-white py-3.5 rounded-xl hover:shadow-lg transition-all duration-300 font-semibold text-sm hover:scale-[1.02] active:scale-[0.98] shadow-md disabled:opacity-50"
        >
          <i class="fas fa-paper-plane mr-2"></i>
          {{ isSubmitting ? 'Sending...' : 'Send Reset Link' }}
        </button>
      </form>

      <div class="text-center mt-6">
        <button 
          @click="$emit('back-to-login')"
          class="text-gray-600 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 font-medium transition-colors text-sm"
        >
          <i class="fas fa-arrow-left mr-2"></i>Back to Login
        </button>
      </div>

      <!-- Close Button -->
      <button 
        @click="$emit('close')"
        class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-all duration-300 hover:scale-110 hover:bg-gray-200 dark:hover:bg-gray-600"
        aria-label="Close modal"
      >
        <i class="fas fa-times text-sm"></i>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const email = ref('');
const isSubmitting = ref(false);

const modalClasses = computed(() => ({
  'opacity-0 invisible scale-95': false,
  'opacity-100 visible scale-100': true
}));

const contentClasses = computed(() => ({
  'scale-95': false,
  'scale-100': true
}));

const handleSubmit = async () => {
  isSubmitting.value = true;
  
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1500));
    
    alert('Password reset link sent! Check your email.');
    email.value = '';
    emit('close');
  } catch (error) {
    console.error('Password reset failed:', error);
  } finally {
    isSubmitting.value = false;
  }
};

defineEmits(['close', 'back-to-login']);
</script>