import { defineStore } from "pinia";
import {
  fetchProductsAPI,
  addProductAPI,
  updateProductAPI,
  deleteProductAPI,
} from "@/services/productService";

export const useProductStore = defineStore("productStore", {
  state: () => ({
    products: [],
    searchQuery: "",
    selectedCategory: "",
    categories: [],
    currentPage: 1,
    perPage: 10,
    totalProducts: 0,
    loading: false,
  }),

  getters: {
    filteredProducts: (state) => {
      let filtered = state.products || [];
      if (state.searchQuery) {
        filtered = filtered.filter((product) =>
          product.title.toLowerCase().includes(state.searchQuery.toLowerCase())
        );
      }
      if (state.selectedCategory) {
        filtered = filtered.filter(
          (product) => product.category === state.selectedCategory
        );
      }
      return filtered;
    },

    totalPages: (state) => {
      return Math.ceil(state.filteredProducts.length / state.perPage);
    },

    paginatedProducts: (state) => {
      const startIndex = (state.currentPage - 1) * state.perPage;
      const endIndex = startIndex + state.perPage;
      return state.filteredProducts.slice(startIndex, endIndex);
    },

    totalFilteredProducts: (state) => state.filteredProducts.length,
    from: (state) => (state.currentPage - 1) * state.perPage + 1,
    to: (state) =>
      Math.min(
        state.currentPage * state.perPage,
        state.filteredProducts.length
      ),
  },

  actions: {
    async fetchProducts(page = 1) {
      this.loading = true;
      this.currentPage = page; // Set current page
      try {
        await new Promise((resolve) => setTimeout(resolve, 500)); // Simulate API delay
        const response = await fetchProductsAPI();
        this.products = response.data.data || [];
        this.totalProducts = response.data.total || 0; // Store the total count of products
      } catch (error) {
        console.error("Error fetching products:", error);
      } finally {
        this.loading = false;
      }
    },
 

    async addProduct(product) {
      await addProductAPI(product);
      this.fetchProducts();
    },

    async updateProduct(product) {
      await updateProductAPI(product);
      this.fetchProducts();
    },

    async deleteProduct(id) {
      await deleteProductAPI(id);
      this.fetchProducts();
    },
  },
});
