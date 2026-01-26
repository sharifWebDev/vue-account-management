<template>
  <!-- Page Header -->
  <div class="mb-6">
    <div class="flex justify-between items-start">
      <div>
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">Banking Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Comprehensive overview of banking operations and
          financial status</p>
      </div>
      <div class="flex items-center space-x-4">
        <!-- Filter Controls -->
        <div class="flex items-center space-x-2">
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
            class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition-colors">
            Apply
          </button>
          <button @click="resetFilters"
            class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs rounded-lg transition-colors">
            Reset
          </button>
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400">
          Last updated: {{ formatDate(currentTime) }}
          <button @click="refreshData" class="ml-2 p-1 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
            :disabled="loading">
            <i :class="loading ? 'fas fa-spinner fa-spin' : 'fas fa-sync-alt'" class="text-xs"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Loading State -->
  <div v-if="loading" class="flex justify-center items-center py-12">
    <div class="text-center">
      <i class="fas fa-spinner fa-spin text-3xl text-blue-500 mb-3"></i>
      <p class="text-gray-600 dark:text-gray-400">Loading dashboard data...</p>
    </div>
  </div>

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


    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Deposits</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">
              {{ formatCurrency(stats.total_deposit) }}
            </p>
          </div>
          <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
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
          <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
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
          <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
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
          <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
            <i class="fas fa-exchange-alt text-purple-600 dark:text-purple-400 text-xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Banking Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
      <!-- Total Accounts -->
      <div
        class="dashboard-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total Accounts</p>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ stats.totalAccounts }}</h3>
          </div>
          <div class="p-2.5 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
            <i class="fas fa-landmark text-blue-600 dark:text-blue-400 text-lg"></i>
          </div>
        </div>
        <div class="mt-4">
          <div class="flex items-center text-xs text-green-600 dark:text-green-400" v-if="stats.accountsGrowth > 0">
            <i class="fas fa-arrow-up mr-1 text-xs"></i>
            <span>{{ stats.accountsGrowth }} new this month</span>
          </div>
          <div class="flex items-center text-xs text-gray-600 dark:text-gray-400" v-else>
            <span>No change this month</span>
          </div>
        </div>
      </div>

      <!-- Total Balance -->
      <div
        class="dashboard-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total Balance</p>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ formatCurrency(stats.totalBalance) }}
            </h3>
          </div>
          <div class="p-2.5 bg-green-50 dark:bg-green-900/30 rounded-lg">
            <i class="fas fa-money-bill-wave text-green-600 dark:text-green-400 text-lg"></i>
          </div>
        </div>
        <div class="mt-4">
          <div class="flex items-center text-xs"
            :class="stats.balanceChange >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
            <i :class="stats.balanceChange >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'" class="mr-1 text-xs"></i>
            <span>{{ formatCurrency(Math.abs(stats.balanceChange)) }} this month</span>
          </div>
        </div>
      </div>

      <!-- Active Companies -->
      <div
        class="dashboard-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Active Companies</p>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ stats.activeCompanies }}</h3>
          </div>
          <div class="p-2.5 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
            <i class="fas fa-building text-purple-600 dark:text-purple-400 text-lg"></i>
          </div>
        </div>
        <div class="mt-4">
          <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
            <i class="fas fa-users mr-1.5 text-xs"></i>
            <span>{{ stats.totalUsers }} active users</span>
          </div>
        </div>
      </div>

      <!-- Recent Transactions -->
      <div
        class="dashboard-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Recent Transactions</p>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ stats.recentTransactionsCount }}</h3>
          </div>
          <div class="p-2.5 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg">
            <i class="fas fa-exchange-alt text-yellow-600 dark:text-yellow-400 text-lg"></i>
          </div>
        </div>
        <div class="mt-4">
          <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
            <i class="far fa-calendar-alt mr-1.5 text-xs"></i>
            <span>Last 30 days</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-7">
      <!-- Average Transaction Value -->
      <div
        class="dashboard-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Avg. Transaction</p>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{
              formatCurrency(stats.averageTransactionValue) }}</h3>
          </div>
          <div class="p-2.5 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
            <i class="fas fa-chart-line text-indigo-600 dark:text-indigo-400 text-lg"></i>
          </div>
        </div>
      </div>

      <!-- Total Deposits -->
      <div
        class="dashboard-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total Deposits</p>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ formatCurrency(stats.totalDeposits) }}
            </h3>
          </div>
          <div class="p-2.5 bg-green-50 dark:bg-green-900/30 rounded-lg">
            <i class="fas fa-arrow-down text-green-600 dark:text-green-400 text-lg"></i>
          </div>
        </div>
        <div class="mt-2">
          <div class="text-xs text-gray-600 dark:text-gray-400">Current period</div>
        </div>
      </div>

      <!-- Total Withdrawals -->
      <div
        class="dashboard-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total Withdrawals</p>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ formatCurrency(stats.totalWithdrawals)
            }}</h3>
          </div>
          <div class="p-2.5 bg-red-50 dark:bg-red-900/30 rounded-lg">
            <i class="fas fa-arrow-up text-red-600 dark:text-red-400 text-lg"></i>
          </div>
        </div>
        <div class="mt-2">
          <div class="text-xs text-gray-600 dark:text-gray-400">Current period</div>
        </div>
      </div>

      <!-- Net Cash Flow -->
      <div
        class="dashboard-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Net Cash Flow</p>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mt-1"
              :class="stats.netFlow >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
              {{ formatCurrency(stats.netFlow) }}
            </h3>
          </div>
          <div class="p-2.5"
            :class="stats.netFlow >= 0 ? 'bg-green-50 dark:bg-green-900/30' : 'bg-red-50 dark:bg-red-900/30'">
            <i :class="stats.netFlow >= 0 ? 'fas fa-trend-up text-green-600 dark:text-green-400' : 'fas fa-trend-down text-red-600 dark:text-red-400'"
              class="text-lg"></i>
          </div>
        </div>
        <div class="mt-2">
          <div class="text-xs text-gray-600 dark:text-gray-400">Deposits - Withdrawals</div>
        </div>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-7">


      <!-- Transactions Table -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

            <SortableTableHeader :columns="tableColumns" :sort-by="transactionStore.sortBy"
              :sort-direction="transactionStore.sortDirection" :show-actions-column="false" :show-sl-column="false"
              @sort="sortByColumn" />


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
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                      {{
                        filters.search || filters.account_number || filters.date_from || filters.date_to
                          ? 'No transactions match your filter criteria.'
                          : 'Get started by creating your first transaction.'
                      }}
                    </p>
                    <button @click="openCreateModal"
                      class="btn px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center space-x-2 transition-colors">
                      <i class="fas fa-plus"></i>
                      Create Transaction
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Transactions List -->
              <tr v-for="(transaction, index) in transactions" :key="transaction.id"
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
                  <div class="text-sm text-gray-900 dark:text-white">
                    {{ transaction.account?.account_name ? 'A/C Name: ' + transaction.account.account_name : 'N/A' }}
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ transaction.account?.account_number ? 'A/C No: ' + transaction.account.account_number : 'N/A'
                    }}
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ transaction.account?.bank_name ? 'Bank: ' + transaction.account.bank_name : '' }}
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ transaction.account?.branch_name ? 'Branch: ' + transaction.account.branch_name : '' }}
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
                    {{ formatCurrency(calculateRunningBalance(transaction)) }}
                  </div>
                </td>

                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate">
                    {{ transaction.details }}
                  </div>
                  <div v-if="transaction.receive_from" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Pay From: {{ transaction.receive_from }}
                  </div>
                  <div v-if="transaction.payment_to" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Pay To: {{ transaction.payment_to }}
                  </div>
                  <div v-if="transaction.bounce_transaction_id" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Bounce Transaction ID: {{ transaction.bounce_transaction_id }}
                  </div>
                  <!-- transaction by -->
                  <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Transaction By: {{ transaction.user_name || 'N/A' }}
                  </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <button :class="[
                    'px-3 py-1 rounded-full text-xs font-medium transition-colors',
                    transaction.status
                      ? 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800'
                      : 'bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800'
                  ]">
                    {{ transaction.status ? 'Success' : 'Failed' }}
                  </button>
                </td>

              </tr>
            </tbody>
          </table>
        </div>
      </div>


      <!-- Accounts Overview Table -->
      <div class="lg:col-span-2">
        <div
          class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700 h-full">
          <div class="flex justify-between items-center mb-5">
            <h2 class="font-semibold text-gray-800 dark:text-white">Bank Accounts Overview</h2>
            <a href="/accounts" class="text-xs text-gray-600 dark:text-gray-400 hover:underline flex items-center">
              View All <i class="fas fa-chevron-right ml-1 text-xs"></i>
            </a>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                  <th scope="col"
                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Account Name
                  </th>
                  <th scope="col"
                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Account Number
                  </th>
                  <th scope="col"
                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Company
                  </th>
                  <th scope="col"
                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Bank/Branch
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
                <tr v-for="account in displayedAccounts" :key="account.id"
                  class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer"
                  @click="viewAccountDetails(account)">
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center">
                      <div
                        class="flex-shrink-0 h-8 w-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-landmark text-blue-600 dark:text-blue-400 text-sm"></i>
                      </div>
                      <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ account.account_name }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm text-gray-900 dark:text-white font-mono">{{ account.account_number }}</div>
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
                      {{ formatCurrency(account.balance) }}
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

          <div v-if="accounts.length === 0" class="text-center py-8">
            <div class="text-gray-400 dark:text-gray-500">
              <i class="fas fa-landmark text-3xl mb-3"></i>
              <p class="text-sm">No accounts found</p>
            </div>
          </div>

          <div class="mt-4 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
            <div>Showing {{ Math.min(displayedAccounts.length, accountsPerPage) }} of {{ accounts.length }} accounts
            </div>
            <div class="flex space-x-2">
              <button @click="prevAccounts" :disabled="accountPage === 0"
                :class="['p-1 rounded', accountPage === 0 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700']">
                <i class="fas fa-chevron-left"></i>
              </button>
              <button @click="nextAccounts" :disabled="accountPage >= Math.ceil(accounts.length / accountsPerPage) - 1"
                :class="['p-1 rounded', accountPage >= Math.ceil(accounts.length / accountsPerPage) - 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700']">
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Stats & Actions -->
      <div class="space-y-6">
        <!-- Company Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
          <h2 class="font-semibold text-gray-800 dark:text-white mb-4">Company Distribution</h2>
          <div class="space-y-3">
            <div v-for="company in companyDistribution" :key="company.company_id"
              class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="h-2 w-2 rounded-full bg-blue-500 mr-2"></div>
                <span class="text-sm text-gray-700 dark:text-gray-300 truncate max-w-[120px]">{{ company.company_name
                }}</span>
              </div>
              <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ formatCurrency(company.total_balance)
                }}</span>
                <span
                  class="text-sm font-medium text-gray-900 dark:text-white bg-blue-50 dark:bg-blue-900/20 px-2 py-0.5 rounded">
                  {{ company.account_count }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Transaction Types -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
          <h2 class="font-semibold text-gray-800 dark:text-white mb-4">Transaction Types</h2>
          <div class="space-y-3">
            <div v-for="type in transactionTypes" :key="type.name" class="flex items-center justify-between">
              <div class="flex items-center">
                <div :class="['p-1.5 rounded mr-2', type.color]">
                  <i :class="type.icon" class="text-xs"></i>
                </div>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ type.name }}</span>
              </div>
              <span class="text-sm font-medium text-gray-900 dark:text-white">{{ type.count }}</span>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
          <h2 class="font-semibold text-gray-800 dark:text-white mb-4">Quick Actions</h2>
          <div class="space-y-3">
            <a href="/transactions/create"
              class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <div class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg">
                <i class="fas fa-plus text-green-600 dark:text-green-400 text-sm"></i>
              </div>
              <span class="text-gray-700 dark:text-gray-300 text-sm">New Transaction</span>
            </a>

            <a href="/accounts/create"
              class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                <i class="fas fa-landmark text-blue-600 dark:text-blue-400 text-sm"></i>
              </div>
              <span class="text-gray-700 dark:text-gray-300 text-sm">Add Bank Account</span>
            </a>

            <a href="/reports"
              class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <div class="p-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                <i class="fas fa-chart-bar text-purple-600 dark:text-purple-400 text-sm"></i>
              </div>
              <span class="text-gray-700 dark:text-gray-300 text-sm">View Reports</span>
            </a>

            <a href="/users"
              class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <div class="p-2 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg">
                <i class="fas fa-users text-yellow-600 dark:text-yellow-400 text-sm"></i>
              </div>
              <span class="text-gray-700 dark:text-gray-300 text-sm">Manage Users</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Transactions & System Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Recent Transactions -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center mb-5">
          <h2 class="font-semibold text-gray-800 dark:text-white">Recent Transactions</h2>
          <a href="/transactions" class="text-xs text-gray-600 dark:text-gray-400 hover:underline">View All</a>
        </div>

        <div class="space-y-4">
          <div v-for="transaction in recentTransactions" :key="transaction.id"
            class="flex items-start space-x-3 p-3 rounded-lg border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <div :class="[
              'p-2 rounded-lg mt-0.5',
              transaction.type === 'Balance' ? 'bg-blue-50 dark:bg-blue-900/30' :
                transaction.deposit > 0 ? 'bg-green-50 dark:bg-green-900/30' : 'bg-red-50 dark:bg-red-900/30'
            ]">
              <i :class="[
                'text-sm',
                transaction.type === 'Balance' ? 'fas fa-balance-scale text-blue-600 dark:text-blue-400' :
                  transaction.deposit > 0 ? 'fas fa-arrow-down text-green-600 dark:text-green-400' : 'fas fa-arrow-up text-red-600 dark:text-red-400'
              ]"></i>
            </div>
            <div class="flex-1">
              <div class="flex justify-between">
                <p class="text-gray-800 dark:text-white text-sm font-medium">{{ transaction.details }}</p>
                <span :class="[
                  'text-sm font-medium',
                  transaction.deposit > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'
                ]">
                  {{ transaction.deposit > 0 ? '+' : '-' }}{{ formatCurrency(transaction.deposit ||
                    transaction.withdraw) }}
                </span>
              </div>
              <div class="flex justify-between items-center mt-1">
                <p class="text-xs text-gray-600 dark:text-gray-400">
                  {{ transaction.account_name }}
                  <span v-if="transaction.receive_from"> • From: {{ transaction.receive_from }}</span>
                  <span v-if="transaction.payment_to"> • To: {{ transaction.payment_to }}</span>
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(transaction.date) }}</p>
              </div>
            </div>
          </div>

          <div v-if="recentTransactions.length === 0" class="text-center py-4">
            <div class="text-gray-400 dark:text-gray-500">
              <i class="fas fa-exchange-alt text-xl mb-2"></i>
              <p class="text-sm">No recent transactions</p>
            </div>
          </div>
        </div>
      </div>

      <!-- System Activity -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center mb-5">
          <h2 class="font-semibold text-gray-800 dark:text-white">System Activity</h2>
          <span class="text-xs text-gray-600 dark:text-gray-400">Recent activities</span>
        </div>

        <div class="space-y-4">
          <div v-for="activity in systemActivity" :key="activity.title + activity.timestamp"
            class="flex items-start space-x-3">
            <div :class="['p-2 rounded-lg mt-0.5', activity.color]">
              <i :class="[activity.icon, 'text-sm']"></i>
            </div>
            <div class="flex-1">
              <p class="text-gray-800 dark:text-white text-sm">{{ activity.title }}</p>
              <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">{{ activity.description }}</p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ formatRelativeTime(activity.timestamp) }}
              </p>
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
import { useToast } from "vue-toastification";

const router = useRouter();

// Reactive data
const currentTime = ref(new Date());
const loading = ref(true);
const error = ref(null);

// Filters
const filters = ref({
  startDate: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0], // 30 days ago
  endDate: new Date().toISOString().split('T')[0], // Today
  companyId: null,
  accountId: null
});

// Dashboard data
const stats = ref({
  totalAccounts: 0,
  totalBalance: 0,
  activeCompanies: 0,
  recentTransactionsCount: 0,
  accountsGrowth: 0,
  balanceChange: 0,
  totalUsers: 0,
  averageTransactionValue: 0,
  totalDeposits: 0,
  totalWithdrawals: 0,
  netFlow: 0
});

const accounts = ref([]);
const recentTransactions = ref([]);
const companyDistribution = ref([]);
const transactionTypes = ref([]);
const systemActivity = ref([]);
const dailyTrends = ref([]);
const topAccounts = ref([]);
const monthlySummary = ref([]);
const bankDistribution = ref([]);

// Pagination
const accountPage = ref(0);
const accountsPerPage = 5;

// Computed properties
const displayedAccounts = computed(() => {
  const start = accountPage.value * accountsPerPage;
  const end = start + accountsPerPage;
  return accounts.value.slice(start, end);
});


const tableColumns = ref([
  {
    key: 'date',
    label: 'Date',
    sortable: false,
    width: '200px'
  },
  {
    key: 'type',
    label: 'Type',
    sortable: false,
    width: '250px'
  },
  {
    key: 'account_name',
    label: 'Account Info',
    sortable: false,
    width: '250px'
  },
  {
    key: 'deposit',
    label: 'Deposit',
    sortable: false,
    width: '200px'
  },
  {
    key: 'withdraw',
    label: 'Withdraw',
    sortable: false,
    width: '200px'
  },
  {
    key: 'balance',
    label: 'Balance',
    sortable: false,
    width: '200px'
  },
  {
    key: 'details',
    label: 'Transaction Details',
    sortable: false,
    width: '200px'
  },
  {
    key: 'status',
    label: 'Status',
    sortable: false,
    width: '200px'
  },
]);

// Methods
const fetchDashboardData = async () => {
  try {
    loading.value = true;
    error.value = null;

    // Build query parameters
    const params = new URLSearchParams();
    if (filters.value.startDate) params.append('start_date', filters.value.startDate);
    if (filters.value.endDate) params.append('end_date', filters.value.endDate);
    if (filters.value.companyId) params.append('company_id', filters.value.companyId);
    if (filters.value.accountId) params.append('account_id', filters.value.accountId);

    const queryString = params.toString();

    const response = await axiosClient.get(`${import.meta.env.VITE_API_BASE_URL}dashboard${queryString ? `?${queryString}` : ''}`);

    const result = response.data;
    updateDashboardData(result.data);
    currentTime.value = new Date(result.data.timestamp);

  } catch (err) {
    console.error('Error fetching dashboard data:', err);
    error.value = err.message;
    if (accounts.value.length === 0) {
      loadMockData();
    }
  } finally {
    loading.value = false;
  }
};

const updateDashboardData = (data) => {
  stats.value = data.stats;
  accounts.value = data.accounts;
  recentTransactions.value = data.recentTransactions;
  companyDistribution.value = data.companyDistribution;
  transactionTypes.value = data.transactionTypes;
  systemActivity.value = data.systemActivity;
  dailyTrends.value = data.dailyTrends || [];
  topAccounts.value = data.topAccounts || [];
  monthlySummary.value = data.monthlySummary || [];
  bankDistribution.value = data.bankDistribution || [];
};

const loadMockData = () => {
  // This is your original mock data as fallback
  stats.value = {
    totalAccounts: 12,
    totalBalance: 523747,
    activeCompanies: 4,
    recentTransactionsCount: 7,
    accountsGrowth: 2,
    balanceChange: 50000,
    totalUsers: 3,
    averageTransactionValue: 15000,
    totalDeposits: 300000,
    totalWithdrawals: 120000,
    netFlow: 180000
  };

  accounts.value = [
    { id: 1, account_name: 'iMesh Limited', account_number: '198.110.10172', company_name: 'i-Mesh Limited', bank_name: 'Dutch Bangla Bank Ltd.', branch_name: 'Karanigonj', status: 1, balance: 7604 },
    { id: 2, account_name: 'e-Sufiana.com', account_number: '136.110.17642', company_name: 'e-Sufiana.com', bank_name: 'Dutch Bangla Bank Ltd.', branch_name: 'Naya bazar', status: 1, balance: 136450 },
    { id: 3, account_name: 'Imaginative', account_number: '136.110.16874', company_name: 'Imaginative', bank_name: 'Dutch Bangla Bank Ltd.', branch_name: 'Naya bazar', status: 1, balance: 225572 },
    { id: 4, account_name: 'Triple S Trade Link', account_number: '224.120.566', company_name: 'Triple S Trade Link', bank_name: 'Dutch Bangla Bank Ltd.', branch_name: 'Wari', status: 1, balance: 154216 },
  ];

  recentTransactions.value = [
    { id: 1, date: '2023-10-01', type: 'Balance', details: 'Opening Balance', deposit: '7604', withdraw: '', account_name: 'iMesh Limited' },
    { id: 2, date: '2023-10-01', type: 'Balance', details: 'Opening Balance', deposit: '136450', withdraw: '', account_name: 'e-Sufiana.com' },
    { id: 13, date: '2023-10-25', type: 'Cheque', details: 'Insurance payment', deposit: '78000', withdraw: '', account_name: 'iMesh Limited', receive_from: 'Provati Insurance Ltd.' },
  ];

  companyDistribution.value = [
    { company_id: 1, company_name: 'i-Mesh Limited', account_count: 6, total_balance: 250000 },
    { company_id: 2, company_name: 'e-Sufiana.com', account_count: 3, total_balance: 136450 },
    { company_id: 3, company_name: 'Imaginative', account_count: 2, total_balance: 225572 },
    { company_id: 4, company_name: 'Triple S Trade Link', account_count: 1, total_balance: 154216 },
  ];

  transactionTypes.value = [
    { name: 'Balance', count: 4, icon: 'fas fa-balance-scale', color: 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' },
    { name: 'Cheque', count: 3, icon: 'fas fa-money-check', color: 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' },
    { name: 'Cash', count: 1, icon: 'fas fa-money-bill-wave', color: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400' },
  ];

  systemActivity.value = [
    {
      title: 'New user account created',
      description: 'User: John Doe',
      timestamp: new Date(Date.now() - 2 * 60 * 60 * 1000), // 2 hours ago
      icon: 'fas fa-user-plus',
      color: 'bg-green-100 dark:bg-green-900/30'
    },
    {
      title: 'New bank account added',
      description: 'Added account: ABC Corp',
      timestamp: new Date(Date.now() - 24 * 60 * 60 * 1000), // 1 day ago
      icon: 'fas fa-landmark',
      color: 'bg-blue-100 dark:bg-blue-900/30'
    },
  ];
};

const refreshData = () => {
  fetchDashboardData();
};

const applyFilters = () => {
  accountPage.value = 0; // Reset to first page
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

// Example: 
// formatCurrency(39544084) -> "৳3,95,44,084.00"
const formatRelativeTime = (timestamp) => {
  if (!timestamp) return 'Just now';
  const date = new Date(timestamp);
  const now = new Date();
  const diffInSeconds = Math.floor((now - date) / 1000);

  if (diffInSeconds < 60) return 'Just now';
  if (diffInSeconds < 3600) {
    const minutes = Math.floor(diffInSeconds / 60);
    return `${minutes} ${minutes === 1 ? 'minute' : 'minutes'} ago`;
  }
  if (diffInSeconds < 86400) {
    const hours = Math.floor(diffInSeconds / 3600);
    return `${hours} ${hours === 1 ? 'hour' : 'hours'} ago`;
  }
  if (diffInSeconds < 604800) {
    const days = Math.floor(diffInSeconds / 86400);
    return `${days} ${days === 1 ? 'day' : 'days'} ago`;
  }

  return date.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: diffInSeconds > 31536000 ? 'numeric' : undefined
  });
};

const viewAccountDetails = (account) => {
  router.push(`/accounts/${account.id}`);
};

const prevAccounts = () => {
  if (accountPage.value > 0) {
    accountPage.value--;
  }
};

const nextAccounts = () => {
  if (accountPage.value < Math.ceil(accounts.value.length / accountsPerPage) - 1) {
    accountPage.value++;
  }
};

// Lifecycle
onMounted(() => {
  fetchDashboardData();

  // Update current time every minute
  setInterval(() => {
    currentTime.value = new Date();
  }, 60000);

  // Auto-refresh dashboard data every 5 minutes
  setInterval(() => {
    if (!loading.value) {
      fetchDashboardData();
    }
  }, 5 * 60 * 1000);
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

/* Custom scrollbar for tables */
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