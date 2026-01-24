import { defineStore } from "pinia";
import { ref } from "vue";
import axiosClient from "@/axiosClient";
import { useToast } from "vue-toastification";

export const useSizeTypeStore = defineStore("sizeType", () => {
  
  const toast = useToast();
  // State
  const sizeTypes = ref([]);
  const loading = ref(false);
  const error = ref(null);
  const success = ref(null);
  const validationErrors = ref({});

  const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0,
    from: 0,
    to: 0,
  });

  const API_URL = "hk-prod-size-types";

  // Clear validation errors
  const clearValidationErrors = () => {
    validationErrors.value = {};
  };

  const handleError = (err) => {
    loading.value = false;
    success.value = null;

    if (err.response) {
      const { status, data } = err.response;

      // Validation Error
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

  const clearMessages = () => {
    error.value = null;
    success.value = null;
    validationErrors.value = {};
  };

  // Fetch Data
  const fetchSizeTypes = async (url = {}) => {
    try {
      loading.value = true;
      clearMessages();

      const response = await axiosClient.get(API_URL, { url });

      const responseData = response.data.data;
      sizeTypes.value = responseData.data || responseData;

      if (responseData.meta) {
        pagination.value = {
          current_page: responseData.meta.current_page,
          last_page: responseData.meta.last_page,
          per_page: responseData.meta.per_page,
          total: responseData.meta.total,
          from: responseData.meta.from,
          to: responseData.meta.to,
        };
      }

      return { success: true };
    } catch (err) {
      handleError(err);
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  // Create Data (FormData Support)
  const createSizeType = async (sizeTypeData) => {
    try {
      loading.value = true;
      clearMessages();

      // Create FormData
      const formData = new FormData();

      // Append all fields
      Object.keys(sizeTypeData).forEach((key) => {
        if (sizeTypeData[key] !== null && sizeTypeData[key] !== undefined) {
          formData.append(key, sizeTypeData[key]);
        }
      });

      const response = await axiosClient.post(API_URL, formData);

      // Add to beginning of list
      sizeTypes.value.unshift(response.data.data);

      // Update pagination total
      pagination.value.total += 1;

      toast.success(response.data.message || "Size type created successfully");
      return { success: true, data: response.data.data };
    } catch (err) {
      handleError(err);
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  // Update Data
  const updateSizeType = async (id, sizeTypeData) => {
    try {
      loading.value = true;
      clearMessages();

      const formData = new FormData();

      // Append all fields
      Object.keys(sizeTypeData).forEach((key) => {
        if (sizeTypeData[key] !== null && sizeTypeData[key] !== undefined) {
          formData.append(key, sizeTypeData[key]);
        }
      });

      // For Laravel PUT method
      formData.append("_method", "PUT");

      const response = await axiosClient.post(
        `${API_URL}/update/${id}`,
        formData
      );

      // Update in local state
      const index = sizeTypes.value.findIndex((item) => item.id === id);
      if (index !== -1) {
        sizeTypes.value[index] = {
          ...sizeTypes.value[index],
          ...response.data.data,
        };
      }

      toast.success(response.data.message || "Size type updated successfully");
      return { success: true, data: response.data.data };
    } catch (err) {
      handleError(err);
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  // Delete Data
  const deleteSizeType = async (id) => {
    try {
      loading.value = true;
      clearMessages();
      const response = await axiosClient.delete(`${API_URL}/destroy/${id}`);

      // Remove from local state
      sizeTypes.value = sizeTypes.value.filter((item) => item.id !== id);

      // Update pagination total
      pagination.value.total -= 1;

      toast.success(response.data.message || "Size type deleted successfully");
      return { success: true };
    } catch (err) {
      handleError(err);
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  // Status Toggle
  const toggleActiveStatusOld = async (id) => {
    try {
      loading.value = true;
      clearMessages();

      const response = await axiosClient.put(`${API_URL}/${id}/change-status`);
 
      const index = sizeTypes.value.findIndex((item) => item.id === id);
      if (index !== -1) {
        sizeTypes.value[index].status = response.data.data.status;
      }

      toast.success(response.data.message || "Status updated successfully");
      return { success: true };
    } catch (err) {
      handleError(err);
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const toggleActiveStatus = async (item) => {
    if (!item) return;
  
    const payload = {
      ...item,  
      status: item.status ? false : true  
    };

    const response = await axiosClient.put(`${API_URL}/update/${item.id}`, payload);
    toast.success(response.data.message || "Size type updated successfully");
    return { success: true, data: response.data.data };
  };

 
  return {
    // State
    sizeTypes,
    loading,
    error,
    success,
    validationErrors,
    pagination,

    // Actions
    fetchSizeTypes,
    createSizeType,
    updateSizeType,
    deleteSizeType,
    toggleActiveStatus, 
    clearMessages,
    clearValidationErrors,
  };
});
