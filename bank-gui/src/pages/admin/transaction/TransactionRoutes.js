import TransactionManagement from './TransactionManagement.vue'; 

export default [
  {
    path: '/transactions-history',
    component: TransactionManagement,
    meta: {
      requiresAuth: true,
      title: 'transactions',
      breadcrumb: 'transactions'
    }
  }
]

