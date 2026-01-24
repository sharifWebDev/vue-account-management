import CompanyManagement from './CompanyManagement.vue';
import CompanyTrash from '@/components/CompanyTrash.vue';

export default [
  {
    path: '/company',
    component: CompanyManagement,
    meta: {
      requiresAuth: true,
      title: 'Company',
      breadcrumb: 'Company'
    }
  },
  {
    path: '/company/trash',
    component: CompanyTrash,
    meta: {
      requiresAuth: true,
      title: 'Company Trash',
      breadcrumb: 'Company Trash'
    }
  }
]

