import { createBaseStore } from "./baseStore";

export const useTransactionTypeStore = createBaseStore("transactionType", "transaction-types", {
  useFormData: true,
  primaryKey: "id",
});
