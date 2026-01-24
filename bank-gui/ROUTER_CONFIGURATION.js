// Router configuration to add to src/router/index.js
// Add these routes to your router after the existing routes

// ==================== ACCOUNT ROUTES ====================
{
  path: '/account',
  component: () => import('@/pages/admin/account/Account.vue'),
  meta: { requiresAuth: true }
},
{
  path: '/account/add',
  component: () => import('@/pages/admin/account/Account.vue'),
  meta: { requiresAuth: true }
},
{
  path: '/account/trash',
  component: () => import('@/pages/admin/account/AccountTrash.vue'),
  meta: { requiresAuth: true }
},

// ==================== BANK ROUTES ====================
{
  path: '/bank',
  component: () => import('@/pages/admin/bank/Bank.vue'),
  meta: { requiresAuth: true }
},
{
  path: '/bank/add',
  component: () => import('@/pages/admin/bank/Bank.vue'),
  meta: { requiresAuth: true }
},
{
  path: '/bank/trash',
  component: () => import('@/pages/admin/bank/BankTrash.vue'),
  meta: { requiresAuth: true }
},

// ==================== BRANCH ROUTES ====================
{
  path: '/branch',
  component: () => import('@/pages/admin/branch/Branch.vue'),
  meta: { requiresAuth: true }
},
{
  path: '/branch/add',
  component: () => import('@/pages/admin/branch/Branch.vue'),
  meta: { requiresAuth: true }
},
{
  path: '/branch/trash',
  component: () => import('@/pages/admin/branch/BranchTrash.vue'),
  meta: { requiresAuth: true }
},

// ==================== COMPANY ROUTES ====================
{
  path: '/company',
  component: () => import('@/pages/admin/company/Company.vue'),
  meta: { requiresAuth: true }
},
{
  path: '/company/add',
  component: () => import('@/pages/admin/company/Company.vue'),
  meta: { requiresAuth: true }
},
{
  path: '/company/trash',
  component: () => import('@/pages/admin/company/CompanyTrash.vue'),
  meta: { requiresAuth: true }
},

// ==================== TRANSACTION ROUTES ====================
{
  path: '/transaction',
  component: () => import('@/pages/admin/transaction/Transaction.vue'),
  meta: { requiresAuth: true }
},
{
  path: '/transaction/add',
  component: () => import('@/pages/admin/transaction/Transaction.vue'),
  meta: { requiresAuth: true }
},
{
  path: '/transaction/trash',
  component: () => import('@/pages/admin/transaction/TransactionTrash.vue'),
  meta: { requiresAuth: true }
},

// ==================== USER ROUTES ====================
{
  path: '/user',
  component: () => import('@/pages/admin/user/User.vue'),
  meta: { requiresAuth: true }
},
{
  path: '/user/add',
  component: () => import('@/pages/admin/user/User.vue'),
  meta: { requiresAuth: true }
},
{
  path: '/user/trash',
  component: () => import('@/pages/admin/user/UserTrash.vue'),
  meta: { requiresAuth: true }
},
