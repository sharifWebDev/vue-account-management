<template>
  <div 
    id="packageModal" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 transition-all duration-300"
    :class="{ 'opacity-0 invisible': !isOpen, 'opacity-100 visible': isOpen }"
    @click.self="closeModal"
  >
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white">{{ modalTitle }}</h3>
        <button 
          @click="closeModal" 
          class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition"
          aria-label="Close modal"
        >
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      <div class="p-6">
        <div v-if="selectedPackage" class="space-y-6">
          <div>
            <h4 class="font-semibold text-gray-800 dark:text-white mb-4 text-lg">Features Included:</h4>
            <ul class="space-y-3">
              <li v-for="feature in selectedPackage.features" :key="feature" class="flex items-start">
                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                <span class="text-gray-600 dark:text-gray-300">{{ feature }}</span>
              </li>
            </ul>
          </div>
          
          <div>
            <h4 class="font-semibold text-gray-800 dark:text-white mb-4 text-lg">Limitations:</h4>
            <ul class="space-y-3">
              <li v-for="limitation in selectedPackage.limitations" :key="limitation" class="flex items-start">
                <i class="fas fa-times text-red-400 mt-1 mr-3"></i>
                <span class="text-gray-600 dark:text-gray-300">{{ limitation }}</span>
              </li>
            </ul>
          </div>
          
          <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
            <button 
              @click="handleBuyNow" 
              class="w-full bg-gradient-to-r from-gray-500 to-gray-600 text-white py-3 rounded-xl hover:shadow-lg transition font-semibold"
            >
              Buy Now - {{ selectedPackage.price }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const isOpen = ref(false);
const selectedPackage = ref(null);
const modalTitle = ref('Package Details');

const packageDetails = {
  basic: {
    title: 'Basic Package',
    price: '$49/month',
    features: [
      'Laravel Service Pattern with Blade Templates',
      'jQuery DataTable Integration',
      'Basic CRUD Operations',
      'User Authentication',
      'Role-based Permissions',
      'Responsive Design',
      'Email Support'
    ],
    limitations: [
      'No API Integration',
      'Limited Customization Options',
      'No Advanced Features'
    ]
  },
  standard: {
    title: 'Standard Package',
    price: '$99/month',
    features: [
      'Laravel Service Pattern with Interface Binding',
      'API + Blade/jQuery DataTable',
      'Advanced CRUD Operations',
      'User Authentication & Authorization',
      'Role-based Permissions',
      'API Integration',
      'Customizable Templates',
      'Priority Email Support',
      'Live Chat Support (Business Hours)'
    ],
    limitations: [
      'No Repository Pattern',
      'Limited Advanced Features'
    ]
  },
  enterprise: {
    title: 'Enterprise Package',
    price: '$199/month',
    features: [
      'Laravel Service Repository Pattern with Interface',
      'API + Blade + jQuery DataTable',
      'Advanced CRUD Operations with Custom Logic',
      'Multi-level User Authentication & Authorization',
      'Advanced Role-based Permissions',
      'Full API Integration',
      'Customizable Templates & Themes',
      'Advanced Reporting & Analytics',
      'Database Import/Export Functionality',
      'Priority Phone & Email Support',
      'Dedicated Account Manager',
      'Custom Feature Development'
    ],
    limitations: [
      'None - Full access to all features'
    ]
  }
};

const openModal = (packageType) => {
  selectedPackage.value = packageDetails[packageType];
  modalTitle.value = selectedPackage.value.title;
  isOpen.value = true;
  document.body.style.overflow = 'hidden';
};

const closeModal = () => {
  isOpen.value = false;
  selectedPackage.value = null;
  document.body.style.overflow = '';
};

const handleBuyNow = () => {
  closeModal();
  // Trigger purchase modal
  const event = new CustomEvent('show-purchase-modal', { 
    detail: Object.keys(packageDetails).find(key => packageDetails[key].title === selectedPackage.value.title)
  });
  window.dispatchEvent(event);
};

// Listen for events from other components
const handleShowPackageModal = (event) => {
  openModal(event.detail);
};

onMounted(() => {
  window.addEventListener('show-package-modal', handleShowPackageModal);
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isOpen.value) {
      closeModal();
    }
  });
});

onUnmounted(() => {
  window.removeEventListener('show-package-modal', handleShowPackageModal);
});
</script>