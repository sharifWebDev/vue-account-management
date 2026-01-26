<template>
  <!-- Page Header -->
  <div class="mb-6">
    <div class="flex justify-between items-start">
      <div>
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">Banking Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Comprehensive overview of banking operations and
          financial status</p>
      </div>
      <div class="text-xs text-gray-500 dark:text-gray-400">
        Last updated: {{ formatDate(currentTime) }}
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
          <h3 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ accounts.length }}</h3>
        </div>
        <div class="p-2.5 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
          <i class="fas fa-landmark text-blue-600 dark:text-blue-400 text-lg"></i>
        </div>
      </div>
      <div class="mt-4">
        <div class="flex items-center text-xs text-green-600 dark:text-green-400" v-if="accountsGrowth > 0">
          <i class="fas fa-arrow-up mr-1 text-xs"></i>
          <span>{{ accountsGrowth }} new this month</span>
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
          <h3 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ formatCurrency(totalBalance) }}</h3>
        </div>
        <div class="p-2.5 bg-green-50 dark:bg-green-900/30 rounded-lg">
          <i class="fas fa-money-bill-wave text-green-600 dark:text-green-400 text-lg"></i>
        </div>
      </div>
      <div class="mt-4">
        <div class="flex items-center text-xs"
          :class="balanceChange >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
          <i :class="balanceChange >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'" class="mr-1 text-xs"></i>
          <span>{{ formatCurrency(Math.abs(balanceChange)) }} this month</span>
        </div>
      </div>
    </div>

    <!-- Active Companies -->
    <div
      class="dashboard-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Active Companies</p>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ activeCompanies }}</h3>
        </div>
        <div class="p-2.5 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
          <i class="fas fa-building text-purple-600 dark:text-purple-400 text-lg"></i>
        </div>
      </div>
      <div class="mt-4">
        <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
          <i class="fas fa-users mr-1.5 text-xs"></i>
          <span>{{ users.length }} active users</span>
        </div>
      </div>
    </div>

    <!-- Recent Transactions -->
    <div
      class="dashboard-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Recent Transactions</p>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ recentTransactionsCount }}</h3>
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

  <!-- Main Content Area -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-7">
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
                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Account Name
                </th>
                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Account Number
                </th>
                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Company
                </th>
                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Bank/Branch
                </th>
                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Balance
                </th>
                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="account in displayedAccounts" :key="account.account_id"
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
                  <div class="text-sm text-gray-900 dark:text-white">{{ getCompanyName(account.company_id) }}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <div class="text-sm text-gray-600 dark:text-gray-300">{{ getBankName(account.bank_id) }}</div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ getBranchName(account.branch_id) }}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <div class="text-sm font-semibold text-gray-900 dark:text-white">
                    {{ formatCurrency(getAccountBalance(account.account_id)) }}
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
          <div>Showing {{ Math.min(displayedAccounts.length, 5) }} of {{ accounts.length }} accounts</div>
          <div class="flex space-x-2">
            <button @click="prevAccounts" :disabled="accountPage === 0"
              :class="['p-1 rounded', accountPage === 0 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700']">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button @click="nextAccounts" :disabled="accountPage >= Math.ceil(accounts.length / 5) - 1"
              :class="['p-1 rounded', accountPage >= Math.ceil(accounts.length / 5) - 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700']">
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
          <div v-for="company in companies" :key="company.company_id" class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="h-2 w-2 rounded-full bg-blue-500 mr-2"></div>
              <span class="text-sm text-gray-700 dark:text-gray-300">{{ company.company_name }}</span>
            </div>
            <span class="text-sm font-medium text-gray-900 dark:text-white">
              {{ getCompanyAccountCount(company.company_id) }} accounts
            </span>
          </div>
        </div>
      </div>

      <!-- Transaction Types -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700">
        <h2 class="font-semibold text-gray-800 dark:text-white mb-4">Recent Transaction Types</h2>
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
        <div v-for="transaction in recentTransactions" :key="transaction.transaction_id"
          class="flex items-start space-x-3 p-3 rounded-lg border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
          <div :class="[
            'p-2 rounded-lg mt-0.5',
            transaction.type === 'Balance' ? 'bg-blue-50 dark:bg-blue-900/30' :
              transaction.deposit ? 'bg-green-50 dark:bg-green-900/30' : 'bg-red-50 dark:bg-red-900/30'
          ]">
            <i :class="[
              'text-sm',
              transaction.type === 'Balance' ? 'fas fa-balance-scale text-blue-600 dark:text-blue-400' :
                transaction.deposit ? 'fas fa-arrow-down text-green-600 dark:text-green-400' : 'fas fa-arrow-up text-red-600 dark:text-red-400'
            ]"></i>
          </div>
          <div class="flex-1">
            <div class="flex justify-between">
              <p class="text-gray-800 dark:text-white text-sm font-medium">{{ transaction.details }}</p>
              <span :class="[
                'text-sm font-medium',
                transaction.deposit ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'
              ]">
                {{ transaction.deposit ? '+' : '-' }}{{ formatCurrency(transaction.deposit || transaction.withdraw) }}
              </span>
            </div>
            <div class="flex justify-between items-center mt-1">
              <p class="text-xs text-gray-600 dark:text-gray-400">
                {{ getAccountName(transaction.account_id) }}
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
        <span class="text-xs text-gray-600 dark:text-gray-400">Last 7 days</span>
      </div>

      <div class="space-y-4">
        <div class="flex items-start space-x-3">
          <div class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg mt-0.5">
            <i class="fas fa-user-plus text-green-600 dark:text-green-400 text-sm"></i>
          </div>
          <div class="flex-1">
            <p class="text-gray-800 dark:text-white text-sm">New user account created: John Doe</p>
            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">2 hours ago</p>
          </div>
        </div>

        <div class="flex items-start space-x-3">
          <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg mt-0.5">
            <i class="fas fa-landmark text-blue-600 dark:text-blue-400 text-sm"></i>
          </div>
          <div class="flex-1">
            <p class="text-gray-800 dark:text-white text-sm">New bank account added: ABC Corp</p>
            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Yesterday</p>
          </div>
        </div>

        <div class="flex items-start space-x-3">
          <div class="p-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg mt-0.5">
            <i class="fas fa-file-export text-purple-600 dark:text-purple-400 text-sm"></i>
          </div>
          <div class="flex-1">
            <p class="text-gray-800 dark:text-white text-sm">Monthly financial report generated</p>
            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">3 days ago</p>
          </div>
        </div>

        <div class="flex items-start space-x-3">
          <div class="p-2 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg mt-0.5">
            <i class="fas fa-shield-alt text-yellow-600 dark:text-yellow-400 text-sm"></i>
          </div>
          <div class="flex-1">
            <p class="text-gray-800 dark:text-white text-sm">System backup completed successfully</p>
            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">1 week ago</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

// Current time
const currentTime = ref(new Date());

// Mock data - In real application, this would come from your API
const accounts = ref([
  { account_id: 1, account_name: 'iMesh Limited', account_number: '198.110.10172', company_id: 1, bank_id: 1, branch_id: 1, status: 1 },
  { account_id: 2, account_name: 'e-Sufiana.com', account_number: '136.110.17642', company_id: 2, bank_id: 1, branch_id: 2, status: 1 },
  { account_id: 3, account_name: 'Imaginative', account_number: '136.110.16874', company_id: 3, bank_id: 1, branch_id: 2, status: 1 },
  { account_id: 4, account_name: 'Triple S Trade Link', account_number: '224.120.566', company_id: 4, bank_id: 1, branch_id: 3, status: 1 },
]);

const companies = ref([
  { company_id: 1, company_name: 'i-Mesh Limited', status: 1 },
  { company_id: 2, company_name: 'e-Sufiana.com', status: 1 },
  { company_id: 3, company_name: 'Imaginative', status: 1 },
  { company_id: 4, company_name: 'Triple S Trade Link', status: 1 },
]);

const banks = ref([
  { bank_id: 1, bank_name: 'Dutch Bangla Bank Ltd.' },
  { bank_id: 2, bank_name: 'City Bank Ltd.' },
]);

const branches = ref([
  { branch_id: 1, branch_name: 'Karanigonj' },
  { branch_id: 2, branch_name: 'Naya bazar' },
  { branch_id: 3, branch_name: 'Wari' },
]);

const users = ref([
  { user_id: 1, first_name: 'Sahed', last_name: 'Ali', email: 'sahed@imeshbd.com', user_type: 'admin', status: 1 },
  { user_id: 11, first_name: 'Masudul', last_name: 'Kabir', email: 'masud@imeshbd.com', user_type: 'user', status: 1 },
  { user_id: 12, first_name: 'Shuvojit', last_name: 'Shaha', email: 'shuvojitshaha660@gmail.com', user_type: 'user', status: 1 },
  { user_id: 13, first_name: 'Tamal', last_name: 'Chakraborty', email: 'i.org.T19@gmail.com', user_type: 'user', status: 1 },
]);

const transactions = ref([
  { transaction_id: 1, date: '2023-10-01', type: 'Balance', details: 'Opening Balance', deposit: '7604', withdraw: '', account_id: 1 },
  { transaction_id: 2, date: '2023-10-01', type: 'Balance', details: 'Opening Balance', deposit: '136450', withdraw: '', account_id: 2 },
  { transaction_id: 3, date: '2023-10-01', type: 'Balance', details: 'Opening Balance', deposit: '225572', withdraw: '', account_id: 3 },
  { transaction_id: 4, date: '2023-10-01', type: 'Balance', details: 'Opening Balance', deposit: '154216', withdraw: '', account_id: 4 },
  { transaction_id: 11, date: '2023-10-15', type: 'Debit/Credit Card', details: 'Office expenses', deposit: '', withdraw: '5000', account_id: 1, payment_to: 'Office expenses purpose' },
  { transaction_id: 12, date: '2023-10-20', type: 'Cheque', details: 'Cash withdraw', deposit: '', withdraw: '100000', account_id: 4, payment_to: 'Cash withdraw by shuvojit Shaha' },
  { transaction_id: 13, date: '2023-10-25', type: 'Cheque', details: 'Insurance payment', deposit: '78000', withdraw: '', account_id: 1, receive_from: 'Provati Insurance Ltd.' },
]);

// Pagination for accounts table
const accountPage = ref(0);
const accountsPerPage = 5;

// Computed properties
const displayedAccounts = computed(() => {
  const start = accountPage.value * accountsPerPage;
  const end = start + accountsPerPage;
  return accounts.value.slice(start, end);
});

const totalBalance = computed(() => {
  return accounts.value.reduce((sum, account) => {
    return sum + getAccountBalance(account.account_id);
  }, 0);
});

const activeCompanies = computed(() => {
  return companies.value.filter(company => company.status === 1).length;
});

const recentTransactions = computed(() => {
  return transactions.value.slice().sort((a, b) => new Date(b.date) - new Date(a.date)).slice(0, 5);
});

const recentTransactionsCount = computed(() => {
  const thirtyDaysAgo = new Date();
  thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
  return transactions.value.filter(t => new Date(t.date) >= thirtyDaysAgo).length;
});

const accountsGrowth = computed(() => {
  // Mock growth calculation - in real app, this would compare with previous month
  return 2;
});

const balanceChange = computed(() => {
  // Mock change calculation - in real app, this would compare with previous month
  return 50000;
});

const transactionTypes = computed(() => {
  const types = {};
  transactions.value.forEach(t => {
    if (!types[t.type]) {
      types[t.type] = 0;
    }
    types[t.type]++;
  });

  return Object.entries(types).map(([name, count]) => {
    let icon = 'fas fa-exchange-alt';
    let color = 'bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-gray-400';

    if (name === 'Balance') {
      icon = 'fas fa-balance-scale';
      color = 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400';
    } else if (name === 'Cheque') {
      icon = 'fas fa-money-check';
      color = 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400';
    } else if (name === 'Cash') {
      icon = 'fas fa-money-bill-wave';
      color = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400';
    }

    return { name, count, icon, color };
  });
});

// Methods
const formatCurrency = (amount) => {
  const num = parseFloat(amount) || 0;
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(num);
};

const formatDate = (dateString) => {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const getCompanyName = (companyId) => {
  const company = companies.value.find(c => c.company_id === companyId);
  return company ? company.company_name : 'Unknown';
};

const getBankName = (bankId) => {
  const bank = banks.value.find(b => b.bank_id === bankId);
  return bank ? bank.bank_name : 'Unknown';
};

const getBranchName = (branchId) => {
  const branch = branches.value.find(b => b.branch_id === branchId);
  return branch ? branch.branch_name : 'Unknown';
};

const getAccountName = (accountId) => {
  const account = accounts.value.find(a => a.account_id === accountId);
  return account ? account.account_name : 'Unknown Account';
};

const getAccountBalance = (accountId) => {
  const accountTransactions = transactions.value.filter(t => t.account_id === accountId);
  return accountTransactions.reduce((balance, transaction) => {
    const deposit = parseFloat(transaction.deposit) || 0;
    const withdraw = parseFloat(transaction.withdraw) || 0;
    return balance + deposit - withdraw;
  }, 0);
};

const getCompanyAccountCount = (companyId) => {
  return accounts.value.filter(account => account.company_id === companyId).length;
};

const viewAccountDetails = (account) => {
  router.push(`/accounts/${account.account_id}`);
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
</style>