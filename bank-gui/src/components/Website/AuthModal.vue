<template>
  <div 
    id="authModal" 
    class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 z-50 transition-all duration-300"
    :class="modalClasses"
    @click.self="closeModal"
  >
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-md transform transition-all duration-300 shadow-2xl relative overflow-hidden" :class="contentClasses">
      <!-- Gradient Top Border -->
      <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-gray-500 via-purple-500 to-pink-500"></div>
      
      <!-- Animated Background Elements -->
      <div class="absolute -top-20 -right-20 w-40 h-40 bg-gray-200 dark:bg-gray-900 rounded-full opacity-20 animate-pulse"></div>
      <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-purple-200 dark:bg-purple-900 rounded-full opacity-20 animate-pulse"></div>
      
      <div class="relative z-10">
        <!-- Header -->
        <div class="text-center mb-8">
          <div class="w-16 h-16 bg-gradient-to-r from-gray-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
            <i class="fas fa-cube text-white text-2xl"></i>
          </div>
          <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-600 to-purple-600 bg-clip-text text-transparent">
            {{ activeTab === 'login' ? 'Welcome Back' : 'Create Account' }}
          </h2>
          <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">
            {{ activeTab === 'login' ? 'Sign in to your Banking Pro account' : 'Join our platform and start building' }}
          </p>
        </div>

        <!-- Tab Navigation -->
        <div class="flex bg-gray-100 dark:bg-gray-700 rounded-2xl p-1 mb-8">
          <button 
            @click="setActiveTab('login')"
            class="tab-button flex-1 py-3 px-4 rounded-xl text-sm font-medium transition-all duration-300 flex items-center justify-center"
            :class="activeTab === 'login' ? 'bg-white dark:bg-gray-800 text-gray-600 shadow-sm' : 'text-gray-600 dark:text-gray-400'"
          >
            <i class="fas fa-sign-in-alt mr-2 text-sm"></i>
            Login
          </button>
          <button 
            @click="setActiveTab('register')"
            class="tab-button flex-1 py-3 px-4 rounded-xl text-sm font-medium transition-all duration-300 flex items-center justify-center"
            :class="activeTab === 'register' ? 'bg-white dark:bg-gray-800 text-gray-600 shadow-sm' : 'text-gray-600 dark:text-gray-400'"
          >
            <i class="fas fa-user-plus mr-2 text-sm"></i>
            Register
          </button>
        </div>

        <!-- Login Form -->
        <div v-show="activeTab === 'login'" class="form-content space-y-6">
          <div v-if="loginSuccess" class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl text-sm animate-fade-in">
            <i class="fas fa-check-circle mr-2"></i>Login successful! Redirecting...
          </div>
          
          <form @submit.prevent="handleLogin" class="space-y-5">
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
              <div class="relative">
                <input 
                  v-model="loginForm.email"
                  type="email" 
                  class="w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:text-white transition-all duration-300 pl-11" 
                  placeholder="your@email.com" 
                  required
                />
                <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
              </div>
            </div>
            
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
              <div class="relative">
                <input 
                  v-model="loginForm.password"
                  :type="showLoginPassword ? 'text' : 'password'"
                  class="w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:text-white transition-all duration-300 pl-11 pr-11" 
                  placeholder="••••••••" 
                  required
                />
                <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                <button 
                  type="button" 
                  @click="showLoginPassword = !showLoginPassword"
                  class="password-toggle absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                >
                  <i :class="showLoginPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                </button>
              </div>
            </div>
            
            <div class="flex items-center justify-between text-sm">
              <label class="flex items-center space-x-2 cursor-pointer">
                <input 
                  v-model="loginForm.rememberMe"
                  type="checkbox" 
                  class="w-4 h-4 text-gray-600 rounded focus:ring-gray-500"
                />
                <span class="text-gray-700 dark:text-gray-300">Remember me</span>
              </label>
              <button 
                type="button" 
                @click="showForgotPasswordModal"
                class="text-gray-600 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-colors font-medium"
              >
                Forgot password?
              </button>
            </div>
            
            <button 
              type="submit" 
              :disabled="isLoggingIn"
              class="w-full bg-gradient-to-r from-gray-500 to-purple-600 text-white py-3.5 rounded-xl hover:shadow-lg transition-all duration-300 font-semibold text-sm hover:scale-[1.02] active:scale-[0.98] shadow-md disabled:opacity-50"
            >
              <i class="fas fa-sign-in-alt mr-2"></i>
              {{ isLoggingIn ? 'Signing In...' : 'Sign In to Account' }}
            </button>
          </form>
          
          <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-3 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">Or continue with</span>
            </div>
          </div>
          
          <div class="grid grid-cols-1 gap-3">
            <button 
              v-for="provider in socialProviders" 
              :key="provider.name"
              @click="socialLogin(provider.name)"
              class="social-login-btn flex items-center justify-center space-x-3 w-full py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-xl hover:shadow-md transition-all duration-300 text-sm font-medium text-gray-700 dark:text-gray-300 hover:scale-[1.02] active:scale-[0.98]"
            >
              <i :class="provider.icon" class="text-base"></i>
              <span>Continue with {{ provider.name }}</span>
            </button>
          </div>
          
          <div class="text-center text-sm text-gray-600 dark:text-gray-400">
            Don't have an account? 
            <button 
              @click="setActiveTab('register')"
              class="text-gray-600 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 font-medium transition-colors"
            >
              Sign up now
            </button>
          </div>
        </div>

        <!-- Register Form -->
        <div v-show="activeTab === 'register'" class="form-content space-y-6">
          <div v-if="registerSuccess" class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl text-sm animate-fade-in">
            <i class="fas fa-check-circle mr-2"></i>Registration successful! Welcome to Banking Pro.
          </div>
          
          <form @submit.prevent="handleRegister" class="space-y-5">
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
              <div class="relative">
                <input 
                  v-model="registerForm.name"
                  type="text" 
                  class="w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:text-white transition-all duration-300 pl-11" 
                  placeholder="John Doe" 
                  required
                />
                <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
              </div>
            </div>
            
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
              <div class="relative">
                <input 
                  v-model="registerForm.email"
                  type="email" 
                  class="w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:text-white transition-all duration-300 pl-11" 
                  placeholder="your@email.com" 
                  required
                />
                <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
              </div>
            </div>
            
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
              <div class="relative">
                <input 
                  v-model="registerForm.password"
                  :type="showRegisterPassword ? 'text' : 'password'"
                  class="w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:text-white transition-all duration-300 pl-11 pr-11" 
                  placeholder="••••••••" 
                  required
                />
                <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                <button 
                  type="button" 
                  @click="showRegisterPassword = !showRegisterPassword"
                  class="password-toggle absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                >
                  <i :class="showRegisterPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                </button>
              </div>
            </div>
            
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
              <div class="relative">
                <input 
                  v-model="registerForm.confirmPassword"
                  :type="showConfirmPassword ? 'text' : 'password'"
                  class="w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:text-white transition-all duration-300 pl-11 pr-11" 
                  placeholder="••••••••" 
                  required
                />
                <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                <button 
                  type="button" 
                  @click="showConfirmPassword = !showConfirmPassword"
                  class="password-toggle absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                >
                  <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                </button>
              </div>
            </div>
            
            <label class="flex items-start space-x-3 text-sm cursor-pointer">
              <input 
                v-model="registerForm.agreeTerms"
                type="checkbox" 
                class="w-4 h-4 text-gray-600 rounded focus:ring-gray-500 mt-0.5"
                required
              />
              <span class="text-gray-700 dark:text-gray-300">
                I agree to the <a href="#" class="text-gray-600 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 font-medium">Terms of Service</a> and <a href="#" class="text-gray-600 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 font-medium">Privacy Policy</a>
              </span>
            </label>
            
            <button 
              type="submit" 
              :disabled="isRegistering"
              class="w-full bg-gradient-to-r from-gray-500 to-purple-600 text-white py-3.5 rounded-xl hover:shadow-lg transition-all duration-300 font-semibold text-sm hover:scale-[1.02] active:scale-[0.98] shadow-md disabled:opacity-50"
            >
              <i class="fas fa-user-plus mr-2"></i>
              {{ isRegistering ? 'Creating Account...' : 'Create New Account' }}
            </button>
          </form>
          
          <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-3 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">Or sign up with</span>
            </div>
          </div>
          
          <div class="grid grid-cols-1 gap-3">
            <button 
              v-for="provider in socialProviders" 
              :key="provider.name"
              @click="socialRegister(provider.name)"
              class="social-login-btn flex items-center justify-center space-x-3 w-full py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-xl hover:shadow-md transition-all duration-300 text-sm font-medium text-gray-700 dark:text-gray-300 hover:scale-[1.02] active:scale-[0.98]"
            >
              <i :class="provider.icon" class="text-base"></i>
              <span>Sign up with {{ provider.name }}</span>
            </button>
          </div>
          
          <div class="text-center text-sm text-gray-600 dark:text-gray-400">
            Already have an account? 
            <button 
              @click="setActiveTab('login')"
              class="text-gray-600 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 font-medium transition-colors"
            >
              Sign in here
            </button>
          </div>
        </div>
      </div>

      <!-- Close Button -->
      <button 
        @click="closeModal"
        class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-all duration-300 hover:scale-110 hover:bg-gray-200 dark:hover:bg-gray-600"
        aria-label="Close modal"
      >
        <i class="fas fa-times text-sm"></i>
      </button>
    </div>
  </div>

  <!-- Forgot Password Modal -->
  <ForgotPasswordModal 
    v-if="showForgotPassword" 
    @close="showForgotPassword = false" 
    @back-to-login="handleBackToLogin"
  />
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import ForgotPasswordModal from './ForgotPasswordModal.vue';

const isOpen = ref(false);
const activeTab = ref('login');
const showLoginPassword = ref(false);
const showRegisterPassword = ref(false);
const showConfirmPassword = ref(false);
const showForgotPassword = ref(false);
const isLoggingIn = ref(false);
const isRegistering = ref(false);
const loginSuccess = ref(false);
const registerSuccess = ref(false);

const loginForm = ref({
  email: '',
  password: '',
  rememberMe: false
});

const registerForm = ref({
  name: '',
  email: '',
  password: '',
  confirmPassword: '',
  agreeTerms: false
});

const socialProviders = [
  { name: 'Google', icon: 'fab fa-google text-red-500' },
  { name: 'GitHub', icon: 'fab fa-github text-gray-800 dark:text-white' },
  { name: 'Facebook', icon: 'fab fa-facebook text-gray-600' }
];

const modalClasses = computed(() => ({
  'opacity-0 invisible scale-95': !isOpen.value,
  'opacity-100 visible scale-100': isOpen.value
}));

const contentClasses = computed(() => ({
  'scale-95': !isOpen.value,
  'scale-100': isOpen.value
}));

const setActiveTab = (tab) => {
  activeTab.value = tab;
  loginSuccess.value = false;
  registerSuccess.value = false;
};

const openModal = () => {
  isOpen.value = true;
  document.body.style.overflow = 'hidden';
};

const closeModal = () => {
  isOpen.value = false;
  activeTab.value = 'login';
  resetForms();
  document.body.style.overflow = '';
};

const resetForms = () => {
  loginForm.value = {
    email: '',
    password: '',
    rememberMe: false
  };
  registerForm.value = {
    name: '',
    email: '',
    password: '',
    confirmPassword: '',
    agreeTerms: false
  };
  loginSuccess.value = false;
  registerSuccess.value = false;
};

const handleLogin = async () => {
  isLoggingIn.value = true;
  
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1500));
    
    loginSuccess.value = true;
    
    // Simulate redirect after successful login
    setTimeout(() => {
      closeModal();
      // Navigate to dashboard
      navigateTo('/dashboard');
    }, 2000);
  } catch (error) {
    console.error('Login failed:', error);
  } finally {
    isLoggingIn.value = false;
  }
};

const handleRegister = async () => {
  if (registerForm.value.password !== registerForm.value.confirmPassword) {
    alert('Passwords do not match');
    return;
  }
  
  isRegistering.value = true;
  
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1500));
    
    registerSuccess.value = true;
    
    // Auto-switch to login after successful registration
    setTimeout(() => {
      setActiveTab('login');
      registerSuccess.value = false;
    }, 3000);
  } catch (error) {
    console.error('Registration failed:', error);
  } finally {
    isRegistering.value = false;
  }
};

const socialLogin = (provider) => {
  console.log(`Social login with ${provider}`);
  // Implement social login logic here
};

const socialRegister = (provider) => {
  console.log(`Social register with ${provider}`);
  // Implement social register logic here
};

const showForgotPasswordModal = () => {
  isOpen.value = false;
  showForgotPassword.value = true;
};

const handleBackToLogin = () => {
  showForgotPassword.value = false;
  openModal();
  setActiveTab('login');
};

// Listen for events from other components
const handleShowAuthModal = () => {
  openModal();
};

onMounted(() => {
  window.addEventListener('show-auth-modal', handleShowAuthModal);
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isOpen.value) {
      closeModal();
    }
  });
});

onUnmounted(() => {
  window.removeEventListener('show-auth-modal', handleShowAuthModal);
});
</script>