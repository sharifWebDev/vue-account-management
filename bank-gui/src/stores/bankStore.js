// stores/accountStore.js
import { createBaseStore } from "./baseStore";

// Create the base store directly
export const useBankStore = createBaseStore("bank", "banks", {
  useFormData: true,
  primaryKey: "id",
});

// If you need additional custom methods, create a wrapper function
/*
export const useAccountStore = () => {
  const store = baseStore();

  // Add any account-specific methods here
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
