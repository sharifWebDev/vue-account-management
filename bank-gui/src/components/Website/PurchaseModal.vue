<template>
  <div 
    id="purchaseModal" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 transition-all duration-300"
    :class="{ 'opacity-0 invisible': !isOpen, 'opacity-100 visible': isOpen }"
    @click.self="closeModal"
  >
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full shadow-2xl">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Complete Purchase</h3>
        <button 
          @click="closeModal" 
          class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition"
          aria-label="Close modal"
        >
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      
      <!-- Step Indicators -->
      <div class="px-6 pt-6">
        <div class="flex justify-between">
          <div 
            v-for="step in steps" 
            :key="step.id" 
            class="step-indicator flex flex-col items-center"
          >
            <div 
              class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-sm font-semibold transition-all duration-300"
              :class="getStepClass(step.id)"
            >
              <i v-if="currentStep > step.id" class="fas fa-check text-xs"></i>
              <span v-else>{{ step.id }}</span>
            </div>
            <span class="text-xs mt-1 text-gray-600 dark:text-gray-400">{{ step.label }}</span>
          </div>
        </div>
      </div>
      
      <div class="p-6">
        <!-- Step 1: Account Details -->
        <div v-show="currentStep === 1" class="step-content">
          <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Create Your Account</h4>
          <form @submit.prevent="goToStep(2)" class="space-y-4">
            <div>
              <label for="purchaseName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
              <input 
                v-model="form.name"
                type="text" 
                id="purchaseName" 
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:text-white transition" 
                placeholder="Your name"
                required
              />
            </div>
            
            <div>
              <label for="purchaseEmail" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
              <input 
                v-model="form.email"
                type="email" 
                id="purchaseEmail" 
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:text-white transition" 
                placeholder="Your email"
                required
              />
            </div>
            
            <div>
              <label for="purchasePassword" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
              <input 
                v-model="form.password"
                type="password" 
                id="purchasePassword" 
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:text-white transition" 
                placeholder="Create a password"
                required
              />
            </div>
            
            <div class="flex items-start">
              <input 
                v-model="form.agreeTerms"
                type="checkbox" 
                id="terms" 
                class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 rounded mt-1"
                required
              />
              <label for="terms" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                I agree to the <a href="#" class="text-gray-600 hover:text-gray-500">Terms of Service</a> and <a href="#" class="text-gray-600 hover:text-gray-500">Privacy Policy</a>
              </label>
            </div>
          </form>
          
          <div class="mt-6 flex justify-between">
            <button @click="closeModal" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition">
              Cancel
            </button>
            <button @click="goToStep(2)" class="bg-gray-600 text-white px-6 py-2 rounded-xl hover:bg-gray-700 transition font-medium">
              Next
            </button>
          </div>
        </div>
        
        <!-- Step 2: Payment -->
        <div v-show="currentStep === 2" class="step-content">
          <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Payment Details</h4>
          
          <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 mb-6">
            <div class="flex justify-between mb-2">
              <span class="text-gray-600 dark:text-gray-300">Package:</span>
              <span class="font-semibold text-gray-800 dark:text-white">{{ selectedPackageName }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600 dark:text-gray-300">Total:</span>
              <span class="font-semibold text-gray-800 dark:text-white">{{ selectedPackagePrice }}</span>
            </div>
          </div>
          
          <form @submit.prevent="processPayment" class="space-y-4">
            <div>
              <label for="cardNumber" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Card Number</label>
              <input 
                v-model="payment.cardNumber"
                type="text" 
                id="cardNumber" 
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:text-white transition" 
                placeholder="1234 5678 9012 3456"
                required
              />
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="expiryDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expiry Date</label>
                <input 
                  v-model="payment.expiryDate"
                  type="text" 
                  id="expiryDate" 
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:text-white transition" 
                  placeholder="MM/YY"
                  required
                />
              </div>
              
              <div>
                <label for="cvv" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CVV</label>
                <input 
                  v-model="payment.cvv"
                  type="text" 
                  id="cvv" 
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:text-white transition" 
                  placeholder="123"
                  required
                />
              </div>
            </div>
            
            <div>
              <label for="cardName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name on Card</label>
              <input 
                v-model="payment.cardName"
                type="text" 
                id="cardName" 
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:text-white transition" 
                placeholder="Your name"
                required
              />
            </div>
          </form>
          
          <div class="mt-6 flex justify-between">
            <button @click="goToStep(1)" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition">
              Back
            </button>
            <button @click="processPayment" class="bg-gray-600 text-white px-6 py-2 rounded-xl hover:bg-gray-700 transition font-medium">
              Next
            </button>
          </div>
        </div>
        
        <!-- Step 3: Confirmation -->
        <div v-show="currentStep === 3" class="step-content">
          <div class="text-center">
            <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-check text-green-600 dark:text-green-400 text-2xl"></i>
            </div>
            
            <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Purchase Successful!</h4>
            <p class="text-gray-600 dark:text-gray-300 mb-6">
              Thank you for your purchase. You can now access your dashboard and start building applications.
            </p>
            
            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 mb-6">
              <div class="flex justify-between mb-2">
                <span class="text-gray-600 dark:text-gray-300">Package:</span>
                <span class="font-semibold text-gray-800 dark:text-white">{{ selectedPackageName }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-300">Total:</span>
                <span class="font-semibold text-gray-800 dark:text-white">{{ selectedPackagePrice }}</span>
              </div>
            </div>
            
            <div class="flex flex-col space-y-3">
              <router-link 
                to="/dashboard" 
                class="bg-gray-600 text-white py-3 rounded-xl hover:bg-gray-700 transition font-semibold text-center"
                @click.native="closeModal"
              >
                Go to Dashboard
              </router-link>
              <button @click="closeModal" class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const isOpen = ref(false);
const currentStep = ref(1);
const selectedPackageName = ref('');
const selectedPackagePrice = ref('');

const steps = [
  { id: 1, label: 'Account' },
  { id: 2, label: 'Payment' },
  { id: 3, label: 'Confirm' }
];

const form = ref({
  name: '',
  email: '',
  password: '',
  agreeTerms: false
});

const payment = ref({
  cardNumber: '',
  expiryDate: '',
  cvv: '',
  cardName: ''
});

const packagePrices = {
  basic: '$49/month',
  standard: '$99/month',
  enterprise: '$199/month'
};

const packageNames = {
  basic: 'Basic',
  standard: 'Standard',
  enterprise: 'Enterprise'
};

const getStepClass = (stepId) => {
  if (currentStep.value > stepId) {
    return 'border-green-500 bg-green-500 text-white';
  } else if (currentStep.value === stepId) {
    return 'border-gray-600 text-gray-600 bg-gray-100 dark:bg-gray-900/30';
  } else {
    return 'border-gray-300 dark:border-gray-600 text-gray-500';
  }
};

const goToStep = (step) => {
  currentStep.value = step;
};

const openModal = (packageType) => {
  selectedPackageName.value = packageNames[packageType];
  selectedPackagePrice.value = packagePrices[packageType];
  isOpen.value = true;
  currentStep.value = 1;
  document.body.style.overflow = 'hidden';
};

const closeModal = () => {
  isOpen.value = false;
  currentStep.value = 1;
  resetForms();
  document.body.style.overflow = '';
};

const resetForms = () => {
  form.value = {
    name: '',
    email: '',
    password: '',
    agreeTerms: false
  };
  payment.value = {
    cardNumber: '',
    expiryDate: '',
    cvv: '',
    cardName: ''
  };
};

const processPayment = async () => {
  // Simulate payment processing
  await new Promise(resolve => setTimeout(resolve, 1000));
  goToStep(3);
};

// Listen for events from other components
const handleShowPurchaseModal = (event) => {
  openModal(event.detail);
};

onMounted(() => {
  window.addEventListener('show-purchase-modal', handleShowPurchaseModal);
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isOpen.value) {
      closeModal();
    }
  });
});

onUnmounted(() => {
  window.removeEventListener('show-purchase-modal', handleShowPurchaseModal);
});
</script>