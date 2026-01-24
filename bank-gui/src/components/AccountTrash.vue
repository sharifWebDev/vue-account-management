<template>
  <div class="mb-4">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Deleted Accounts</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage permanently deleted accounts or restore them</p>
      </div>
      <Breadcrumb />
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 p-3 rounded-lg dark:border dark:border-gray-700">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-4 gap-4">
      <div>
        <h6>Deleted Accounts</h6>
      </div>
      <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
        <div class="relative flex-grow max-w-md">
          <input v-model="searchQuery" @input="handleSearch" type="search" placeholder="Search deleted accounts..."
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
          <SortableTableHeader :columns="tableColumns" :sort-by="accountStore.sortBy"
            :sort-direction="accountStore.sortDirection" :show-actions-column="true" :show-sl-column="true"
            @sort="sortByColumn" />

          <!-- Loading State - Inside table body -->
          <TableLoadingState v-if="accountStore.loading" :is-loading="true" :total-items="accountStore.totalItems"
            :item-name="'accounts'" loading-text="Loading deleted accounts..."
            loading-subtext="Fetching your deleted account data" />


          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Loading State - Inside table body -->

            <!-- Empty State -->
            <TableLoadingState v-if="accountStore.items.length === 0" :is-loading="false" :isEmpty="true"
              :has-search="!!searchQuery" :item-name="'deleted accounts'" empty-icon="fas fa-trash"
              empty-title="No deleted accounts"
              :empty-message="searchQuery ? 'No deleted accounts match your search criteria.' : 'There are no deleted accounts to display.'"
              @clear-search="clearSearch" />

            <tr v-else v-for="(data, index) in displayedItems" :key="data.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ calculateRowNumber(index) }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ data.account_name }}
                </div>
                <div v-if="data.deleted_at" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                  Deleted: {{ formatDate(data.deleted_at) }}
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
                <span
                  class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                  {{ data.deleted_at ? 'Deleted' : 'Active' }}
                </span>
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ formatDate(data.deleted_at) }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                <button @click="confirmRestore(data)" :disabled="accountStore.loading"
                  class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 p-1 rounded bg-green-50 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                  title="Restore">
                  <i class="fas fa-undo"></i>
                </button>
                <button @click="confirmPermanentDelete(data)" :disabled="accountStore.loading"
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

    <div v-if="accountStore.totalItems > 0" class="mt-6">
      <Pagination v-if="accountStore.totalItems > 0" :total-items="accountStore.totalItems"
        :per-page="accountStore.perPage" :current-page="accountStore.currentPage" :last-page="accountStore.lastPage"
        :is-loading="accountStore.isLoading" @change-page="handlePageChange" @change-per-page="handlePerPageChange" />
    </div>
  </div>

  <!-- Restore Confirmation Modal -->
  <BaseModal :show="showRestoreModal" title="Restore Account" maxWidth="lg" @close="closeRestoreModal">
    <div class="px-6 py-3">
      <div class="text-center">
        <div
          class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 mb-4">
          <i class="fas fa-undo text-green-600 dark:text-green-300 text-xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Restore Account</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
          Are you sure you want to restore <strong>{{ restoringAccount?.account_name }}</strong>?
          This will make the account active again in the system.
        </p>
      </div>
      <div class="flex justify-center space-x-3">
        <button @click="closeRestoreModal" :disabled="accountStore.loading"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
          Cancel
        </button>
        <button @click="handleRestore" :disabled="accountStore.loading" :class="[
          'px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors',
          accountStore.loading ? 'bg-green-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'
        ]">
          <span v-if="accountStore.loading">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            Restoring...
          </span>
          <span v-else>Restore Account</span>
        </button>
      </div>
    </div>
  </BaseModal>

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
  },
  {
    key: 'deleted_at',
    label: 'Deleted At',
    sortable: true,
    width: '200px'
  }
]);

// Initialize stores
const accountStore = useAccountStore();
const companyStore = useCompanyStore();
const bankStore = useBankStore();
const branchStore = useBranchStore();

// Modal states
const showRestoreModal = ref(false);
const showPermanentDeleteModal = ref(false);

// Account states
const restoringAccount = ref(null);
const permanentDeletingAccount = ref(null);

// Search query with debounce
const searchQuery = ref('');
let searchTimeout = null;

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
  if (accountStore.currentPage && accountStore.perPage) {
    return (accountStore.currentPage - 1) * accountStore.perPage + index + 1;
  }
  return index + 1;
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};


const handleSearch = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }
  searchTimeout = setTimeout(async () => {
    const searchValue = searchQuery.value || '';
    await accountStore.trashed({ search: searchValue });
  }, 500);
};

const clearSearch = () => {
  searchQuery.value = '';
  loadTrashedAccounts();
};

watch(searchQuery, (newValue) => {
  if (newValue === '') {
    if (searchTimeout) {
      clearTimeout(searchTimeout);
    }
    accountStore.trashed({ search: '' });
  }
  handleSearch();
});

onMounted(async () => {
  await loadTrashedAccounts();
  loadRelatedData();
});

const loadRelatedData = () => {
  companyStore.getAll();
  bankStore.getAll();
  branchStore.getAll();
};

const loadTrashedAccounts = async (search = '') => {
  try {
    await accountStore.trashed(search);
  } catch (error) {
    console.error('Error loading trashed accounts:', error);
  }
};

// Restore functions
const confirmRestore = (account) => {
  restoringAccount.value = account;
  showRestoreModal.value = true;
};

const closeRestoreModal = () => {
  showRestoreModal.value = false;
  restoringAccount.value = null;
};

const handleRestore = async () => {
  if (!restoringAccount.value) return;

  try {
    const result = await accountStore.restore(restoringAccount.value.id);
    if (result && result.success) {
      closeRestoreModal();
      await loadTrashedAccounts();
    }
  } catch (error) {
    console.error('Error restoring account:', error);
  }
};

// Permanent delete functions
const confirmPermanentDelete = (account) => {
  permanentDeletingAccount.value = account;
  showPermanentDeleteModal.value = true;
};

const closePermanentDeleteModal = () => {
  showPermanentDeleteModal.value = false;
  permanentDeletingAccount.value = null;
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

// Pagination and sorting
const handlePerPageChange = async (newPerPage) => {
  await accountStore.changePerPage(newPerPage);
  await loadTrashedAccounts(searchQuery.value);
};

const handlePageChange = async (page) => {
  await accountStore.paginate(page);
  await loadTrashedAccounts(searchQuery.value);
};

const sortByColumn = async (column) => {
  const order = accountStore.sortDirection === 'asc' ? 'desc' : 'asc';
  await accountStore.sort(column, order);
  await loadTrashedAccounts(searchQuery.value);
};
</script>

<style scoped>
input,
select,
textarea {
  transition: border-color 0.3s ease, background-color 0.3s ease;
}
</style>