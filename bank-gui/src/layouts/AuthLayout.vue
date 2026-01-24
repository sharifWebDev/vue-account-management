<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 
  dark:from-gray-900 dark:to-gray-800 flex items-center justify-center">
    <div class="w-full">
      <router-view v-slot="{ Component }">
        <transition name="auth" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </div>
  </div>
</template>

<script setup>
  
</script>

<style scoped>
.auth-enter-active,
.auth-leave-active {
  transition: all 0.3s ease;
}

.auth-enter-from,
.auth-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
</style>