<!-- TransactionTypes.vue -->
<template>
  <div class="mb-4">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Transaction Types</h1>
      </div>
      <Breadcrumb />
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 p-3 rounded-lg dark:border dark:border-gray-700">
    <!-- Header with Filters and Search -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-4 gap-4">
      <!-- Left Side: Create Button -->
      <div class="flex flex-col sm:flex-row gap-4">
        <button @click="openCreateModal"
          class="btn px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center space-x-2 transition-colors">
          <i class="fas fa-plus"></i>
          <span>Create New</span>
        </button>
      </div>

      <!-- Right Side: Search -->
      <div class="relative flex-grow max-w-md">
        <BaseInput v-model="searchQuery" type="search" placeholder="Search by name or code..." :show-clear="true"
          :debounce="500" @search="handleSearchDebounced" @clear="clearSearch">
          <template #prefix>
            <i class="fas fa-search text-gray-400"></i>
          </template>
        </BaseInput>
      </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 relative">
          <SortableTableHeader :columns="tableColumns" :sort-by="transactionTypeStore.sortBy"
            :sort-direction="transactionTypeStore.sortDirection" :show-actions-column="true" :show-sl-column="true"
            @sort="sortByColumn" />

          <!-- Loading State -->
          <TableLoadingState v-if="transactionTypeStore.loading" :is-loading="true"
            :total-items="transactionTypeStore.totalItems" :item-name="'transaction types'"
            loading-text="Loading transaction types..." loading-subtext="Fetching data" />

          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Empty State -->
            <TableLoadingState v-if="transactionTypeStore.items.length === 0" :is-loading="false" :isEmpty="true"
              :has-search="!!searchQuery" :item-name="'transaction types'" empty-icon="fas fa-list"
              empty-title="No transaction types found"
              :empty-message="searchQuery ? 'No transaction types match your search criteria.' : 'Get started by creating your first transaction type.'"
              :create-button-text="'Add First Type'" @create="openCreateModal" @clear-search="clearSearch" />

            <!-- Data Rows -->
            <tr v-else v-for="(data, index) in displayedItems" :key="data.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ calculateRowNumber(index) }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <div class="flex items-center space-x-3">
                  <div v-if="data.icon"
                    class="h-8 w-8 flex items-center justify-center rounded bg-gray-100 dark:bg-gray-700">
                    <i :class="data.icon" class="text-gray-600 dark:text-gray-300"></i>
                  </div>
                  <div class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ data.name }}
                  </div>
                </div>
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ data.code }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ data.category || 'General' }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ data.order || 0 }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <span :class="[
                  data.status === 1 || data.status === true
                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                  'px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full'
                ]">
                  {{ data.status === 1 || data.status === true ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                <button @click="viewTransactionType(data)"
                  class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 p-1 rounded bg-blue-50 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors dark:bg-gray-500"
                  title="View">
                  <i class="fas fa-eye"></i>
                </button>
                <button @click="toggleStatus(data)"
                  class="p-1 rounded bg-gray-50 dark:bg-gray-500 hover:bg-gray-100 dark:hover:bg-gray-900/30 transition-colors"
                  title="Toggle Status">
                  <span :class="[
                    'relative inline-flex h-4 w-7 items-center rounded-full transition-colors duration-300',
                    data.status === 1 || data.status === true
                      ? 'bg-green-500'
                      : 'bg-gray-400 dark:bg-gray-600'
                  ]">
                    <span :class="[
                      'inline-block h-3 w-3 transform rounded-full bg-white transition-transform duration-300',
                      data.status === 1 || data.status === true
                        ? 'translate-x-3.5'
                        : 'translate-x-0.5'
                    ]"></span>
                  </span>
                </button>
                <button @click="editTransactionType(data)"
                  class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 p-1 rounded bg-yellow-50 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors dark:bg-gray-500"
                  title="Edit">
                  <i class="fas fa-edit"></i>
                </button>
                <button @click="confirmDelete(data)"
                  class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1 rounded bg-red-50 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors dark:bg-gray-500"
                  title="Delete">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="transactionTypeStore.pagination.total > 0" class="mt-6">
      <Pagination v-if="transactionTypeStore.totalItems > 0" :total-items="transactionTypeStore.totalItems"
        :per-page="transactionTypeStore.perPage" :current-page="transactionTypeStore.currentPage"
        :last-page="transactionTypeStore.lastPage" :is-loading="transactionTypeStore.isLoading"
        @change-page="handlePageChange" @change-per-page="handlePerPageChange" />
    </div>
  </div>

  <!-- Create/Edit Modal -->
  <BaseModal v-if="isModalOpen" :show="isModalOpen"
    :title="modalMode === 'create' ? 'Create Transaction Type' : 'Edit Transaction Type'" maxWidth="3xl"
    @close="closeModal">
    <form @submit.prevent="handleSubmit" class="px-6 py-4">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Left Column -->
        <div class="space-y-4">
          <!-- Text Input -->
          <BaseInput v-model="name" type="text" label="Name" placeholder="Enter name" />

          <!-- Email Input -->
          <BaseInput v-model="email" type="email" label="Email" prefix-icon="fas fa-envelope" />

          <!-- Password with Toggle -->
          <BaseInput v-model="password" type="password" label="Password" :show-password-toggle="true" />

          <!-- Number Input -->
          <BaseInput v-model="quantity" type="number" label="Quantity" :min="0" :max="100" />

          <!-- Phone Input -->
          <BaseInput v-model="phone" type="tel" label="Phone" prefix-icon="fas fa-phone" />

          <!-- Date Input -->
          <BaseInput v-model="birthdate" type="date" label="Birth Date" />

          <!-- Time Input -->
          <BaseInput v-model="time" type="time" label="Appointment Time" />

          <!-- Textarea -->
          <BaseInput v-model="description" type="textarea" label="Description" :rows="4" :auto-grow="true" />

          <!-- Rich Text Editor -->
          <BaseInput v-model="content" type="richtext" label="Content" />

          <!-- Radio Group -->
          <BaseInput v-model="status" type="radio" label="Status" :options="statusOptions" variant="success" />

          <!-- Checkbox -->
          <BaseInput v-model="agree" type="checkbox" label="I agree to terms" checkbox-label="Accept Terms" />

          <!-- Select Dropdown -->
          <BaseInput v-model="country" type="select" label="Country" :options="countryOptions" />

          <!-- File Upload -->
          <BaseInput v-model="file" type="file" label="Upload File" accept="image/*" :max-size="5242880" />

          <!-- Color Picker -->
          <BaseInput v-model="color" type="color" label="Choose Color" />

          <!-- Range Slider -->
          <BaseInput v-model="volume" type="range" label="Volume" :min="0" :max="100" :step="1" />

          <!-- URL Input -->
          <BaseInput v-model="website" type="url" label="Website" prefix-icon="fas fa-globe" />

          <!-- Search Input -->
          <BaseInput v-model="search" type="search" label="Search" :show-clear="true" prefix-icon="fas fa-search" />
        </div>
      </div>

      <!-- Advanced Section (Collapsible) -->
      <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
        <button type="button" @click="showAdvanced = !showAdvanced"
          class="flex items-center justify-between w-full text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
          <span>Advanced Settings</span>
          <i :class="showAdvanced ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
        </button>

        <div v-if="showAdvanced" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Max Amount -->
          <BaseInput v-model="formData.max_amount" type="number" label="Maximum Amount" :min="0" :step="0.01"
            helper-text="Maximum allowed amount" />

          <!-- Min Amount -->
          <BaseInput v-model="formData.min_amount" type="number" label="Minimum Amount" :min="0" :step="0.01"
            helper-text="Minimum allowed amount" />

          <!-- Frequency Limit -->
          <BaseInput v-model="formData.frequency_limit" type="number" label="Frequency Limit (per day)" :min="0"
            :step="1" helper-text="Maximum transactions per day" />

          <!-- Start Date -->
          <BaseInput v-model="formData.start_date" type="text" label="Start Date" placeholder="YYYY-MM-DD"
            helper-text="When this type becomes active">
            <template #suffix>
              <button type="button" @click="openDatePicker('start_date')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-calendar"></i>
              </button>
            </template>
          </BaseInput>

          <!-- End Date -->
          <BaseInput v-model="formData.end_date" type="text" label="End Date" placeholder="YYYY-MM-DD"
            helper-text="When this type becomes inactive">
            <template #suffix>
              <button type="button" @click="openDatePicker('end_date')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-calendar"></i>
              </button>
            </template>
          </BaseInput>

          <!-- Additional Notes -->
          <BaseInput v-model="formData.notes" type="textarea" label="Internal Notes"
            placeholder="Internal notes for administrators..." :rows="3" class="md:col-span-2" />
        </div>
      </div>

      <!-- Global Form Errors -->
      <div v-if="Object.keys(transactionTypeStore.validationErrors).length > 0"
        class="mt-6 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
        <p class="text-sm text-red-600 dark:text-red-400 font-medium mb-1">Please fix the following errors:</p>
        <ul class="text-xs text-red-500 dark:text-red-400 space-y-1">
          <li v-for="(errors, field) in transactionTypeStore.validationErrors" :key="field">
            • {{ field }}: {{ errors[0] }}
          </li>
        </ul>
      </div>

      <!-- Form Actions -->
      <div class="mt-6 flex justify-end space-x-3">
        <button type="button" @click="closeModal"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
          Cancel
        </button>
        <button type="submit" :disabled="transactionTypeStore.loading" :class="[
          'px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors',
          transactionTypeStore.loading ? 'bg-gray-400 cursor-not-allowed' : 'bg-gray-600 hover:bg-gray-700'
        ]">
          <span v-if="transactionTypeStore.loading">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            {{ modalMode === 'create' ? 'Creating...' : 'Updating...' }}
          </span>
          <span v-else>{{ modalMode === 'create' ? 'Create' : 'Update' }}</span>
        </button>
      </div>
    </form>
  </BaseModal>

  <!-- View Modal -->
  <BaseModal v-if="showViewModal" :show="showViewModal" :title="viewingItem?.name" maxWidth="3xl"
    @close="closeViewModal">
    <div v-if="viewingItem" class="px-6 py-4">
      <!-- Header with Icon and Status -->
      <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-3">
          <div class="h-12 w-12 rounded-lg flex items-center justify-center"
            :style="{ backgroundColor: viewingItem.color || '#6B7280' }">
            <i :class="viewingItem.icon || 'fas fa-question-circle'" class="text-white text-xl"></i>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ viewingItem.name }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ viewingItem.code }}</p>
          </div>
        </div>
        <span :class="[
          viewingItem.status === 1 || viewingItem.status === true
            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
          'px-3 py-1 text-sm font-semibold rounded-full'
        ]">
          {{ viewingItem.status === 1 || viewingItem.status === true ? 'Active' : 'Inactive' }}
        </span>
      </div>

      <!-- Details Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left Column -->
        <div class="space-y-4">
          <DetailItem label="Category" :value="viewingItem.category || 'General'" />
          <DetailItem label="Display Order" :value="viewingItem.order || '0'" />
          <DetailItem label="Description" :value="viewingItem.description || 'No description'" />
          <DetailItem label="Default Amount" :value="formatCurrency(viewingItem.default_amount)" />
          <DetailItem label="Requires Approval" :value="viewingItem.requires_approval ? 'Yes' : 'No'" />
        </div>

        <!-- Right Column -->
        <div class="space-y-4">
          <DetailItem label="Color" :value="viewingItem.color || '#6B7280'">
            <template #value>
              <div class="flex items-center space-x-2">
                <div class="h-6 w-6 rounded border" :style="{ backgroundColor: viewingItem.color || '#6B7280' }"></div>
                <span>{{ viewingItem.color || '#6B7280' }}</span>
              </div>
            </template>
          </DetailItem>
          <DetailItem label="Notification Email" :value="viewingItem.notification_email || 'Not set'" />
          <DetailItem label="Alert Phone" :value="viewingItem.alert_phone || 'Not set'" />
          <DetailItem label="Created At" :value="formatDate(viewingItem.created_at)" />
          <DetailItem label="Updated At" :value="formatDate(viewingItem.updated_at)" />
        </div>
      </div>

      <!-- Advanced Details (Collapsible) -->
      <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
        <button type="button" @click="showViewAdvanced = !showViewAdvanced"
          class="flex items-center justify-between w-full text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
          <span>Advanced Details</span>
          <i :class="showViewAdvanced ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
        </button>

        <div v-if="showViewAdvanced" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <DetailItem label="Maximum Amount" :value="formatCurrency(viewingItem.max_amount)" />
          <DetailItem label="Minimum Amount" :value="formatCurrency(viewingItem.min_amount)" />
          <DetailItem label="Frequency Limit"
            :value="viewingItem.frequency_limit ? `${viewingItem.frequency_limit} per day` : 'No limit'" />
          <DetailItem label="Start Date" :value="viewingItem.start_date || 'Not set'" />
          <DetailItem label="End Date" :value="viewingItem.end_date || 'Not set'" />
          <DetailItem label="Internal Notes" :value="viewingItem.notes || 'No notes'" />
        </div>
      </div>

      <!-- Logo Preview -->
      <div v-if="viewingItem.logo" class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Logo</h4>
        <div class="flex justify-center">
          <img :src="viewingItem.logo" :alt="viewingItem.name"
            class="h-32 rounded-lg object-contain border border-gray-200 dark:border-gray-700">
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
        <button @click="closeViewModal"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
          Close
        </button>
        <button @click="editTransactionType(viewingItem)"
          class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg text-sm font-medium transition-colors">
          <i class="fas fa-edit mr-2"></i>
          Edit
        </button>
        <button @click="confirmDelete(viewingItem)"
          class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors">
          <i class="fas fa-trash mr-2"></i>
          Delete
        </button>
      </div>
    </div>
  </BaseModal>

  <!-- Delete Confirmation Modal -->
  <ConfirmationModal :show="showDeleteModal" title="Delete Transaction Type" type="danger" confirm-button-text="Delete"
    loading-text="Deleting..." :loading="transactionTypeStore.loading" @close="closeDeleteModal"
    @confirm="handleDelete">
    <template #message>
      Are you sure you want to delete <strong>{{ deletingItem?.name }}</strong>? This action cannot be undone.
    </template>
  </ConfirmationModal>

  <!-- Status Change Confirmation Modal -->
  <ConfirmationModal :show="showStatusChangeModal" title="Change Status" type="warning"
    confirm-button-text="Confirm Change" loading-text="Updating..." :loading="transactionTypeStore.loading"
    @close="closeStatusChangeModal" @confirm="confirmStatusChange">
    <template #message>
      Are you sure you want to change the status of
      <strong>{{ statusChangingItem?.name }}</strong>
      to
      <strong>
        <span :class="[
          statusChangingItem?.status === 1 || statusChangingItem?.status === true
            ? 'text-red-600 dark:text-red-400'
            : 'text-green-600 dark:text-green-400'
        ]">
          {{ statusChangingItem?.status === 1 || statusChangingItem?.status === true ? 'Inactive' : 'Active' }}
        </span>
      </strong>?
    </template>
  </ConfirmationModal>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useTransactionTypeStore } from '@/stores/transactionTypeStore';
import BaseModal from '@/components/common/BaseModal.vue';
import Pagination from '@/components/common/Pagination.vue';
import Breadcrumb from '@/components/core/Breadcrumb.vue';
import TableLoadingState from '@/components/core/TableLoadingState.vue';
import SortableTableHeader from '@/components/common/SortableTableHeader.vue';
import ConfirmationModal from '@/components/common/ConfirmationModal.vue';
import BaseInput from '@/components/common/BaseInput.vue';

// Detail Item Component (for View Modal)
const DetailItem = {
  props: ['label', 'value'],
  template: `
    <div class="space-y-1">
      <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ label }}</dt>
      <dd class="text-sm text-gray-900 dark:text-white">
        <slot name="value">{{ value }}</slot>
      </dd>
    </div>
  `
};

const tableColumns = ref([
  { key: 'name', label: 'Name', sortable: true },
  { key: 'code', label: 'Code', sortable: true },
  { key: 'category', label: 'Category', sortable: true },
  { key: 'order', label: 'Order', sortable: true },
  { key: 'status', label: 'Status', sortable: true, width: '100px' }
]);

// Initialize stores
const transactionTypeStore = useTransactionTypeStore();

// Modal states
const isModalOpen = ref(false);
const showViewModal = ref(false);
const showDeleteModal = ref(false);
const showStatusChangeModal = ref(false);

// UI states
const showAdvanced = ref(false);
const showViewAdvanced = ref(false);
const showColorPicker = ref(false);
const showIconPicker = ref(false);

// Search query with debounce
const searchQuery = ref('');
let searchTimeout = null;

// Form data with all fields
const formData = ref({
  name: '',
  code: '',
  category: '',
  description: '',
  status: 1,
  order: 0,
  color: '#3B82F6',
  icon: 'fas fa-exchange-alt',
  logo: null,
  default_amount: 0,
  requires_approval: 0,
  notification_email: '',
  alert_phone: '',
  max_amount: null,
  min_amount: null,
  frequency_limit: null,
  start_date: '',
  end_date: '',
  notes: ''
});

// Current items
const currentItem = ref(null);
const viewingItem = ref(null);
const deletingItem = ref(null);
const statusChangingItem = ref(null);
const modalMode = ref('create');

// Preset data
const presetColors = ref([
  '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
  '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1'
]);

const presetIcons = ref([
  'fas fa-exchange-alt', 'fas fa-money-bill-wave', 'fas fa-credit-card', 'fas fa-university',
  'fas fa-wallet', 'fas fa-piggy-bank', 'fas fa-chart-line', 'fas fa-chart-bar',
  'fas fa-receipt', 'fas fa-tags', 'fas fa-shopping-cart', 'fas fa-gift',
  'fas fa-utensils', 'fas fa-car', 'fas fa-home', 'fas fa-briefcase'
]);

const statusOptions = ref([
  { value: 1, label: 'Active', icon: 'fas fa-check-circle' },
  { value: 0, label: 'Inactive', icon: 'fas fa-times-circle' }
]);

const approvalOptions = ref([
  { value: 1, label: 'Yes', icon: 'fas fa-check', description: 'Requires approval' },
  { value: 0, label: 'No', icon: 'fas fa-times', description: 'No approval needed' }
]);

const displayedItems = computed(() => {
  return transactionTypeStore.items || [];
});

const calculateRowNumber = (index) => {
  if (transactionTypeStore.pagination?.from && transactionTypeStore.pagination.from > 0) {
    return transactionTypeStore.pagination.from + index;
  }
  return (transactionTypeStore.pagination.current_page - 1) * transactionTypeStore.pagination.per_page + index + 1;
};

const clearValidationError = (fieldName) => {
  if (transactionTypeStore.validationErrors[fieldName]) {
    const updatedErrors = { ...transactionTypeStore.validationErrors };
    delete updatedErrors[fieldName];
    transactionTypeStore.validationErrors = updatedErrors;
  }
};

const clearAllValidationErrors = () => {
  transactionTypeStore.validationErrors = {};
};

// Search with debounce
const handleSearchDebounced = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }

  searchTimeout = setTimeout(async () => {
    await transactionTypeStore.search(searchQuery.value);
  }, 500);
};

const handleSearch = () => {
  handleSearchDebounced();
};

const clearSearch = () => {
  searchQuery.value = '';
  transactionTypeStore.search('');
};

watch(searchQuery, (newValue) => {
  if (newValue === '') {
    if (searchTimeout) {
      clearTimeout(searchTimeout);
    }
    transactionTypeStore.search('');
  }
  handleSearch();
  clearAllValidationErrors();
});

onMounted(async () => {
  await transactionTypeStore.get();
});

// Modal functions
const openCreateModal = () => {
  resetForm();
  modalMode.value = 'create';
  isModalOpen.value = true;
  showAdvanced.value = false;
  clearAllValidationErrors();
};

const viewTransactionType = (item) => {
  viewingItem.value = item;
  showViewModal.value = true;
  showViewAdvanced.value = false;
};

const editTransactionType = (item) => {
  currentItem.value = item;
  modalMode.value = 'edit';

  // Populate form with item data
  formData.value = {
    name: item.name || '',
    code: item.code || '',
    category: item.category || '',
    description: item.description || '',
    status: Number(item.status) || 1,
    order: item.order || 0,
    color: item.color || '#3B82F6',
    icon: item.icon || 'fas fa-exchange-alt',
    logo: item.logo || null,
    default_amount: item.default_amount || 0,
    requires_approval: item.requires_approval || 0,
    notification_email: item.notification_email || '',
    alert_phone: item.alert_phone || '',
    max_amount: item.max_amount || null,
    min_amount: item.min_amount || null,
    frequency_limit: item.frequency_limit || null,
    start_date: item.start_date || '',
    end_date: item.end_date || '',
    notes: item.notes || ''
  };

  isModalOpen.value = true;
  showAdvanced.value = false;
  clearAllValidationErrors();
};

const confirmDelete = (item) => {
  deletingItem.value = item;
  showDeleteModal.value = true;
};

const toggleStatus = (item) => {
  statusChangingItem.value = item;
  showStatusChangeModal.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  resetForm();
  clearAllValidationErrors();
};

const closeViewModal = () => {
  showViewModal.value = false;
  viewingItem.value = null;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  deletingItem.value = null;
};

const closeStatusChangeModal = () => {
  showStatusChangeModal.value = false;
  statusChangingItem.value = null;
};

const resetForm = () => {
  formData.value = {
    name: '',
    code: '',
    category: '',
    description: '',
    status: 1,
    order: 0,
    color: '#3B82F6',
    icon: 'fas fa-exchange-alt',
    logo: null,
    default_amount: 0,
    requires_approval: 0,
    notification_email: '',
    alert_phone: '',
    max_amount: null,
    min_amount: null,
    frequency_limit: null,
    start_date: '',
    end_date: '',
    notes: ''
  };
  currentItem.value = null;
};

// CRUD operations
const handleSubmit = async () => {
  clearAllValidationErrors();

  const formDataToSend = new FormData();

  // Append all form data
  for (const key in formData.value) {
    const value = formData.value[key];

    if (value === null || value === undefined || value === '') {
      continue;
    }

    // Handle File objects
    if (value instanceof File) {
      formDataToSend.append(key, value);
    } else {
      formDataToSend.append(key, value);
    }
  }

  let result;
  if (modalMode.value === 'create') {
    result = await transactionTypeStore.create(formDataToSend);
  } else {
    formDataToSend.append('_method', 'PUT');
    result = await transactionTypeStore.update(currentItem.value.id, formDataToSend);
  }

  if (result && result.success) {
    closeModal();
    await transactionTypeStore.get();
  }
};

const handleDelete = async () => {
  if (deletingItem.value) {
    const result = await transactionTypeStore.deleteItem(deletingItem.value.id);
    if (result.success) {
      closeDeleteModal();
      await transactionTypeStore.get();
    }
  }
};

const confirmStatusChange = async () => {
  if (statusChangingItem.value) {
    try {
      const field = 'status';
      await transactionTypeStore.toggleStatus(statusChangingItem.value, field);
      closeStatusChangeModal();
      await transactionTypeStore.get();
    } catch (error) {
      console.error("Error toggling status:", error);
    }
  }
};

// File upload handlers
const handleLogoUpload = (files) => {
  if (files && files.length > 0) {
    formData.value.logo = files[0];
  }
};

const handleLogoRemove = () => {
  formData.value.logo = null;
};

// Date picker
const openDatePicker = (field) => {
  // Implement date picker logic here
  console.log('Open date picker for', field);
};

// Format helpers
const formatCurrency = (amount) => {
  if (amount === null || amount === undefined) return 'N/A';
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount);
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

// Pagination and sorting
const handlePerPageChange = async (newPerPage) => {
  await transactionTypeStore.changePerPage(newPerPage);
};

const handlePageChange = async (page) => {
  await transactionTypeStore.paginate(page);
};

const sortByColumn = async (column) => {
  const order = transactionTypeStore.sortDirection === 'asc' ? 'desc' : 'asc';
  await transactionTypeStore.sort(column, order);
};
</script>

<style scoped>
input,
select,
textarea {
  transition: border-color 0.3s ease, background-color 0.3s ease;
}
</style>