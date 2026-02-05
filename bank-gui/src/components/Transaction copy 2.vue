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
      <!-- Header -->
      <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div></div>
        <div class="flex flex-wrap gap-3">
          <button @click="openCreateModal"
            class="btn px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center space-x-2 transition-colors">
            <i class="fas fa-plus"></i>
            New Transaction
          </button>

          <button @click="openDepositModal" class="btn-success flex items-center gap-2">
            <i class="fas fa-money-bill-wave"></i>
            Deposit
          </button>

          <button @click="openWithdrawModal" class="btn-danger flex items-center gap-2">
            <i class="fas fa-money-bill-transfer"></i>
            Withdraw
          </button>

          <button @click="openHistoryModal" class="btn-secondary flex items-center gap-2">
            <i class="fas fa-history"></i>
            History
          </button>

          <!-- <button @click="navigateToTrash" class="btn-warning flex items-center gap-2">
            <i class="fas fa-trash"></i>
            Trash ({{ trashCount }})
          </button> -->
        </div>
      </div>

      <!-- Filters -->
      <!-- Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex flex-wrap items-end gap-4">

          <!-- Search -->
          <div class="flex-1 min-w-[220px]">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Search
            </label>
            <div class="relative">
              <input v-model="filters.search" type="search" placeholder="Search transactions..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                 focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                 dark:bg-gray-700 dark:text-white" />
              <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
          </div>

          <!-- Account Number -->
          <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Account Number
            </label>
            <input v-model="filters.account_number" type="text" placeholder="Account number" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
               focus:ring-2 focus:ring-blue-500 focus:border-blue-500
               dark:bg-gray-700 dark:text-white" />
          </div>

          <!-- From Date -->
          <div class="flex-1 min-w-[160px]">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              From
            </label>
            <input v-model="filters.date_from" type="date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
               focus:ring-2 focus:ring-blue-500 focus:border-blue-500
               dark:bg-gray-700 dark:text-white" />
          </div>

          <!-- To Date -->
          <div class="flex-1 min-w-[160px]">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              To
            </label>
            <input v-model="filters.date_to" type="date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
               focus:ring-2 focus:ring-blue-500 focus:border-blue-500
               dark:bg-gray-700 dark:text-white" />
          </div>

          <!-- Buttons -->
          <div class="flex gap-2">
            <button @click="applyFilters"
              class="btn px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center space-x-2 transition-colors h-[42px] mt-6">
              <i class="fas fa-filter"></i>
              Apply
            </button>

            <button @click="resetFilters" class="btn-secondary flex items-center gap-2 h-[42px] mt-6">
              <i class="fas fa-redo"></i>
              Reset
            </button>
          </div>

        </div>
      </div>


      <!-- Transactions Table -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

            <SortableTableHeader :columns="tableColumns" :sort-by="transactionStore.sortBy"
              :sort-direction="transactionStore.sortDirection" :show-actions-column="true" :show-sl-column="true"
              @sort="sortByColumn" />

            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

              <TableLoadingState v-if="loading" :is-loading="true" :total-items="transactionStore.totalItems"
                :item-name="'transactions'" loading-text="Loading transactions..."
                loading-subtext="Fetching your transactions data" />

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
                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  {{ calculateRowNumber(index) }}
                </td>
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
                    {{ formatCurrency(transaction.opening_balance) }}
                  </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-bold text-gray-900 dark:text-white">
                    {{ formatCurrency(transaction.closing_balance) }}
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

                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex items-center gap-2">
                    <!-- View Button -->
                    <button @click="viewTransaction(transaction)"
                      class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 p-2 rounded bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 transition-colors"
                      title="View Details">
                      <i class="fas fa-eye"></i>
                    </button>

                    <!-- Edit Button -->
                    <button @click="editTransaction(transaction)"
                      class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 p-2 rounded bg-yellow-50 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:hover:bg-yellow-900/40 transition-colors"
                      title="Edit">
                      <i class="fas fa-edit"></i>
                    </button>

                    <!-- Bounce Button (for non-bounce transactions)
                    <button v-if="transaction.type !== 'Cheque Bounce'" @click="openBounceModal(transaction)"
                      class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300 p-2 rounded bg-orange-50 hover:bg-orange-100 dark:bg-orange-900/20 dark:hover:bg-orange-900/40 transition-colors"
                      title="Mark as Bounced">
                      <i class="fas fa-exclamation-triangle"></i>
                    </button> -->

                    <!-- Soft Delete Button -->
                    <button @click="softDeleteTransaction(transaction)"
                      class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-2 rounded bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 transition-colors"
                      title="Move to Trash">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="transactions.length > 0" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
          <Pagination :total-items="pagination.total" :per-page="pagination.per_page"
            :current-page="pagination.current_page" :last-page="pagination.last_page" @change-page="changePage"
            @change-per-page="changePerPage" />
        </div>
      </div>
    </div>

    <!-- Modals -->

    <!-- Create/Edit Transaction Modal -->
    <BaseModal :show="showTransactionModal" :title="modalMode === 'create' ? 'Create Transaction' : 'Edit Transaction'"
      maxWidth="4xl" @close="closeTransactionModal">
      <form @submit.prevent="saveTransaction">
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
          <!-- Transaction Type -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Transaction Type *
            </label>
            <select v-model="form.type" @change="handleTypeChange"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required>
              <option value="">-- Select Type --</option>
              <option value="Deposit">Deposit</option>
              <option value="Withdraw">Withdraw</option>
              <option value="Transfer">Transfer</option>
              <!-- <option value="Cheque Bounce">Cheque Bounce</option>
              <option value="Other">Other</option> -->
            </select>
          </div>

          <!-- Account Selection -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Account *
            </label>
            <select v-model="form.account_id"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required>
              <option value="">-- Select Account --</option>
              <option v-for="account in accounts" :key="account.id" :value="account.id">
                {{ account.account_name }} ({{ account.account_number }})
              </option>
            </select>
          </div>

          <!-- Date -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Date *
            </label>
            <input v-model="form.date" type="datetime-local"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required>
          </div>

          <!-- Amount Fields -->
          <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
            <div v-if="form.type === 'Deposit' || form.type === 'Transfer'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Deposit Amount *
              </label>
              <input v-model="form.deposit" type="number" step="0.01" min="0" placeholder="0.00"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                required>
            </div>

            <div v-if="form.type === 'Withdraw' || form.type === 'Transfer'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Withdraw Amount *
              </label>
              <input v-model="form.withdraw" type="number" step="0.01" min="0" placeholder="0.00"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                required>
            </div>
          </div>

          <!-- Related Party Fields -->
          <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
            <div v-if="form.type === 'Deposit'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Receive From *
              </label>
              <input v-model="form.receive_from" type="text" placeholder="Enter sender name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500
                 focus:border-blue-500" required>
            </div>

            <div v-if="form.type === 'Withdraw'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Payment To *
              </label>
              <input v-model="form.payment_to" type="text" placeholder="Enter recipient name"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
          </div>

          <!-- Details -->
          <div class="mt-4 col-span-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Details
            </label>
            <textarea v-model="form.details" rows="3" placeholder="Enter transaction details"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
          </div>

          <!-- Bounce Transaction -->
          <div v-if="modalMode === 'edit'">
            <!-- <label class="flex items-center">
              <input v-model="form.has_bounce" type="checkbox"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                This transaction has bounced
              </span>
            </label> -->

            <div v-if="form.has_bounce" class="mt-4">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Bounce Details
              </label>
              <textarea v-model="form.bounce_details" rows="2" placeholder="Enter bounce details"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
          </div>
          <!-- Error Messages -->
          <div v-if="error"
            class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-600 dark:text-red-400">{{ error }}</p>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
          <button type="button" @click="closeTransactionModal" class="btn-secondary">
            Cancel
          </button>
          <button type="submit" :disabled="saving"
            class="btn px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center space-x-2 transition-colors">
            <span v-if="saving">
              <i class="fas fa-spinner fa-spin mr-2"></i>
              Saving...
            </span>
            <span v-else>
              {{ modalMode === 'create' ? 'Save Transaction' : 'Update Transaction' }}
            </span>
          </button>
        </div>
      </form>
    </BaseModal>

    <!-- View Transaction Modal -->
    <BaseModal :show="showViewModal" title="Transaction Details" maxWidth="2xl" @close="closeViewModal">
      <div v-if="viewingTransaction" class="p-6">
        <div class="space-y-6">
          <!-- Header -->
          <div class="flex justify-between items-start">
            <div>
              <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                {{ viewingTransaction.type }}
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ formatDate(viewingTransaction.date) }} at {{ formatTime(viewingTransaction.date) }}
              </p>
            </div>
            <span :class="[
              'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
              getTransactionTypeClass(viewingTransaction.type)
            ]">
              {{ viewingTransaction.type }}
            </span>
          </div>

          <!-- Details Grid -->
          <div class="grid grid-cols-2 gap-6">
            <!-- Account Info -->
            <div>
              <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Account</label>
              <p class="text-sm text-gray-900 dark:text-white mt-1">
                Account Name: {{ viewingTransaction.account?.account_name || 'N/A' }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400">
                Account Number: {{ viewingTransaction.account?.account_number || 'N/A' }}
              </p>
              <div class="text-xs text-gray-500 dark:text-gray-400">
                {{ viewingTransaction.account?.bank_name ? 'Bank: ' + viewingTransaction.account.bank_name : '' }}
              </div>
              <div class="text-xs text-gray-500 dark:text-gray-400">
                {{ viewingTransaction.account?.branch_name ? 'Branch: ' + viewingTransaction.account.branch_name : '' }}
              </div>
            </div>
            <div>


              <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Transaction Details</label>

              <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate">
                {{ viewingTransaction.details }}
              </div>
              <div v-if="viewingTransaction.receive_from" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Pay From: {{ viewingTransaction.receive_from }}
              </div>
              <div v-if="viewingTransaction.payment_to" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Pay To: {{ viewingTransaction.payment_to }}
              </div>
              <div v-if="viewingTransaction.bounce_viewingTransaction_id"
                class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Bounce Transaction ID: {{ viewingTransaction.bounce_viewingTransaction_id }}
              </div>
              <!-- viewingTransaction by -->
              <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Transaction By: {{ viewingTransaction.user_name || 'N/A' }}
              </div>

            </div>

            <!-- Related Party -->
            <div v-if="viewingTransaction.receive_from || viewingTransaction.payment_to">
              <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                {{ viewingTransaction.type === 'Deposit' ? 'Received From' : 'Paid To' }}
              </label>
              <p class="text-sm text-gray-900 dark:text-white mt-1">
                {{ viewingTransaction.receive_from || viewingTransaction.payment_to }}
              </p>
            </div>

            <!-- Balance -->
            <div>
              <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mt-5">Amount</label>
              <p :class="[
                'text-xl font-bold mt-1',
                viewingTransaction.deposit > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'
              ]">
                {{
                  viewingTransaction.deposit > 0
                    ? `+${formatCurrency(viewingTransaction.deposit)}`
                    : `-${formatCurrency(viewingTransaction.withdraw)}`
                }}
              </p>

            </div>

            <!-- Status -->
            <div>
              <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Transaction Status</label>
              <div class="mt-1">
                <span :class="[
                  'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                  viewingTransaction.status
                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                ]">
                  {{ viewingTransaction.status ? 'Success' : 'Failed' }}
                </span>
              </div>
            </div>

            <!-- Created By -->
            <div>
              <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Balance</label>
              <p class="text-lg font-bold text-blue-600 dark:text-blue-400 mt-1">
                {{ formatCurrency(calculateRunningBalance(viewingTransaction)) }}
              </p>
            </div>
          </div>

          <!-- Bounce Transaction Info -->
          <div v-if="viewingTransaction.bounce_transaction_id">
            <div class="flex items-center gap-2 mb-3">
              <i class="fas fa-exclamation-triangle text-orange-500"></i>
              <label class="text-xs font-medium text-orange-600 dark:text-orange-400 uppercase">
                Bounce Transaction
              </label>
            </div>
            <div
              class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 p-4 rounded-lg">
              <p class="text-sm text-orange-800 dark:text-orange-300">
                {{ viewingTransaction.bounce_details || 'This transaction has been marked as bounced.' }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- View Modal Footer -->
      <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
        <button type="button" @click="closeViewModal" class="btn-secondary">
          Close
        </button>
        <button @click="editTransaction(viewingTransaction)"
          class="btn px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center space-x-2 transition-colors">
          <i class="fas fa-edit mr-2"></i>
          Edit Transaction
        </button>
      </div>
    </BaseModal>

    <!-- Deposit Modal -->
    <BaseModal :show="showDepositModal" title="Make Deposit" maxWidth="2xl" @close="closeDepositModal">
      <form @submit.prevent="saveDeposit">
        <div class="p-6 space-y-0 grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Account *
            </label>
            <select v-model="depositForm.account_id"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required>
              <option value="">-- Select Account --</option>
              <option v-for="account in accounts" :key="account.id" :value="account.id">
                {{ account.account_name }} ({{ account.account_number }})
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Amount *
            </label>
            <input v-model="depositForm.amount" type="number" step="0.01" min="0" placeholder="0.00"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Received From *
            </label>
            <input v-model="depositForm.receive_from" type="text" placeholder="Enter sender name"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Details
            </label>
            <textarea v-model="depositForm.details" rows="3" placeholder="Enter deposit details"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
          </div>
        </div>

        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
          <button type="button" @click="closeDepositModal" class="btn-secondary">
            Cancel
          </button>
          <button type="submit" :disabled="saving" class="btn-success">
            <span v-if="saving">
              <i class="fas fa-spinner fa-spin mr-2"></i>
              Processing...
            </span>
            <span v-else>Make Deposit</span>
          </button>
        </div>
      </form>
    </BaseModal>

    <!-- Withdraw Modal -->
    <BaseModal :show="showWithdrawModal" title="Make Withdrawal" maxWidth="2xl" @close="closeWithdrawModal">
      <form @submit.prevent="saveWithdraw">
        <div class="p-6 space-y-0 grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Account *
            </label>
            <select v-model="withdrawForm.account_id"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required>
              <option value="">-- Select Account --</option>
              <option v-for="account in accounts" :key="account.id" :value="account.id">
                {{ account.account_name }} ({{ account.account_number }})
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Amount *
            </label>
            <input v-model="withdrawForm.amount" type="number" step="0.01" min="0" placeholder="0.00"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Payment To *
            </label>
            <input v-model="withdrawForm.payment_to" type="text" placeholder="Enter recipient name"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Details
            </label>
            <textarea v-model="withdrawForm.details" rows="3" placeholder="Enter withdrawal details"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
          </div>
        </div>

        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
          <button type="button" @click="closeWithdrawModal" class="btn-secondary">
            Cancel
          </button>
          <button type="submit" :disabled="saving" class="btn-danger">
            <span v-if="saving">
              <i class="fas fa-spinner fa-spin mr-2"></i>
              Processing...
            </span>
            <span v-else>Make Withdrawal</span>
          </button>
        </div>
      </form>
    </BaseModal>

    <!-- Bounce Modal -->
    <BaseModal :show="showBounceModal" :title="'Mark Transaction as Bounced'" maxWidth="lg" @close="closeBounceModal">
      <form @submit.prevent="saveBounce">
        <div class="p-6 space-y-6">
          <div
            class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 p-4 rounded-lg">
            <div class="flex items-center gap-3">
              <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-xl"></i>
              <div>
                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">
                  You are marking a transaction as bounced
                </p>
                <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                  This will create a negative transaction to reverse the original amount.
                </p>
              </div>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Bounce Details
            </label>
            <textarea v-model="bounceForm.details" rows="4"
              placeholder="Enter bounce details (e.g., cheque bounced due to insufficient funds)"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
          </div>

          <div>
            <label class="flex items-center">
              <input v-model="bounceForm.notify_parties" type="checkbox"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Notify related parties about the bounce
              </span>
            </label>
          </div>
        </div>

        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
          <button type="button" @click="closeBounceModal" class="btn-secondary">
            Cancel
          </button>
          <button type="submit" :disabled="saving" class="btn-warning">
            <span v-if="saving">
              <i class="fas fa-spinner fa-spin mr-2"></i>
              Processing...
            </span>
            <span v-else>Mark as Bounced</span>
          </button>
        </div>
      </form>
    </BaseModal>

    <!-- History Modal -->
    <BaseModal :show="showHistoryModal" title="Transaction History" maxWidth="5xl" @close="closeHistoryModal">
      <div class="p-6">
        <!-- History Filters -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Account Number *
            </label>
            <input v-model="historyFilters.account_number" type="text" placeholder="Enter account number"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              From Date
            </label>
            <input v-model="historyFilters.date_from" type="date"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              To Date
            </label>
            <input v-model="historyFilters.date_to" type="date"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
          </div>

          <div class="flex items-end">
            <button @click="fetchHistory"
              class="btn px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center space-x-2 transition-colors">
              <i class="fas fa-history mr-2"></i>
              Get History
            </button>
          </div>
        </div>

        <!-- History Table -->
        <div v-if="history.length > 0" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                  Date
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                  Type
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                  Deposit
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                  Withdraw
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                  Balance
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                  Details
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="(item, index) in history" :key="index" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                  {{ formatDate(item.date) }}
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <span :class="[
                    'px-2 py-1 text-xs font-medium rounded-full',
                    getTransactionTypeClass(item.type)
                  ]">
                    {{ item.type }}
                  </span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600 dark:text-green-400 font-medium">
                  <span v-if="item.deposit > 0">
                    +{{ formatCurrency(item.deposit) }}
                  </span>
                  <span v-else class="text-gray-400">-</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600 dark:text-red-400 font-medium">
                  <span v-if="item.withdraw > 0">
                    -{{ formatCurrency(item.withdraw) }}
                  </span>
                  <span v-else class="text-gray-400">-</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                  {{ formatCurrency(item.balance) }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                  {{ item.details }}
                  <div v-if="item.receive_from" class="text-xs text-gray-500">
                    From: {{ item.receive_from }}
                  </div>
                  <div v-if="item.payment_to" class="text-xs text-gray-500">
                    To: {{ item.payment_to }}
                  </div>
                </td>
              </tr>
            </tbody>
            <tfoot class="bg-gray-50 dark:bg-gray-900">
              <tr>
                <td colspan="2" class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                  Totals
                </td>
                <td class="px-4 py-3 text-sm font-bold text-green-600 dark:text-green-400">
                  +{{ formatCurrency(historyTotals.deposit) }}
                </td>
                <td class="px-4 py-3 text-sm font-bold text-red-600 dark:text-red-400">
                  -{{ formatCurrency(historyTotals.withdraw) }}
                </td>
                <td class="px-4 py-3 text-sm font-bold text-blue-600 dark:text-blue-400">
                  {{ formatCurrency(historyTotals.balance) }}
                </td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>

        <!-- No History -->
        <div v-else-if="historyLoading" class="text-center py-12">
          <i class="fas fa-spinner fa-spin text-2xl text-blue-600 dark:text-blue-400 mr-3"></i>
          <span class="text-gray-600 dark:text-gray-400">Loading history...</span>
        </div>

        <div v-else class="text-center py-12">
          <i class="fas fa-history text-4xl text-gray-400 mb-4"></i>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Transaction History</h3>
          <p class="text-gray-600 dark:text-gray-400">
            Enter an account number and date range to view transaction history.
          </p>
        </div>
      </div>

      <div class="px-6 py-2 border-t border-gray-200 dark:border-gray-700 flex justify-end">
        <button @click="closeHistoryModal" class="btn-secondary">
          Close
        </button>
      </div>
    </BaseModal>

    <!-- Delete Confirmation Modal -->
    <BaseModal :show="showDeleteModal" title="Confirm Delete" maxWidth="lg" @close="closeDeleteModal">
      <div class="p-6 text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900 mb-4">
          <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300 text-2xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
          Delete Transaction
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
          Are you sure you want to delete this transaction?
          <span v-if="deletingTransaction">
            This action will remove the transaction from
            <strong>{{ formatDate(deletingTransaction.date) }}</strong>.
          </span>
        </p>
        <div class="flex justify-center space-x-3">
          <button @click="closeDeleteModal" class="btn-secondary">
            Cancel
          </button>
          <button @click="confirmDelete" :disabled="deleting" class="btn-danger">
            <span v-if="deleting">
              <i class="fas fa-spinner fa-spin mr-2"></i>
              Deleting...
            </span>
            <span v-else>Delete Transaction</span>
          </button>
        </div>
      </div>
    </BaseModal>

    <!-- Undo Modal -->
    <BaseModal :show="showUndoModal" title="Undo Transaction" maxWidth="sm" @close="closeUndoModal">
      <div class="p-6 text-center">
        <div
          class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900 mb-4">
          <i class="fas fa-undo text-green-600 dark:text-green-300 text-2xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
          Restore Transaction
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
          Are you sure you want to restore this transaction?
          <span v-if="undoingTransaction">
            This will restore the transaction from
            <strong>{{ formatDate(undoingTransaction.date) }}</strong>.
          </span>
        </p>
        <div class="flex justify-center space-x-3">
          <button @click="closeUndoModal" class="btn-secondary">
            Cancel
          </button>
          <button @click="confirmUndo" :disabled="undoing" class="btn-success">
            <span v-if="undoing">
              <i class="fas fa-spinner fa-spin mr-2"></i>
              Restoring...
            </span>
            <span v-else>Restore</span>
          </button>
        </div>
      </div>
    </BaseModal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import BaseModal from '@/components/common/BaseModal.vue';
import Pagination from '@/components/common/Pagination.vue';
import Breadcrumb from '@/components/core/Breadcrumb.vue';
import { useTransactionStore } from '@/stores/transactionStore';
import { useAccountStore } from '@/stores/accountStore';
import TableLoadingState from '@/components/core/TableLoadingState.vue';

import SortableTableHeader from '@/components/common/SortableTableHeader.vue';


import axiosClient from "@/axiosClient";
import { useToast } from "vue-toastification";

const toast = useToast();

const router = useRouter();
const transactionStore = useTransactionStore();
const accountStore = useAccountStore();

// States
const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);
const undoing = ref(false);
const historyLoading = ref(false);
const trashCount = ref(0);

// Modal states
const showTransactionModal = ref(false);
const showViewModal = ref(false);
const showDepositModal = ref(false);
const showWithdrawModal = ref(false);
const showBounceModal = ref(false);
const showHistoryModal = ref(false);
const showDeleteModal = ref(false);
const showUndoModal = ref(false);

// Selected items
const viewingTransaction = ref(null);
const deletingTransaction = ref(null);
const undoingTransaction = ref(null);
const bouncingTransaction = ref(null);

// Modal mode
const modalMode = ref('create'); // 'create' or 'edit'

const tableColumns = ref([
  {
    key: 'date',
    label: 'Date',
    sortable: true,
    width: '200px'
  },
  {
    key: 'type',
    label: 'Type',
    sortable: true,
    width: '250px'
  },
  {
    key: 'account_name',
    label: 'Account Info',
    sortable: true,
    width: '250px'
  },
  {
    key: 'deposit',
    label: 'Deposit',
    sortable: true,
    width: '200px'
  },
  {
    key: 'withdraw',
    label: 'Withdraw',
    sortable: true,
    width: '200px'
  },
  {
    key: 'opening_balance',
    label: 'Opening Balance',
    sortable: true,
    width: '200px'
  },
  {
    key: 'closing_balance',
    label: 'Closing Balance',
    sortable: true,
    width: '200px'
  },
  {
    key: 'details',
    label: 'Transaction Details',
    sortable: true,
    width: '200px'
  },
  {
    key: 'status',
    label: 'Status',
    sortable: true,
    width: '200px'
  },
]);

// Filters
const filters = ref({
  search: '',
  account_number: '',
  date_from: '',
  date_to: '',
  status: '',
  type: ''
});

const historyFilters = ref({
  account_number: '',
  date_from: '',
  date_to: ''
});

// Forms
const form = ref({
  type: 'Deposit',
  account_id: '',
  date: new Date().toISOString().slice(0, 16),
  deposit: '',
  withdraw: '',
  receive_from: '',
  payment_to: '',
  details: '',
  bounce_details: '',
  bounce_transaction_id: null,
  has_bounce: false,
  status: true
});

const depositForm = ref({
  account_id: '',
  amount: '',
  receive_from: '',
  details: ''
});

const withdrawForm = ref({
  account_id: '',
  amount: '',
  payment_to: '',
  details: ''
});

const bounceForm = ref({
  details: '',
  notify_parties: false
});

// History data
const history = ref([]);

// Computed properties
const transactions = computed(() => transactionStore.items || []);
const pagination = computed(() => transactionStore.pagination || {});
const accounts = computed(() => accountStore.items || []);
const error = computed(() => transactionStore.error || '');

const breadcrumbItems = computed(() => [
  { label: 'Dashboard', to: '/dashboard' },
  { label: 'Transactions', active: true }
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

const historyTotals = computed(() => {
  let deposit = 0;
  let withdraw = 0;
  let balance = 0;

  history.value.forEach(item => {
    deposit += parseFloat(item.deposit) || 0;
    withdraw += parseFloat(item.withdraw) || 0;
    balance = parseFloat(item.balance) || 0;
  });

  return { deposit, withdraw, balance };
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

const calculateRowNumber = (index) => {
  const currentPage = pagination.value.current_page;
  const perPage = pagination.value.per_page;
  return (currentPage - 1) * perPage + index + 1;
}
const sortByColumn = async (column) => {
  const order = transactionStore.sortDirection === 'asc' ? 'desc' : 'asc';
  await transactionStore.sort(column, order);
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

const calculateRunningBalance = (transaction) => {
  if (transaction.balance !== undefined) {
    return transaction.balance;
  }

  // Calculate running balance based on previous transactions
  let balance = 0;
  const transactionIndex = transactions.value.findIndex(t => t.id === transaction.id);

  for (let i = 0; i <= transactionIndex; i++) {
    const t = transactions.value[i];
    const deposit = parseFloat(t.deposit) || 0;
    const withdraw = parseFloat(t.withdraw) || 0;
    balance += (deposit - withdraw);
  }

  return balance;
};

// Event handlers
const handleTypeChange = () => {
  // Reset related fields when type changes
  if (form.value.type === 'Deposit') {
    form.value.withdraw = '';
    form.value.payment_to = '';
  } else if (form.value.type === 'Withdraw') {
    form.value.deposit = '';
    form.value.receive_from = '';
  } else if (form.value.type === 'Transfer') {
    // Keep both fields for transfers
  } else {
    form.value.deposit = '';
    form.value.withdraw = '';
    form.value.receive_from = '';
    form.value.payment_to = '';
  }
};

const applyFilters = async () => {
  loading.value = true;
  try {
    await transactionStore.get(filters.value);
  } catch (error) {
    toast.error('Failed to fetch transactions');
    console.error(error);
  } finally {
    loading.value = false;
  }
};

const resetFilters = () => {
  filters.value = {
    search: '',
    account_number: '',
    date_from: '',
    date_to: '',
    status: '',
    type: ''
  };
  applyFilters();
};

const changePage = (page) => {
  transactionStore.paginate(page);
};

const changePerPage = (perPage) => {
  transactionStore.changePerPage(perPage);
};

// Modal functions
const openCreateModal = () => {
  modalMode.value = 'create';
  resetForm();
  showTransactionModal.value = true;
};

const openDepositModal = () => {
  resetDepositForm();
  showDepositModal.value = true;
};

const openWithdrawModal = () => {
  resetWithdrawForm();
  showWithdrawModal.value = true;
};

const openBounceModal = (transaction) => {
  bouncingTransaction.value = transaction;
  bounceForm.value = {
    details: '',
    notify_parties: false
  };
  showBounceModal.value = true;
};

const openHistoryModal = () => {
  resetHistoryFilters();
  history.value = [];
  showHistoryModal.value = true;
};

const viewTransaction = (transaction) => {
  viewingTransaction.value = transaction;
  showViewModal.value = true;
};

const editTransaction = (transaction) => {
  modalMode.value = 'edit';
  viewingTransaction.value = transaction;
  form.value = {
    type: transaction.type,
    account_id: transaction.account_id,
    date: new Date(transaction.date).toISOString().slice(0, 16),
    deposit: transaction.deposit || '',
    withdraw: transaction.withdraw || '',
    receive_from: transaction.receive_from || '',
    payment_to: transaction.payment_to || '',
    details: transaction.details || '',
    bounce_details: transaction.bounce_details || '',
    bounce_transaction_id: transaction.bounce_transaction_id,
    has_bounce: !!transaction.bounce_transaction_id,
    status: transaction.status ? 1 : 0,
  };
  showTransactionModal.value = true;
  showViewModal.value = false;
};

const softDeleteTransaction = (transaction) => {
  deletingTransaction.value = transaction;
  showDeleteModal.value = true;
};

const undoTransaction = (transaction) => {
  undoingTransaction.value = transaction;
  showUndoModal.value = true;
};

const closeTransactionModal = () => {
  showTransactionModal.value = false;
  resetForm();
};

const closeViewModal = () => {
  showViewModal.value = false;
  viewingTransaction.value = null;
};

const closeDepositModal = () => {
  showDepositModal.value = false;
  resetDepositForm();
};

const closeWithdrawModal = () => {
  showWithdrawModal.value = false;
  resetWithdrawForm();
};

const closeBounceModal = () => {
  showBounceModal.value = false;
  bouncingTransaction.value = null;
  bounceForm.value = {
    details: '',
    notify_parties: false
  };
};

const closeHistoryModal = () => {
  showHistoryModal.value = false;
  history.value = [];
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  deletingTransaction.value = null;
};

const closeUndoModal = () => {
  showUndoModal.value = false;
  undoingTransaction.value = null;
};

// Reset forms
const resetForm = () => {
  form.value = {
    type: 'Deposit',
    account_id: '',
    date: new Date().toISOString().slice(0, 16),
    deposit: '',
    withdraw: '',
    receive_from: '',
    payment_to: '',
    details: '',
    bounce_details: '',
    bounce_transaction_id: null,
    has_bounce: false,
    status: 1
  };
};

const resetDepositForm = () => {
  depositForm.value = {
    account_id: '',
    amount: '',
    receive_from: '',
    details: ''
  };
};

const resetWithdrawForm = () => {
  withdrawForm.value = {
    account_id: '',
    amount: '',
    payment_to: '',
    details: ''
  };
};

const resetHistoryFilters = () => {
  historyFilters.value = {
    account_number: '',
    date_from: '',
    date_to: ''
  };
};

const saveTransaction = async () => {
  saving.value = true;
  try {
    const formDataToSend = new FormData();


    for (const key in form.value) {
      const value = form.value[key];

      if (value === null || value === undefined || value === '') {
        continue;
      }

      if (key === 'logo' && value instanceof File) {
        formDataToSend.append(key, value);
      } else {
        formDataToSend.append(key, value);
      }
    }

    if (modalMode.value === 'create') {
      await transactionStore.create(formDataToSend);
    } else {
      formDataToSend.append('_method', 'PUT');
      await transactionStore.update(viewingTransaction.value.id, formDataToSend);
    }

    closeTransactionModal();
    await applyFilters();

  } catch (error) {
    console.error(error);
  } finally {
    saving.value = false;
  }
};

const saveDeposit = async () => {
  saving.value = true;
  try {
    const formData = new FormData();

    const data = {
      type: 'Deposit',
      account_id: depositForm.value.account_id,
      date: new Date().toISOString(),
      deposit: depositForm.value.amount,
      receive_from: depositForm.value.receive_from,
      details: depositForm.value.details,
      status: 1
    };

    Object.entries(data).forEach(([key, value]) => {
      if (value === null || value === undefined || value === '') return;
      formData.append(key, value);
    });

    formData.set('_method', 'POST');
    await transactionStore.create(formData);

    closeDepositModal();
    await applyFilters();
  } catch (error) {
    console.error(error);
  } finally {
    saving.value = false;
  }
};


const saveWithdraw = async () => {
  saving.value = true;
  try {
    const formData = new FormData();

    const data = {
      type: 'Withdraw',
      account_id: withdrawForm.value.account_id,
      date: new Date().toISOString(),
      withdraw: withdrawForm.value.amount,
      payment_to: withdrawForm.value.payment_to,
      details: withdrawForm.value.details,
      status: 1
    };

    Object.entries(data).forEach(([key, value]) => {
      if (value === null || value === undefined || value === '') return;
      formData.append(key, value);
    });

    formData.set('_method', 'POST');
    await transactionStore.create(formData);
    closeWithdrawModal();
    await applyFilters();
  } catch (error) {
    console.error(error);
  } finally {
    saving.value = false;
  }
};

const saveBounce = async () => {
  saving.value = true;
  try {
    if (!bouncingTransaction.value) return;

    const formData = new FormData();

    const data = {
      bounce_details: bounceForm.value.details,
      create_bounce_transaction: 1
    };

    Object.entries(data).forEach(([key, value]) => {
      if (value === null || value === undefined || value === '') return;
      formData.append(key, value);
    });

    formData.set('_method', 'PUT');
    await transactionStore.update(bouncingTransaction.value.id, formData);

    closeBounceModal();
    await applyFilters();
  } catch (error) {
    console.error(error);
  } finally {
    saving.value = false;
  }
};


const confirmDelete = async () => {
  deleting.value = true;
  try {
    if (!deletingTransaction.value) return;

    await transactionStore.deleteItem(deletingTransaction.value.id);

    closeDeleteModal();
    await applyFilters();
  } catch (error) {
    toast.error('Failed to delete transaction');
    console.error(error);
  } finally {
    deleting.value = false;
  }
};

const confirmUndo = async () => {
  undoing.value = true;
  try {
    if (!undoingTransaction.value) return;

    await transactionStore.undoSoftDelete(undoingTransaction.value.id);
    toast.success('Transaction restored successfully');

    closeUndoModal();
    await applyFilters();
  } catch (error) {
    toast.error('Failed to restore transaction');
    console.error(error);
  } finally {
    undoing.value = false;
  }
};

const fetchHistory = async () => {
  if (!historyFilters.value.account_number) {
    toast.error('Please enter an account number');
    return;
  }

  historyLoading.value = true;
  try {
    const params = new URLSearchParams();

    if (historyFilters.value.account_number) {
      params.append('account_number', historyFilters.value.account_number);
    }
    if (historyFilters.value.date_from) {
      params.append('date_from', historyFilters.value.date_from);
    }
    if (historyFilters.value.date_to) {
      params.append('date_to', historyFilters.value.date_to);
    }

    const response = await axiosClient.get(`/transactions/history?${params}`);
    history.value = response.data.data || [];
  } catch (error) {
    toast.error('Failed to fetch transaction history');
    console.error(error);
  } finally {
    historyLoading.value = false;
  }
};

const navigateToTrash = () => {
  router.push('/transactions/trash-list');
};

// Lifecycle
onMounted(async () => {
  await Promise.all([
    applyFilters(),
    accountStore.getAll(),
    fetchTrashCount()
  ]);
});

const fetchTrashCount = async () => {
  try {
    const response = await axiosClient.get('/transactions/trash-list', {
      params: { per_page: 1 }
    });
    trashCount.value = response.data.total || 0;
  } catch (error) {
    console.error('Failed to fetch trash count:', error);
  }
};

// Watchers
watch(
  () => filters.value,
  () => {
    applyFilters();
  },
  { deep: true, immediate: false }
);
</script>

<style scoped>
.btn-primary {
  @apply px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
}

.btn-secondary {
  @apply px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2;
}

.btn-success {
  @apply px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2;
}

.btn-danger {
  @apply px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2;
}

.btn-warning {
  @apply px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2;
}
</style>