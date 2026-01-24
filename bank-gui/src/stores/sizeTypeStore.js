// stores/sizeTypeStore.js
import { ref, computed } from "vue";
import { createBaseStore } from "./baseStore";

const baseStore = createBaseStore("sizeType", "hk-prod-size-types", {
  useFormData: true,
  primaryKey: "id",
});

// Extend the base store with custom functionality
export const useSizeTypeStore = () => {
  const store = baseStore();

  // Custom state
  const sortField = ref("sequence");
  const sortOrder = ref("asc");

  // Custom method: Toggle active status
  const toggleActiveStatus = async (item) => {
    const newStatus = item.status === 1 || item.status === true ? 0 : 1;
    return store.update(item.id, {
      ...item,
      status: newStatus,
    });
  };

  // Custom method: Apply sorting
  const applySort = async (field, order = "asc") => {
    sortField.value = field;
    sortOrder.value = order;
    await store.sort(field, order);
  };

  // Custom method: Handle per page change
  const handlePerPageChange = async (perPageValue) => {
    return await store.changePerPage(perPageValue);
  };

  // Custom method: Handle page change
  const handlePageChange = async (page) => {
    return await store.paginate(page);
  };

  // Computed: Filtered items with local sorting
  const filteredItems = computed(() => {
    const items = [...store.items];

    if (sortField.value) {
      items.sort((a, b) => {
        const aValue = a[sortField.value];
        const bValue = b[sortField.value];

        // Handle null/undefined values
        if (aValue == null && bValue == null) return 0;
        if (aValue == null) return 1;
        if (bValue == null) return -1;

        // Number sorting
        if (typeof aValue === "number" && typeof bValue === "number") {
          return sortOrder.value === "asc" ? aValue - bValue : bValue - aValue;
        }

        // String sorting
        if (typeof aValue === "string" && typeof bValue === "string") {
          return sortOrder.value === "asc"
            ? aValue.localeCompare(bValue)
            : bValue.localeCompare(aValue);
        }

        // Boolean sorting
        if (typeof aValue === "boolean" && typeof bValue === "boolean") {
          return sortOrder.value === "asc"
            ? aValue === bValue
              ? 0
              : aValue
                ? 1
                : -1
            : aValue === bValue
              ? 0
              : aValue
                ? -1
                : 1;
        }

        return 0;
      });
    }

    return items;
  });

  return {
    // Base Store state
    ...store,

    // Custom state
    sortField,
    sortOrder,

    // Custom methods
    toggleActiveStatus,
    applySort,
    handlePerPageChange,
    handlePageChange,

    // Custom computed
    filteredItems,
  };
};
