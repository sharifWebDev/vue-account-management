// stores/baseStore.js
import { defineStore } from "pinia";
import { ref, computed } from "vue";
import axiosClient from "@/axiosClient";
import { useToast } from "vue-toastification";

export const createBaseStore = (storeName, apiEndpoint, defaultConfig = {}) => {
  return defineStore(storeName, () => {
    const toast = useToast();

    // ========== STATE ==========
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

    const config = ref({
      useFormData: false,
      primaryKey: "id",
      softDelete: false,
      timestamps: true,
      ...defaultConfig,
    });

    // ========== GETTERS/COMPUTED ==========
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
      pagination.value.per_page >= pagination.value.total
        ? "all"
        : pagination.value.per_page
    );

    // ========== CORE METHODS ==========

    /**
     * Get all items with pagination
     */
    const get = async (params = {}) => {
      try {
      // if (loading.value) {
      //   console.log("Already loading, skipping duplicate call");
      //   return { success: false, data: items.value };
      // }
        loading.value = true;
        clearMessages();

        // Prepare request parameters
        const finalParams = {
          page: pagination.value.current_page,
          per_page: pagination.value.per_page,
          search: searchQuery.value,
          ...filters.value,
          ...params,
        };

        // Add sorting if specified
        if (sortBy.value) {
          finalParams.sort_by = sortBy.value;
          finalParams.sort_direction = sortDirection.value;
        }

        // Make API request
        const response = await axiosClient.get(apiEndpoint, {
          params: finalParams,
        });

        const apiResponse = response.data;

        // Check if API request was successful
        if (!apiResponse.success) {
          throw new Error(apiResponse.message || "API request failed");
        }

        const { data: itemsData, meta: paginationMeta } = apiResponse.data;

        // IMPORTANT: Update reactive items array
        items.value = Array.isArray(itemsData) ? itemsData : [];

        // Update pagination metadata
        if (paginationMeta) {
          Object.assign(pagination.value, {
            current_page: paginationMeta.current_page || 1,
            last_page: paginationMeta.last_page || 1,
            per_page: paginationMeta.per_page || 10,
            total: paginationMeta.total || 0,
            from: paginationMeta.from || 0,
            to: paginationMeta.to || 0,
          });
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

    // Inside your Store (e.g., companyStore.js)
    const getAll = async () => {
      try {
        loading.value = true;
        const response = await axiosClient.get(`${apiEndpoint}/all`);
        const apiResponse = response.data;

        const dataArray = apiResponse.data?.data || [];

        items.value = Array.isArray(dataArray) ? dataArray : [];

        return { success: true, data: items.value };
      } catch (err) {
        return { success: false, error: err.message };
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

        const response = await axiosClient.get(`${apiEndpoint}/find/${id}`);
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
    const createold = async (data) => {
      try {
        loading.value = true;
        clearMessages();

        const payload = preparePayload(data);
        const response = await axiosClient.post(apiEndpoint, payload);
        const newItem = response.data.data || response.data;

        // Add new item to the beginning of the list
        items.value.unshift(newItem);

        // Update total count
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

    const create = async (data) => {
      try {
        loading.value = true;
        clearMessages();
        const payload = data instanceof FormData ? data : preparePayload(data);

        const response = await axiosClient.post(apiEndpoint, payload, {
          headers:
            data instanceof FormData
              ? { "Content-Type": "multipart/form-data" }
              : { "Content-Type": "application/json" },
        });

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

        let payload;
        if (data instanceof FormData) {
          payload = data;
          if (!payload.has("_method")) {
            payload.append("_method", "PUT");
            console.log("re");
          }
        } else {
          payload = preparePayload(data);
        }

        const endpoint = `${apiEndpoint}/update/${id}`;

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

        const response = await axiosClient.delete(
          `${apiEndpoint}/destroy/${id}`
        );

        // Remove item from the list
        removeItemFromList(id);

        // Update total count
        pagination.value.total = Math.max(0, pagination.value.total - 1);

        // Clear current item if it's the same
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
    const toggleStatus = async (item, fieldName = "status") => {
      try {
        const currentValue = !!item[fieldName];
        const newValue = !currentValue;

        await update(item.id, {
          [fieldName]: newValue,
        });

        return newValue;
      } catch (error) {
        console.error("Error toggling status:", error);
        return null;
      }
    };
/**
 * Get all trashed/soft-deleted items with pagination
 * @param {Object} params - Additional parameters for the request
 * @returns {Promise<Object>} - Response object with success status and data
 */
const trashed = async (params = {}) => {
  try {
    loading.value = true;
    clearMessages();

    // Prepare request parameters
    const finalParams = {
      page: Number(pagination.value.current_page) || 1,
      per_page: Number(pagination.value.per_page) || 10,
      ...filters.value,
      ...params,
    };

    // Handle search parameter properly
    // Check if searchQuery is a string, not a function
    let searchValue = '';
    if (typeof searchQuery === 'function') {
      // If searchQuery is a computed/ref, get its value
      searchValue = searchQuery.value || '';
    } else if (typeof searchQuery === 'string') {
      searchValue = searchQuery;
    } else if (searchQuery && typeof searchQuery.value === 'string') {
      searchValue = searchQuery.value;
    }
    
    // Only add search if it's a non-empty string
    if (typeof finalParams.search === 'string' && finalParams.search.trim() !== '') {
      finalParams.search = searchValue;
    }

    // Add sorting if specified
    if (sortBy.value) {
      finalParams.sort_by = sortBy.value;
      finalParams.sort_direction = sortDirection.value;
    }

    console.log('Request params:', finalParams); // Debug log

    // Make API request
    const response = await axiosClient.get(`${apiEndpoint}/trash-list`, {
      params: finalParams,
    });

    const apiResponse = response.data;

    // Check if API request was successful
    if (!apiResponse.success) {
      throw new Error(apiResponse.message || "Failed to fetch trashed items");
    }

    // Extract data safely - handle different API response structures
    let itemsData = [];
    let paginationMeta = null;

    if (apiResponse.data) {
      // Case 1: Laravel-style pagination { data: [...], meta: {...}, links: {...} }
      if (apiResponse.data.data && Array.isArray(apiResponse.data.data)) {
        itemsData = apiResponse.data.data;
        paginationMeta = apiResponse.data.meta || null;
      }
      // Case 2: Simple array response
      else if (Array.isArray(apiResponse.data)) {
        itemsData = apiResponse.data;
        // Create simple pagination metadata for array responses
        paginationMeta = {
          current_page: 1,
          last_page: 1,
          per_page: itemsData.length,
          total: itemsData.length,
          from: 1,
          to: itemsData.length,
        };
      }
      // Case 3: Object with items property
      else if (apiResponse.data.items && Array.isArray(apiResponse.data.items)) {
        itemsData = apiResponse.data.items;
        paginationMeta = apiResponse.data.meta || apiResponse.data.pagination || null;
      }
    }

    // Update reactive items array
    items.value = itemsData;

    // Update pagination if metadata exists
    if (paginationMeta) {
      Object.assign(pagination.value, {
        current_page: Number(paginationMeta.current_page) || 1,
        last_page: Number(paginationMeta.last_page) || 1,
        per_page: Number(paginationMeta.per_page) || finalParams.per_page,
        total: Number(paginationMeta.total) || itemsData.length,
        from: Number(paginationMeta.from) || 0,
        to: Number(paginationMeta.to) || 0,
      });
    } else {
      // Default pagination for non-paginated responses
      Object.assign(pagination.value, {
        current_page: 1,
        last_page: 1,
        per_page: itemsData.length,
        total: itemsData.length,
        from: itemsData.length > 0 ? 1 : 0,
        to: itemsData.length,
      });
    }

    return {
      success: true,
      data: items.value,
      meta: paginationMeta,
      pagination: pagination.value,
    };
  } catch (err) {
    console.error('Error in trashed method:', err);
    handleError(err);
    errorMessage.value = err.message || "Failed to load trashed items";
    return { 
      success: false, 
      error: err.message,
      data: [],
      meta: null 
    };
  } finally {
    loading.value = false;
  }
};
    /**
     * Restore soft-deleted item by ID
     */
    const restore = async (id) => {
      try {
        loading.value = true;
        clearMessages();

        const response = await axiosClient.post(`${apiEndpoint}/restore/${id}`);
        const restoredItem = response.data.data || response.data;
 
        toast.success(response.data.message || "Item restored successfully");
        return { success: true, data: restoredItem };
      } catch (err) {
        handleError(err);
        return { success: false };
      } finally {
        loading.value = false;
      }
    };

    /**
     * Permanently delete item by ID
     */
    const forceDelete = async (id) => {
      try {
        loading.value = true;
        clearMessages();

        const response = await axiosClient.delete(
          `${apiEndpoint}/force-delete/${id}`
        );

        // // Remove item from the list
        // removeItemFromList(id);

        // // Update total count
        // pagination.value.total = Math.max(0, pagination.value.total - 1);

        toast.success(response.data.message || "Item permanently deleted");
        return { success: true };
      } catch (err) {
        handleError(err);
        return { success: false };
      } finally {
        loading.value = false;
      }
    };

    // ========== DATA MANIPULATION METHODS ==========

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
      if (perPageValue === "all") {
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

    // ========== HELPER METHODS ==========

    /**
     * Prepare payload for FormData or JSON
     */
    const preparePayload = (data) => {
      if (!config.value.useFormData || !data) return data;

      const formData = new FormData();

      Object.keys(data).forEach((key) => {
        let value = data[key];

        if (typeof value === "boolean") {
          value = value ? 1 : 0;
        }

        if (value !== null && value !== undefined) {
          formData.append("_method", "PUT");
          formData.append(key, value); // use modified value
        }
      });

      return formData;
    };

    /**
     * Update item in the items list
     */
    const updateItemInList = (id, updatedData) => {
      const index = items.value.findIndex(
        (item) => item[config.value.primaryKey] === id
      );
      if (index !== -1) {
        items.value[index] = { ...items.value[index], ...updatedData };
      }
    };

    /**
     * Remove item from the items list
     */
    const removeItemFromList = (id) => {
      items.value = items.value.filter(
        (item) => item[config.value.primaryKey] !== id
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

    // ========== RETURN STORE ==========
    return {
      // Reactive State (exposed directly for Vue reactivity)
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
      toggleStatus,
      trashed,
      restore,
      forceDelete,

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

      // Optional: Expose helper methods for debugging
      updateItemInList,
      removeItemFromList,
      preparePayload,
    };
  });
};
