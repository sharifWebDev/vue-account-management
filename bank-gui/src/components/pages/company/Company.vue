<template>
  <div class="mb-4">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Companies</h1>
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
          <input v-model="searchQuery" @input="handleSearch" type="search" placeholder="Search by company name..."
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white" />
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
          <SortableTableHeader :columns="tableColumns" :sort-by="companyStore.sortBy"
            :sort-direction="companyStore.sortDirection" :show-actions-column="true" :show-sl-column="true"
            @sort="sortByColumn" />

          <!-- Loading State - Inside table body -->
          <TableLoadingState v-if="companyStore.loading" :is-loading="true" :total-items="companyStore.totalItems"
            :item-name="'companies'" loading-text="Loading companies..." loading-subtext="Fetching your company data" />

          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

            <!-- Empty State -->
            <TableLoadingState v-if="companyStore.items.length === 0" :is-loading="false" :isEmpty="true"
              :has-search="!!searchQuery" :item-name="'companies'" empty-icon="fas fa-building"
              empty-title="No companies found"
              :empty-message="searchQuery ? 'No companies match your search criteria.' : 'Get started by creating your first company.'"
              :create-button-text="'Add First Company'" @create="openCreateModal" @clear-search="clearSearch" />

            <tr v-else v-for="(data, index) in displayedItems" :key="data.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ calculateRowNumber(index) }}
              </td>
              <td class="px-6 py-3">
                <div class="flex items-center space-x-3">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img v-if="data.logo" :src="data.logo" :alt="data.company_name"
                      class="h-10 w-10 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
                    <div v-else
                      class="h-10 w-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                      <i class="fas fa-building text-gray-400"></i>
                    </div>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                      {{ data.company_name }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                      {{ data.tatal_accounts }} Accounts
                    </div>
                  </div>
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
                <button @click="viewCompany(data)"
                  class="text-cyan-600 hover:text-cyan-900 dark:text-cyan-400 dark:hover:text-cyan-300 p-1 rounded bg-blue-50 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors  dark:bg-gray-500 "
                  title="View">
                  <i class="fas fa-eye"></i>
                </button>
                <button @click="editCompany(data)"
                  class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 p-1 rounded bg-yellow-50 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors dark:bg-gray-500 "
                  title=" Edit">
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

    <div v-if="companyStore.pagination.total > 0" class="mt-6">
      <Pagination v-if="companyStore.totalItems > 0" :total-items="companyStore.totalItems"
        :per-page="companyStore.perPage" :current-page="companyStore.currentPage" :last-page="companyStore.lastPage"
        :is-loading="companyStore.isLoading" @change-page="handlePageChange" @change-per-page="handlePerPageChange" />
    </div>
  </div>

  <!-- Create/Edit Modal -->
  <BaseModal v-if="isModalOpen" :show="isModalOpen" :title="modalMode === 'create' ? 'Create Company' : 'Edit Company'"
    maxWidth="4xl" @close="closeModal">
    <form @submit.prevent="handleSubmit" class="px-6 py-3">
      <div class="grid grid-cols-1 md:grid-cols-1 2xl:grid-cols-1 gap-4">
        <!-- Company Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name *</label>
          <input v-model="formData.company_name" type="text" placeholder="Enter company name"
            @change="clearValidationError('company_name')"
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': companyStore.validationErrors.company_name }">
          <p v-if="companyStore.validationErrors.company_name" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ companyStore.validationErrors.company_name[0] }}
          </p>
        </div>

        <!-- Address -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address *</label>
          <input v-model="formData.address" type="text" placeholder="Enter address"
            @change="clearValidationError('address')"
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': companyStore.validationErrors.address }">
          <p v-if="companyStore.validationErrors.address" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ companyStore.validationErrors.address[0] }}
          </p>
        </div>

        <!-- Phone -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
          <input v-model="formData.phone" type="text" placeholder="Enter phone number"
            @change="clearValidationError('phone')"
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': companyStore.validationErrors.phone }">
          <p v-if="companyStore.validationErrors.phone" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ companyStore.validationErrors.phone[0] }}
          </p>
        </div>

        <!-- Mobile -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mobile</label>
          <input v-model="formData.mobile" type="text" placeholder="Enter mobile number"
            @change="clearValidationError('mobile')"
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': companyStore.validationErrors.mobile }">
          <p v-if="companyStore.validationErrors.mobile" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ companyStore.validationErrors.mobile[0] }}
          </p>
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
          <input v-model="formData.email" type="email" placeholder="Enter email address"
            @change="clearValidationError('email')"
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': companyStore.validationErrors.email }">
          <p v-if="companyStore.validationErrors.email" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ companyStore.validationErrors.email[0] }}
          </p>
        </div>

        <!-- Website -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website</label>
          <input v-model="formData.website" type="url" placeholder="Enter website URL"
            @change="clearValidationError('website')"
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': companyStore.validationErrors.website }">
          <p v-if="companyStore.validationErrors.website" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ companyStore.validationErrors.website[0] }}
          </p>
        </div>

        <!-- Logo -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo</label>
          <div class="flex items-center space-x-4">
            <div v-if="previewImage" class="relative">
              <img :src="previewImage" alt="Logo Preview"
                class="h-24 w-24 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
              <button @click="removeImage" type="button"
                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                <i class="fas fa-times text-xs"></i>
              </button>
            </div>
            <div v-else-if="currentCompany?.logo" class="relative">
              <img :src="currentCompany.logo" :alt="currentCompany.company_name"
                class="h-24 w-24 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
              <button @click="removeImage" type="button"
                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                <i class="fas fa-times text-xs"></i>
              </button>
            </div>
            <div>
              <label for="company-logo"
                class="cursor-pointer bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors inline-flex items-center">
                <i class="fas fa-upload mr-2"></i>
                {{ currentCompany?.logo || previewImage ? 'Change Logo' : 'Upload Logo' }}
              </label>
              <input id="company-logo" type="file" accept="image/*" @change="handleImageUpload" class="hidden">
            </div>
          </div>
          <p v-if="companyStore.validationErrors.logo" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ companyStore.validationErrors.logo[0] }}
          </p>
        </div>

        <!-- Status -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
          <div class="flex items-center space-x-6">
            <label class="inline-flex items-center cursor-pointer">
              <input v-model="formData.status" type="radio" :value="1" @change="clearValidationError('status')"
                class="h-4 w-4 text-gray-600 focus:ring-green-600 border-green-300 dark:border-green-600 dark:bg-green-700 accent-green-600">
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
            </label>
            <label class="inline-flex items-center cursor-pointer">
              <input v-model="formData.status" type="radio" :value="0" @change="clearValidationError('status')"
                class="h-4 w-4 text-red-600 focus:ring-red-600 border-red-300 dark:border-red-600 dark:bg-red-700 accent-red-600">
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Inactive</span>
            </label>
          </div>
        </div>

        <!-- Global Form Errors -->
        <div v-if="Object.keys(companyStore.validationErrors).length > 0"
          class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg col-span-1 md:col-span-2 2xl:col-span-3">
          <p class="text-sm text-red-600 dark:text-red-400 font-medium mb-1">Please fix the following errors:</p>
          <ul class="text-xs text-red-500 dark:text-red-400 space-y-1">
            <li v-for="(errors, field) in companyStore.validationErrors" :key="field">
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
          <button type="submit" :disabled="companyStore.loading" :class="[
            'px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors',
            companyStore.loading ? 'bg-gray-400 cursor-not-allowed' : 'bg-gray-600 hover:bg-gray-700'
          ]">
            <span v-if="companyStore.loading">
              <i class="fas fa-spinner fa-spin mr-2"></i>
              {{ modalMode === 'create' ? 'Creating...' : 'Updating...' }}
            </span>
            <span v-else>{{ modalMode === 'create' ? 'Create Company' : 'Update Company' }}</span>
          </button>
        </div>
      </div>
    </form>
  </BaseModal>

  <!-- View Modal -->
  <BaseModal v-if="viewingCompany" :show="showViewModal" maxWidth="xl" title="Company Details" @close="closeViewModal">
    <div class="px-6 py-3">
      <div class="text-center mb-6">
        <div v-if="viewingCompany.logo" class="mb-4">
          <img :src="viewingCompany.logo" :alt="viewingCompany.company_name"
            class="h-32 w-32 rounded-lg mx-auto object-cover border border-gray-200 dark:border-gray-700">
        </div>
        <div v-else
          class="h-32 w-32 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-building text-gray-400 text-3xl"></i>
        </div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ viewingCompany.company_name }}</h2>
        <p class="text-gray-600 dark:text-gray-400">{{ viewingCompany.email || 'No email provided' }}</p>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">ID</label>
          <p class="text-sm text-gray-900 dark:text-white">{{ viewingCompany.id }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</label>
          <div class="mt-1">
            <span :class="[
              'px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
              viewingCompany.status === 1 || viewingCompany.status === true
                ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'
                : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'
            ]">
              {{ viewingCompany.status === 1 || viewingCompany.status === true ? 'Active' : 'Inactive' }}
            </span>
          </div>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Address</label>
          <p class="text-sm text-gray-900 dark:text-white">{{ viewingCompany.address || 'N/A' }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Phone</label>
          <p class="text-sm text-gray-900 dark:text-white">{{ viewingCompany.phone || 'N/A' }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mobile</label>
          <p class="text-sm text-gray-900 dark:text-white">{{ viewingCompany.mobile || 'N/A' }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</label>
          <p class="text-sm text-gray-900 dark:text-white">{{ viewingCompany.email || 'N/A' }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Website</label>
          <p class="text-sm text-gray-900 dark:text-white">{{ viewingCompany.website || 'N/A' }}</p>
        </div>
      </div>
    </div>

    <!-- Actions -->
    <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
      <button type="button" @click="closeViewModal"
        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-1 focus:ring-gray-500">
        Close
      </button>
      <button @click="editCompany(viewingCompany)"
        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition-colors focus:outline-none focus:ring-1 focus:ring-gray-500">
        Edit Company
      </button>
    </div>
  </BaseModal>

  <!-- Delete Confirmation Modal -->
  <BaseModal :show="showDeleteModal" title="Delete Company" maxWidth="lg" :showClose="false" @close="closeDeleteModal">
    <div class="px-6 py-3">
      <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
          <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300 text-xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Delete Company</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
          Are you sure you want to delete <strong>{{ deletingCompany?.company_name }}</strong>? This action cannot be
          undone.
        </p>
      </div>
      <div class="flex justify-center space-x-3">
        <button @click="closeDeleteModal"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
          Cancel
        </button>
        <button @click="handleDelete" :disabled="companyStore.loading" :class="[
          'px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors',
          companyStore.loading ? 'bg-red-400 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700'
        ]">
          <span v-if="companyStore.loading">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            Deleting...
          </span>
          <span v-else>Delete Company</span>
        </button>
      </div>
    </div>
  </BaseModal>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useCompanyStore } from '@/stores/companyStore';
import BaseModal from '@/components/common/BaseModal.vue';
import Pagination from '@/components/common/Pagination.vue';
import Breadcrumb from '@/components/core/Breadcrumb.vue';
import TableLoadingState from '@/components/core/TableLoadingState.vue';
import SortableTableHeader from '@/components/common/SortableTableHeader.vue';

const tableColumns = ref([
  {
    key: 'company_name',
    label: 'Company Name',
    sortable: true,
    width: '350px'
  },
  {
    key: 'address',
    label: 'Address',
    sortable: false,
    width: '200px'
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

// Initialize store
const companyStore = useCompanyStore();

// Modal states
const isModalOpen = ref(false);
const showViewModal = ref(false);
const showDeleteModal = ref(false);

// Search query with debounce
const searchQuery = ref('');
let searchTimeout = null;

// Form data
const formData = ref({
  company_name: '',
  address: '',
  phone: '',
  mobile: '',
  email: '',
  website: '',
  status: 1,
  logo: null
});

const currentCompany = ref(null);
const viewingCompany = ref(null);
const deletingCompany = ref(null);
const modalMode = ref('create');
const previewImage = ref(null);

const displayedItems = computed(() => {
  return companyStore.items || [];
});

const calculateRowNumber = (index) => {
  if (companyStore.pagination?.from && companyStore.pagination.from > 0) {
    return companyStore.pagination.from + index;
  }
  return (companyStore.pagination.current_page - 1) * companyStore.pagination.per_page + index + 1;
};

const clearValidationError = (fieldName) => {
  if (companyStore.validationErrors[fieldName]) {
    const updatedErrors = { ...companyStore.validationErrors };
    delete updatedErrors[fieldName];
    companyStore.validationErrors = updatedErrors;
  }
};

const clearAllValidationErrors = () => {
  companyStore.validationErrors = {};
};

// Search with debounce
const handleSearch = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }

  searchTimeout = setTimeout(async () => {
    await companyStore.search(searchQuery.value);
  }, 500);
};

const clearSearch = () => {
  searchQuery.value = '';
  companyStore.search('');
};

watch(searchQuery, (newValue) => {
  if (newValue === '') {
    if (searchTimeout) {
      clearTimeout(searchTimeout);
    }
    companyStore.search('');
  }
  handleSearch();
  clearAllValidationErrors();
});

// Lifecycle
onMounted(async () => {
  await companyStore.get();
});

// Modal functions
const openCreateModal = () => {
  resetForm();
  modalMode.value = 'create';
  isModalOpen.value = true;
  clearAllValidationErrors();
};

const editCompany = (company) => {
  currentCompany.value = company;
  modalMode.value = 'edit';
  formData.value = {
    company_name: company.company_name,
    address: company.address,
    phone: company.phone || '',
    mobile: company.mobile || '',
    email: company.email || '',
    website: company.website || '',
    status: Number(company.status),
    logo: null
  };
  previewImage.value = company.logo || null;
  isModalOpen.value = true;
  clearAllValidationErrors();
};

const viewCompany = (company) => {
  viewingCompany.value = company;
  showViewModal.value = true;
};

const confirmDelete = (company) => {
  deletingCompany.value = company;
  showDeleteModal.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  resetForm();
  clearAllValidationErrors();
};

const closeViewModal = () => {
  showViewModal.value = false;
  viewingCompany.value = null;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  deletingCompany.value = null;
};

const resetForm = () => {
  formData.value = {
    company_name: '',
    address: '',
    phone: '',
    mobile: '',
    email: '',
    website: '',
    status: 1,
    logo: null
  };
  currentCompany.value = null;
  previewImage.value = null;
};

// Image upload
const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  formData.value.logo = file;
  previewImage.value = URL.createObjectURL(file);
};

const removeImage = () => {
  formData.value.logo = null;
  previewImage.value = null;
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
    result = await companyStore.create(formDataToSend);
  } else {
    formDataToSend.append('_method', 'PUT');
    result = await companyStore.update(currentCompany.value.id, formDataToSend);
  }

  if (result && result.success) {
    closeModal();
    await companyStore.get();
  }
};

const handleDelete = async () => {
  if (deletingCompany.value) {
    const result = await companyStore.deleteItem(deletingCompany.value.id);
    if (result.success) {
      closeDeleteModal();
      await companyStore.get();
    }
  }
};

const toggleStatus = async (item, field) => {
  try {
    await companyStore.toggleStatus(item, field);
    await companyStore.get();
  } catch (error) {
    console.error("Error toggling status:", error);
  }
};

// Pagination and sorting
const handlePerPageChange = async (newPerPage) => {
  await companyStore.changePerPage(newPerPage);
};

const handlePageChange = async (page) => {
  await companyStore.paginate(page);
};

const sortByColumn = async (column) => {
  const order = companyStore.sortDirection === 'asc' ? 'desc' : 'asc';
  await companyStore.sort(column, order);
};
</script>

<style scoped>
input,
select,
textarea {
  transition: border-color 0.3s ease, background-color 0.3s ease;
}
</style>