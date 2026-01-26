import UserManagement from './UserManagement.vue';
import TrashUser from '@/components/TrashUser.vue';

export default [
  {
    path: '/user',
    component: UserManagement,
    meta: {
      requiresAuth: true,
      title: 'User',
      breadcrumb: 'User'
    }
  },
  {
    path: '/user/trash',
    component: TrashUser,
    meta: {
      requiresAuth: true,
      title: 'User Trash',
      breadcrumb: 'User Trash'
    }
  }
]

