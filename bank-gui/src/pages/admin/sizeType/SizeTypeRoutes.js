import SizeTypeManagement from './SizeTypeManagement.vue';
// import SizeTypeManagement from '../../components/SizeTypeManagement.vue';

export default [
  {
    path: 'hk-prod-size-types',
    component: SizeTypeManagement,
    meta: {
      requiresAuth: true,
      title: 'Size Types',
      breadcrumb: 'Size Types'
    }
  }
]

