<template>
  <div class="mb-4">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Deleted Branches</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
          Manage permanently deleted branches or restore them
        </p>
      </div>
      <Breadcrumb />
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 p-3 rounded-lg dark:border dark:border-gray-700">
    <!-- Search -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-4 gap-4">
      <h6>Deleted Branches</h6>
      <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
        <div class="relative flex-grow max-w-md">
          <input v-model="searchQuery" @input="handleSearch" type="search" placeholder="Search by branch name..."
            class="w-full pl-4 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-400 dark:bg-gray-700 dark:text-white" />
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
          <SortableTableHeader :columns="tableColumns" :sort-by="branchStore.sortBy"
            :sort-direction="branchStore.sortDirection" :show-actions-column="true" :show-sl-column="true"
            @sort="sortByColumn" />

          <!-- Loading State -->
          <TableLoadingState v-if="branchStore.loading" :is-loading="true" :total-items="branchStore.totalItems"
            :item-name="'branches'" loading-text="Loading deleted branches..."
            loading-subtext="Fetching your deleted branch data" />

          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Empty State -->
            <TableLoadingState v-if="branchStore.items.length === 0" :is-loading="false" :isEmpty="true"
              :has-search="!!searchQuery" :item-name="'deleted branches'" empty-icon="fas fa-code-branch"
              empty-title="No deleted branches"
              :empty-message="searchQuery ? 'No deleted branches match your search criteria.' : 'There are no deleted branches to display.'"
              @clear-search="clearSearch" />

            <!-- Branch rows -->
            <tr v-else v-for="(data, index) in displayedItems" :key="data.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ calculateRowNumber(index) }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ data.bank_name }}</div>
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ data.address }}</div>
              </td>
              <td class="px-6 py-3">
                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ data.branch_name }}</div>
                <div v-if="data.deleted_at" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                  Deleted: {{ formatDate(data.deleted_at) }}
                </div>
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <span :class="[
                  data.deleted_at ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                  'px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full'
                ]">
                  {{ data.deleted_at ? 'Deleted' : 'Active' }}
                </span>
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                {{ formatDate(data.deleted_at) }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                <button @click="confirmRestore(data)" :disabled="branchStore.loading"
                  class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 p-1 rounded bg-green-50 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                  title="Restore">
                  <i class="fas fa-undo"></i>
                </button>
                <button @click="confirmPermanentDelete(data)" :disabled="branchStore.loading"
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

    <!-- Pagination -->
    <div v-if="branchStore.totalItems > 0" class="mt-6">
      <Pagination :total-items="branchStore.totalItems" :per-page="branchStore.perPage"
        :current-page="branchStore.currentPage" :last-page="branchStore.lastPage" :is-loading="branchStore.loading"
        @change-page="handlePageChange" @change-per-page="handlePerPageChange" />
    </div>
  </div>

  <!-- Restore Modal -->
  <BaseModal :show="showRestoreModal" title="Restore Branch" maxWidth="lg" @close="closeRestoreModal">
    <div class="px-6 py-3 text-center">
      <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 mb-4">
        <i class="fas fa-undo text-green-600 dark:text-green-300 text-xl"></i>
      </div>
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Restore Branch</h3>
      <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
        Are you sure you want to restore <strong>{{ restoringBranch?.branch_name }}</strong>?
        This will make the branch active again in the system.
      </p>
      <div class="flex justify-center space-x-3">
        <button @click="closeRestoreModal" :disabled="branchStore.loading"
          class="px-4 py-2 border rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
          Cancel
        </button>
        <button @click="handleRestore" :disabled="branchStore.loading"
          :class="['px-4 py-2 rounded-lg text-white transition-colors', branchStore.loading ? 'bg-green-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700']">
          <span v-if="branchStore.loading">
            <i class="fas fa-spinner fa-spin mr-2"></i> Restoring...
          </span>
          <span v-else>Restore Branch</span>
        </button>
      </div>
    </div>
  </BaseModal>

  <!-- Permanent Delete Modal -->
  <BaseModal :show="showPermanentDeleteModal" title="Permanently Delete Branch" maxWidth="lg"
    @close="closePermanentDeleteModal">
    <div class="px-6 py-3 text-center">
      <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300 text-xl"></i>
      </div>
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Permanently Delete Branch</h3>
      <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
        Are you sure you want to permanently delete <strong>{{ permanentDeletingBranch?.branch_name }}</strong>?
        This action <span class="font-bold text-red-600">cannot</span> be undone.
      </p>
      <div class="flex justify-center space-x-3">
        <button @click="closePermanentDeleteModal" :disabled="branchStore.loading"
          class="px-4 py-2 border rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
          Cancel
        </button>
        <button @click="handlePermanentDelete" :disabled="branchStore.loading"
          :class="['px-4 py-2 rounded-lg text-white transition-colors', branchStore.loading ? 'bg-red-400 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700']">
          <span v-if="branchStore.loading">
            <i class="fas fa-spinner fa-spin mr-2"></i> Deleting...
          </span>
          <span v-else>Delete Permanently</span>
        </button>
      </div>
    </div>
  </BaseModal>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useBranchStore } from "@/stores/branchStore";
import BaseModal from "@/components/common/BaseModal.vue";
import Pagination from "@/components/common/Pagination.vue";
import Breadcrumb from "@/components/core/Breadcrumb.vue";
import TableLoadingState from "@/components/core/TableLoadingState.vue";
import SortableTableHeader from "@/components/common/SortableTableHeader.vue";

// Stores
const branchStore = useBranchStore();

// Columns
const tableColumns = ref([
  { key: "branch_name", label: "Branch Name", sortable: true },
  { key: "bank_name", label: "Bank", sortable: true },
  { key: "address", label: "Address", sortable: true },
  { key: "status", label: "Status", sortable: true },
  { key: "deleted_at", label: "Deleted At", sortable: true },
]);

// State
const searchQuery = ref("");
const showRestoreModal = ref(false);
const showPermanentDeleteModal = ref(false);
const restoringBranch = ref(null);
const permanentDeletingBranch = ref(null);

const displayedItems = computed(() => branchStore.items || []);

// Row number
const calculateRowNumber = (index) => (branchStore.currentPage - 1) * branchStore.perPage + index + 1;

// Date format
const formatDate = (dateString) => {
  if (!dateString) return "N/A";
  const date = new Date(dateString);
  return date.toLocaleDateString("en-US", { year: "numeric", month: "short", day: "numeric" });
};

// Load data
const loadTrashedBranches = async () => {
  try {
    await branchStore.trashed({ search: searchQuery.value });
  } catch (error) {
    console.error("Error loading trashed branches:", error);
  }
};

onMounted(loadTrashedBranches);

// Search
let searchTimeout = null;
const handleSearch = () => {
  if (searchTimeout) clearTimeout(searchTimeout);
  searchTimeout = setTimeout(loadTrashedBranches, 500);
};
const clearSearch = () => {
  searchQuery.value = "";
  loadTrashedBranches();
};

// Restore
const confirmRestore = (branch) => { restoringBranch.value = branch; showRestoreModal.value = true; };
const closeRestoreModal = () => { showRestoreModal.value = false; restoringBranch.value = null; };
const handleRestore = async () => {
  if (!restoringBranch.value) return;
  try {
    const result = await branchStore.restore(restoringBranch.value.id);
    if (result?.success) { closeRestoreModal(); await loadTrashedBranches(); }
  } catch (error) { console.error(error); }
};

// Permanent delete
const confirmPermanentDelete = (branch) => { permanentDeletingBranch.value = branch; showPermanentDeleteModal.value = true; };
const closePermanentDeleteModal = () => { showPermanentDeleteModal.value = false; permanentDeletingBranch.value = null; };
const handlePermanentDelete = async () => {
  if (!permanentDeletingBranch.value) return;
  try {
    const result = await branchStore.forceDelete(permanentDeletingBranch.value.id);
    if (result?.success) { closePermanentDeleteModal(); await loadTrashedBranches(); }
  } catch (error) { console.error(error); }
};

// Pagination & sorting
const handlePerPageChange = async (perPage) => { await branchStore.changePerPage(perPage); await loadTrashedBranches(); };
const handlePageChange = async (page) => { await branchStore.paginate(page); await loadTrashedBranches(); };
const sortByColumn = async (column) => {
  const order = branchStore.sortDirection === "asc" ? "desc" : "asc";
  await branchStore.sort(column, order);
  await loadTrashedBranches();
};
</script>

<style scoped>
input,
select,
textarea {
  transition: border-color 0.3s ease, background-color 0.3s ease;
}
</style>
