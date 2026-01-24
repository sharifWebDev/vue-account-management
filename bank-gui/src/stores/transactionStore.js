// stores/userStore.js
import { createBaseStore } from "./baseStore";

// Create the base store directly
export const useTransactionStore = createBaseStore("transaction", "transactions", {
  useFormData: true,
  primaryKey: "id",
});

// If you need additional custom methods, create a wrapper function
/*
export const useStore = () => {
  const store = baseStore();

  // Add any -specific methods here
  const toggleActiveStatus = async (item) => {
    const newStatus = item.status === 1 || item.status === true ? 0 : 1;
    return store.update(item.id, {
      ...item,
      status: newStatus,
    });
  };

  return {
    ...store,
    toggleActiveStatus,
  };
};
*/
