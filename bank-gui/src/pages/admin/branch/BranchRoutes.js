import BranchManagement from "./BranchManagement.vue";
import BranchTrash from "@/components/pages/branch/BranchTrash.vue";

export default [
  {
    path: "/branch",
    component: BranchManagement,
    meta: {
      requiresAuth: true,
      title: "Branch",
      breadcrumb: "Branch",
    },
  },
  {
    path: "/branch/trash",
    component: BranchTrash,
    meta: {
      requiresAuth: true,
      title: "Branch Trash",
      breadcrumb: "Branch Trash",
    },
  },
];
