<template>
  <div class="mb-4">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Accounts</h1>
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
            placeholder="Search by account name or number..."
            class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-1 focus:ring-gray-500 focus:border-gray-500 outline-none transition-all focus:shadow-sm" />
          <div class="absolute left-3 top-2.5 text-gray-400">
            <i class="fas fa-search text-base"></i>
          </div>
        </div>
        <div
          class="text-xs text-gray-500 dark:text-gray-400 flex items-center border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1">
          <button @click="refreshData" class="p-1 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
            :disabled="refresh">
            <i class="fas fa-sync-alt text-xs" :class="{ 'animate-spin': refresh }"></i>
          </button>
        </div>

      </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-visible"> <!-- Changed from overflow-hidden -->
      <div class="overflow-visible"> <!-- Changed from overflow-x-auto -->
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 relative">
          <SortableTableHeader :columns="tableColumns" :sort-by="accountStore.sortBy"
            :sort-direction="accountStore.sortDirection" :show-actions-column="true" :show-sl-column="true"
            @sort="sortByColumn" />

          <!-- Loading State - Inside table body -->
          <TableLoadingState v-if="accountStore.loading" :is-loading="true" :total-items="accountStore.totalItems"
            :item-name="'accounts'" loading-text="Loading accounts..." loading-subtext="Fetching your account data" />

          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

            <!-- Empty State -->
            <TableLoadingState v-if="accountStore.items.length === 0" :is-loading="false" :isEmpty="true"
              :has-search="!!searchQuery" :item-name="'accounts'" empty-icon="fas fa-users"
              empty-title="No accounts found"
              :empty-message="searchQuery ? 'No accounts match your search criteria.' : 'Get started by creating your first account.'"
              :create-button-text="'Add First Account'" @create="openCreateModal" @clear-search="clearSearch" />

            <tr v-else v-for="(data, index) in displayedItems" :key="data.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ calculateRowNumber(index) }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ data.account_name }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                  Company: {{ data.company_name ? data.company_name : '' }}
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
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ data.opening_balance || '' }}
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
              <td class="px-6 py-3 text-right text-sm font-medium relative">
                <div class="flex items-center justify-end gap-2">

                  <button @click="toggleStatus(data, 'status')"
                    class="p-1 rounded bg-gray-50 dark:bg-gray-500 hover:bg-gray-100 dark:hover:bg-gray-900/30 transition-colors"
                    title="Toggle Status">
                    <span :class="[
                      'relative inline-flex h-4 w-7 items-center rounded-full transition-colors duration-300',
                      data.status === 1 || data.status === true ? 'bg-green-500' : 'bg-gray-400 dark:bg-gray-600'
                    ]">
                      <span :class="[
                        'inline-block h-3 w-3 transform rounded-full bg-white transition-transform duration-300',
                        data.status === 1 || data.status === true ? 'translate-x-3.5' : 'translate-x-0.5'
                      ]"></span>
                    </span>
                  </button>

                  <button @click="viewAccount(data)"
                    class="text-cyan-600 hover:text-cyan-900 dark:text-cyan-400 dark:hover:text-cyan-300 p-1 rounded bg-blue-50 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors dark:bg-gray-500"
                    title="View">
                    <i class="fas fa-eye"></i>
                  </button>

                  <div class="relative inline-block text-left" v-click-outside="() => data.showDropdown = false">
                    <button @click="data.showDropdown = !data.showDropdown"
                      class="p-1.5 rounded-md bg-gray-50 dark:bg-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                      title="Actions">
                      <i class="fas fa-ellipsis-v text-gray-600 dark:text-gray-200"></i>
                    </button>

                    <div v-if="data.showDropdown" class="absolute right-0 z-[100] w-44 mt-2
                      origin-top-right rounded-md shadow-xl
                      bg-white dark:bg-gray-800
                      ring-1 ring-black ring-opacity-5
                      divide-y divide-gray-100 dark:divide-gray-700
                      focus:outline-none">

                      <div class="py-1">
                        <button @click="editAccount(data); data.showDropdown = false"
                          class="w-full px-4 py-2 text-left text-sm flex items-center gap-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                          <i class="fas fa-edit text-yellow-500"></i>
                          Edit
                        </button>

                        <button @click="confirmDelete(data); data.showDropdown = false"
                          class="w-full px-4 py-2 text-left text-sm flex items-center gap-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30">
                          <i class="fas fa-trash"></i>
                          Delete
                        </button>
                      </div>
                    </div>
                  </div>

                </div>
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

  <!-- Create/Edit Modal -->
  <BaseModal v-if="isModalOpen" :show="isModalOpen" :title="modalMode === 'create' ? 'Create Account' : 'Edit Account'"
    maxWidth="4xl" @close="closeModal">
    <form @submit.prevent="handleSubmit" class="px-6 py-3">
      <div class="grid grid-cols-1 md:grid-cols-1 2xl:grid-cols-1 gap-4">
        <!-- Company Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name *</label>
          <select v-model="formData.company_id" @change="clearValidationError('company_id')"
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': accountStore.validationErrors.company_id }">
            <option value="">--Select Company--</option>
            <option v-for="company in companies" :key="company.id" :value="company.id">
              {{ company.company_name }}
            </option>
          </select>
          <p v-if="accountStore.validationErrors.company_id" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ accountStore.validationErrors.company_id[0] }}
          </p>
        </div>

        <!-- Bank Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bank Name *</label>
          <select v-model="formData.bank_id" @change="clearValidationError('bank_id')"
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': accountStore.validationErrors.bank_id }">
            <option value="">--Select Bank--</option>
            <option v-for="bank in banks" :key="bank.id" :value="bank.id">
              {{ bank.bank_name }}
            </option>
          </select>
          <p v-if="accountStore.validationErrors.bank_id" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ accountStore.validationErrors.bank_id[0] }}
          </p>
        </div>

        <!-- Branch Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Branch Name *</label>
          <select v-model="formData.branch_id" @change="clearValidationError('branch_id')"
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': accountStore.validationErrors.branch_id }">
            <option value="">--Select Branch--</option>
            <option v-for="branch in branches" :key="branch.id" :value="branch.id">
              {{ branch.branch_name }}
            </option>
          </select>
          <p v-if="accountStore.validationErrors.branch_id" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ accountStore.validationErrors.branch_id[0] }}
          </p>
        </div>

        <!-- Account Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account Name *</label>
          <input v-model="formData.account_name" type="text" placeholder="Enter account name"
            @change="clearValidationError('account_name')"
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': accountStore.validationErrors.account_name }">
          <p v-if="accountStore.validationErrors.account_name" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ accountStore.validationErrors.account_name[0] }}
          </p>
        </div>

        <!-- Account Number -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account Number *</label>
          <input v-model="formData.account_number" type="text" placeholder="Enter account number"
            @change="clearValidationError('account_number')"
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-500': accountStore.validationErrors.account_number }">
          <p v-if="accountStore.validationErrors.account_number" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ accountStore.validationErrors.account_number[0] }}
          </p>
        </div>

        <!-- Cheque Book -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cheque Book</label>
          <input v-model="formData.cheque_book" type="text" placeholder="Enter cheque book"
            @change="clearValidationError('cheque_book')"
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white">
        </div>

        <!-- Opening Balance -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Opening Balance</label>
          <input v-model="formData.opening_balance" type="number" placeholder="Enter opening balance"
            @change="clearValidationError('opening_balance')"
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white">
          <p v-if="accountStore.validationErrors.opening_balance" class="text-xs text-red-600 dark:text-red-400 mt-1">
            {{ accountStore.validationErrors.opening_balance[0] }}
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
        <div v-if="Object.keys(accountStore.validationErrors).length > 0"
          class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg col-span-1 md:col-span-2 2xl:col-span-3">
          <p class="text-sm text-red-600 dark:text-red-400 font-medium mb-1">Please fix the following errors:</p>
          <ul class="text-xs text-red-500 dark:text-red-400 space-y-1">
            <li v-for="(errors, field) in accountStore.validationErrors" :key="field">
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
          <button type="submit" :disabled="accountStore.loading" :class="[
            'px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors',
            accountStore.loading ? 'bg-gray-400 cursor-not-allowed' : 'bg-gray-600 hover:bg-gray-700'
          ]">
            <span v-if="accountStore.loading">
              <i class="fas fa-spinner fa-spin mr-2"></i>
              {{ modalMode === 'create' ? 'Creating...' : 'Updating...' }}
            </span>
            <span v-else>{{ modalMode === 'create' ? 'Create Account' : 'Update Account' }}</span>
          </button>
        </div>
      </div>
    </form>
  </BaseModal>

  <!-- View Modal -->
  <BaseModal v-if="viewingAccount" :show="showViewModal" maxWidth="xl" title="Account Details" @close="closeViewModal">
    <div class="px-6 py-3">
      <div class="grid grid-cols-1 md:grid-cols-1 2xl:grid-cols-1 gap-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Company Name</label>
            <p class="text-sm text-gray-900 dark:text-white mt-1">{{ viewingAccount.company_name || 'N/A' }}</p>
          </div>
          <div>
            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Bank Name</label>
            <p class="text-sm text-gray-900 dark:text-white mt-1">{{ viewingAccount.bank_name || 'N/A' }}</p>
          </div>
          <div>
            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Branch Name</label>
            <p class="text-sm text-gray-900 dark:text-white mt-1">{{ viewingAccount.branch_name || 'N/A' }}</p>
          </div>
          <div>
            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Account Name</label>
            <p class="text-sm text-gray-900 dark:text-white mt-1">{{ viewingAccount.account_name }}</p>
          </div>
          <div>
            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Account Number</label>
            <p class="text-sm text-gray-900 dark:text-white mt-1">{{ viewingAccount.account_number }}</p>
          </div>
          <div>
            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Cheque Book</label>
            <p class="text-sm text-gray-900 dark:text-white mt-1">{{ viewingAccount.cheque_book || 'N/A' }}</p>
          </div>
          <div>
            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Opening Balance</label>
            <p class="text-sm text-gray-900 dark:text-white mt-1">{{ viewingAccount.opening_balance }}</p>
          </div>
          <div>
            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</label>
            <div class="mt-1">
              <span :class="[
                'px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                viewingAccount.status === 1 || viewingAccount.status === true
                  ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'
                  : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'
              ]">
                {{ viewingAccount.status === 1 || viewingAccount.status === true ? 'Active' : 'Inactive' }}
              </span>
            </div>
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
      <button @click="editAccount(viewingAccount)"
        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition-colors focus:outline-none focus:ring-1 focus:ring-gray-500">
        Edit Account
      </button>
    </div>
  </BaseModal>

  <!-- Delete Confirmation Modal -->
  <BaseModal :show="showDeleteModal" title="Delete Account" maxWidth="lg" :showClose="false" @close="closeDeleteModal">
    <div class="px-6 py-3">
      <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
          <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300 text-xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Delete Account</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
          Are you sure you want to delete <strong>{{ deletingAccount?.account_name }}</strong>? This action cannot be
          undone.
        </p>
      </div>
      <div class="flex justify-center space-x-3">
        <button @click="closeDeleteModal"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
          Cancel
        </button>
        <button @click="handleDelete" :disabled="accountStore.loading" :class="[
          'px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors',
          accountStore.loading ? 'bg-red-400 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700'
        ]">
          <span v-if="accountStore.loading">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            Deleting...
          </span>
          <span v-else>Delete Account</span>
        </button>
      </div>
    </div>
  </BaseModal>

  <!-- Status Change Confirmation Modal -->
  <BaseModal :show="showStatusChangeModal" title="Change Status" maxWidth="lg" :showClose="false"
    @close="closeStatusChangeModal">
    <div class="px-6 py-3">
      <div class="text-center">
        <div
          class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900 mb-4">
          <i class="fas fa-exchange-alt text-yellow-600 dark:text-yellow-300 text-xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Change Account Status</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
          Are you sure you want to change the status of
          <strong>{{ statusChangingAccount?.account_name }}</strong>
          to
          <strong>
            <span :class="[
              statusChangingAccount?.status === 1 || statusChangingAccount?.status === true
                ? 'text-red-600 dark:text-red-400'
                : 'text-green-600 dark:text-green-400'
            ]">
              {{ statusChangingAccount?.status === 1 || statusChangingAccount?.status === true ? 'Inactive' : 'Active'
              }}
            </span>
          </strong>?
        </p>
      </div>
      <div class="flex justify-center space-x-3">
        <button @click="closeStatusChangeModal"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
          Cancel
        </button>
        <button @click="confirmStatusChange" :disabled="accountStore.loading" :class="[
          'px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors',
          accountStore.loading ? 'bg-gray-400 cursor-not-allowed' : 'bg-gray-600 hover:bg-gray-700'
        ]">
          <span v-if="accountStore.loading">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            Updating...
          </span>
          <span v-else>Confirm Change</span>
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
const refresh = ref(false);


const tableColumns = ref([
  {
    key: 'account_name',
    label: 'Account',
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
    key: 'opening_balance',
    label: 'Opening Balance',
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
const showStatusChangeModal = ref(false); // New modal for status change

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
const statusChangingAccount = ref(null); // New variable for status change
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
  getData();
  loadRelatedData();
});

const getData = async () => {
  await accountStore.get();
};

const refreshData = async () => {
  refresh.value = true;
  try {
    clearSearch();
  } finally {
    refresh.value = false;
  }
}


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

const toggleStatus = (account, field) => {
  statusChangingAccount.value = account;
  showStatusChangeModal.value = true;
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

const closeStatusChangeModal = () => {
  showStatusChangeModal.value = false;
  statusChangingAccount.value = null;
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
    getData();
  }
};

const handleDelete = async () => {
  if (deletingAccount.value) {
    const result = await accountStore.deleteItem(deletingAccount.value.id);
    if (result.success) {
      closeDeleteModal();
      getData();
    }
  }
};

const confirmStatusChange = async () => {
  if (statusChangingAccount.value) {
    try {
      const field = 'status';
      await accountStore.toggleStatus(statusChangingAccount.value, field);
      closeStatusChangeModal();
      getData();
    } catch (error) {
      console.error("Error toggling status:", error);
    }
  }
};

// Pagination and sorting
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
