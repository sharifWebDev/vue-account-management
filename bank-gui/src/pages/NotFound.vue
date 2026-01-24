<template>
    <div
        class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex flex-col items-center justify-center p-6">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none my-auto">
            <div
                class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse">
            </div>
            <div
                class="absolute -bottom-40 -left-40 w-80 h-80 bg-gray-300 dark:bg-gray-900 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse delay-1000">
            </div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-pink-300 dark:bg-pink-900 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse delay-500">
            </div>
        </div>

        <!-- Main Content -->
        <div class="relative z-10 text-center max-w-2xl mx-auto">
            <!-- Animated Number -->
            <div class="relative mb-8">
                <span
                    class="text-[4rem] md:text-[7rem] font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-500 leading-none tracking-tighter">
                    404
                </span>
            </div>

            <!-- Title & Message -->
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Oops! Page Lost in Space
            </h1>

            <p class="text-lg text-gray-600 dark:text-gray-300 mb-8 max-w-lg mx-auto">
                The page you're looking for seems to have drifted into the unknown. Don't worry, we'll help you navigate
                back.
            </p>


            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-7">
                <router-link to="/"
                    class="group relative px-5 text-sm py-3 bg-gradient-to-r from-gray-500 to-cyan-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-101">
                    <div
                        class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-xl transition-opacity duration-300">
                    </div>
                    <i class="fas fa-home mr-2"></i>
                    Back to Home
                </router-link>


                <button @click="goBack"
                    class="group relative px-5 py-3 bg-gradient-to-r from-gray-700 to-gray-900 dark:from-gray-600 dark:to-gray-800 text-white text-sm rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-101">
                    <div
                        class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-xl transition-opacity duration-300">
                    </div>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Go Back
                </button>
            </div>
 
        </div>

        <!-- Footer Note -->
        <div class="absolute bottom-2 text-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">
                © {{ currentYear }} Your App Name •
                <button @click="reportIssue"
                    class="hover:text-purple-500 dark:hover:text-purple-400 transition-colors ml-2">
                    Report this issue
                </button>
            </p>
        </div>

        <!-- Toast Notification -->
        <Transition name="toast">
            <div v-if="showToast"
                class="fixed top-6 right-6 bg-gradient-to-r from-green-500 to-gray-600 text-white px-6 py-3 rounded-lg shadow-xl flex items-center space-x-3">
                <i class="fas fa-check-circle"></i>
                <span>{{ toastMessage }}</span>
            </div>
        </Transition>
    </div>
</template>
<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()
const searchQuery = ref('')
const showToast = ref(false)
const toastMessage = ref('')

const currentYear = computed(() => new Date().getFullYear())
const isAuthenticated = computed(() => authStore.isAuthenticated)

const quickLinks = [
    { to: '/', label: 'Home', icon: 'fas fa-home' },
    { to: '/dashboard', label: 'Dashboard', icon: 'fas fa-tachometer-alt' },
    { to: '/about', label: 'About', icon: 'fas fa-info-circle' },
    { to: '/contact', label: 'Contact', icon: 'fas fa-envelope' },
    { to: '/help', label: 'Help Center', icon: 'fas fa-question-circle' },
]

const goBack = () => {
    if (window.history.length > 1) {
        router.go(-1)
    } else {
        router.push('/')
    }
}

const contactSupport = () => {
    // Implement contact support logic
    showToastMessage('Opening support chat...')
}

const reportIssue = () => {
    // Implement issue reporting
    showToastMessage('Issue reported successfully!')
}

const showToastMessage = (message) => {
    toastMessage.value = message
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 3000)
}

// Add floating particles animation on mount
onMounted(() => {
    if (typeof window !== 'undefined') {
        // Could add particle.js or custom floating elements here
    }
})
</script>

<style scoped>
/* Custom animations */
@keyframes float {

    0%,
    100% {
        transform: translateY(0) rotate(0deg);
    }

    50% {
        transform: translateY(-20px) rotate(10deg);
    }
}

@keyframes spin-slow {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-spin-slow {
    animation: spin-slow 8s linear infinite;
}

/* Toast transition */
.toast-enter-active {
    transition: all 0.3s ease-out;
}

.toast-leave-active {
    transition: all 0.3s ease-in;
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(100px);
}

.toast-leave-to {
    opacity: 0;
    transform: translateX(100px);
}

/* Gradient text animation */
.bg-gradient-animate {
    background-size: 200% 200%;
    animation: gradient 3s ease infinite;
}

@keyframes gradient {

    0%,
    100% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }
}

/* Smooth transitions */
* {
    transition: background-color 0.3s ease, border-color 0.3s ease;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #8B5CF6, #EC4899);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #7C3AED, #DB2777);
}
</style>