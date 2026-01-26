<template>
  <div class="flex h-screen bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <!-- Preloader Overlay -->
    <div v-if="isLoading" class="fixed inset-0 bg-white dark:bg-gray-900 z-50 flex items-center justify-center">
      <PreLoader />
    </div>

    <!-- Dashboard Sidebar -->
    <Sidebar :user="getUserData()" :class="[
      'fixed md:relative z-30',
      sidebarStore.isMobileOpen ? 'open' : ''
    ]" />

    <!-- Mobile Overlay -->
    <div v-if="sidebarStore.isMobileOpen" @click="sidebarStore.closeMobile"
      class="overlay fixed inset-0 bg-opacity-50 z-40 md:hidden" />

    <!-- Main Content Area -->
    <div class="main-content flex-1 flex flex-col overflow-hidden transition-all duration-300">
      <!-- Dashboard Header -->
      <Header :user="getUserData()" @toggle-sidebar="toggleSidebar" @toggle-mobile-sidebar="toggleMobileSidebar"
        @logout="logout" />

      <!-- Alert Messages -->
      <div class="px-5">
        <AlertMessages :errors="authStore.error ? [authStore.error] : []" :errorMessage="authStore.error"
          :successMessage="''" @clearErrors="clearErrors" @clearSuccess="clearSuccess" />
      </div>

      <main
        class="content p-4 md:px-5 lg:px-7 overflow-y-auto no-scrollbar flex-1 min-h-[calc(100vh-9rem)] max-h-[100vh-9rem]">
        <!-- <transition name="fade" mode="out-in"> -->
        <!-- <keep-alive> -->
        <Suspense>
          <template #default>
            <router-view />
          </template>
          <template #fallback>
            <PreLoader />
          </template>
        </Suspense>
        <!-- </keep-alive> -->
        <!-- </transition> -->
      </main>

      <!-- Dashboard Footer -->
      <!-- <Footer /> -->
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import { useAuthStore } from "@/stores/authStore";
import { useSidebarStore } from "@/stores/sidebar";
import { useRouter } from "vue-router";
import PreLoader from "@/components/core/PreLoader.vue";
import Sidebar from "@/layouts/Dashboard/Sidebar.vue";
import Header from "@/layouts/Dashboard/Header.vue";
import Footer from "@/layouts/Dashboard/Footer.vue";
import AlertMessages from "@/components/core/AlertMessages.vue";

const authStore = useAuthStore();
const sidebarStore = useSidebarStore();
const router = useRouter();

const isLoading = ref(true);
const windowWidth = ref(window.innerWidth);

// Computed properties from auth store
const user = computed(() => authStore.user);
const userInitials = computed(() => authStore.initials);
const userName = computed(() => authStore.fullName);
const userEmail = computed(() => authStore.user?.email || "");
const userRole = computed(() => authStore.role);

onMounted(async () => {
  // Initialize auth on mount
  await authStore.initializeAuth();

  // Check if user is authenticated
  if (!authStore.isAuthenticated) {
    router.push("/login");
    return;
  }

  // Simulate loading delay
  setTimeout(() => {
    isLoading.value = false;
  }, 500);

  // Handle window resize
  window.addEventListener("resize", handleResize);
});

onUnmounted(() => {
  window.removeEventListener("resize", handleResize);
});

const handleResize = () => {
  windowWidth.value = window.innerWidth;
  if (windowWidth.value >= 768) {
    sidebarStore.isMobileOpen = false;
  }
};

// Logout Function
const logout = () => {
  authStore.logout();
};

// Clear alert messages
const clearErrors = () => {
  authStore.clearError();
};

const clearSuccess = () => {
  // Clear success message if you have one
};

// Toggle sidebar
const toggleSidebar = () => {
  sidebarStore.toggleCollapse();
};

const toggleMobileSidebar = () => {
  sidebarStore.toggleMobile();
};

// Pass user data to child components
const getUserData = () => ({
  user: user.value,
  initials: userInitials.value,
  name: userName.value,
  email: userEmail.value,
  role: userRole.value,
  avatar: user.value?.image || null,
});
</script>

<style scoped></style>