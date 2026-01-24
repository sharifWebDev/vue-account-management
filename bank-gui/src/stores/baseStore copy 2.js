// stores/baseStore.js
import { defineStore } from "pinia";
import { ref, computed } from "vue";
import axiosClient from "@/axiosClient";
import { useToast } from "vue-toastification";

export const createBaseStore = (storeName, apiEndpoint, defaultConfig = {}) => {
  return defineStore(storeName, () => {
    const toast = useToast();

    // State
    const items = ref([]);
    const item = ref(null);
    const loading = ref(false);
    const error = ref(null);
    const validationErrors = ref({});

    const pagination = ref({
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
      from: 0,
      to: 0,
    });

    const filters = ref({});
    const sortBy = ref(null);
    const sortDirection = ref("asc");
    const searchQuery = ref("");

    // Configuration
    const config = ref({
      useFormData: false,
      primaryKey: "id",
      softDelete: false,
      timestamps: true,
      ...defaultConfig,
    });

    // Getters/Computed - Return these directly for reactivity
    const getItems = computed(() => items.value);
    const getItem = computed(() => item.value);
    const isLoading = computed(() => loading.value);
    const getError = computed(() => error.value);
    const getValidationErrors = computed(() => validationErrors.value);
    const getPagination = computed(() => pagination.value);
    const totalItems = computed(() => pagination.value.total);
    const lastPage = computed(() => pagination.value.last_page);
    const currentPage = computed(() => pagination.value.current_page);
    const perPage = computed(() => pagination.value.per_page);
    const computedPerPage = computed(() => 
      pagination.value.per_page >= pagination.value.total ? 'all' : pagination.value.per_page
    );

    /**
     * Get all items with pagination
     */
    const get = async (params = {}) => {
      try {
        loading.value = true;
        clearMessages();

        // Merge params with existing filters, search, sort
        const finalParams = {
          page: pagination.value.current_page,
          per_page: pagination.value.per_page,
          search: searchQuery.value,
          ...filters.value,
          ...params,
        };

        if (sortBy.value) {
          finalParams.sort_by = sortBy.value;
          finalParams.sort_direction = sortDirection.value;
        }

        const response = await axiosClient.get(apiEndpoint, {
          params: finalParams,
        });
        
        const apiResponse = response.data;

        if (!apiResponse.success) {
          throw new Error(apiResponse.message || "API request failed");
        }

        const { data: itemsData, meta: paginationMeta } = apiResponse.data;

        // IMPORTANT: Update the reactive array
        items.value = itemsData || [];

        if (paginationMeta) {
          Object.assign(pagination.value, paginationMeta);
        }

        return {
          success: true,
          data: items.value,
          meta: paginationMeta,
        };
      } catch (err) {
        handleError(err);
        return { success: false };
      } finally {
        loading.value = false;
      }
    };

 /**
     * Get all items without pagination
     */
    const getAll = async () => {
      try {
        loading.value = true;
        clearMessages();

        const response = await axiosClient.get(`${apiEndpoint}/all`);
        items.value = response.data.data || response.data;

        return { success: true, data: items.value };
      } catch (err) {
        handleError(err);
        return { success: false };
      } finally {
        loading.value = false;
      }
    };

    /**
     * View single item by ID
     */
    const show = async (id) => {
      try {
        loading.value = true;
        clearMessages();

        const response = await axiosClient.get(`${apiEndpoint}/${id}`);
        item.value = response.data.data || response.data;

        return { success: true, data: item.value };
      } catch (err) {
        handleError(err);
        return { success: false };
      } finally {
        loading.value = false;
      }
    };

    /**
     * Create new item
     */
    const create = async (data) => {
      try {
        loading.value = true;
        clearMessages();

        const payload = preparePayload(data);
        const response = await axiosClient.post(apiEndpoint, payload);
        const newItem = response.data.data || response.data;

        items.value.unshift(newItem);
        pagination.value.total += 1;

        toast.success(response.data.message || "Item created successfully");
        return { success: true, data: newItem };
      } catch (err) {
        handleError(err);
        return { success: false };
      } finally {
        loading.value = false;
      }
    };

    /**
     * Update item by ID
     */
    const update = async (id, data) => {
      try {
        loading.value = true;
        clearMessages();

        let payload = preparePayload(data);
        
        if (config.value.useFormData && data) {
          payload.append("_method", "PUT");
        }

        const endpoint = config.value.useFormData 
          ? `${apiEndpoint}/update/${id}`
          : `${apiEndpoint}/${id}`;

        const response = await axiosClient.post(endpoint, payload);
        const updatedItem = response.data.data || response.data;

        updateItemInList(id, updatedItem);
        
        if (item.value && item.value[config.value.primaryKey] === id) {
          item.value = updatedItem;
        }

        toast.success(response.data.message || "Item updated successfully");
        return { success: true, data: updatedItem };
      } catch (err) {
        handleError(err);
        return { success: false };
      } finally {
        loading.value = false;
      }
    };

    /**
     * Delete item by ID
     */
    const deleteItem = async (id) => {
      try {
        loading.value = true;
        clearMessages();

        const response = await axiosClient.delete(`${apiEndpoint}/destroy/${id}`);

        removeItemFromList(id);
        pagination.value.total = Math.max(0, pagination.value.total - 1);

        if (item.value && item.value[config.value.primaryKey] === id) {
          item.value = null;
        }

        toast.success(response.data.message || "Item deleted successfully");
        return { success: true };
      } catch (err) {
        handleError(err);
        return { success: false };
      } finally {
        loading.value = false;
      }
    };

    /**
     * Search items
     */
    const search = async (query) => {
      searchQuery.value = query;
      pagination.value.current_page = 1;
      return await get();
    };

    /**
     * Apply filters
     */
    const filter = async (newFilters) => {
      filters.value = { ...filters.value, ...newFilters };
      pagination.value.current_page = 1;
      return await get();
    };

    /**
     * Clear all filters
     */
    const clearFilters = () => {
      filters.value = {};
      searchQuery.value = "";
      sortBy.value = null;
      sortDirection.value = "asc";
      return get();
    };

    /**
     * Sort items
     */
    const sort = async (field, direction = "asc") => {
      sortBy.value = field;
      sortDirection.value = direction;
      return await get();
    };

    /**
     * Paginate to specific page
     */
    const paginate = async (page) => {
      if (page >= 1 && page <= pagination.value.last_page) {
        pagination.value.current_page = page;
        return await get();
      }
      return { success: false, message: "Invalid page number" };
    };

    /**
     * Change items per page
     */
    const changePerPage = async (perPageValue) => {
      if (perPageValue === 'all') {
        // Fetch total count first
        const tempResponse = await axiosClient.get(apiEndpoint, {
          params: {
            page: 1,
            per_page: 1,
            search: searchQuery.value,
            ...filters.value,
          },
        });

        const totalCount = tempResponse.data.data?.meta?.total || 0;
        
        if (totalCount > 0) {
          pagination.value.per_page = totalCount;
          pagination.value.current_page = 1;
          return await get();
        }
      } else {
        pagination.value.per_page = Number(perPageValue);
        pagination.value.current_page = 1;
        return await get();
      }
    };

    // Helper methods
    const preparePayload = (data) => {
      if (!config.value.useFormData || !data) return data;

      const formData = new FormData();
      Object.keys(data).forEach((key) => {
        if (data[key] !== null && data[key] !== undefined) {
          formData.append(key, data[key]);
        }
      });
      return formData;
    };

    const updateItemInList = (id, updatedData) => {
      const index = items.value.findIndex(
        item => item[config.value.primaryKey] === id
      );
      if (index !== -1) {
        items.value[index] = { ...items.value[index], ...updatedData };
      }
    };

    const removeItemFromList = (id) => {
      items.value = items.value.filter(
        item => item[config.value.primaryKey] !== id
      );
    };

    /**
     * Handle API errors
     */
    const handleError = (err) => {
      loading.value = false;

      if (err.response) {
        const { status, data } = err.response;

        if (status === 422) {
          validationErrors.value = data.errors || {};
          const allErrors = Object.values(data.errors).flat();
          error.value = allErrors[0] || "Validation failed";
          toast.error("Please check the form for errors");
        } else {
          validationErrors.value = {};
          error.value = data.message || `Error: ${status}`;
          toast.error(error.value);
        }
      } else {
        validationErrors.value = {};
        error.value = "Network error or server is unreachable";
        toast.error(error.value);
      }
    };

    /**
     * Clear all messages
     */
    const clearMessages = () => {
      error.value = null;
      validationErrors.value = {};
    };

    /**
     * Clear validation error for specific field
     */
    const clearValidationError = (field) => {
      if (validationErrors.value[field]) {
        const updatedErrors = { ...validationErrors.value };
        delete updatedErrors[field];
        validationErrors.value = updatedErrors;
      }
    };

    /**
     * Reset store to initial state
     */
    const reset = () => {
      items.value = [];
      item.value = null;
      loading.value = false;
      error.value = null;
      validationErrors.value = {};
      pagination.value = {
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0,
        from: 0,
        to: 0,
      };
      filters.value = {};
      sortBy.value = null;
      sortDirection.value = "asc";
      searchQuery.value = "";
    };


    return {
      // Reactive State (exposed directly)
      items,
      item,
      loading,
      error,
      validationErrors,
      pagination,
      filters,
      sortBy,
      sortDirection,
      searchQuery,
      config,

      // Getters (computed properties)
      getItems,
      getItem,
      isLoading,
      getError,
      getValidationErrors,
      getPagination,
      totalItems,
      lastPage,
      currentPage,
      perPage,
      computedPerPage,

      // CRUD Methods
      getAll,
      get,
      show,
      create,
      update,
      deleteItem,

      // Data Manipulation Methods
      search,
      filter,
      clearFilters,
      sort,
      paginate,
      changePerPage,

      // Helper Methods
      handleError,
      clearMessages,
      clearValidationError,
      reset,
    };
  });
};