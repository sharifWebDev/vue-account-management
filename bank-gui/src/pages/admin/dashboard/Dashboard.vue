<template>
  <div class="px-0">
    <!-- Page Header -->
    <div class="mb-6">
      <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
          <h1 class="text-xl font-bold text-gray-800 dark:text-white">Banking Dashboard</h1>
          <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Comprehensive overview of banking operations and
            financial status</p>
        </div>
        <div
          class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 w-full lg:w-auto">
          <!-- Filter Controls -->
          <div class="flex items-center space-x-2 flex-wrap gap-2">
            <div class="relative">
              <input type="date" v-model="filters.startDate"
                class="text-xs px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200">
            </div>
            <span class="text-gray-500 dark:text-gray-400 text-xs">to</span>
            <div class="relative">
              <input type="date" v-model="filters.endDate"
                class="text-xs px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200">
            </div>
            <button @click="applyFilters"
              class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition-colors whitespace-nowrap">
              Apply
            </button>
            <button @click="resetFilters"
              class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs rounded-lg transition-colors whitespace-nowrap">
              Reset
            </button>
          </div>
          <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
            Last updated: {{ formatDate(currentTime) }}
            <button @click="refreshData" class="ml-2 p-1 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
              :disabled="loading">
              <i :class="loading ? 'fas fa-spinner fa-spin' : 'fas fa-sync-alt'" class="text-xs"></i>
            </button>
          </div>
        </div>
      </div>
    </div>


    <TableLoadingState v-if="loading" :is-loading="true" :total-items="false" :item-name="'dashboard'"
      loading-text="Loading dashboard..." loading-subtext="Fetching your dashboard data" />

    <!-- Error State -->
    <div v-else-if="error"
      class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
      <div class="flex items-center">
        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
        <div>
          <p class="text-red-700 dark:text-red-400 font-medium">Failed to load dashboard data</p>
          <p class="text-red-600 dark:text-red-300 text-sm mt-1">{{ error }}</p>
          <button @click="fetchDashboardData"
            class="mt-2 px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded text-sm hover:bg-red-200 dark:hover:bg-red-900/50">
            Try Again
          </button>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div v-else>
      <!-- Stats Row -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 gap-4 mb-6">
        <!-- Total Transactions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:border dark:border-gray-700 p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total Transactions</p>
              <p class="text-xl font-bold text-purple-600 dark:text-purple-400 mt-1">
                {{ stats.total_transactions?.toLocaleString() || '0' }}
              </p>
            </div>
            <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
              <i class="fas fa-exchange-alt text-purple-600 dark:text-purple-400"></i>
            </div>
          </div>
          <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Selected period
          </div>
        </div>

        <!-- Total Balance -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:border dark:border-gray-700 p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total Balance</p>
              <h3 class="text-xl font-bold text-gray-800 dark:text-white mt-1">
                {{ formatCurrency(stats.totalBalance) }}
              </h3>
            </div>
            <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
              <i class="fas fa-money-bill-wave text-green-600 dark:text-green-400"></i>
            </div>
          </div>
          <div class="mt-2">
            <div class="flex items-center text-xs"
              :class="stats.balanceChange >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
              <i :class="stats.balanceChange >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'" class="mr-1 text-xs"></i>
              <span>{{ formatCurrency(Math.abs(stats.balanceChange)) }} this month</span>
            </div>
          </div>
        </div>

        <!-- Total Deposits -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:border dark:border-gray-700 p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total Deposits</p>
              <p class="text-xl font-bold text-green-600 dark:text-green-400 mt-1">
                {{ formatCurrency(stats.totalDeposits) }}
              </p>
            </div>
            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
              <i class="fas fa-arrow-down text-green-600 dark:text-green-400"></i>
            </div>
          </div>
          <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Selected period
          </div>
        </div>

        <!-- Total Withdrawals -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:border dark:border-gray-700 p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total Withdrawals</p>
              <p class="text-xl font-bold text-red-600 dark:text-red-400 mt-1">
                {{ formatCurrency(stats.totalWithdrawals) }}
              </p>
            </div>
            <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
              <i class="fas fa-arrow-up text-red-600 dark:text-red-400"></i>
            </div>
          </div>
          <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Selected period
          </div>
        </div>

        <!-- Average Transaction -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:border dark:border-gray-700 p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Avg. Transaction</p>
              <h3 class="text-xl font-bold text-gray-800 dark:text-white mt-1">
                {{ formatCurrency(stats.averageTransactionValue) }}
              </h3>
            </div>
            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
              <i class="fas fa-chart-line text-indigo-600 dark:text-indigo-400"></i>
            </div>
          </div>
          <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Average per transaction AVG(depo+wit)
          </div>
        </div>

        <!-- Net Cash Flow -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:border dark:border-gray-700 p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Net Cash Flow</p>
              <h3 class="text-xl font-bold mt-1"
                :class="stats.netFlow >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                {{ formatCurrency(stats.netFlow) }}
              </h3>
            </div>
            <div class="p-2"
              :class="stats.netFlow >= 0 ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30'">
              <i
                :class="stats.netFlow >= 0 ? 'fas fa-arrow-up text-green-600 dark:text-green-400' : 'fas fa-arrow-down text-red-600 dark:text-red-400'"></i>
            </div>
          </div>
          <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Deposits-Withdrawals SUM(depo-wit)
          </div>
        </div>
      </div>

      <!-- Recent Transactions Section -->
      <div class="mb-6">
        <div
          class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
          <div class="p-5 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
              <h2 class="font-semibold text-gray-800 dark:text-white">Recent Transactions</h2>
              <a href="/transactions-history"
                class="text-xs text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                View All <i class="fas fa-chevron-right ml-1 text-xs"></i>
              </a>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                  <th scope="col"
                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date & Time
                  </th>
                  <th scope="col"
                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Type
                  </th>
                  <th scope="col"
                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Account
                  </th>
                  <th scope="col"
                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Deposit
                  </th>
                  <th scope="col"
                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Withdraw
                  </th>
                  <th scope="col"
                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Opening Balance
                  </th>
                  <th scope="col"
                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Closing Balance
                  </th>
                  <th scope="col"
                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Details
                  </th>
                  <th scope="col"
                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-if="dashboardData.recentTransactions?.length === 0">
                  <td colspan="7" class="px-4 py-8 text-center">
                    <div class="flex flex-col items-center justify-center">
                      <i class="fas fa-exchange-alt text-3xl text-gray-400 mb-3"></i>
                      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No transactions found</h3>
                      <p class="text-gray-600 dark:text-gray-400 text-sm">Try adjusting your date filters</p>
                    </div>
                  </td>
                </tr>

                <tr v-for="transaction in dashboardData.recentTransactions.data" :key="transaction.id"
                  class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm text-gray-900 dark:text-white">
                      {{ formatDate(transaction.date) }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                      {{ formatTime(transaction.date) }}
                    </div>
                  </td>

                  <td class="px-4 py-3 whitespace-nowrap">
                    <span :class="[
                      'px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full',
                      getTransactionTypeClass(transaction.type)
                    ]">
                      {{ transaction.type }}
                    </span>
                  </td>

                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm text-gray-900 dark:text-white">
                      {{ transaction.account?.account_name || 'N/A' }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                      {{ transaction.account?.account_number || '' }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                      {{ transaction.account?.bank_name || '' }}
                    </div>
                  </td>

                  <td class="px-4 py-3 whitespace-nowrap">
                    <div v-if="transaction.deposit > 0" class="text-sm font-medium text-green-600 dark:text-green-400">
                      +{{ formatCurrency(transaction.deposit) }}
                    </div>
                    <div v-else class="text-sm text-gray-400">-</div>
                  </td>

                  <td class="px-4 py-3 whitespace-nowrap">
                    <div v-if="transaction.withdraw > 0" class="text-sm font-medium text-red-600 dark:text-red-400">
                      -{{ formatCurrency(transaction.withdraw) }}
                    </div>
                    <div v-else class="text-sm text-gray-400">-</div>
                  </td>

                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                      {{ formatCurrency(transaction.opening_balance) }}
                    </div>
                  </td>

                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                      {{ formatCurrency(transaction.closing_balance) }}
                    </div>
                  </td>

                  <td class="px-4 py-3">
                    <div class="text-sm text-gray-900 dark:text-white max-w-xs">
                      {{ transaction.details || 'No details' }}
                    </div>
                    <div v-if="transaction.receive_from" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                      From: {{ transaction.receive_from }}
                    </div>
                    <div v-if="transaction.payment_to" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                      To: {{ transaction.payment_to }}
                    </div>
                  </td>

                  <td class="px-4 py-3 whitespace-nowrap">
                    <span :class="[
                      'px-2 py-1 rounded-full text-xs font-medium',
                      transaction.status
                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                    ]">
                      {{ transaction.status ? 'Success' : 'Failed' }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Accounts and Company Distribution -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Accounts Overview -->
        <div class="lg:col-span-2">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700">
              <div class="flex justify-between items-center">
                <h2 class="font-semibold text-gray-800 dark:text-white">Bank Accounts ({{ stats.totalAccounts }})</h2>
                <a href="/accounts" class="text-xs text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                  View All <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                  <tr>
                    <th scope="col"
                      class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Account
                    </th>
                    <th scope="col"
                      class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Company
                    </th>
                    <th scope="col"
                      class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Bank
                    </th>
                    <th scope="col"
                      class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Balance
                    </th>
                    <th scope="col"
                      class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                  <tr v-for="account in displayedAccounts.data" :key="account.id"
                    class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer"
                    @click="viewAccountDetails(account)">
                    <td class="px-4 py-3 whitespace-nowrap">
                      <div class="flex items-center">
                        <div
                          class="h-8 w-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                          <i class="fas fa-landmark text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                          <div class="text-sm font-medium text-gray-900 dark:text-white">{{ account.account_name }}
                          </div>
                          <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ account.account_number }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                      <div class="text-sm text-gray-900 dark:text-white">{{ account.company_name }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                      <div class="text-sm text-gray-600 dark:text-gray-300">{{ account.bank_name }}</div>
                      <div class="text-xs text-gray-500 dark:text-gray-400">{{ account.branch_name }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                      <div class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ formatCurrency(account.opening_balance) }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                      <span :class="[
                        'px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full',
                        account.status === 1
                          ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                          : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'
                      ]">
                        {{ account.status === 1 ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Sidebar Stats -->
        <div class="space-y-6">
          <!-- Company Distribution -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h2 class="font-semibold text-gray-800 dark:text-white mb-4">Company Distribution</h2>
            <div class="space-y-4">
              <div v-for="company in dashboardData.companyDistribution" :key="company.company_id"
                class="flex items-center justify-between">
                <div class="flex items-center">
                  <div class="h-2 w-2 rounded-full bg-blue-500 mr-2"></div>
                  <span class="text-sm text-gray-700 dark:text-gray-300 truncate max-w-[140px]">
                    {{ company.company_name }}
                  </span>
                </div>
                <div class="flex items-center space-x-2">
                  <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ formatCurrency(company.total_balance) }}
                  </span>
                  <span
                    class="text-xs font-medium text-gray-900 dark:text-white bg-blue-50 dark:bg-blue-900/20 px-2 py-0.5 rounded">
                    {{ company.account_count }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Transaction Types -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h2 class="font-semibold text-gray-800 dark:text-white mb-4">Transaction Types</h2>
            <div class="space-y-3">
              <div v-for="type in dashboardData.transactionTypes" :key="type.name"
                class="flex items-center justify-between">
                <div class="flex items-center">
                  <div :class="['p-1.5 rounded mr-2', type.color.split(' ')[0]]">
                    <i :class="type.icon" class="text-xs"></i>
                  </div>
                  <span class="text-sm text-gray-700 dark:text-gray-300">{{ type.name }}</span>
                </div>
                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ type.count }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axiosClient from "@/axiosClient";
import TableLoadingState from '@/components/core/TableLoadingState.vue';

const router = useRouter();

// Reactive data
const loading = ref(false);
const error = ref(null);
const currentTime = ref(new Date());
const dashboardData = ref({
  stats: {},
  accounts: [],
  recentTransactions: [],
  companyDistribution: [],
  transactionTypes: []
});

// Filters
const filters = ref({
  startDate: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
  endDate: new Date().toISOString().split('T')[0],
  companyId: null,
  accountId: null
});

// Pagination
const accountPage = ref(0);
const accountsPerPage = 5;

// Computed properties
const stats = computed(() => dashboardData.value.stats || {});
const displayedAccounts = computed(() => {
  return dashboardData.value.accounts || [];
});

// Methods
const fetchDashboardData = async () => {
  try {
    loading.value = true;
    error.value = null;

    const params = new URLSearchParams();
    if (filters.value.startDate) params.append('start_date', filters.value.startDate);
    if (filters.value.endDate) params.append('end_date', filters.value.endDate);
    if (filters.value.companyId) params.append('company_id', filters.value.companyId);
    if (filters.value.accountId) params.append('account_id', filters.value.accountId);

    const response = await axiosClient.get(`/dashboard?${params.toString()}`);

    if (response.data.success) {
      dashboardData.value = response.data.data;
      currentTime.value = new Date(response.data.data.timestamp);
    } else {
      throw new Error(response.data.message || 'Failed to fetch dashboard data');
    }
  } catch (err) {
    console.error('Error fetching dashboard data:', err);
    error.value = err.response?.data?.message || err.message || 'An error occurred';
  } finally {
    loading.value = false;
  }
};

const refreshData = () => {
  fetchDashboardData();
};

const applyFilters = () => {
  accountPage.value = 0;
  fetchDashboardData();
};

const resetFilters = () => {
  filters.value = {
    startDate: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
    endDate: new Date().toISOString().split('T')[0],
    companyId: null,
    accountId: null
  };
  applyFilters();
};

const formatCurrency = (amount) => {
  const num = parseFloat(amount) || 0;
  const formattedNumber = new Intl.NumberFormat('en-IN', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(num);
  return `৳${formattedNumber}`;
};

const formatDate = (dateString) => {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-GB', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    timeZone: 'Asia/Dhaka'
  });
};

const formatTime = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit',
    timeZone: 'Asia/Dhaka'
  });
};

const viewAccountDetails = (account) => {
  router.push(`/account`);
};

const prevAccounts = () => {
  if (accountPage.value > 0) {
    accountPage.value--;
  }
};

const nextAccounts = () => {
  const totalAccounts = dashboardData.value.accounts?.length || 0;
  if (accountPage.value < Math.ceil(totalAccounts / accountsPerPage) - 1) {
    accountPage.value++;
  }
};

const getTransactionTypeClass = (type) => {
  const classMap = {
    'Deposit': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    'Withdraw': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    'Transfer': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
    'Balance': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
    'Cheque Bounce': 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
    'Other': 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
  };
  return classMap[type] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};

// Lifecycle
onMounted(() => {
  fetchDashboardData();

  // Auto-refresh every 5 minutes
  setInterval(() => {
    if (!loading.value) {
      fetchDashboardData();
    }
  }, 5 * 60 * 1000);

  // Update current time every minute
  setInterval(() => {
    currentTime.value = new Date();
  }, 60000);
});
</script>

<style scoped>
.dashboard-card {
  transition: all 0.3s ease;
}

.dashboard-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* Custom scrollbar */
.overflow-x-auto::-webkit-scrollbar {
  height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

.dark .overflow-x-auto::-webkit-scrollbar-track {
  background: #374151;
}

.dark .overflow-x-auto::-webkit-scrollbar-thumb {
  background: #4b5563;
}

.dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #6b7280;
}
</style>