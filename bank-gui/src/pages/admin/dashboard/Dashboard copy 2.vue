<template>
  <div>

    <div class="mb-4">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Transactions</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your financial transactions</p>
        </div>
        <Breadcrumb :items="breadcrumbItems" />
      </div>
    </div>

    <!-- Main Content -->
    <div class="space-y-6">

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Deposits</p>
              <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">
                {{ formatCurrency(stats.total_deposit) }}
              </p>
            </div>
            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
              <i class="fas fa-arrow-down text-green-600 dark:text-green-400 text-xl"></i>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Withdrawals</p>
              <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">
                {{ formatCurrency(stats.total_withdraw) }}
              </p>
            </div>
            <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
              <i class="fas fa-arrow-up text-red-600 dark:text-red-400 text-xl"></i>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Current Balance</p>
              <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                {{ formatCurrency(stats.balance) }}
              </p>
            </div>
            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
              <i class="fas fa-wallet text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Transactions</p>
              <p class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1">
                {{ stats.total_transactions }}
              </p>
            </div>
            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
              <i class="fas fa-exchange-alt text-purple-600 dark:text-purple-400 text-xl"></i>
            </div>
          </div>
        </div>
      </div>


      <!-- Transactions Table -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <SortableTableHeader :columns="tableColumns" :sort-by="transactionStore.sortBy"
              :sort-direction="transactionStore.sortDirection" :show-actions-column="false" :show-sl-column="false" />

            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <!-- Loading State -->
              <tr v-if="loading">
                <td colspan="9" class="px-6 py-12 text-center">
                  <div class="flex justify-center items-center">
                    <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mr-3"></i>
                    <span class="text-gray-600 dark:text-gray-400">Loading transactions...</span>
                  </div>
                </td>
              </tr>

              <!-- Empty State -->
              <tr v-else-if="transactions.length === 0">
                <td colspan="9" class="px-6 py-12 text-center">
                  <div class="flex flex-col items-center justify-center">
                    <i class="fas fa-exchange-alt text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No transactions found</h3>
                    <p class="text-gray-600 dark:text-gray-400">Try adjusting your filters or create a new transaction.
                    </p>
                  </div>
                </td>
              </tr>

              <!-- Transactions List -->
              <tr v-for="(transaction, index) in transactionsWithBalance" :key="transaction.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900 dark:text-white">
                    {{ formatDate(transaction.date) }}
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ formatTime(transaction.date) }}
                  </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="[
                    'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                    getTransactionTypeClass(transaction.type)
                  ]">
                    {{ transaction.type }}
                  </span>
                  <div v-if="transaction.bounce_transaction_id"
                    class="text-xs text-orange-500 mt-1 flex items-center gap-1">
                    <i class="fas fa-exclamation-triangle"></i>
                    Has Bounce Transaction
                  </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <div v-if="transaction.account" class="text-sm text-gray-900 dark:text-white">
                    {{ transaction.account.account_name || 'N/A' }}
                  </div>
                  <div v-else class="text-sm text-gray-400">No Account</div>

                  <div v-if="transaction.account" class="text-xs text-gray-500 dark:text-gray-400">
                    {{ transaction.account.account_number || '' }}
                  </div>
                  <div v-if="transaction.account?.bank_name" class="text-xs text-gray-500 dark:text-gray-400">
                    {{ transaction.account.bank_name }}
                  </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <div v-if="transaction.deposit > 0" class="text-sm font-medium text-green-600 dark:text-green-400">
                    +{{ formatCurrency(transaction.deposit) }}
                  </div>
                  <div v-else class="text-sm text-gray-400">-</div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <div v-if="transaction.withdraw > 0" class="text-sm font-medium text-red-600 dark:text-red-400">
                    -{{ formatCurrency(transaction.withdraw) }}
                  </div>
                  <div v-else class="text-sm text-gray-400">-</div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-bold text-gray-900 dark:text-white">
                    {{ formatCurrency(transaction.calculatedBalance) }}
                  </div>
                </td>

                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white max-w-xs break-words">
                    {{ transaction.details || 'No details provided' }}
                  </div>
                  <div v-if="transaction.receive_from" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Received from: {{ transaction.receive_from }}
                  </div>
                  <div v-if="transaction.payment_to" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Paid to: {{ transaction.payment_to }}
                  </div>
                  <div v-if="transaction.bounce_transaction_id" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Bounce ID: {{ transaction.bounce_transaction_id }}
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    By: {{ transaction.user_name || 'System' }}
                  </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="[
                    'px-3 py-1 rounded-full text-xs font-medium',
                    transaction.status
                      ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                      : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                  ]">
                    {{ transaction.status ? 'Active' : 'Inactive' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import Breadcrumb from '@/components/core/Breadcrumb.vue';
import { useTransactionStore } from '@/stores/transactionStore';
import { useAccountStore } from '@/stores/accountStore';
import SortableTableHeader from '@/components/common/SortableTableHeader.vue';

const transactionStore = useTransactionStore();

// States
const loading = ref(false);

const tableColumns = ref([
  { key: 'date', label: 'Date', sortable: false, width: '200px' },
  { key: 'type', label: 'Type', sortable: false, width: '250px' },
  { key: 'account_name', label: 'Account Info', sortable: false, width: '250px' },
  { key: 'deposit', label: 'Deposit', sortable: false, width: '200px' },
  { key: 'withdraw', label: 'Withdraw', sortable: false, width: '200px' },
  { key: 'balance', label: 'Balance', sortable: false, width: '200px' },
  { key: 'details', label: 'Transaction Details', sortable: false, width: '200px' },
  { key: 'status', label: 'Status', sortable: false, width: '200px' },
]);

// Computed properties
const transactions = computed(() => transactionStore.items || []);

const breadcrumbItems = computed(() => [
  { label: 'Dashboard', to: '/dashboard' },
  { label: 'Transactions', active: false }
]);

const stats = computed(() => {
  let totalDeposit = 0;
  let totalWithdraw = 0;

  transactions.value.forEach(t => {
    totalDeposit += parseFloat(t.deposit) || 0;
    totalWithdraw += parseFloat(t.withdraw) || 0;
  });

  const balance = totalDeposit - totalWithdraw;

  return {
    total_deposit: totalDeposit,
    total_withdraw: totalWithdraw,
    balance: balance,
    total_transactions: transactions.value.length
  };
});

const transactionsWithBalance = computed(() => {
  let runningBalance = 0;
  return transactions.value.map(transaction => {
    const deposit = parseFloat(transaction.deposit) || 0;
    const withdraw = parseFloat(transaction.withdraw) || 0;
    runningBalance += (deposit - withdraw);

    return {
      ...transaction,
      calculatedBalance: runningBalance
    };
  });
});

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

const formatTime = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit'
  });
};

const formatCurrency = (amount) => {
  const num = parseFloat(amount) || 0;
  return new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(num);
};

const getTransactionTypeClass = (type) => {
  const classMap = {
    'Deposit': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    'Withdraw': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    'Transfer': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
    'Cheque Bounce': 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
    'Other': 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
  };
  return classMap[type] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};

onMounted(async () => {
  await transactionStore.get();
});

</script>

<style scoped>
.break-words {
  word-break: break-word;
  overflow-wrap: break-word;
}
</style>