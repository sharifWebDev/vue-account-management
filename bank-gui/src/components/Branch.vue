<template>
  <div class="mb-4">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Branches</h1>
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
      <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
        <div class="relative flex-grow max-w-md">
          <input v-model="searchQuery" @input="handleSearch" type="search"
            placeholder="Search by branch name..."
            class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-1 focus:ring-gray-500 focus:border-gray-500 outline-none transition-all focus:shadow-sm" />
          <div class="absolute left-3 top-2.5 text-gray-400">
            <i class="fas fa-search text-base"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 relative">
          <SortableTableHeader :columns="tableColumns" :sort-by="branchStore.sortBy"
            :sort-direction="branchStore.sortDirection" :show-actions-column="true" :show-sl-column="true"
            @sort="sortByColumn" />

          <!-- Loading State - Inside table body -->
          <TableLoadingState v-if="branchStore.loading" :is-loading="true" :total-items="branchStore.totalItems"
            :item-name="'branches'" loading-text="Loading branches..." loading-subtext="Fetching your branch data" />

          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

            <!-- Empty State -->
            <TableLoadingState v-if="branchStore.items.length === 0" :is-loading="false" :isEmpty="true"
              :has-search="!!searchQuery" :item-name="'branches'" empty-icon="fas fa-code-branch"
              empty-title="No branches found"
              :empty-message="searchQuery ? 'No branches match your search criteria.' : 'Get started by creating your first branch.'"
              :create-button-text="'Add First Branch'" @create="openCreateModal" @clear-search="clearSearch" />

            <tr v-else v-for="(data, index) in displayedItems" :key="data.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ calculateRowNumber(index) }}
              </td>
              <td class="px-6 py-3">
                <div class="flex items-center space-x-3">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                      <i class="fas fa-code-branch text-gray-400"></i>
                    </div>
                  </div>
                  <div class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ data.branch_name }}
                  </div>
                </div>
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ data.bank_name || 'N/A' }}
                </div>
              </td>
              <td class="px-6 py-3">
                <div class="text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">
                  {{ data.address || 'N/A' }}
                </div>
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ data.phone || 'N/A' }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ data.email || 'N/A' }}
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
                <button @click="toggleStatus(data, 'status')"
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
                <button @click="viewBranch(data)"
                  class="text-cyan-600 hover:text-cyan-900 dark:text-cyan-400 dark:hover:text-cyan-300 p-1 rounded bg-blue-50 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors  dark:bg-gray-500 "
                  title="View">
                  <i class="fas fa-eye"></i>
                </button>
                <button @click="editBranch(data)"
                  class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 p-1 rounded bg-yellow-50 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors dark:bg-gray-500 "
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

    <div v-if="branchStore.pagination.total > 0" class="mt-6">
      <Pagination v-if="branchStore.totalItems > 0" :total-items="branchStore.totalItems"
        :per-page="branchStore.perPage" :current-page="branchStore.currentPage" :last-page="branchStore.lastPage"
        :is-loading="branchStore.isLoading" @change-page="handlePageChange" @change-per-page="handlePerPageChange" />
    </div>
  </div>

  <!-- Create/Edit Modal -->
  <BaseModal v-if="isModalOpen" :show="isModalOpen" :title="modalMode === 'create' ? 'Create Branch' : 'Edit Branch'"
    maxWidth="4xl" @close="closeModal">
    <form @submit.prevent="handleSubmit" class="px-6 py-3">
      <div class="grid grid-cols-1 md:grid-cols-1 2xl:grid-cols-1 gap-4">
        <!-- Bank Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bank Name *</label>
          <select v-model="formData.bank_id" @change="clearValidationError('bank_id')"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': branchStore.validationErrors.bank_id }">
            <option value="">--Select Bank--</option>
            <option v-for="bank in banks" :key="bank.id" :value="bank.id">
              {{ bank.bank_name }}
            </option>
          </select>
          <p v-if="branchStore.validationErrors.bank_id" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ branchStore.validationErrors.bank_id[0] }}
          </p>
        </div>

        <!-- Branch Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Branch Name *</label>
          <input v-model="formData.branch_name" type="text" placeholder="Enter branch name"
            @change="clearValidationError('branch_name')"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': branchStore.validationErrors.branch_name }">
          <p v-if="branchStore.validationErrors.branch_name" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ branchStore.validationErrors.branch_name[0] }}
          </p>
        </div>

        <!-- Address -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address *</label>
          <input v-model="formData.address" type="text" placeholder="Enter address"
            @change="clearValidationError('address')"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': branchStore.validationErrors.address }">
          <p v-if="branchStore.validationErrors.address" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ branchStore.validationErrors.address[0] }}
          </p>
        </div>

        <!-- Phone -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone *</label>
          <input v-model="formData.phone" type="text" placeholder="Enter phone number"
            @change="clearValidationError('phone')"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': branchStore.validationErrors.phone }">
          <p v-if="branchStore.validationErrors.phone" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ branchStore.validationErrors.phone[0] }}
          </p>
        </div>

        <!-- Mobile -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mobile *</label>
          <input v-model="formData.mobile" type="text" placeholder="Enter mobile number"
            @change="clearValidationError('mobile')"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': branchStore.validationErrors.mobile }">
          <p v-if="branchStore.validationErrors.mobile" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ branchStore.validationErrors.mobile[0] }}
          </p>
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
          <input v-model="formData.email" type="email" placeholder="Enter email address"
            @change="clearValidationError('email')"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': branchStore.validationErrors.email }">
          <p v-if="branchStore.validationErrors.email" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ branchStore.validationErrors.email[0] }}
          </p>
        </div>

        <!-- Website -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website</label>
          <input v-model="formData.website" type="url" placeholder="Enter website URL"
            @change="clearValidationError('website')"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': branchStore.validationErrors.website }">
          <p v-if="branchStore.validationErrors.website" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ branchStore.validationErrors.website[0] }}
          </p>
        </div>

        <!-- Status -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
          <div class="flex items-center space-x-6">
            <label class="inline-flex items-center cursor-pointer">
              <input v-model="formData.status" type="radio" :value="1" @change="clearValidationError('status')"
                class="h-4 w-4 text-gray-600 focus:ring-green-600 border-green-300 dark:border-green-600 dark:bg-green-700">
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
            </label>
            <label class="inline-flex items-center cursor-pointer">
              <input v-model="formData.status" type="radio" :value="0" @change="clearValidationError('status')"
                class="h-4 w-4 text-red-600 focus:ring-red-600 border-red-300 dark:border-red-600 dark:bg-red-700">
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Inactive</span>
            </label>
          </div>
        </div>

        <!-- Global Form Errors -->
        <div v-if="Object.keys(branchStore.validationErrors).length > 0"
          class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg col-span-1 md:col-span-2 2xl:col-span-3">
          <p class="text-sm text-red-600 dark:text-red-400 font-medium mb-1">Please fix the following errors:</p>
          <ul class="text-xs text-red-500 dark:text-red-400 space-y-1">
            <li v-for="(errors, field) in branchStore.validationErrors" :key="field">
              • {{ errors[0] }}
            </li>
          </ul>
        </div>

        <!-- Form Actions -->
        <div class="mt-6 flex justify-end space-x-3 col-span-1 md:col-span-2 2xl:col-span-3">
          <button type="button" @click="closeModal"
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            Cancel
          </button>
          <button type="submit" :disabled="branchStore.loading" :class="[
            'px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors',
            branchStore.loading ? 'bg-gray-400 cursor-not-allowed' : 'bg-gray-600 hover:bg-gray-700'
          ]">
            <span v-if="branchStore.loading">
              <i class="fas fa-spinner fa-spin mr-2"></i>
              {{ modalMode === 'create' ? 'Creating...' : 'Updating...' }}
            </span>
            <span v-else>{{ modalMode === 'create' ? 'Create Branch' : 'Update Branch' }}</span>
          </button>
        </div>
      </div>
    </form>
  </BaseModal>

  <!-- View Modal -->
  <BaseModal v-if="viewingBranch" :show="showViewModal" maxWidth="xl" title="Branch Details" @close="closeViewModal">
    <div class="px-6 py-3">
      <div class="text-center mb-6">
        <div class="h-32 w-32 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-code-branch text-gray-400 text-3xl"></i>
        </div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ viewingBranch.branch_name }}</h2>
        <p class="text-gray-600 dark:text-gray-400">{{ viewingBranch.email || 'No email provided' }}</p>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">ID</label>
          <p class="text-sm text-gray-900 dark:text-white">{{ viewingBranch.id }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Bank Name</label>
          <p class="text-sm text-gray-900 dark:text-white">{{ viewingBranch.bank_name || 'N/A' }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</label>
          <div class="mt-1">
            <span :class="[
              'px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
              viewingBranch.status === 1 || viewingBranch.status === true
                ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'
                : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'
            ]">
              {{ viewingBranch.status === 1 || viewingBranch.status === true ? 'Active' : 'Inactive' }}
            </span>
          </div>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Address</label>
          <p class="text-sm text-gray-900 dark:text-white">{{ viewingBranch.address || 'N/A' }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Phone</label>
          <p class="text-sm text-gray-900 dark:text-white">{{ viewingBranch.phone || 'N/A' }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mobile</label>
          <p class="text-sm text-gray-900 dark:text-white">{{ viewingBranch.mobile || 'N/A' }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</label>
          <p class="text-sm text-gray-900 dark:text-white">{{ viewingBranch.email || 'N/A' }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Website</label>
          <p class="text-sm text-gray-900 dark:text-white">{{ viewingBranch.website || 'N/A' }}</p>
        </div>
      </div>
    </div>

    <!-- Actions -->
    <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
      <button type="button" @click="closeViewModal"
        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-1 focus:ring-gray-500">
        Close
      </button>
      <button @click="editBranch(viewingBranch)"
        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition-colors focus:outline-none focus:ring-1 focus:ring-gray-500">
        Edit Branch
      </button>
    </div>
  </BaseModal>

  <!-- Delete Confirmation Modal -->
  <BaseModal :show="showDeleteModal" title="Delete Branch" maxWidth="lg" :showClose="false" @close="closeDeleteModal">
    <div class="px-6 py-3">
      <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
          <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300 text-xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Delete Branch</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
          Are you sure you want to delete <strong>{{ deletingBranch?.branch_name }}</strong>? This action cannot be
          undone.
        </p>
      </div>
      <div class="flex justify-center space-x-3">
        <button @click="closeDeleteModal"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
          Cancel
        </button>
        <button @click="handleDelete" :disabled="branchStore.loading" :class="[
          'px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors',
          branchStore.loading ? 'bg-red-400 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700'
        ]">
          <span v-if="branchStore.loading">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            Deleting...
          </span>
          <span v-else>Delete Branch</span>
        </button>
      </div>
    </div>
  </BaseModal>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useBranchStore } from '@/stores/branchStore';
import { useBankStore } from '@/stores/bankStore';
import BaseModal from '@/components/common/BaseModal.vue';
import Pagination from '@/components/common/Pagination.vue';
import Breadcrumb from '@/components/core/Breadcrumb.vue';
import TableLoadingState from '@/components/core/TableLoadingState.vue';
import SortableTableHeader from '@/components/common/SortableTableHeader.vue';

const tableColumns = ref([
  {
    key: 'branch_name',
    label: 'Branch Name',
    sortable: true,
    width: '200px'
  },
  {
    key: 'bank_name',
    label: 'Bank',
    sortable: true,
    width: '200px'
  },
  {
    key: 'address',
    label: 'Address',
    sortable: false
  },
  {
    key: 'phone',
    label: 'Phone',
    sortable: true
  },
  {
    key: 'email',
    label: 'Email',
    sortable: true
  },
  {
    key: 'status',
    label: 'Status',
    sortable: true,
    width: '100px'
  }
]);

// Initialize stores
const branchStore = useBranchStore();
const bankStore = useBankStore();

// Modal states
const isModalOpen = ref(false);
const showViewModal = ref(false);
const showDeleteModal = ref(false);

// Search query with debounce
const searchQuery = ref('');
let searchTimeout = null;

// Form data
const formData = ref({
  branch_name: '',
  bank_id: '',
  address: '',
  phone: '',
  mobile: '',
  email: '',
  website: '',
  status: 1
});

const currentBranch = ref(null);
const viewingBranch = ref(null);
const deletingBranch = ref(null);
const modalMode = ref('create');

const displayedItems = computed(() => {
  return branchStore.items || [];
});

const banks = computed(() => {
  const items = bankStore.items || [];
  return items.filter(item => item !== undefined && item !== null);
});

const calculateRowNumber = (index) => {
  if (branchStore.pagination?.from && branchStore.pagination.from > 0) {
    return branchStore.pagination.from + index;
  }
  return (branchStore.pagination.current_page - 1) * branchStore.pagination.per_page + index + 1;
};

const clearValidationError = (fieldName) => {
  if (branchStore.validationErrors[fieldName]) {
    const updatedErrors = { ...branchStore.validationErrors };
    delete updatedErrors[fieldName];
    branchStore.validationErrors = updatedErrors;
  }
};

const clearAllValidationErrors = () => {
  branchStore.validationErrors = {};
};

// Search with debounce
const handleSearch = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }

  searchTimeout = setTimeout(async () => {
    await branchStore.search(searchQuery.value);
  }, 500);
};

const clearSearch = () => {
  searchQuery.value = '';
  branchStore.search('');
};

watch(searchQuery, (newValue) => {
  if (newValue === '') {
    if (searchTimeout) {
      clearTimeout(searchTimeout);
    }
    branchStore.search('');
  }
  handleSearch();
  clearAllValidationErrors();
});

onMounted(async () => {
  await branchStore.get();
  loadRelatedData();
});

const loadRelatedData = () => {
  bankStore.getAll();
};

// Modal functions
const openCreateModal = () => {
  resetForm();
  modalMode.value = 'create';
  isModalOpen.value = true;
  clearAllValidationErrors();
};

const editBranch = (branch) => {
  currentBranch.value = branch;
  modalMode.value = 'edit';
  formData.value = {
    branch_name: branch.branch_name,
    bank_id: branch.bank_id || '',
    address: branch.address || '',
    phone: branch.phone || '',
    mobile: branch.mobile || '',
    email: branch.email || '',
    website: branch.website || '',
    status: Number(branch.status)
  };
  isModalOpen.value = true;
  clearAllValidationErrors();
};

const viewBranch = (branch) => {
  viewingBranch.value = branch;
  showViewModal.value = true;
};

const confirmDelete = (branch) => {
  deletingBranch.value = branch;
  showDeleteModal.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  resetForm();
  clearAllValidationErrors();
};

const closeViewModal = () => {
  showViewModal.value = false;
  viewingBranch.value = null;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  deletingBranch.value = null;
};

const resetForm = () => {
  formData.value = {
    branch_name: '',
    bank_id: '',
    address: '',
    phone: '',
    mobile: '',
    email: '',
    website: '',
    status: 1
  };
  currentBranch.value = null;
};

// CRUD operations
const handleSubmit = async () => {
  clearAllValidationErrors();

  const formDataToSend = new FormData();

  for (const key in formData.value) {
    const value = formData.value[key];

    if (value === null || value === undefined || value === '') {
      continue;
    }

    if (key === 'logo' && value instanceof File) {
      formDataToSend.append(key, value);
    } else {
      formDataToSend.append(key, value);
    }
  }

  let result;
  if (modalMode.value === 'create') {
    result = await branchStore.create(formDataToSend);
  } else {
    formDataToSend.append('_method', 'PUT');
    result = await branchStore.update(currentBranch.value.id, formDataToSend);
  }

  if (result && result.success) {
    closeModal();
    await branchStore.get();
  }
};

const handleDelete = async () => {
  if (deletingBranch.value) {
    const result = await branchStore.deleteItem(deletingBranch.value.id);
    if (result.success) {
      closeDeleteModal();
      await branchStore.get();
    }
  }
};

const toggleStatus = async (item, field) => {
  try {
    await branchStore.toggleStatus(item, field);
    await branchStore.get();
  } catch (error) {
    console.error("Error toggling status:", error);
  }
};

// Pagination and sorting
const handlePerPageChange = async (newPerPage) => {
  await branchStore.changePerPage(newPerPage);
};

const handlePageChange = async (page) => {
  await branchStore.paginate(page);
};

const sortByColumn = async (column) => {
  const order = branchStore.sortDirection === 'asc' ? 'desc' : 'asc';
  await branchStore.sort(column, order);
};
</script>

<style scoped>
input,
select,
textarea {
  transition: border-color 0.3s ease, background-color 0.3s ease;
}
</style>