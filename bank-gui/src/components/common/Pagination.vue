<template>
  <!-- Pagination Wrapper -->
  <div v-if="totalItems > 0" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <!-- Left: Showing results -->
      <div class="text-sm text-gray-700 dark:text-gray-300">
        Showing
        <span class="font-medium">{{ showingFrom }}</span>
        to
        <span class="font-medium">{{ showingTo }}</span>
        of
        <span class="font-medium">{{ totalItems }}</span>
        results
      </div>

      <!-- Center: Per page dropdown -->
      <div class="flex items-center space-x-2">
        <span class="text-sm text-gray-600 dark:text-gray-400">Show</span>
        <select v-model="localPerPage" :disabled="loadingAllData" @change="handlePerPageChange" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 text-sm
                 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300
                 focus:outline-none focus:ring-2 focus:ring-gray-600
                 transition-all shadow-sm disabled:opacity-60">
          <option v-for="option in perPageOptions" :key="option.value" :value="option.value">
            {{ option.label }}
          </option>
        </select>
        <span class="text-sm text-gray-600 dark:text-gray-400">entries</span>
      </div>

      <!-- Right: Pagination buttons -->
      <div class="flex space-x-1">
        <button @click="goToPage(1)" :disabled="currentPage === 1 || isLoading" :class="buttonClass(currentPage === 1)">
          <i class="fas fa-angle-double-left"></i>
        </button>
        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1 || isLoading"
          :class="buttonClass(currentPage === 1)">
          <i class="fas fa-chevron-left"></i>
        </button>

        <template v-for="page in getPageNumbers()" :key="page">
          <button v-if="page !== '...'" @click="goToPage(page)" :disabled="isLoading" :class="[
            'px-2 py-1.5 rounded-lg text-sm font-medium min-w-[2rem] transition-all',
            page === currentPage
              ? 'bg-gray-600 text-white shadow-sm'
              : 'bg-gray-200 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-700',
            isLoading ? 'opacity-50 cursor-not-allowed' : ''
          ]">
            {{ page }}
          </button>
          <span v-else class="px-2 py-1.5 text-gray-500 select-none">...</span>
        </template>

        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === lastPage || isLoading"
          :class="buttonClass(currentPage === lastPage)">
          <i class="fas fa-chevron-right"></i>
        </button>
        <button @click="goToPage(lastPage)" :disabled="currentPage === lastPage || isLoading"
          :class="buttonClass(currentPage === lastPage)">
          <i class="fas fa-angle-double-right"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Loading Overlay -->
  <div v-if="loadingAllData" class="fixed inset-0 bg-gray-400/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl text-center">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-600 mx-auto mb-4"></div>
      <p class="text-gray-700 dark:text-gray-300 font-medium">Loading all data, please wait...</p>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">This may take a moment for large datasets</p>
    </div>
  </div>

  <!-- Confirmation Modal -->
  <div v-if="showConfirmationModal" class="fixed inset-0 bg-gray-400/50 flex items-center justify-center z-50 px-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full shadow-2xl">
      <div class="flex items-center mb-4">
        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
          <i class="fas fa-exclamation-triangle text-yellow-600"></i>
        </div>
        <h3 class="ml-4 text-lg font-bold text-gray-900 dark:text-white">Show All Records</h3>
      </div>
      <p class="text-gray-600 dark:text-gray-300 mb-6">
        You are about to load
        <span class="font-bold text-gray-600">{{ totalItems.toLocaleString() }}</span>
        records. This may slow down your browser.
      </p>
      <div class="flex justify-end space-x-3">
        <button @click="cancelLoadAll"
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
          Cancel
        </button>
        <button @click="confirmLoadAll"
          class="px-4 py-2 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-lg shadow-md">
          Yes, Load All
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  totalItems: { type: Number, required: true },
  perPage: { type: Number, required: true },
  currentPage: { type: Number, required: true },
  lastPage: { type: Number, required: true },
  isLoading: { type: Boolean, default: false } // Add loading state from parent
});

const emit = defineEmits(['change-page', 'change-per-page']);

/* Local state */
const localPerPage = ref(props.perPage);
const previousPerPage = ref(props.perPage);
const showConfirmationModal = ref(false);
const loadingAllData = ref(false);

/* Per page options */
const perPageOptions = [
  { label: 10, value: 10 },
  { label: 20, value: 20 },
  { label: 50, value: 50 },
  { label: 100, value: 100 },
  { label: 500, value: 500 },
  { label: 'All', value: 'all' }
];

/* Showing range */
const showingFrom = computed(() =>
  props.totalItems === 0 ? 0 : (props.currentPage - 1) * props.perPage + 1
);

const showingTo = computed(() =>
  Math.min(props.currentPage * props.perPage, props.totalItems)
);

/* Sync perPage when parent updates */
watch(
  () => props.perPage,
  (val) => {
    localPerPage.value = val === props.totalItems ? 'all' : val;
    previousPerPage.value = localPerPage.value;
  },
  { immediate: true }
);

/* Handle per page change */
const handlePerPageChange = () => {
  if (localPerPage.value === 'all' && props.totalItems > 1000) {
    showConfirmationModal.value = true;
  } else {
    const perPageVal = localPerPage.value === 'all' ? props.totalItems : Number(localPerPage.value);
    emit('change-per-page', perPageVal);
  }
};

/* Confirm load all */
const confirmLoadAll = async () => {
  showConfirmationModal.value = false;
  loadingAllData.value = true;

  const perPageVal = props.totalItems;
  emit('change-per-page', perPageVal);
  emit('change-page', 1);

  // Simulate loading time for UX
  setTimeout(() => {
    loadingAllData.value = false;
  }, 1500);
};

const cancelLoadAll = () => {
  showConfirmationModal.value = false;
  localPerPage.value = previousPerPage.value;
};

/* Pagination navigation */
const goToPage = (page) => {
  // Prevent navigation if already on that page or loading
  if (page >= 1 && page <= props.lastPage && page !== props.currentPage && !props.isLoading) {
    emit('change-page', page);
  }
};

/* Optimized page numbers with dots */
const getPageNumbers = () => {
  const current = props.currentPage;
  const last = props.lastPage;
  const delta = 2;
  const pages = [];

  for (let i = Math.max(1, current - delta); i <= Math.min(last, current + delta); i++) {
    pages.push(i);
  }

  if (pages[0] > 2) pages.unshift('...');
  if (pages[0] !== 1) pages.unshift(1);

  if (pages[pages.length - 1] < last - 1) pages.push('...');
  if (pages[pages.length - 1] !== last) pages.push(last);

  return pages;
};

/* Button class helper */
const buttonClass = (disabled) => [
  'px-2 py-1.5 rounded-lg text-sm font-medium bg-gray-200 transition-all',
  disabled || props.isLoading ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-300 dark:text-gray-300 dark:hover:bg-gray-700'
];
</script>