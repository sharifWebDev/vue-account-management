<template>
  <div class="mb-4">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Deleted Accounts</h1>
      </div>
      <Breadcrumb />
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 p-3 rounded-lg dark:border dark:border-gray-700">
    <!-- Header with Filters and Search -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-4 gap-4">
      <!-- Left Side: Create Button -->
      <div class="flex flex-col sm:flex-row gap-4">
       
      </div>

      <!-- Right Side: Search -->
      <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
        <div class="relative flex-grow max-w-md">
          <input v-model="searchQuery" @input="handleSearch" type="search"
            placeholder="Search by account name or number..."
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
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <SortableTableHeader
            :columns="tableColumns"
            :sort-by="accountStore.sortBy"
            :sort-direction="accountStore.sortDirection"
            :show-actions-column="true"
            :show-sl-column="true"
            @sort="sortByColumn"
          />
         
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
       
            <!-- Loading State - Inside table body -->
            <TableLoadingState
              v-if="accountStore.loading"
              :is-loading="true"
              :total-items="accountStore.totalItems"
              :item-name="'accounts'"
              loading-text="Loading accounts..."
              loading-subtext="Fetching your account data"
            />
            
            <!-- Empty State -->
            <TableLoadingState
              v-else-if="accountStore.items.length === 0"
              :is-loading="false"
              :isEmpty="true"
              :has-search="!!searchQuery"
              :item-name="'accounts'"
              empty-icon="fas fa-users"
              empty-title="No accounts found"
              :empty-message="searchQuery ? 'No accounts match your search criteria.' : 'Get started by creating your first account.'"
              :create-button-text="'Add First Account'"
              @create="openCreateModal"
              @clear-search="clearSearch"
            />


            <tr v-else v-for="(data, index) in displayedItems" :key="data.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ calculateRowNumber(index) }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ data.account_name }}
                </div>
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ data.account_number }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ data.bank_name || 'N/A' }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ data.branch_name || 'N/A' }}
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
                <button @click="confirmRestore(data)" :disabled="accountStore.loading"
                  class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 p-1 rounded bg-green-50 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                  title="Restore">
                  <i class="fas fa-undo"></i>
                </button>
                <button @click="handlePermanentDelete(data)" :disabled="accountStore.loading"
                  class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1 rounded bg-red-50 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors dark:bg-gray-500 disabled:opacity-50 disabled:cursor-not-allowed"
                  title="Permanently Delete">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-if="accountStore.pagination.total > 0" class="mt-6">
      <Pagination v-if="accountStore.totalItems > 0" :total-items="accountStore.totalItems"
        :per-page="accountStore.perPage" :current-page="accountStore.currentPage" :last-page="accountStore.lastPage"
        :is-loading="accountStore.isLoading" @change-page="handlePageChange" @change-per-page="handlePerPageChange" />
    </div>
  </div>
  
  <!-- Permanent Delete Confirmation Modal -->
  <BaseModal :show="showPermanentDeleteModal" title="Permanently Delete Account" maxWidth="lg"
    @close="closePermanentDeleteModal">
    <div class="px-6 py-3">
      <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
          <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300 text-xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Permanently Delete Account</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
          Are you sure you want to permanently delete <strong>{{ permanentDeletingAccount?.account_name }}</strong>?
          This action <span class="font-bold text-red-600">cannot</span> be undone and will remove the account from the
          system completely.
        </p>
      </div>
      <div class="flex justify-center space-x-3">
        <button @click="closePermanentDeleteModal" :disabled="accountStore.loading"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
          Cancel
        </button>
        <button @click="handlePermanentDelete" :disabled="accountStore.loading" :class="[
          'px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors',
          accountStore.loading ? 'bg-red-400 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700'
        ]">
          <span v-if="accountStore.loading">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            Deleting...
          </span>
          <span v-else>Delete Permanently</span>
        </button>
      </div>
    </div>
  </BaseModal>
</template>
<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useAccountStore } from '@/stores/accountStore';
import { useCompanyStore } from '@/stores/companyStore';
import { useBankStore } from '@/stores/bankStore';
import { useBranchStore } from '@/stores/branchStore';
import BaseModal from '@/components/common/BaseModal.vue';
import Pagination from '@/components/common/Pagination.vue';
import Breadcrumb from '@/components/core/Breadcrumb.vue';
import TableLoadingState from '@/components/core/TableLoadingState.vue';
import SortableTableHeader from '@/components/common/SortableTableHeader.vue';

const tableColumns = ref([
  {
    key: 'account_name',
    label: 'Account Name',
    sortable: true,
    width: '200px'
  },
  {
    key: 'account_number',
    label: 'Account Number',
    sortable: true,
    width: '200px'
  },
  {
    key: 'bank_name',
    label: 'Bank Name',
    sortable: true
  },
  {
    key: 'branch_name',
    label: 'Branch Name',
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
const accountStore = useAccountStore();
const companyStore = useCompanyStore();
const bankStore = useBankStore();
const branchStore = useBranchStore();

// Modal states
const isModalOpen = ref(false);
const showViewModal = ref(false);
const showDeleteModal = ref(false);

// Search query with debounce
const searchQuery = ref('');
let searchTimeout = null;

// Form data
const formData = ref({
  company_id: '',
  bank_id: '',
  branch_id: '',
  account_name: '',
  account_number: '',
  cheque_book: '',
  opening_balance: 0,
  status: 1
});

const currentAccount = ref(null);
const viewingAccount = ref(null);
const deletingAccount = ref(null);
const modalMode = ref('create');

const displayedItems = computed(() => {
  return accountStore.items || [];
});

const companies = computed(() => {
  return companyStore.items || [];
});

const banks = computed(() => {
  const items = bankStore.items || [];
  return items.filter(item => item !== undefined && item !== null);
});

const branches = computed(() => {
  const items = branchStore.items || [];
  return items.filter(item => item !== undefined && item !== null);
});

const calculateRowNumber = (index) => {
  if (accountStore.pagination?.from && accountStore.pagination.from > 0) {
    return accountStore.pagination.from + index;
  }
  return (accountStore.pagination.current_page - 1) * accountStore.pagination.per_page + index + 1;
};

const clearValidationError = (fieldName) => {
  if (accountStore.validationErrors[fieldName]) {
    const updatedErrors = { ...accountStore.validationErrors };
    delete updatedErrors[fieldName];
    accountStore.validationErrors = updatedErrors;
  }
};

const clearAllValidationErrors = () => {
  accountStore.validationErrors = {};
};

// Search with debounce
const handleSearch = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }

  searchTimeout = setTimeout(async () => {
    await accountStore.search(searchQuery.value);
  }, 500);
};

const clearSearch = () => {
  searchQuery.value = '';
  accountStore.search('');
};

watch(searchQuery, (newValue) => {
  if (newValue === '') {
    if (searchTimeout) {
      clearTimeout(searchTimeout);
    }
    accountStore.search('');
  }
  handleSearch();
  clearAllValidationErrors();
});

onMounted(async () => {
  if (accountStore.items.length === 0) {
    await accountStore.get();
  } else {
    console.log('Data already loaded.');
  }
  loadRelatedData();
});

const loadRelatedData = () => {
  companyStore.getAll();
  bankStore.getAll();
  branchStore.getAll();
};

// Modal functions
const openCreateModal = () => {
  resetForm();
  modalMode.value = 'create';
  isModalOpen.value = true;
  clearAllValidationErrors();
};

const editAccount = (account) => {
  currentAccount.value = account;
  modalMode.value = 'edit';
  formData.value = {
    company_id: account.company_id || '',
    bank_id: account.bank_id || '',
    branch_id: account.branch_id || '',
    account_name: account.account_name,
    account_number: account.account_number,
    cheque_book: account.cheque_book || '',
    opening_balance: account.opening_balance || 0,
    status: Number(account.status)
  };
  isModalOpen.value = true;
  clearAllValidationErrors();
};

const viewAccount = (account) => {
  viewingAccount.value = account;
  showViewModal.value = true;
};

const confirmDelete = (account) => {
  deletingAccount.value = account;
  showDeleteModal.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  resetForm();
  clearAllValidationErrors();
};

const closeViewModal = () => {
  showViewModal.value = false;
  viewingAccount.value = null;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  deletingAccount.value = null;
};

const resetForm = () => {
  formData.value = {
    company_id: '',
    bank_id: '',
    branch_id: '',
    account_name: '',
    account_number: '',
    cheque_book: '',
    opening_balance: 0,
    status: 1
  };
  currentAccount.value = null;
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
    result = await accountStore.create(formDataToSend);
  } else {
    formDataToSend.append('_method', 'PUT');
    result = await accountStore.update(currentAccount.value.id, formDataToSend);
  }

  if (result && result.success) {
    closeModal();
    await accountStore.get();
  }
};

const handlePermanentDelete = async () => {
  if (!permanentDeletingAccount.value) return;

  try {
    const result = await accountStore.forceDelete(permanentDeletingAccount.value.id);
    if (result && result.success) {
      closePermanentDeleteModal();
      await loadTrashedAccounts();
    }
  } catch (error) {
    console.error('Error permanently deleting account:', error);
  }
};

// Pagination and sorting - FIXED: These functions now correctly handle pagination events
const handlePerPageChange = async (newPerPage) => {
  await accountStore.changePerPage(newPerPage);
};

const handlePageChange = async (page) => {
  await accountStore.paginate(page);
};

const sortByColumn = async (column) => {
  const order = accountStore.sortDirection === 'asc' ? 'desc' : 'asc';
  await accountStore.sort(column, order);
};

</script>
<style scoped>
input,
select,
textarea {
  transition: border-color 0.3s ease, background-color 0.3s ease;
}
</style>