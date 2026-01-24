<template>
  <div class="mb-4">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Transactions</h1>
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

      <!-- Right Side: Filters and Search -->
      <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
        <!-- Date Filters -->
        <div class="flex flex-col sm:flex-row gap-3">
          <div class="relative">
            <input v-model="filterFormDate" type="date" placeholder="From Date"
              class="w-full pl-3 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-1 focus:ring-gray-500 focus:border-gray-500 outline-none transition-all focus:shadow-sm" />
          </div>
          <div class="relative">
            <input v-model="filterToDate" type="date" placeholder="To Date"
              class="w-full pl-3 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-1 focus:ring-gray-500 focus:border-gray-500 outline-none transition-all focus:shadow-sm" />
          </div>
          <button @click="applyDateFilters"
            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors flex items-center space-x-2">
            <i class="fas fa-filter"></i>
            <span>Apply</span>
          </button>
          <button v-if="filterFormDate || filterToDate" @click="clearDateFilters"
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center space-x-2">
            <i class="fas fa-times"></i>
            <span>Clear</span>
          </button>
        </div>

        <!-- Search -->
        <div class="relative flex-grow max-w-md">
          <input v-model="searchQuery" @input="handleSearch" type="search"
            placeholder="Search transactions..."
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
          <SortableTableHeader :columns="tableColumns" :sort-by="transactionStore.sortBy"
            :sort-direction="transactionStore.sortDirection" :show-actions-column="true" :show-sl-column="true"
            @sort="sortByColumn" />

          <!-- Loading State - Inside table body -->
          <TableLoadingState v-if="transactionStore.loading" :is-loading="true" :total-items="transactionStore.totalItems"
            :item-name="'transactions'" loading-text="Loading transactions..." loading-subtext="Fetching your transaction data" />

          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

            <!-- Empty State -->
            <TableLoadingState v-if="transactionStore.items.length === 0" :is-loading="false" :isEmpty="true"
              :has-search="!!searchQuery || !!filterFormDate || !!filterToDate" :item-name="'transactions'" empty-icon="fas fa-exchange-alt"
              empty-title="No transactions found"
              :empty-message="(searchQuery || filterFormDate || filterToDate) ? 'No transactions match your filter criteria.' : 'Get started by creating your first transaction.'"
              :create-button-text="'Add First Transaction'" @create="openCreateModal" @clear-search="clearAllFilters" />

            <tr v-else v-for="(data, index) in displayedItems" :key="data.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ calculateRowNumber(index) }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                {{ formatDate(data.transaction_date || data.date) }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <div class="text-sm font-medium text-green-600 dark:text-green-400" v-if="data.deposit">
                  +{{ formatCurrency(data.deposit) }}
                </div>
                <div class="text-sm text-gray-400" v-else>-</div>
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <div class="text-sm font-medium text-red-600 dark:text-red-400" v-if="data.withdraw">
                  -{{ formatCurrency(data.withdraw) }}
                </div>
                <div class="text-sm text-gray-400" v-else>-</div>
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ formatCurrency(data.balance || calculateBalance(data)) }}
                </div>
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <span :class="[
                  getTransactionTypeClass(data.type),
                  'px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full'
                ]">
                  {{ getTransactionTypeLabel(data.type) }}
                </span>
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 max-w-xs truncate">
                {{ data.details || 'N/A' }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ data.transaction_by || data.created_by || 'N/A' }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                <button @click="viewTransaction(data)"
                  class="text-cyan-600 hover:text-cyan-900 dark:text-cyan-400 dark:hover:text-cyan-300 p-1 rounded bg-blue-50 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors  dark:bg-gray-500 "
                  title="View">
                  <i class="fas fa-eye"></i>
                </button>
                <button @click="editTransaction(data)"
                  class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 p-1 rounded bg-yellow-50 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors dark:bg-gray-500 "
                  title="Edit">
                  <i class="fas fa-edit"></i>
                </button>
                <!-- <button @click="confirmDelete(data)"
                  class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1 rounded bg-red-50 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors dark:bg-gray-500"
                  title="Delete">
                  <i class="fas fa-trash"></i>
                </button> -->
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-if="transactionStore.pagination.total > 0" class="mt-6">
      <Pagination v-if="transactionStore.totalItems > 0" :total-items="transactionStore.totalItems"
        :per-page="transactionStore.perPage" :current-page="transactionStore.currentPage" :last-page="transactionStore.lastPage"
        :is-loading="transactionStore.isLoading" @change-page="handlePageChange" @change-per-page="handlePerPageChange" />
    </div>
  </div>

  <!-- Create/Edit Modal -->
  <BaseModal v-if="isModalOpen" :show="isModalOpen"
    :title="modalMode === 'create' ? 'Create Transaction' : 'Edit Transaction'" maxWidth="4xl" @close="closeModal">
    <form @submit.prevent="handleSubmit" class="px-6 py-3">
      <div class="grid grid-cols-1 md:grid-cols-1 2xl:grid-cols-1 gap-4">
        <!-- Transaction Date -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Transaction Date *</label>
          <input v-model="formData.transaction_date" type="date" @change="clearValidationError('transaction_date')"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': transactionStore.validationErrors.transaction_date }">
          <p v-if="transactionStore.validationErrors.transaction_date"
            class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ transactionStore.validationErrors.transaction_date[0] }}
          </p>
        </div>

        <!-- Transaction Type -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Transaction Type *</label>
          <select v-model="formData.type" @change="handleTypeChange" @input="clearValidationError('type')"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': transactionStore.validationErrors.type }">
            <option value="">--Select Type--</option>
            <option value="deposit">Deposit</option>
            <option value="withdraw">Withdraw</option>
            <option value="transfer">Transfer</option>
            <option value="payment">Payment</option>
            <option value="expense">Expense</option>
            <option value="income">Income</option>
          </select>
          <p v-if="transactionStore.validationErrors.type" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ transactionStore.validationErrors.type[0] }}
          </p>
        </div>

        <!-- Account -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account *</label>
          <select v-model="formData.account_id" @change="clearValidationError('account_id')"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': transactionStore.validationErrors.account_id }">
            <option value="">--Select Account--</option>
            <option v-for="account in accounts" :key="account.id" :value="account.id">
              {{ account.account_name }} ({{ account.account_number }})
            </option>
          </select>
          <p v-if="transactionStore.validationErrors.account_id" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ transactionStore.validationErrors.account_id[0] }}
          </p>
        </div>

        <!-- Amount -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount *</label>
          <input v-model="formData.amount" type="number" step="0.01" placeholder="Enter amount"
            @change="clearValidationError('amount')"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': transactionStore.validationErrors.amount }">
          <p v-if="transactionStore.validationErrors.amount" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ transactionStore.validationErrors.amount[0] }}
          </p>
        </div>

        <!-- Related Party -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Related Party</label>
          <input v-model="formData.related_party" type="text" placeholder="Enter related party"
            @change="clearValidationError('related_party')"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
        </div>

        <!-- Details -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Details</label>
          <textarea v-model="formData.details" rows="3" placeholder="Enter transaction details"
            @change="clearValidationError('details')"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></textarea>
          <p v-if="transactionStore.validationErrors.details" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ transactionStore.validationErrors.details[0] }}
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
        <div v-if="Object.keys(transactionStore.validationErrors).length > 0"
          class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg col-span-1 md:col-span-2 2xl:col-span-3">
          <p class="text-sm text-red-600 dark:text-red-400 font-medium mb-1">Please fix the following errors:</p>
          <ul class="text-xs text-red-500 dark:text-red-400 space-y-1">
            <li v-for="(errors, field) in transactionStore.validationErrors" :key="field">
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
          <button type="submit" :disabled="transactionStore.loading" :class="[
            'px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors',
            transactionStore.loading ? 'bg-gray-400 cursor-not-allowed' : 'bg-gray-600 hover:bg-gray-700'
          ]">
            <span v-if="transactionStore.loading">
              <i class="fas fa-spinner fa-spin mr-2"></i>
              {{ modalMode === 'create' ? 'Creating...' : 'Updating...' }}
            </span>
            <span v-else>{{ modalMode === 'create' ? 'Create Transaction' : 'Update Transaction' }}</span>
          </button>
        </div>
      </div>
    </form>
  </BaseModal>

  <!-- View Modal -->
  <BaseModal v-if="viewingTransaction" :show="showViewModal" maxWidth="xl" title="Transaction Details"
    @close="closeViewModal">
    <div class="px-6 py-3">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</label>
          <p class="text-sm text-gray-900 dark:text-white mt-1">{{ formatDate(viewingTransaction.transaction_date ||
            viewingTransaction.date) }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</label>
          <p class="text-sm text-gray-900 dark:text-white mt-1">{{ getTransactionTypeLabel(viewingTransaction.type) }}
          </p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Account</label>
          <p class="text-sm text-gray-900 dark:text-white mt-1">{{ viewingTransaction.account_name || 'N/A' }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</label>
          <p class="text-sm text-gray-900 dark:text-white mt-1">{{ formatCurrency(viewingTransaction.amount) }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Deposit</label>
          <p class="text-sm text-gray-900 dark:text-white mt-1">{{ viewingTransaction.deposit ?
            formatCurrency(viewingTransaction.deposit) : '-' }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Withdraw</label>
          <p class="text-sm text-gray-900 dark:text-white mt-1">{{ viewingTransaction.withdraw ?
            formatCurrency(viewingTransaction.withdraw) : '-' }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Balance</label>
          <p class="text-sm text-gray-900 dark:text-white mt-1">{{ formatCurrency(viewingTransaction.balance ||
            calculateBalance(viewingTransaction)) }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Related Party</label>
          <p class="text-sm text-gray-900 dark:text-white mt-1">{{ viewingTransaction.related_party || 'N/A' }}</p>
        </div>
        <div class="col-span-2">
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Details</label>
          <p class="text-sm text-gray-900 dark:text-white mt-1">{{ viewingTransaction.details || 'N/A' }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Transaction By</label>
          <p class="text-sm text-gray-900 dark:text-white mt-1">{{ viewingTransaction.transaction_by ||
            viewingTransaction.created_by || 'N/A' }}</p>
        </div>
        <div>
          <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</label>
          <div class="mt-1">
            <span :class="[
              'px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
              viewingTransaction.status === 1 || viewingTransaction.status === true
                ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'
                : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'
            ]">
              {{ viewingTransaction.status === 1 || viewingTransaction.status === true ? 'Active' : 'Inactive'
              }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Actions -->
    <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
      <button type="button" @click="closeViewModal"
        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-1 focus:ring-gray-500">
        Close
      </button>
      <button @click="editTransaction(viewingTransaction)"
        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition-colors focus:outline-none focus:ring-1 focus:ring-gray-500">
        Edit Transaction
      </button>
    </div>
  </BaseModal>

  <!-- Delete Confirmation Modal -->
  <BaseModal :show="showDeleteModal" title="Delete Transaction" maxWidth="lg" :showClose="false"
    @close="closeDeleteModal">
    <div class="px-6 py-3">
      <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
          <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300 text-xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Delete Transaction</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
          Are you sure you want to delete the transaction from <strong>{{ deletingTransaction ?
            formatDate(deletingTransaction.transaction_date || deletingTransaction.date) : '' }}</strong>? This action
          cannot be undone.
        </p>
      </div>
      <div class="flex justify-center space-x-3">
        <button @click="closeDeleteModal"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
          Cancel
        </button>
        <button @click="handleDelete" :disabled="transactionStore.loading" :class="[
          'px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors',
          transactionStore.loading ? 'bg-red-400 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700'
        ]">
          <span v-if="transactionStore.loading">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            Deleting...
          </span>
          <span v-else>Delete Transaction</span>
        </button>
      </div>
    </div>
  </BaseModal>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useTransactionStore } from '@/stores/transactionStore';
import { useAccountStore } from '@/stores/accountStore';
import BaseModal from '@/components/common/BaseModal.vue';
import Pagination from '@/components/common/Pagination.vue';
import Breadcrumb from '@/components/core/Breadcrumb.vue';
import TableLoadingState from '@/components/core/TableLoadingState.vue';
import SortableTableHeader from '@/components/common/SortableTableHeader.vue';

const tableColumns = ref([
  {
    key: 'transaction_date',
    label: 'Date',
    sortable: true,
    width: '120px'
  },
  {
    key: 'deposit',
    label: 'Deposit',
    sortable: true,
    width: '120px'
  },
  {
    key: 'withdraw',
    label: 'Withdraw',
    sortable: true,
    width: '120px'
  },
  {
    key: 'balance',
    label: 'Balance',
    sortable: true,
    width: '120px'
  },
  {
    key: 'type',
    label: 'Type',
    sortable: true,
    width: '100px'
  },
  {
    key: 'details',
    label: 'Details',
    sortable: false
  },
  {
    key: 'transaction_by',
    label: 'Transaction By',
    sortable: true,
    width: '150px'
  }
]);

// Initialize stores
const transactionStore = useTransactionStore();
const accountStore = useAccountStore();

// Modal states
const isModalOpen = ref(false);
const showViewModal = ref(false);
const showDeleteModal = ref(false);

// Search and filter states
const searchQuery = ref('');
const filterFormDate = ref('');
const filterToDate = ref('');
let searchTimeout = null;

// Form data
const formData = ref({
  transaction_date: '',
  type: '',
  account_id: '',
  amount: 0,
  related_party: '',
  details: '',
  status: 1
});

const currentTransaction = ref(null);
const viewingTransaction = ref(null);
const deletingTransaction = ref(null);
const modalMode = ref('create');

const displayedItems = computed(() => {
  return transactionStore.items || [];
});

const accounts = computed(() => {
  const items = accountStore.items || [];
  return items.filter(item => item !== undefined && item !== null);
});

const calculateRowNumber = (index) => {
  if (transactionStore.pagination?.from && transactionStore.pagination.from > 0) {
    return transactionStore.pagination.from + index;
  }
  return (transactionStore.pagination.current_page - 1) * transactionStore.pagination.per_page + index + 1;
};

const clearValidationError = (fieldName) => {
  if (transactionStore.validationErrors[fieldName]) {
    const updatedErrors = { ...transactionStore.validationErrors };
    delete updatedErrors[fieldName];
    transactionStore.validationErrors = updatedErrors;
  }
};

const clearAllValidationErrors = () => {
  transactionStore.validationErrors = {};
};

// Search with debounce
const handleSearch = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }

  searchTimeout = setTimeout(async () => {
    await applyFilters();
  }, 500);
};

const clearSearch = () => {
  searchQuery.value = '';
  applyFilters();
};

const clearDateFilters = () => {
  filterFormDate.value = '';
  filterToDate.value = '';
  applyFilters();
};

const clearAllFilters = () => {
  searchQuery.value = '';
  filterFormDate.value = '';
  filterToDate.value = '';
  applyFilters();
};

const applyDateFilters = () => {
  applyFilters();
};

const applyFilters = async () => {
  const params = {
    search: searchQuery.value
  };

  if (filterFormDate.value) {
    params.from_date = filterFormDate.value;
  }

  if (filterToDate.value) {
    params.to_date = filterToDate.value;
  }

  await transactionStore.get(params);
};

watch(searchQuery, (newValue) => {
  if (newValue === '') {
    if (searchTimeout) {
      clearTimeout(searchTimeout);
    }
    handleSearch();
  } else {
    handleSearch();
  }
  clearAllValidationErrors();
});

watch([filterFormDate, filterToDate], () => {
  applyFilters();
});

onMounted(async () => {
  await transactionStore.get();
  loadRelatedData();
});

const loadRelatedData = () => {
  accountStore.getAll();
};

// Modal functions
const openCreateModal = () => {
  resetForm();
  modalMode.value = 'create';
  isModalOpen.value = true;
  clearAllValidationErrors();
};

const editTransaction = (transaction) => {
  currentTransaction.value = transaction;
  modalMode.value = 'edit';
  formData.value = {
    transaction_date: transaction.transaction_date || transaction.date,
    type: transaction.type,
    account_id: transaction.account_id,
    amount: transaction.amount || transaction.deposit || transaction.withdraw,
    related_party: transaction.related_party || '',
    details: transaction.details,
    status: Number(transaction.status)
  };
  isModalOpen.value = true;
  clearAllValidationErrors();
};

const viewTransaction = (transaction) => {
  viewingTransaction.value = transaction;
  showViewModal.value = true;
};

const confirmDelete = (transaction) => {
  deletingTransaction.value = transaction;
  showDeleteModal.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  resetForm();
  clearAllValidationErrors();
};

const closeViewModal = () => {
  showViewModal.value = false;
  viewingTransaction.value = null;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  deletingTransaction.value = null;
};

const resetForm = () => {
  formData.value = {
    transaction_date: new Date().toISOString().split('T')[0],
    type: '',
    account_id: '',
    amount: 0,
    related_party: '',
    details: '',
    status: 1
  };
  currentTransaction.value = null;
};

const handleTypeChange = () => {
  formData.value.related_party = '';
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

    formDataToSend.append(key, value);
  }

  let result;
  if (modalMode.value === 'create') {
    result = await transactionStore.create(formDataToSend);
  } else {
    formDataToSend.append('_method', 'PUT');
    result = await transactionStore.update(currentTransaction.value.id, formDataToSend);
  }

  if (result && result.success) {
    closeModal();
    await transactionStore.get();
  }
};

const handleDelete = async () => {
  if (deletingTransaction.value) {
    const result = await transactionStore.deleteItem(deletingTransaction.value.id);
    if (result.success) {
      closeDeleteModal();
      await transactionStore.get();
    }
  }
};

// Pagination and sorting
const handlePerPageChange = async (newPerPage) => {
  await transactionStore.changePerPage(newPerPage);
};

const handlePageChange = async (page) => {
  await transactionStore.paginate(page);
};

const sortByColumn = async (column) => {
  const order = transactionStore.sortDirection === 'asc' ? 'desc' : 'asc';
  await transactionStore.sort(column, order);
};

// Helper functions
const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const formatCurrency = (amount) => {
  const num = parseFloat(amount) || 0;
  return new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(num);
};

const calculateBalance = (transaction) => {
  const deposit = parseFloat(transaction.deposit) || 0;
  const withdraw = parseFloat(transaction.withdraw) || 0;
  return deposit - withdraw;
};

const getTransactionTypeLabel = (type) => {
  const typeMap = {
    'deposit': 'Deposit',
    'withdraw': 'Withdraw',
    'transfer': 'Transfer',
    'payment': 'Payment',
    'expense': 'Expense',
    'income': 'Income'
  };
  return typeMap[type] || type || 'N/A';
};

const getTransactionTypeClass = (type) => {
  const classMap = {
    'deposit': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    'income': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    'withdraw': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    'expense': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    'payment': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    'transfer': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'
  };
  return classMap[type] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};
</script>

<style scoped>
input,
select,
textarea {
  transition: border-color 0.3s ease, background-color 0.3s ease;
}
</style>