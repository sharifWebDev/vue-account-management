<template>
  <!-- Header -->
  <div class="mb-4">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Deleted Companies</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
          Manage permanently deleted companies or restore them
        </p>
      </div>
      <Breadcrumb />
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 p-3 rounded-lg dark:border dark:border-gray-700">
    <!-- Search -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-4 gap-4">
      <h6>Deleted Companies</h6>

      <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
        <div class="relative flex-grow max-w-md">
          <input v-model="searchQuery" @input="handleSearch" type="search" placeholder="Search deleted companies..."
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
            :sort-by="companyStore.sortBy"
            :sort-direction="companyStore.sortDirection"
            :show-actions-column="true"
            :show-sl-column="true"
            @sort="sortByColumn"
          />

          <!-- Loading -->
          <TableLoadingState
            v-if="companyStore.loading"
            :is-loading="true"
            item-name="companies"
            loading-text="Loading deleted companies..."
          />

          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Empty -->
            <TableLoadingState
              v-if="!companyStore.loading && companyStore.items.length === 0"
              :is-loading="false"
              :isEmpty="true"
              :has-search="!!searchQuery"
              item-name="deleted companies"
              empty-icon="fas fa-building"
              empty-title="No deleted companies"
              :empty-message="searchQuery
                ? 'No deleted companies match your search.'
                : 'There are no deleted companies.'"
              @clear-search="clearSearch"
            />

            <!-- Rows -->
            <tr
              v-else
              v-for="(data, index) in companyStore.items"
              :key="data.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700"
            >
              <td class="px-6 py-3 text-sm text-gray-500">
                {{ rowNumber(index) }}
              </td>

              <td class="px-6 py-3">
                <div class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ data.company_name }}
                </div>
              </td>

              <td class="px-6 py-3 text-sm text-gray-600">
                {{ data.email || '-' }}
              </td>

              <td class="px-6 py-3 text-sm text-gray-600">
                {{ data.phone || '-' }}
              </td>

              <td class="px-6 py-3 text-sm text-gray-600">
                {{ data.deleted_at ? formatDate(data.deleted_at) : 'N/A' }}
              </td>

              <td class="px-6 py-3">
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                  Deleted
                </span>
              </td>

              <td class="px-6 py-3 text-sm text-right space-x-2">
                <button
                  @click="confirmRestore(data)"
                  class="text-green-600 hover:text-green-800"
                >
                  <i class="fas fa-undo"></i>
                </button>

                <button
                  @click="confirmPermanentDelete(data)"
                  class="text-red-600 hover:text-red-800"
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
    <Pagination
      v-if="companyStore.totalItems > 0"
      :total-items="companyStore.totalItems"
      :per-page="companyStore.perPage"
      :current-page="companyStore.currentPage"
      :last-page="companyStore.lastPage"
      @change-page="handlePageChange"
      @change-per-page="handlePerPageChange"
    />
  </div>

  <!-- Restore Modal -->
  <BaseModal :show="showRestoreModal" title="Restore Company" @close="closeRestore">
    <p class="text-center">
      Restore <strong>{{ selectedCompany?.company_name }}</strong>?
    </p>
    <div class="flex justify-center mt-4 gap-3">
      <button @click="closeRestore" class="btn">Cancel</button>
      <button @click="handleRestore" class="btn bg-green-600 text-white">
        Restore
      </button>
    </div>
  </BaseModal>

  <!-- Permanent Delete Modal -->
  <BaseModal
    :show="showDeleteModal"
    title="Permanently Delete Company"
    @close="closeDelete"
  >
    <p class="text-center text-red-600">
      This action cannot be undone.
    </p>
    <div class="flex justify-center mt-4 gap-3">
      <button @click="closeDelete" class="btn">Cancel</button>
      <button @click="handlePermanentDelete" class="btn bg-red-600 text-white">
        Delete Permanently
      </button>
    </div>
  </BaseModal>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useCompanyStore } from "@/stores/companyStore";
import Breadcrumb from "@/components/core/Breadcrumb.vue";
import Pagination from "@/components/common/Pagination.vue";
import BaseModal from "@/components/common/BaseModal.vue";
import TableLoadingState from "@/components/core/TableLoadingState.vue";
import SortableTableHeader from "@/components/common/SortableTableHeader.vue";

const companyStore = useCompanyStore();

const searchQuery = ref("");
const showRestoreModal = ref(false);
const showDeleteModal = ref(false);
const selectedCompany = ref(null);

const tableColumns = [
  { key: "company_name", label: "Company Name", sortable: true },
  { key: "email", label: "Email" },
  { key: "phone", label: "Phone" },
  { key: "status", label: "Status" },
  { key: "deleted_at", label: "Deleted At" },
];

onMounted(() => {
  loadTrashed();
});

const loadTrashed = async (search = "") => {
  await companyStore.trashed({ search });
};

const handleSearch = () => {
  loadTrashed(searchQuery.value);
};

const clearSearch = () => {
  searchQuery.value = "";
  loadTrashed();
};

const rowNumber = (index) =>
  (companyStore.currentPage - 1) * companyStore.perPage + index + 1;

const formatDate = (d) =>
  d ? new Date(d).toLocaleString() : "-";

const sortByColumn = (col) => {
  companyStore.sort(col);
  loadTrashed(searchQuery.value);
};

const handlePageChange = (p) => {
  companyStore.paginate(p);
  loadTrashed(searchQuery.value);
};

const handlePerPageChange = (pp) => {
  companyStore.changePerPage(pp);
  loadTrashed(searchQuery.value);
};

// Restore
const confirmRestore = (item) => {
  selectedCompany.value = item;
  showRestoreModal.value = true;
};
const closeRestore = () => {
  showRestoreModal.value = false;
};
const handleRestore = async () => {
  await companyStore.restore(selectedCompany.value.id);
  closeRestore();
  loadTrashed();
};

// Permanent delete
const confirmPermanentDelete = (item) => {
  selectedCompany.value = item;
  showDeleteModal.value = true;
};
const closeDelete = () => {
  showDeleteModal.value = false;
};
const handlePermanentDelete = async () => {
  await companyStore.forceDelete(selectedCompany.value.id);
  closeDelete();
  loadTrashed();
};
</script>
