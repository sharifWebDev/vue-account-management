<script setup>
import { onMounted } from 'vue';

const props = defineProps({
  message: String,
  type: { type: String, default: 'success' }, // 'success' | 'error'
  duration: { type: Number, default: 4000 }
});

const emit = defineEmits(['close']);

onMounted(() => {
  setTimeout(() => emit('close'), props.duration);
});
</script>

<template>
  <Transition name="toast-slide">
    <div 
      class="pointer-events-auto relative w-full max-w-sm overflow-hidden rounded-xl bg-white/90 dark:bg-gray-900/90 backdrop-blur-lg shadow-[0_8px_30px_rgb(0,0,0,0.12)] border-none"
    >
      <div 
        class="absolute left-0 top-0 bottom-0 w-1.5"
 :class="type === 'error' ? 'bg-red-500' : 'bg-gray-500'"
      ></div>

      <div class="p-5">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <div 
              class="flex h-10 w-10 items-center justify-center rounded-full"
              :class="type === 'error' ? 'bg-red-100 dark:bg-red-900/30' : 'bg-gray-100 dark:bg-gray-900/30'"
            >
              <i v-if="type === 'error'" class="fas fa-exclamation text-red-600 dark:text-red-400"></i>
              <i v-else class="fas fa-check text-gray-600 dark:text-gray-400"></i>
            </div>
          </div>
          
          <div class="ml-4 flex-1">
            <p class="text-sm font-bold text-gray-900 dark:text-white">
              {{ type === 'error' ? 'Something went wrong' : 'Action Successful' }}
            </p>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
              {{ message }}
            </p>
          </div>

          <div class="ml-4 flex flex-shrink-0">
            <button 
              @click="$emit('close')"
              class="inline-flex rounded-md bg-transparent text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none transition-colors"
            >
              <i class="fas fa-times text-xs"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-gray-100 dark:bg-gray-800">
        <div 
          class="h-full opacity-60 transition-all linear"
 :class="type === 'error' ? 'bg-red-500' : 'bg-gray-500'"
          :style="{ width: '100%', animation: `shrink ${duration}ms linear forwards` }"
        ></div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
/* High-performance animations */
.toast-slide-enter-active {
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.toast-slide-leave-active {
  transition: all 0.3s ease-in;
}
.toast-slide-enter-from {
  transform: translateX(100%) scale(0.9);
  opacity: 0;
}
.toast-slide-leave-to {
  transform: translateX(20px);
  opacity: 0;
}

@keyframes shrink {
  from { width: 100%; }
  to { width: 0%; }
}
</style>


<!-- use -->
 <!-- <template>
  <Teleport to="body">
    <div class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start z-[999]">
      <div class="flex flex-col items-center space-y-4 w-full sm:items-end">
        
        <BaseToast 
          v-if="sizeTypeStore.error"
          :message="sizeTypeStore.error"
          type="error"
          @close="sizeTypeStore.clearMessages"
        />

        <BaseToast 
          v-if="sizeTypeStore.success"
          :message="sizeTypeStore.success"
          type="success"
          @close="sizeTypeStore.clearMessages"
        />
        
      </div>
    </div>
  </Teleport>
</template> -->