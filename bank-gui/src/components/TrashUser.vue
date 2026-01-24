<template>
  <div class="mb-4">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Deleted Users</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage permanently deleted users or restore them</p>
      </div>
      <Breadcrumb />
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 p-3 rounded-lg dark:border dark:border-gray-700">
    <!-- Search -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-4 gap-4">
      <div></div>
      <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
        <div class="relative flex-grow max-w-md">
          <input v-model="searchQuery" @input="handleSearch" type="search"
            placeholder="Search deleted users..."
            class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-1 focus:ring-gray-500 focus:border-gray-500 outline-none transition-all focus:shadow-sm" />
          <div class="absolute left-3 top-2.5 text-gray-400">
            <i class="fas fa-search text-base"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <SortableTableHeader
            :columns="tableColumns"
            :sort-by="userStore.sortBy"
            :sort-direction="userStore.sortDirection"
            :show-actions-column="true"
            :show-sl-column="true"
            @sort="sortByColumn" />

          <!-- Loading State -->
          <TableLoadingState v-if="userStore.loading"
            :is-loading="true"
            :total-items="userStore.totalItems"
            :item-name="'users'"
            loading-text="Loading deleted users..."
            loading-subtext="Fetching your deleted user data" />

          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Empty State -->
            <TableLoadingState v-if="userStore.items.length === 0 && !userStore.loading"
              :is-loading="false"
              :isEmpty="true"
              :has-search="!!searchQuery"
              :item-name="'deleted users'"
              empty-icon="fas fa-users-slash"
              empty-title="No deleted users"
              :empty-message="searchQuery ? 'No deleted users match your search criteria.' : 'There are no deleted users to display.'"
              @clear-search="clearSearch" />

            <tr v-else v-for="(user, index) in displayedItems" :key="user.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ calculateRowNumber(index) }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ user.first_name }} {{ user.last_name }}
                </div>
                <div v-if="user.email" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                  {{ user.email }}
                </div>
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ user.phone || '-' }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ user.user_type || 'N/A' }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                  {{ user.deleted_at ? 'Deleted' : 'Active' }}
                </span>
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ formatDate(user.deleted_at) }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                <button @click="confirmRestore(user)" :disabled="userStore.loading"
                  class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 p-1 rounded bg-green-50 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                  title="Restore">
                  <i class="fas fa-undo"></i>
                </button>
                <button @click="confirmPermanentDelete(user)" :disabled="userStore.loading"
                  class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1 rounded bg-red-50 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                  title="Permanently Delete">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="userStore.totalItems > 0" class="mt-6">
      <Pagination
        :total-items="userStore.totalItems"
        :per-page="userStore.perPage"
        :current-page="userStore.currentPage"
        :last-page="userStore.lastPage"
        :is-loading="userStore.loading"
        @change-page="handlePageChange"
        @change-per-page="handlePerPageChange" />
    </div>
  </div>

  <!-- Restore Modal -->
  <BaseModal :show="showRestoreModal" title="Restore User" maxWidth="lg" @close="closeRestoreModal">
    <div class="px-6 py-3 text-center">
      <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 mb-4">
        <i class="fas fa-undo text-green-600 dark:text-green-300 text-xl"></i>
      </div>
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Restore User</h3>
      <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
        Are you sure you want to restore <strong>{{ restoringUser?.first_name }} {{ restoringUser?.last_name }}</strong>?
      </p>
      <div class="flex justify-center space-x-3">
        <button @click="closeRestoreModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
          Cancel
        </button>
        <button @click="handleRestore" :disabled="userStore.loading" class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors">
          Restore User
        </button>
      </div>
    </div>
  </BaseModal>

  <!-- Permanent Delete Modal -->
  <BaseModal :show="showPermanentDeleteModal" title="Permanently Delete User" maxWidth="lg" @close="closePermanentDeleteModal">
    <div class="px-6 py-3 text-center">
      <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300 text-xl"></i>
      </div>
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Permanently Delete User</h3>
      <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
        Are you sure you want to permanently delete <strong>{{ permanentDeletingUser?.first_name }} {{ permanentDeletingUser?.last_name }}</strong>?
      </p>
      <div class="flex justify-center space-x-3">
        <button @click="closePermanentDeleteModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
          Cancel
        </button>
        <button @click="handlePermanentDelete" :disabled="userStore.loading" class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors">
          Delete Permanently
        </button>
      </div>
    </div>
  </BaseModal>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useUserStore } from '@/stores/userStore';
import Breadcrumb from '@/components/core/Breadcrumb.vue';
import Pagination from '@/components/common/Pagination.vue';
import BaseModal from '@/components/common/BaseModal.vue';
import TableLoadingState from '@/components/core/TableLoadingState.vue';
import SortableTableHeader from '@/components/common/SortableTableHeader.vue';

const userStore = useUserStore();

const searchQuery = ref('');

// Modal states
const showRestoreModal = ref(false);
const showPermanentDeleteModal = ref(false);
const restoringUser = ref(null);
const permanentDeletingUser = ref(null);

const tableColumns = ref([
  { key: 'first_name', label: 'Name', sortable: true },
  // { key: 'email', label: 'Email', sortable: true },
  { key: 'phone', label: 'Phone', sortable: true },
  { key: 'user_type', label: 'User Type', sortable: true },
  { key: 'status', label: 'Status', sortable: true },
  { key: 'deleted_at', label: 'Deleted At', sortable: true },
]);

const displayedItems = computed(() => userStore.items || []);

const calculateRowNumber = (index) => {
  if (userStore.currentPage && userStore.perPage) {
    return (userStore.currentPage - 1) * userStore.perPage + index + 1;
  }
  return index + 1;
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

// Fetch trashed users
const loadTrashedUsers = async (search = '') => {
  await userStore.trashed({ search });
};

// Search handlers
let searchTimeout = null;
const handleSearch = () => {
  if (searchTimeout) clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => loadTrashedUsers(searchQuery.value), 500);
};
const clearSearch = () => { searchQuery.value = ''; loadTrashedUsers(); };

// Restore handlers
const confirmRestore = (user) => { restoringUser.value = user; showRestoreModal.value = true; };
const closeRestoreModal = () => { showRestoreModal.value = false; restoringUser.value = null; };
const handleRestore = async () => { 
  if (!restoringUser.value) return; 
  await userStore.restore(restoringUser.value.id); 
  closeRestoreModal(); 
  loadTrashedUsers();
};

// Permanent delete handlers
const confirmPermanentDelete = (user) => { permanentDeletingUser.value = user; showPermanentDeleteModal.value = true; };
const closePermanentDeleteModal = () => { showPermanentDeleteModal.value = false; permanentDeletingUser.value = null; };
const handlePermanentDelete = async () => { 
  if (!permanentDeletingUser.value) return; 
  await userStore.forceDelete(permanentDeletingUser.value.id); 
  closePermanentDeleteModal(); 
  loadTrashedUsers();
};

// Pagination & sorting
const handlePageChange = async (page) => { await userStore.paginate(page); loadTrashedUsers(searchQuery.value); };
const handlePerPageChange = async (perPage) => { await userStore.changePerPage(perPage); loadTrashedUsers(searchQuery.value); };
const sortByColumn = async (column) => { 
  const order = userStore.sortDirection === 'asc' ? 'desc' : 'asc'; 
  await userStore.sort(column, order); 
  loadTrashedUsers(searchQuery.value);
};

onMounted(() => { loadTrashedUsers(); });
</script>
