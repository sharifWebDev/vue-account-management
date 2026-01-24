<template>
  <div class="mb-4">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-lg font-semibold text-gray-700 dark:text-white">
          Deleted Banks
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
          Restore or permanently delete banks
        </p>
      </div>
      <Breadcrumb />
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 p-3 rounded-lg dark:border dark:border-gray-700">
    <!-- Search -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-4 gap-4">
      <h6>Deleted Banks</h6>
      <div class="relative flex-grow max-w-md">
        <input
          v-model="searchQuery"
          @input="handleSearch"
          type="search"
          placeholder="Search deleted banks..."
          class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-1 focus:ring-gray-500
                 focus:border-gray-500 outline-none transition-all focus:shadow-sm"
        />
        <div class="absolute left-3 top-2.5 text-gray-400">
          <i class="fas fa-search text-base"></i>
        </div>
      </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 relative">
          <SortableTableHeader
            :columns="tableColumns"
            :sort-by="bankStore.sortBy"
            :sort-direction="bankStore.sortDirection"
            :show-actions-column="true"
            :show-sl-column="true"
            @sort="sortByColumn"
          />

          <!-- Loading State -->
          <TableLoadingState
            v-if="bankStore.loading"
            :is-loading="true"
            :total-items="bankStore.totalItems"
            :item-name="'banks'"
            loading-text="Loading deleted banks..."
            loading-subtext="Fetching your deleted bank data"
          />

          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Empty State -->
            <TableLoadingState
              v-if="bankStore.items.length === 0 && !bankStore.loading"
              :is-loading="false"
              :isEmpty="true"
              :has-search="!!searchQuery"
              :item-name="'deleted banks'"
              empty-icon="fas fa-trash"
              empty-title="No deleted banks"
              :empty-message="searchQuery
                ? 'No deleted banks match your search criteria.'
                : 'There are no deleted banks to display.'"
              @clear-search="clearSearch"
            />

            <!-- Data Rows -->
            <tr
              v-else
              v-for="(bank, index) in displayedItems"
              :key="bank.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
            >
              <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ calculateRowNumber(index) }}
              </td>
              <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">
                {{ bank.bank_name }}
                <div v-if="bank.deleted_at" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                  Deleted: {{ formatDate(bank.deleted_at) }}
                </div>
              </td>
              <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-300">
                {{ bank.address || 'N/A' }}
              </td>
              <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-300">
                {{ bank.phone || 'N/A' }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap">
                <span
                  class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300"
                >
                  {{ bank.deleted_at ? 'Deleted' : 'Active' }}
                </span>
              </td>
              <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-300">
                {{ formatDate(bank.deleted_at) }}
              </td>
              <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                <button
                  @click="confirmRestore(bank)"
                  :disabled="bankStore.loading"
                  class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 p-1 rounded bg-green-50 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                  title="Restore"
                >
                  <i class="fas fa-undo"></i>
                </button>
                <button
                  @click="confirmForceDelete(bank)"
                  :disabled="bankStore.loading"
                  class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1 rounded bg-red-50 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                  title="Permanently Delete"
                >
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="bankStore.totalItems > 0" class="mt-6">
      <Pagination
        :total-items="bankStore.totalItems"
        :per-page="bankStore.perPage"
        :current-page="bankStore.currentPage"
        :last-page="bankStore.lastPage"
        :is-loading="bankStore.isLoading"
        @change-page="handlePageChange"
        @change-per-page="handlePerPageChange"
      />
    </div>
  </div>

  <!-- Restore Modal -->
  <BaseModal :show="showRestoreModal" title="Restore Bank" maxWidth="lg" @close="closeRestoreModal">
    <div class="px-6 py-3 text-center">
      <div
        class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 mb-4"
      >
        <i class="fas fa-undo text-green-600 dark:text-green-300 text-xl"></i>
      </div>
      <p>
        Are you sure you want to restore <strong>{{ restoringBank?.bank_name }}</strong>?
      </p>
      <div class="flex justify-center mt-4 gap-3">
        <button @click="closeRestoreModal" class="btn border">Cancel</button>
        <button
          @click="handleRestore"
          class="btn bg-green-600 text-white"
          :disabled="bankStore.loading"
        >
          Restore
        </button>
      </div>
    </div>
  </BaseModal>

  <!-- Force Delete Modal -->
  <BaseModal
    :show="showDeleteModal"
    title="Permanently Delete Bank"
    maxWidth="lg"
    @close="closeForceDeleteModal"
  >
    <div class="px-6 py-3 text-center">
      <div
        class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4"
      >
        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300 text-xl"></i>
      </div>
      <p>
        Are you sure you want to permanently delete <strong>{{ forceDeletingBank?.bank_name }}</strong>?
        This action <span class="font-bold text-red-600">cannot</span> be undone.
      </p>
      <div class="flex justify-center mt-4 gap-3">
        <button @click="closeForceDeleteModal" class="btn border">Cancel</button>
        <button
          @click="handleForceDelete"
          class="btn bg-red-600 text-white"
          :disabled="bankStore.loading"
        >
          Delete Permanently
        </button>
      </div>
    </div>
  </BaseModal>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useBankStore } from '@/stores/bankStore'
import BaseModal from '@/components/common/BaseModal.vue'
import Pagination from '@/components/common/Pagination.vue'
import Breadcrumb from '@/components/core/Breadcrumb.vue'
import TableLoadingState from '@/components/core/TableLoadingState.vue'
import SortableTableHeader from '@/components/common/SortableTableHeader.vue'

const bankStore = useBankStore()

const searchQuery = ref('')
const showRestoreModal = ref(false)
const showDeleteModal = ref(false)
const restoringBank = ref(null)
const forceDeletingBank = ref(null)

let searchTimeout = null

const tableColumns = ref([
  { key: 'bank_name', label: 'Bank Name', sortable: true, width: '200px' },
  { key: 'address', label: 'Address', sortable: true },
  { key: 'phone', label: 'Phone', sortable: true },
  { key: 'status', label: 'Status', sortable: true, width: '100px' },
  { key: 'deleted_at', label: 'Deleted At', sortable: true, width: '200px' }
])

const displayedItems = computed(() => bankStore.items || [])

const calculateRowNumber = (index) =>
  (bankStore.currentPage && bankStore.perPage)
    ? (bankStore.currentPage - 1) * bankStore.perPage + index + 1
    : index + 1

const formatDate = (dateString) =>
  dateString
    ? new Date(dateString).toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })
    : 'N/A'

const loadTrashed = async () => {
  try {
    await bankStore.trashed({ search: searchQuery.value })
  } catch (err) {
    console.error(err)
  }
}

const handleSearch = () => {
  if (searchTimeout) clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => loadTrashed(), 500)
}

const clearSearch = () => {
  searchQuery.value = ''
  loadTrashed()
}

watch(searchQuery, (val) => {
  if (val === '') {
    if (searchTimeout) clearTimeout(searchTimeout)
    loadTrashed()
  }
})

onMounted(() => {
  loadTrashed()
})

// Restore
const confirmRestore = (bank) => {
  restoringBank.value = bank
  showRestoreModal.value = true
}
const closeRestoreModal = () => {
  showRestoreModal.value = false
  restoringBank.value = null
}
const handleRestore = async () => {
  if (!restoringBank.value) return
  await bankStore.restore(restoringBank.value.id)
  closeRestoreModal()
  loadTrashed()
}

// Force Delete
const confirmForceDelete = (bank) => {
  forceDeletingBank.value = bank
  showDeleteModal.value = true
}
const closeForceDeleteModal = () => {
  showDeleteModal.value = false
  forceDeletingBank.value = null
}
const handleForceDelete = async () => {
  if (!forceDeletingBank.value) return
  await bankStore.forceDelete(forceDeletingBank.value.id)
  closeForceDeleteModal()
  loadTrashed()
}

// Pagination & Sorting
const handlePerPageChange = async (newPerPage) => {
  await bankStore.changePerPage(newPerPage)
  loadTrashed()
}
const handlePageChange = async (page) => {
  await bankStore.paginate(page)
  loadTrashed()
}
const sortByColumn = async (column) => {
  const order = bankStore.sortDirection === 'asc' ? 'desc' : 'asc'
  await bankStore.sort(column, order)
  loadTrashed()
}
</script>
