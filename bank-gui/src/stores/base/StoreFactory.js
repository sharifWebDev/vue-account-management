// stores/base/StoreFactory.js
import { ref, computed } from 'vue';
import { useToast } from 'vue-toastification';
import axiosClient from '@/axiosClient';

/**
 * Base Store Factory - Template Method Pattern
 */
class StoreFactory {
  constructor(config = {}) {
    this.config = {
      useFormData: false,
      primaryKey: 'id',
      autoRefresh: true,
      ...config
    };
    
    this.toast = useToast();
    this.baseState = this.createBaseState();
  }

  createBaseState() {
    return {
      items: ref([]),
      item: ref(null),
      loading: ref(false),
      error: ref(null),
      validationErrors: ref({}),
      
      pagination: ref({
        current_page: 1,
        per_page: 10,
        total: 0,
        last_page: 1
      }),
      
      filters: ref({}),
      sortBy: ref(null),
      sortDirection: ref('asc'),
      searchQuery: ref('')
    };
  }

  // ========== TEMPLATE METHODS ==========
  async getAll(params = {}) {
    this.loading.value = true;
    this.clearErrors();
    
    try {
      const query = this.buildQuery(params);
      const response = await axiosClient.get(this.endpoint, { params: query });
      this.handleResponse(response);
      return { success: true, data: this.items.value };
    } catch (error) {
      return this.handleError(error);
    } finally {
      this.loading.value = false;
    }
  }

  async get(id) {
    this.loading.value = true;
    try {
      const response = await axiosClient.get(`${this.endpoint}/${id}`);
      this.item.value = response.data.data;
      return { success: true, data: this.item.value };
    } catch (error) {
      return this.handleError(error);
    } finally {
      this.loading.value = false;
    }
  }

  async create(data) {
    this.loading.value = true;
    try {
      const payload = this.prepareData(data);
      const response = await axiosClient.post(this.endpoint, payload);
      this.addItem(response.data.data);
      this.toast.success('Created successfully');
      return { success: true, data: response.data.data };
    } catch (error) {
      return this.handleError(error);
    } finally {
      this.loading.value = false;
    }
  }

  async update(id, data) {
    this.loading.value = true;
    try {
      const payload = this.prepareData(data, true);
      const response = await axiosClient.post(`${this.endpoint}/${id}`, payload);
      this.updateItem(id, response.data.data);
      this.toast.success('Updated successfully');
      return { success: true, data: response.data.data };
    } catch (error) {
      return this.handleError(error);
    } finally {
      this.loading.value = false;
    }
  }

  async delete(id) {
    this.loading.value = true;
    try {
      await axiosClient.delete(`${this.endpoint}/${id}`);
      this.removeItem(id);
      this.toast.success('Deleted successfully');
      return { success: true };
    } catch (error) {
      return this.handleError(error);
    } finally {
      this.loading.value = false;
    }
  }

  // ========== HELPER METHODS ==========
  buildQuery(params) {
    return {
      page: this.pagination.value.current_page,
      per_page: this.pagination.value.per_page,
      search: this.searchQuery.value,
      ...this.filters.value,
      ...params
    };
  }

  handleResponse(response) {
    const data = response.data.data || response.data;
    this.items.value = data.data || data;
    
    if (data.meta) {
      Object.assign(this.pagination.value, data.meta);
    }
  }

  prepareData(data, isUpdate = false) {
    if (!this.config.useFormData) return data;
    
    const formData = new FormData();
    Object.entries(data).forEach(([key, value]) => {
      if (value != null) formData.append(key, value);
    });
    
    if (isUpdate) formData.append('_method', 'PUT');
    return formData;
  }

  handleError(error) {
    this.loading.value = false;
    
    if (error.response?.status === 422) {
      this.validationErrors.value = error.response.data.errors;
      this.toast.error('Validation failed');
    } else {
      this.error.value = error.response?.data?.message || 'Request failed';
      this.toast.error(this.error.value);
    }
    
    return { success: false, error: this.error.value };
  }

  addItem(item) {
    this.items.value.unshift(item);
    this.pagination.value.total++;
  }

  updateItem(id, newData) {
    const index = this.items.value.findIndex(
      item => item[this.config.primaryKey] === id
    );
    if (index > -1) {
      this.items.value[index] = { ...this.items.value[index], ...newData };
    }
  }

  removeItem(id) {
    this.items.value = this.items.value.filter(
      item => item[this.config.primaryKey] !== id
    );
    this.pagination.value.total--;
  }

  clearErrors() {
    this.error.value = null;
    this.validationErrors.value = {};
  }

  // ========== COMPUTED PROPERTIES ==========
  get computed() {
    return {
      hasItems: computed(() => this.items.value.length > 0),
      isEmpty: computed(() => !this.loading.value && this.items.value.length === 0),
      totalPages: computed(() => this.pagination.value.last_page),
      currentPage: computed(() => this.pagination.value.current_page),
      from: computed(() => (this.currentPage - 1) * this.perPage + 1),
      to: computed(() => Math.min(this.currentPage * this.perPage, this.total)),
      total: computed(() => this.pagination.value.total),
      perPage: computed(() => this.pagination.value.per_page)
    };
  }

  // ========== ACTION METHODS ==========
  async search(query) {
    this.searchQuery.value = query;
    this.pagination.value.current_page = 1;
    return this.getAll();
  }

  async filter(filters) {
    Object.assign(this.filters.value, filters);
    this.pagination.value.current_page = 1;
    return this.getAll();
  }

  async paginate(page) {
    if (page >= 1 && page <= this.computed.totalPages.value) {
      this.pagination.value.current_page = page;
      return this.getAll();
    }
  }

  async changePerPage(perPage) {
    this.pagination.value.per_page = perPage;
    this.pagination.value.current_page = 1;
    return this.getAll();
  }

  async sort(field) {
    if (this.sortBy.value === field) {
      this.sortDirection.value = this.sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
      this.sortBy.value = field;
      this.sortDirection.value = 'asc';
    }
    
    this.filters.value.sort_by = this.sortBy.value;
    this.filters.value.sort_direction = this.sortDirection.value;
    return this.getAll();
  }

  reset() {
    Object.assign(this, this.createBaseState());
  }
}

/**
 * Store Creator Function
 */
export function createStore(endpoint, config = {}) {
  return function() {
    const store = new StoreFactory(config);
    store.endpoint = endpoint;
    
    return {
      // State
      ...store.baseState,
      
      // Computed
      ...store.computed,
      
      // Actions
      getAll: store.getAll.bind(store),
      get: store.get.bind(store),
      create: store.create.bind(store),
      update: store.update.bind(store),
      delete: store.delete.bind(store),
      search: store.search.bind(store),
      filter: store.filter.bind(store),
      paginate: store.paginate.bind(store),
      changePerPage: store.changePerPage.bind(store),
      sort: store.sort.bind(store),
      reset: store.reset.bind(store),
      clearErrors: store.clearErrors.bind(store)
    };
  };
}

/**
 * Define Store Helper
 */
export function defineStore(name, endpoint, config = {}) {
  const storeFn = createStore(endpoint, config);
  return defineStore(name, storeFn);
}