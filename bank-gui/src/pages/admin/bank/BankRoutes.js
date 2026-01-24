import BankManagement from './BankManagement.vue';
import BankTrash from '@/components/BankTrash.vue';

export default [
  {
    path: '/bank',
    component: BankManagement,
    meta: {
      requiresAuth: true,
      title: 'Bank',
      breadcrumb: 'Bank'
    }
  },
  {
    path: '/bank/trash',
    component: BankTrash,
    meta: {
      requiresAuth: true,
      title: 'Bank Trash',
      breadcrumb: 'Bank Trash'
    }
  }
]

