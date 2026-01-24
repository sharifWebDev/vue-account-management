<!-- 
QUICK REFERENCE: Use this template to create Bank.vue, Branch.vue, Company.vue, Transaction.vue, User.vue

Steps:
1. Copy this entire template
2. Replace all instances of:
   - "bank" with entity name (lowercase: account, branch, company, transaction, user)
   - "Bank" with entity name (PascalCase: Account, Branch, Company, Transaction, User)
   - "bank_name" with actual field name
   - "useBankStore" with appropriate store name
   - Form fields - replace with entity-specific fields
   - Table columns - replace with entity-specific columns

Key field mappings:
- Account: account_name, account_number, opening_balance
- Bank: bank_name, address, phone, mobile, fax, email, website
- Branch: branch_name
- Company: company_name, address, phone, mobile, email, website, logo
- Transaction: date, type, details, deposit, withdraw, receive_from, payment_to
- User: first_name, last_name, email, phone, mobile, user_type, image
-->

<template>
  <div class="p-6">
    <Breadcrumb />

    <div v-if="bankStore.loading && bankStore.items.length === 0" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-gray-600"></div>
      <p class="mt-4 text-gray-600 dark:text-gray-400">Loading banks...</p>
    </div>

    <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S/L
              </th>
              <!-- ADD TABLE HEADERS HERE -->
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="(data, index) in displayedItems" :key="data.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{
                calculateRowNumber(index) }}</td>
              <!-- ADD TABLE DATA CELLS HERE -->
              <td class="px-6 py-3 whitespace-nowrap">
                <span :class="[
                  'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                  data.status === 1 ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'
                    : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'
                ]">{{ data.status === 1 ? 'Active' : 'Inactive' }}</span>
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                <button @click="viewBank(data)"
                  class="text-cyan-600 hover:text-cyan-900 dark:text-cyan-400 dark:hover:text-cyan-300 p-1 rounded bg-blue-50 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors  dark:bg-gray-500 "
                  title="View">
                  <i class="fas fa-eye"></i>
                </button>
                <button @click="editBank(data)"
                  class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 p-1 rounded bg-yellow-50 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors dark:bg-gray-500 ""
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
            <tr v-if="displayedItems.length === 0 && !bankStore.loading" class="h-[55vh]">
              <td colspan="10" class="px-6 py-12 text-center">
                <div class="text-gray-500 dark:text-gray-400">
                  <i class="fas fa-inbox text-3xl mb-4"></i>
                  <p class="text-lg font-medium">No banks found</p>
                  <button @click="openCreateModal"
                    class="mt-4 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">Add
                    Bank</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="flex justify-between items-center mt-6 p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
      <div class="flex-1 flex items-center space-x-4">
        <div class="relative flex-1">
          <input type="text" v-model="searchQuery" @input="handleSearch" placeholder="Search banks..."
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
          <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </div>
      <button @click="openCreateModal"
        class="ml-4 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition-colors flex items-center space-x-2">
        <i class="fas fa-plus"></i>
        <span>Add Bank</span>
      </button>
    </div>

    <div v-if="bankStore.pagination.total > 0" class="mt-6">
      <Pagination :total-items="bankStore.pagination.total" :per-page="perPage" :current-page="currentPage"
        :last-page="bankStore.pagination.last_page" @change-page="goToPage" @change-per-page="handlePerPageChange" />
    </div>

    <!-- CREATE/EDIT MODAL -->
    <BaseModal v-if="isModalOpen" :show="isModalOpen" title="Bank Form" maxWidth="4xl" @close="closeModal">
      <form @submit.prevent="handleSubmit" class="px-6 py-3">
        <div class="grid grid-cols-1 md:grid-cols-1 2xl:grid-cols-1 gap-4">
          <!-- ADD FORM FIELDS HERE - EXAMPLE: -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bank Name *</label>
            <input v-model="formData.bank_name" type="text" placeholder="Enter bank name"
              @change="clearValidationError('bank_name')"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
              :class="{ 'border-red-500': bankStore.validationErrors.bank_name }">
            <p v-if="bankStore.validationErrors.bank_name" class="text-xs text-red-600 dark:text-red-400 mt-1">{{
              bankStore.validationErrors.bank_name[0] }}</p>
          </div>

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

          <div v-if="Object.keys(bankStore.validationErrors).length > 0"
            class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-600 dark:text-red-400 font-medium mb-1">Please fix the following errors:</p>
            <ul class="text-xs text-red-500 dark:text-red-400 space-y-1">
              <li v-for="(errors, field) in bankStore.validationErrors" :key="field">• {{ errors[0] }}</li>
            </ul>
          </div>

          <div class="mt-6 flex justify-end space-x-3">
            <button type="button" @click="closeModal"
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
            <button type="submit" :disabled="bankStore.loading"
              :class="['px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors', bankStore.loading ? 'bg-gray-400 cursor-not-allowed' : 'bg-gray-600 hover:bg-gray-700']">
              <span v-if="bankStore.loading"><i class="fas fa-spinner fa-spin mr-2"></i>{{ modalMode === 'create' ?
                'Creating...' : 'Updating...' }}</span>
              <span v-else>{{ modalMode === 'create' ? 'Create Bank' : 'Update Bank' }}</span>
            </button>
          </div>
        </div>
      </form>
    </BaseModal>

    <!-- VIEW MODAL -->
    <BaseModal v-if="viewingBank" :show="showViewModal" maxWidth="xl" title="Bank Details" @close="closeViewModal">
      <div class="px-6 py-3">
        <div class="grid grid-cols-1 md:grid-cols-1 2xl:grid-cols-1 gap-4">
          <!-- ADD VIEW FIELDS HERE -->
        </div>
      </div>
      <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
        <button type="button" @click="closeViewModal"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Close</button>
        <button @click="editBank(viewingBank)"
          class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition-colors">Edit
          Bank</button>
      </div>
    </BaseModal>

    <!-- DELETE MODAL -->
    <BaseModal :show="showDeleteModal" title="Delete Bank" maxWidth="lg" :showClose="false" @close="closeDeleteModal">
      <div class="px-6 py-3">
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300 text-xl"></i>
          </div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Delete Bank</h3>
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this bank? This
            action cannot be undone.</p>
        </div>
        <div class="flex justify-center space-x-3">
          <button @click="closeDeleteModal"
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
          <button @click="handleDelete" :disabled="bankStore.loading"
            :class="['px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors', bankStore.loading ? 'bg-red-400 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700']">
            <span v-if="bankStore.loading"><i class="fas fa-spinner fa-spin mr-2"></i>Deleting...</span>
            <span v-else>Delete Bank</span>
          </button>
        </div>
      </div>
    </BaseModal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useBankStore } from '@/stores/bankStore';
import BaseModal from '@/components/common/BaseModal.vue';
import Pagination from '@/components/common/Pagination.vue';
import Breadcrumb from '@/components/core/Breadcrumb.vue';

const bankStore = useBankStore();

const isModalOpen = ref(false);
const showViewModal = ref(false);
const showDeleteModal = ref(false);
const perPage = ref(10);
const currentPage = ref(1);
const searchQuery = ref('');

const formData = ref({
  bank_name: '',
  status: 1
  // ADD OTHER FIELDS HERE
});

const currentBank = ref(null);
const viewingBank = ref(null);
const deletingBank = ref(null);
const modalMode = ref('create');

const displayedItems = computed(() => {
  const items = Array.isArray(bankStore.filteredItems) ? bankStore.filteredItems :
    Array.isArray(bankStore.items) ? bankStore.items : [];
  return items.filter(item => item !== undefined && item !== null);
});

const calculateRowNumber = (index) => {
  if (bankStore.pagination.from && bankStore.pagination.from > 0) {
    return bankStore.pagination.from + index;
  }
  return (currentPage.value - 1) * perPage.value + index + 1;
};

const clearValidationError = (fieldName) => {
  if (bankStore.validationErrors[fieldName]) {
    const updatedErrors = { ...bankStore.validationErrors };
    delete updatedErrors[fieldName];
    bankStore.validationErrors = updatedErrors;
  }
};

const clearAllValidationErrors = () => {
  bankStore.validationErrors = {};
};

onMounted(() => get());

const get = () => {
  const params = { page: currentPage.value, per_page: perPage.value };
  if (searchQuery.value) params.search = searchQuery.value;
  bankStore.get(params);
};

const openCreateModal = () => {
  resetForm();
  modalMode.value = 'create';
  isModalOpen.value = true;
  clearAllValidationErrors();
};

const editBank = (bank) => {
  currentBank.value = bank;
  modalMode.value = 'edit';
  formData.value = { ...bank };
  isModalOpen.value = true;
  clearAllValidationErrors();
};

const viewBank = (bank) => {
  viewingBank.value = bank;
  showViewModal.value = true;
};

const confirmDelete = (bank) => {
  deletingBank.value = bank;
  showDeleteModal.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  resetForm();
  clearAllValidationErrors();
};

const closeViewModal = () => {
  showViewModal.value = false;
  viewingBank.value = null;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  deletingBank.value = null;
};

const resetForm = () => {
  formData.value = {
    bank_name: '',
    status: 1
    // RESET OTHER FIELDS HERE
  };
  currentBank.value = null;
};

const handleSubmit = async () => {
  clearAllValidationErrors();
  let result;
  if (modalMode.value === 'create') {
    result = await bankStore.create(formData.value);
  } else {
    result = await bankStore.update(currentBank.value.id, formData.value);
  }
  if (result && result.success) {
    closeModal();
    get();
  }
};

const handleDelete = async () => {
  if (deletingBank.value) {
    const result = await bankStore.delete(deletingBank.value.id);
    if (result.success) {
      closeDeleteModal();
      get();
    }
  }
};

const handlePerPageChange = (newPerPage) => {
  perPage.value = newPerPage;
  currentPage.value = 1;
  get();
};

const goToPage = (page) => {
  currentPage.value = page;
  get();
};

const handleSearch = () => {
  currentPage.value = 1;
  get();
};
</script>

<style scoped>
input,
select,
textarea {
  transition: border-color 0.3s ease, background-color 0.3s ease;
}
</style>
