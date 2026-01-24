<script setup>
import { onMounted, onUnmounted, watchEffect } from 'vue'

const props = defineProps({
  show: { type: Boolean, default: false },
  title: { type: String, default: '' },
  // Width control korar jonno (default 2xl)
  maxWidth: { type: String, default: '2xl' }, 
  // Close button show/hide korar jonno
  showClose: { type: Boolean, default: true },
  // Backdrop click korle bondho hobe kina
  closeOnBackdrop: { type: Boolean, default: true }
});

const emit = defineEmits(['close']);

const handleClose = () => {
  if(props.showClose || props.closeOnBackdrop) {
    emit('close');
  }
};

// Close on Escape key
const handleEscape = (e) => {
  if (e.key === 'Escape' && props.show && props.showClose) handleClose();
};

// Prevent background scrolling
watchEffect(() => {
  if (props.show) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
});

onMounted(() => window.addEventListener('keydown', handleEscape));
onUnmounted(() => {
  window.removeEventListener('keydown', handleEscape);
  document.body.style.overflow = '';
});

// Dynamic class for width
const widthClass = {
  'sm': 'max-w-sm',
  'md': 'max-w-md',
  'lg': 'max-w-lg',
  'xl': 'max-w-xl',
  '2xl': 'max-w-2xl',
  '3xl': 'max-w-3xl',
  '4xl': 'max-w-4xl',
  '5xl': 'max-w-5xl',
  'full': 'max-w-[95vw]'
}[props.maxWidth] || 'max-w-2xl';
</script>

<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
        
        <div 
          class="fixed inset-0 bg-gray-700/60 transition-opacity" 
          @click="closeOnBackdrop ? handleClose() : null"
        ></div>

        <Transition name="pop">
          <div
            v-if="show"
            :class="[widthClass]"
            class="relative z-[101] w-full transform overflow-hidden rounded-2xl bg-white dark:bg-gray-900 shadow-2xl transition-all border border-gray-200 dark:border-gray-800"
          >
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-8 py-3">
              <h3 class="text-lg font-semibold text-gray-700 dark:text-white">
               {{ title }}
              </h3>
              <button 
                v-if="showClose"
                @click="handleClose" 
                class="rounded-full p-2 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-600 transition-colors"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <div class="px-8 py-6 max-h-[80vh] overflow-y-auto custom-scrollbar">
              <slot />
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.pop-enter-active { transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
.pop-leave-active { transition: all 0.2s ease-in; }
.pop-enter-from { opacity: 0; transform: scale(0.95) translateY(10px); }
.pop-leave-to { opacity: 0; transform: scale(0.98); }

.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.3);
  border-radius: 10px;
}
</style>

<!-- useages -->
 <!-- <BaseModal 
  :show="isModalOpen" 
  title="Add New Size Type" 
  @close="isModalOpen = false"
>
  <form @submit.prevent="saveData">
    <div class="mt-4 flex justify-end gap-2">
       <button type="button" @click="isModalOpen = false">Cancel</button>
       <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
    </div>
  </form>
</BaseModal>

<BaseModal 
  :show="isModalOpen" 
  title="Confirm Action" 
  maxWidth="lg"
  :showClose="false"
  @close="isModalOpen = false"
>
  <p>Are you sure you want to delete this?</p>
</BaseModal>
-->