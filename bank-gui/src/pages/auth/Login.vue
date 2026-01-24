<template>
  <div :class="[
    'min-h-screen transition-all duration-500 ease-in-out m-0',
    darkMode ? 'bg-gradient-to-br from-gray-900 to-gray-800' : ''
  ]">
    <div class="flex flex-col lg:flex-row align-center justify-center min-h-screen">
      <!-- Left Side Banner (50%) - Enhanced Design -->
      <div :class="[
        'lg:w-1/3 relative overflow-hidden transition-all duration-700']">
        <!-- Animated Background Pattern -->
        <div class="absolute inset-0 opacity-10">
          <div class="absolute inset-0" 
               :style="{
                 backgroundImage: darkMode 
                   ? 'radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 50%)'
                   : 'radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.05) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.05) 0%, transparent 50%)'
               }">
          </div>
        </div>

        <!-- Floating Shapes -->
        <div class="absolute top-1/4 left-1/4 w-64 h-64 rounded-full blur-3xl opacity-20 animate-pulse"
             :class="darkMode ? 'bg-purple-500/20' : 'bg-gray-300/30'"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 rounded-full blur-3xl opacity-10 animate-pulse delay-1000"
             :class="darkMode ? 'bg-gray-500/20' : 'bg-gray-400/20'"></div>

        <!-- Content Container -->
        <div class="relative z-10 h-full flex flex-col justify-center p-8 md:p-12 lg:p-16">
          <!-- Header with Theme Toggle -->
          <div class="flex justify-between items-start">
            <div class="flex items-center space-x-3">
              <div :class="[
                'w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-500 border border bg-gradient-to-br from-blue-500 to-purple-600',
                darkMode ? 'border-purple-400' : 'border-blue-300'
              ]">
                <i class="fas fa-network-wired text-xl text-white"></i>
              </div>
              <div>
                <h2 class="text-2xl font-bold transition-colors duration-500" 
                    :class="darkMode ? 'text-white' : 'text-gray-800'">
                  i-Mesh<span :class="darkMode ? 'text-gray-300' : 'text-gray-600'"> </span>
                </h2>
                <p class="text-sm transition-colors duration-500"
                   :class="darkMode ? 'text-gray-200' : 'text-gray-500'">
                  Bank Management Platform
                </p>
              </div>
            </div> 
          </div>
          
          <!-- Main Content -->
          <div class="mt-4 max-w-md animate-slide-up">
            <h5 class="text-2xl md:text-3xl font-bold mb-6 transition-colors duration-500"
                :class="darkMode ? 'text-white' : 'text-gray-800'">
              Complete Bank Management Solution for Modern Financial Institutions
            </h5>
            
            <!-- Feature List -->
            <div class="space-y-6">
              <div v-for="(feature, index) in features" :key="index"
                   class="flex items-start space-x-4 animate-slide-up"
                   :style="{ animationDelay: `${index * 100}ms` }">
                <div :class="[
                  'w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-500',
                  darkMode ? 'bg-white/10 hover:bg-white/20' : 'bg-gray-50 hover:bg-gray-100'
                ]">
                  <i :class="feature.icon" class="text-lg transition-colors duration-500" ></i>
                </div>
                <div>
                  <h3 class="font-semibold mb-1 transition-colors duration-500"
                      :class="darkMode ? 'text-white' : 'text-gray-800'">
                    {{ feature.title }}
                  </h3>
                  <p class="text-sm transition-colors duration-500"
                     :class="darkMode ? 'text-gray-200' : 'text-gray-600'">
                    {{ feature.description }}
                  </p>
                </div>
              </div>
            </div>
          </div> 
        </div>
      </div>

      <!-- Right Side Login Form (50%) - Enhanced Design -->
      <div class="lg:w-1/2 flex items-center justify-center p-6 md:p-12">
        <div class="w-full max-w-md animate-slide-up" style="animation-delay: 200ms">
          <!-- Form Container -->
          <div :class="[
            'rounded-3xl p-8 transition-all duration-500 overflow-hidden relative border shadow',
            darkMode ? 'bg-gray-800/80 backdrop-blur-lg border border-gray-700/50' : 'bg-white/90 backdrop-blur-sm border border-white/50'
          ]"> 
            <!-- Header -->
            <div class="text-center mb-8">
              <div :class="[
                'w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 transition-all duration-500',
                darkMode ? 'bg-gradient-to-br from-gray-600/20 to-purple-600/20 border border-gray-500/20' : 'bg-gray-50 border border-gray-100'
              ]">
                <i class="fas fa-lock text-2xl" :class="darkMode ? 'text-gray-300' : 'text-gray-600'"></i>
              </div>
              <h3 class="text-2xl font-bold mb-2 transition-colors duration-500"
                  :class="darkMode ? 'text-white' : 'text-gray-800'">
                Welcome Back
              </h3>
              <p class="transition-colors duration-500"
                 :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                Sign in to your account to continue
              </p>
            </div>

            <!-- Messages -->
            <div v-if="successMessage" 
                 :class="[
                   'mb-6 p-4 rounded-xl border transition-all duration-500 animate-slide-down',
                   darkMode ? 'bg-green-900/30 border-green-800' : 'bg-green-50 border-green-200'
                 ]">
              <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 transition-colors duration-500"
                   :class="darkMode ? 'text-green-400' : 'text-green-600'"></i>
                <span :class="darkMode ? 'text-green-300' : 'text-green-800'">{{ successMessage }}</span>
              </div>
            </div>
            
            <div v-if="errorMessage" 
                 :class="[
                   'mb-6 p-4 rounded-xl border transition-all duration-500 animate-slide-down',
                   darkMode ? 'bg-red-900/30 border-red-800' : 'bg-red-50 border-red-200'
                 ]">
              <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 transition-colors duration-500"
                   :class="darkMode ? 'text-red-400' : 'text-red-600'"></i>
                <span :class="darkMode ? 'text-red-300' : 'text-red-800'">{{ errorMessage }}</span>
              </div>
            </div>

            <!-- Login Form -->
            <form @submit.prevent="handleSubmit" class="space-y-6">
              <!-- Email Field -->
              <div>
                <label :class="[
                  'block text-sm font-medium mb-2 transition-colors duration-500',
                  darkMode ? 'text-gray-300' : 'text-gray-700'
                ]">
                  Email
                  <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope transition-colors duration-500"
                       :class="darkMode ? 'text-gray-500' : 'text-gray-400'"></i>
                  </div>
                  <input
                    type="email"
                    v-model.trim="form.email"
                    placeholder="you@example.com"
                    :class="[
                      'w-full pl-10 pr-4 py-2 border outline-none rounded-xl transition-all duration-500 focus:ring-2 focus:ring-gray-500 focus:border-transparent placeholder:transition-colors',
                      darkMode 
                        ? 'bg-gray-700/50 border-gray-600 text-white placeholder-gray-500 focus:bg-gray-700'
                        : 'bg-white border-gray-300 text-gray-900 placeholder-gray-400 focus:bg-white',
                      errors.email.length
                        ? 'border-red-500 focus:ring-red-500'
                        : 'border-gray-300'
                    ]"
                    @blur="validateField('email')"
                    @input="clearFieldError('email')"
                  />
                  <div v-if="form.email && !errors.email.length" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <i class="fas fa-check transition-colors duration-500"
                       :class="darkMode ? 'text-green-400' : 'text-green-500'"></i>
                  </div>
                </div>
                <div v-if="errors.email.length" class="mt-2 flex items-center space-x-2 text-sm animate-slide-down">
                  <i class="fas fa-exclamation-circle text-red-500"></i>
                  <span :class="darkMode ? 'text-red-400' : 'text-red-600'">{{ errors.email[0] }}</span>
                </div>
              </div>

              <!-- Password Field -->
              <div>
                <label :class="[
                  'block text-sm font-medium mb-2 transition-colors duration-500',
                  darkMode ? 'text-gray-300' : 'text-gray-700'
                ]">
                  Password
                  <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock transition-colors duration-500"
                       :class="darkMode ? 'text-gray-500' : 'text-gray-400'"></i>
                  </div>
                  <input
                    :type="showPassword ? 'text' : 'password'"
                    v-model.trim="form.password"
                    placeholder="Enter your password"
                    :class="[
                      'w-full pl-10 pr-12 py-2 border outline-none rounded-xl transition-all duration-500 focus:ring-2 focus:ring-gray-500 focus:border-transparent placeholder:transition-colors',
                      darkMode 
                        ? 'bg-gray-700/50 border-gray-600 text-white placeholder-gray-500 focus:bg-gray-700'
                        : 'bg-white border-gray-300 text-gray-900 placeholder-gray-400 focus:bg-white',
                      errors.password.length
                        ? 'border-red-500 focus:ring-red-500'
                        : 'border-gray-300'
                    ]"
                    @blur="validateField('password')"
                    @input="clearFieldError('password')"
                  />
                  <button
                    type="button"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none transition-colors duration-500 hover:scale-110"
                    @click="showPassword = !showPassword"
                    :class="darkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-600 hover:text-gray-800'"
                  >
                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                  </button>
                </div>
                
                <!-- Password Strength -->
                <div v-if="form.password && !errors.password.length" class="mt-4 space-y-2 animate-slide-up">
                  <div class="flex items-center justify-between text-xs transition-colors duration-500"
                       :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                    <span>Password strength</span>
                    <span :class="passwordStrengthClass">{{ passwordStrengthText }}</span>
                  </div>
                  <div :class="[
                    'w-full rounded-full h-1.5 transition-colors duration-500',
                    darkMode ? 'bg-gray-700' : 'bg-gray-200'
                  ]">
                    <div 
                      class="h-1.5 rounded-full transition-all duration-500"
                      :class="passwordStrengthBarClass"
                      :style="{ width: passwordStrength + '%' }"
                    ></div>
                  </div>
                </div>
                
                <div v-if="errors.password.length" class="mt-2 flex items-center space-x-2 text-sm animate-slide-down">
                  <i class="fas fa-exclamation-circle text-red-500"></i>
                  <span :class="darkMode ? 'text-red-400' : 'text-red-600'">{{ errors.password[0] }}</span>
                </div>
              </div>

              <!-- Remember & Forgot -->
              <div class="flex items-center justify-between">
                <label :class="[
                  'flex items-center space-x-3 cursor-pointer transition-colors duration-500',
                  darkMode ? 'text-gray-300' : 'text-gray-700'
                ]">
                  <div class="relative">
                    <input
                      type="checkbox"
                      v-model="form.remember"
                      class="sr-only"
                    />
                    <div :class="[
                      'w-5 h-5 rounded border transition-colors duration-500 flex items-center justify-center transition-all duration-300',
                      darkMode 
                        ? form.remember ? 'bg-gray-500 border-gray-500' : 'border-gray-500 hover:border-gray-400'
                        : form.remember ? 'bg-gray-500 border-gray-500' : 'border-gray-300 hover:border-gray-400'
                    ]">
                      <i v-if="form.remember" class="fas fa-check text-white text-xs"></i>
                    </div>
                  </div>
                  <span class="text-sm">Remember me</span>
                </label>
                
                <router-link
                  to="/forgot-password"
                  :class="[
                    'text-sm transition-colors duration-500 hover:scale-105 active:scale-95 font-medium',
                    darkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-600 hover:text-gray-800'
                  ]"
                >
                  Forgot password?
                </router-link>
              </div>

              <!-- Login Button -->
              <button
                type="submit"
                :disabled="loadingBtn || !form.email || !form.password"
                :class="[
                  'w-full py-2 rounded-xl font-semibold transition-all duration-500 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center',
                  darkMode 
                    ? 'bg-gradient-to-r from-gray-600 to-purple-600 text-white hover:hover:shadow-gray-500/25'
                    : 'bg-purple-700 text-white hover:bg-purple-800 text-white',
                  !loadingBtn && !disabled ? 'hover:scale-[1.02] active:scale-[0.98]' : ''
                ]"
              >
                <span v-if="loadingBtn" class="animate-spin border-2 border-white border-t-transparent rounded-full w-5 h-5 mr-3"></span>
                <span class="flex items-center">{{ loadingBtn ? 'Signing in...' : 'Sign in to Account' }}</span>
                <i v-if="!loadingBtn" class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
              </button>
  
            </form>
          </div>

          <!-- Footer -->
          <div class="mt-8 text-center text-sm transition-colors duration-500"
               :class="darkMode ? 'text-gray-500' : 'text-gray-400'">
            <p>By signing in, you agree to our</p>
            <p>
              <router-link :to="link.to" v-for="link in footerLinks" :key="link.text"
                :class="[
                  'transition-all duration-500 hover:scale-105 active:scale-95',
                  darkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-600 hover:text-gray-800'
                ]">
                {{ link.text }}
              </router-link>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "@/stores/authStore";
import { useDarkModeStore } from "@/stores/darkMode";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const darkModeStore = useDarkModeStore();
const router = useRouter();

// Theme
const darkMode = computed(() => darkModeStore.darkMode);
const toggleTheme = () => darkModeStore.toggleDarkMode();

// Form state
const form = ref({
  email: "",
  password: "",
  remember: false,
});

const errors = ref({
  email: [],
  password: [],
});

const loadingBtn = ref(false);
const errorMessage = ref("");
const successMessage = ref("");
const showPassword = ref(false);

// Features list
const features = [
  {
    icon: "fas fa-university",
    title: "Multi-Branch Management",
    description: "Manage multiple bank branches from a single unified dashboard"
  },
  {
    icon: "fas fa-lock",
    title: "Advanced Security",
    description: "Bank-grade encryption and multi-level authentication protocols"
  },
  {
    icon: "fas fa-chart-pie",
    title: "Real-time Analytics",
    description: "Monitor transactions and generate detailed financial reports instantly"
  },
  {
    icon: "fas fa-exchange-alt",
    title: "Transaction Processing",
    description: "Fast and secure processing of deposits, withdrawals, and transfers"
  }
];

// Social providers
const socialProviders = [
  {
    name: "Google",
    icon: "fab fa-google",
    color: "text-red-500"
  },
  {
    name: "GitHub",
    icon: "fab fa-github",
    color: darkMode.value ? "text-white" : "text-gray-800"
  }
];

// Footer links
const footerLinks = [
  { text: "Terms of Service", to: "/terms" },
  { text: "and", to: "#", class: "text-gray-500 hover:text-gray-500 cursor-default" },
  { text: "Privacy Policy", to: "/privacy" }
];

// Password strength
const passwordStrength = computed(() => {
  const password = form.value.password;
  if (!password) return 0;
  
  let strength = 0;
  
  if (password.length >= 8) strength += 25;
  if (password.length >= 12) strength += 15;
  if (/[A-Z]/.test(password)) strength += 20;
  if (/[a-z]/.test(password)) strength += 20;
  if (/[0-9]/.test(password)) strength += 20;
  if (/[^A-Za-z0-9]/.test(password)) strength += 25;
  
  return Math.min(strength, 100);
});

const passwordStrengthText = computed(() => {
  const strength = passwordStrength.value;
  if (strength < 40) return "Weak";
  if (strength < 70) return "Fair";
  if (strength < 90) return "Good";
  return "Strong";
});

const passwordStrengthClass = computed(() => {
  const strength = passwordStrength.value;
  if (strength < 40) return darkMode.value ? "text-red-400" : "text-red-600";
  if (strength < 70) return darkMode.value ? "text-yellow-400" : "text-yellow-600";
  if (strength < 90) return darkMode.value ? "text-gray-400" : "text-gray-600";
  return darkMode.value ? "text-green-400" : "text-green-600";
});

const passwordStrengthBarClass = computed(() => {
  const strength = passwordStrength.value;
  if (strength < 40) return "bg-red-500";
  if (strength < 70) return "bg-yellow-500";
  if (strength < 90) return "bg-gray-500";
  return "bg-green-500";
});

// Validation
const validateEmail = (email) => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
};

const validateField = (field) => {
  const value = form.value[field];
  errors.value[field] = [];

  if (!value) {
    errors.value[field] = [`${field.charAt(0).toUpperCase() + field.slice(1)} is required`];
    return false;
  }

  if (field === "email") {
    if (!validateEmail(value)) {
      errors.value.email = ["Please enter a valid email address"];
      return false;
    }
  }

  if (field === "password") {
    if (value.length < 6) {
      errors.value.password = ["Password must be at least 6 characters"];
      return false;
    }
  }

  return true;
};

const clearFieldError = (field) => {
  if (errors.value[field].length) {
    errors.value[field] = [];
  }
};

const validateForm = () => {
  let isValid = true;
  if (!validateField("email")) isValid = false;
  if (!validateField("password")) isValid = false;
  return isValid;
};

// Social login
const socialLogin = (provider) => {
  console.log(`Social login with ${provider}`);
  // Implement OAuth here
};

// Form submission
const handleSubmit = async () => {
  if (!validateForm()) {
    errorMessage.value = "Please fix the errors in the form";
    return;
  }

  try {
    loadingBtn.value = true;
    errorMessage.value = "";
    successMessage.value = "";

    const result = await authStore.login(form.value);

    // if login failed
    if (!result.success) {
      errorMessage.value = result.error || "Invalid email or password.";
      return;
    }

    // ✅ success
    form.value = { email: "", password: "", remember: false };
    successMessage.value = "Login successful! Redirecting...";

    setTimeout(() => {
      router.push("/dashboard");
    }, 1000);

  } finally {
    loadingBtn.value = false;
  }

}; 
</script>

<style scoped>
/* Animations */
@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-slide-up {
  animation: slideUp 0.5s ease-out forwards;
}

.animate-slide-down {
  animation: slideDown 0.3s ease-out forwards;
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Smooth transitions */
* {
  transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
}

/* Glass effect enhancements */
.backdrop-blur-lg {
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}

.backdrop-blur-sm {
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}

/* Gradient text */
.gradient-text {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.1);
}

::-webkit-scrollbar-thumb {
  background: rgba(59, 130, 246, 0.5);
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(59, 130, 246, 0.7);
}

.dark ::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.1);
}

.dark ::-webkit-scrollbar-thumb {
  background: rgba(139, 92, 246, 0.5);
}

.dark ::-webkit-scrollbar-thumb:hover {
  background: rgba(139, 92, 246, 0.7);
}

/* Responsive improvements */
@media (max-width: 1024px) {
  .lg\:w-1\/2 {
    width: 100%;
  }
  
  .min-h-screen {
    min-height: auto;
  }
}

/* Focus styles */
:focus {
  outline: 2px solid rgba(59, 130, 246, 0.5);
  outline-offset: 2px;
}

:focus:not(:focus-visible) {
  outline: none;
}

/* Print styles */
@media print {
  .no-print {
    display: none;
  }
}
</style>