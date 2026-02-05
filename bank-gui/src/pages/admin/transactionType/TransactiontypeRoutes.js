import TransactionTypeManagement from "./TransactionTypeManagement.vue";

export default [
  {
    path: "/transactions-type",
    component: TransactionTypeManagement,
    meta: {
      requiresAuth: true,
      title: "transactions type",
      breadcrumb: "transactions type",
    },
  },
];
