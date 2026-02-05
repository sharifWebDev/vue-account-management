import AccountManagement from "./AccountManagement.vue";

export default [
  {
    path: "/account",
    component: AccountManagement,
    meta: {
      requiresAuth: true,
      title: "Account",
      breadcrumb: "Account",
    },
  },
  {
    path: "/account/trash",
    component: () => import("@/components/pages/accounts/AccountTrash.vue"),
    meta: {
      requiresAuth: true,
      title: "Account Trash",
      breadcrumb: "Account Trash",
    },
  },
];
