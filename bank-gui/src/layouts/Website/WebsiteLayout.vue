<template>
  <div class="min-h-screen bg-white dark:bg-gray-900 transition-colors duration-300"> 
    <!-- <NavigationBar />  -->
    
     
     <main class="content pt-0">
        <transition name="fade" mode="out-in">
          <keep-alive>
            <Suspense>
              <template #default>
                <router-view />
              </template>
              <template #fallback>
                <PreLoader />
              </template>
            </Suspense>
          </keep-alive>
        </transition>
      </main> 
  </div>
</template>

<script setup>
import {onMounted } from 'vue' 
// import NavigationBar from '@/components/Website/NavigationBar.vue' 
import PreLoader from '@/components/core/PreLoader.vue';

import { useGlobalDarkMode } from '@/utils/useDarkMode.js';
import '@/utils/animations.js'
import '@/assets/css/index.css'
 
const { initDarkMode } = useGlobalDarkMode();

onMounted(() => {
  initDarkMode();
});
 
</script>

<style scoped>
.page-enter-active,
.page-leave-active {
  transition: all 0.3s ease;
}

.page-enter-from,
.page-leave-to {
  opacity: 0;
  transform: translateY(10px);
}
</style>