<template>
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center justify-content-between">
        <div class="col">
          <div class="page-pretitle">Overview</div>
          <h2 class="page-title">Items</h2>
        </div>
        <div class="col-auto">
          <button
            @click="handleAdd"
 class="btn btn-gray btn-md me-2"
            :disabled="loadingBtn.add"
          >
            <span v-if="loadingBtn.add">
              <span class="spinner-border spinner-border-sm"></span> Add Item
            </span>
            <span v-else>Add Item</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="page-body item">
    <div class="container-xl">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h3 class="card-title">Items List</h3>
          <div class="d-flex gap-3">
            <input
              v-model="searchQuery"
              @input="applyFilters"
              placeholder="Search..."
              class="form-control"
            />
          </div>
        </div>

        <div v-if="loading" class="loaderw">
          <tableSkeleton />
        </div>

        <div v-else>
          <table class="table table-vcenter card-table datatable">
            <thead>
              <tr>
                <th>icon</th>
                <th>menu_title</th>
                <th>menu_subtitle</th>
                <th>title</th>
                <th>description</th>
                <th>Subdescription</th>
                <th>quick_tips</th>
                <th>priority</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in paginatedItems" :key="product.id">
                <td>{{ product.icon }}</td>
                <td>{{ product.menu_title }}</td>
                <td>{{ product.menu_subtitle }}</td>
                <td>{{ product.title }}</td>
                <td>{{ product.description }}</td>
                <td>{{ product.subdescription }}</td>
                <td>{{ product.quick_tips }}</td>
                <td>{{ product.priority }}</td>
                <td>
                  <button
                    @click="handleView(product.id)"
                    class="btn btn-info btn-sm me-2"
                    :disabled="loadingBtn.view === product.id"
                  >
                    <span v-if="loadingBtn.view === product.id">
                      <span class="spinner-border spinner-border-sm"></span>
                      View
                    </span>
                    <span v-else>View</span>
                  </button>

                  <router-link
                    :to="{ name: 'EditItem', params: { id: product.id } }"
 class="btn btn-gray btn-sm me-2"
                  >
                    Edit
                  </router-link>

                  <button
                    @click="handleDelete(product.id)"
                    class="btn btn-danger btn-sm"
                    :disabled="loadingBtn.delete === product.id"
                  >
                    <span v-if="loadingBtn.delete === product.id">
                      <span class="spinner-border spinner-border-sm"></span>
                      Delete
                    </span>
                    <span v-else>Delete</span>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Use the Pagination component -->
          <Pagination
            v-model:currentPage="currentPage"
            :total-items="totalItems"
            v-model:perPage="perPage"
            :pagination-links="paginationLinks"
            @change-page="changePage"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useRouter } from "vue-router";
import tableSkeleton from "@/components/core/Skeleton.vue";
import Pagination from "@/components/Pagination.vue";
import { fetchItemAPI, deleteItemAPI } from "@/services/ItemService.js";
import { confirmDelete, showAlert } from "@/utils/useAlert.js";


const router = useRouter();
const loading = ref(false);
const searchQuery = ref("");
const products = ref([]);
const currentPage = ref(1);
const perPage = ref(10);
const totalItems = ref(0);
const paginationLinks = ref({
  first: null,
  prev: null,
  next: null,
  last: null,
  pages: [],
});
const loadingBtn = ref({
  add: false,
  delete: null,
  view: null,
  edit: null,
});

onMounted(() => {
  fetchProducts();
});

const fetchProducts = async () => {
  loading.value = true;
  try {
    const response = await fetchItemAPI({
      page: currentPage.value,
      search: searchQuery.value,
      length: perPage.value,
    });

    products.value = response.data;
    totalItems.value = response.meta.total;

    // Update pagination links
    paginationLinks.value = {
      first: response.links.first ? 1 : null,
      prev: response.links.prev ? currentPage.value - 1 : null,
      next: response.links.next ? currentPage.value + 1 : null,
      last: response.links.last ? response.meta.last_page : null,
      pages: generatePageNumbers(
        response.meta.current_page,
        response.meta.last_page
      ),
    };
  } catch (error) {
    console.error("Error fetching products:", error);
  } finally {
    loading.value = false;
  }
};

const generatePageNumbers = (currentPage, lastPage) => {
  const pages = [];
  const maxPagesToShow = 5;

  let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
  let endPage = Math.min(lastPage, startPage + maxPagesToShow - 1);

  if (endPage - startPage < maxPagesToShow - 1) {
    startPage = Math.max(1, endPage - maxPagesToShow + 1);
  }

  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }

  return pages;
};

const paginatedItems = computed(() => products.value);

const handleAdd = async () => {
  loadingBtn.value.add = true;
  try {
    await router.push("/item/add");
  } finally {
    loadingBtn.value.add = false;
  }
};

const handleDelete = async (productId) => {
  loadingBtn.value.delete = productId;

  const isConfirmed = await confirmDelete(productId);
  if (isConfirmed) {
    await deleteItemAPI(productId);
    showAlert("success", "Deleted!", "Your item has been deleted.");
  }

  fetchProducts(); // Refresh the list
  loadingBtn.value.delete = null;
};

const handleView = async (productId) => {
  loadingBtn.value.view = productId;
  try {
    await router.push(`/item/${productId}`);
  } finally {
    setTimeout(() => {
      loadingBtn.value.view = null;
    }, 500);
  }
};

watch([currentPage, perPage], () => {
  fetchProducts();
});

const applyFilters = () => {
  currentPage.value = 1;
  fetchProducts();
};

const changePage = (page) => {
  if (page >= 1 && page <= Math.ceil(totalItems.value / perPage.value)) {
    currentPage.value = page;
    fetchProducts();
  }
};
</script>
