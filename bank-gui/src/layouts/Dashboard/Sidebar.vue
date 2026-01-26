<template>
  <div>
    <!-- Sidebar -->
    <div :class="[
      'sidebar w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700  z-30 h-full transition-all duration-300',
      { collapsed: isCollapsed },
      { open: isMobileOpen },
      'fixed md:relative'
    ]" :style="{ width: isCollapsed && windowWidth >= 768 ? '70px' : '256px' }">
      <!-- Header -->
      <div class="h-[4.42rem] px-4 border-b-transparent dark:border-gray-700 flex items-center justify-between">
        <router-link to="/dashboard" class="flex items-center">
          <!-- <div
            class="w-9 h-9 bg-gray-600 rounded-lg flex items-center justify-center"
          >
            <i class="fa fa-cube text-white text-xl"></i>
          </div> -->
          <img src="./../../../public/logo/app-logo.png" alt="App Logo" class="h-9 w-auto pl-2"
            :style="{ display: isCollapsed && windowWidth >= 768 ? 'none' : 'block' }" />
          <img src="./../../../public/logo/logo-sm.png" alt="App Logo" class="h-9 w-[70px]"
            :style="{ display: isCollapsed && windowWidth >= 768 ? 'block' : 'none' }" />
          <span v-if="!isCollapsed || windowWidth < 768"
            class="logo-text text-lg font-bold text-gray-600 dark:text-white">

          </span>
        </router-link>

        <!-- Collapse -->
        <button v-if="windowWidth >= 768" @click="toggleCollapse" class="hidden md:block p-1 rounded-md text-gray-500"
          :style="{ display: isCollapsed && windowWidth >= 768 ? 'none' : 'block' }">
          <i :class="[
            'fas text-sm text-white dark:text-transparent',
            isCollapsed ? 'fa-chevron-right' : 'fa-chevron-left'
          ]"></i>
        </button>
      </div>

      <!-- Navigation -->
      <nav class="p-3 flex-1  min-h-[calc(100vh-7rem)] max-h-[100vh-7rem]">
        <ul class="space-y-1">
          <li v-for="item in navigationItems" :key="item.id" class="relative" @mouseenter="handleHoverEnter(item)"
            @mouseleave="handleHoverLeave(item)">
            <!-- Parent item with subItems -->
            <div v-if="item.subItems" class="space-y-1">
              <button @click="toggleSubmenu(item.id)" :class="[
                'w-full nav-item flex items-center space-x-3 p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors',
                { active: isActive(item) },
                { 'justify-center': isCollapsed && windowWidth >= 768 }
              ]" :title="item.text">
                <i :class="[
                  item.icon,
                  'w-5 text-center',
                  isCollapsed && windowWidth >= 768 ? 'mx-auto' : ''
                ]"></i>

                <span v-if="!isCollapsed || windowWidth < 768" class="sidebar-text text-sm flex-1 text-left">
                  {{ item.text }}
                </span>

                <i v-if="!isCollapsed || windowWidth < 768" :class="[
                  'fas text-[11px] transition-transform duration-300 text-gray-400 dark:text-gray-400',
                  expandedMenus.includes(item.id) ? 'fa-chevron-down' : 'fa-chevron-right'
                ]"></i>
              </button>

              <!-- Submenu items (Regular) -->
              <ul v-if="expandedMenus.includes(item.id)"
                class="space-y-1 pl-4 border-l-2 border-gray-200 dark:border-gray-700 ml-3">
                <li v-for="subItem in item.subItems" :key="subItem.path">
                  <router-link :to="subItem.path" :class="[
                    'nav-item flex items-center space-x-3 p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300 transition-colors text-sm',
                    { active: route.path === subItem.path }
                  ]" :title="subItem.name" @click="handleNavigation">
                    <i :class="[subItem.icon, 'w-4 text-center']"></i>
                    <span v-if="!isCollapsed || windowWidth < 768" class="sidebar-text">
                      {{ subItem.name }}
                    </span>
                  </router-link>
                </li>
              </ul>

              <!-- Hover Submenu (When collapsed) -->
              <div v-if="isCollapsed && windowWidth >= 768 && hoveredItemId === item.id && item.subItems"
                class="hover-submenu absolute left-full top-0 ml-1 bg-white dark:bg-gray-800 rounded-lg shadow-xl py-2 z-50 border border-gray-200 dark:border-gray-700 min-w-[200px]"
                @mouseenter="handleHoverSubmenuEnter" @mouseleave="handleHoverSubmenuLeave">
                <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                  <span class="font-medium text-gray-800 dark:text-white text-sm">
                    {{ item.text }}
                  </span>
                </div>
                <ul class="space-y-1">
                  <li v-for="subItem in item.subItems" :key="subItem.path">
                    <router-link :to="subItem.path" :class="[
                      'nav-item flex items-center space-x-3 px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300 transition-colors text-sm',
                      { active: route.path === subItem.path }
                    ]" @click="handleNavigation">
                      <i :class="[subItem.icon, 'w-4 text-center']"></i>
                      <span class="sidebar-text">{{ subItem.name }}</span>
                    </router-link>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Regular item without subItems -->
            <router-link v-else :to="item.route" :class="[
              'nav-item flex items-center space-x-3 p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors relative',
              { active: isActive(item) },
              { 'justify-center': isCollapsed && windowWidth >= 768 }
            ]" :title="item.text" @click="handleNavigation">
              <i :class="[
                item.icon,
                'w-5 text-center',
                isCollapsed && windowWidth >= 768 ? 'mx-auto' : ''
              ]"></i>

              <span v-if="!isCollapsed || windowWidth < 768" class="sidebar-text text-sm">
                {{ item.text }}
              </span>

              <!-- Tooltip for collapsed items without subItems -->
              <div v-if="isCollapsed && windowWidth >= 768 && hoveredItemId === item.id && !item.subItems"
                class="hover-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-2 bg-gray-900 dark:bg-gray-700 text-white dark:text-gray-100 px-3 py-1.5 rounded-md text-sm whitespace-nowrap z-50 shadow-lg"
                @mouseenter="handleHoverSubmenuEnter" @mouseleave="handleHoverSubmenuLeave">
                {{ item.text }}
                <div
                  class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-gray-900 dark:border-r-gray-700">
                </div>
              </div>
            </router-link>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { useRoute } from "vue-router";
import { useSidebarStore } from "@/stores/sidebar";

const route = useRoute();
const sidebarStore = useSidebarStore();

const windowWidth = ref(window.innerWidth);
const expandedMenus = ref([]);
const hoveredItemId = ref(null);
const hoverTimeout = ref(null);
const isHoveringSubmenu = ref(false);

/* ================= Menu ================= */

const navigationItems = ref([
  {
    id: 'overview',
    text: 'Home',
    icon: 'fas fa-home',
    route: '/dashboard',
    exact: true
  },
  {
    id: 'history',
    text: 'Transactions',
    icon: 'fa-solid fa-money-bill-transfer',
    route: '/transactions-history'
  },
  {
    id: 'account',
    text: 'Account',
    icon: 'fas fa-database',
    route: '/account',
    subItems: [
      { name: 'Manager Account', path: '/account', icon: 'fas fa-cog' },
      { name: 'Trash Account', path: '/account/trash', icon: 'fas fa-trash' },
    ]
  },
  {
    id: 'company',
    text: 'Company',
    icon: 'fa-regular fa-building',
    route: '/company',
    subItems: [
      { name: 'Manager Company', path: '/company', icon: 'fas fa-cog' },
      { name: 'Trash Company', path: '/company/trash', icon: 'fas fa-trash' }
    ]
  },
  {
    id: 'bank',
    text: 'Bank',
    icon: 'fa-solid fa-building-columns',
    route: '/bank',
    subItems: [
      { name: 'Manager Bank', path: '/bank', icon: 'fas fa-cog' },
      { name: 'Trash Bank', path: '/bank/trash', icon: 'fas fa-trash' }
    ]
  },
  {
    id: 'branch',
    text: 'Branch',
    icon: 'fa-solid fa-code-branch',
    route: '/branch',
    subItems: [
      { name: 'Manager Branch', path: '/branch', icon: 'fas fa-cog' },
      { name: 'Trash Branch', path: '/branch/trash', icon: 'fas fa-trash' }
    ]
  },
  {
    id: 'users',
    text: 'Users',
    icon: 'fas fa-users',
    route: '/user',
    subItems: [
      { name: 'Manager User', path: '/user', icon: 'fas fa-cog' },
      { name: 'Trash User', path: '/user/trash', icon: 'fas fa-trash' }
    ]
  }
]);

/* ================= State ================= */
const isCollapsed = computed(() => sidebarStore.isCollapsed);
const isMobileOpen = computed(() => sidebarStore.isMobileOpen);

/* ================= Active Fix ================= */
const isActive = (item) => {
  if (item.exact) {
    return route.path === item.route;
  }
  return route.path.startsWith(item.route);
};

/* ================= Actions ================= */
const toggleCollapse = () => {
  sidebarStore.toggleCollapse();
};

const toggleSubmenu = (itemId) => {
  if (expandedMenus.value.includes(itemId)) {
    expandedMenus.value = expandedMenus.value.filter(id => id !== itemId);
  } else {
    expandedMenus.value.push(itemId);
  }
};

const handleNavigation = () => {
  if (windowWidth.value < 768) {
    sidebarStore.closeMobile();
  }
  hoveredItemId.value = null;
  isHoveringSubmenu.value = false;
};

const handleHoverEnter = (item) => {
  if (!isCollapsed.value || windowWidth.value < 768) return;

  clearTimeout(hoverTimeout.value);
  hoverTimeout.value = setTimeout(() => {
    hoveredItemId.value = item.id;
  }, 150);
};

const handleHoverLeave = (item) => {
  if (!isCollapsed.value || windowWidth.value < 768) return;

  clearTimeout(hoverTimeout.value);
  hoverTimeout.value = setTimeout(() => {
    if (!isHoveringSubmenu.value) {
      hoveredItemId.value = null;
    }
  }, 200);
};

const handleHoverSubmenuEnter = () => {
  isHoveringSubmenu.value = true;
  clearTimeout(hoverTimeout.value);
};

const handleHoverSubmenuLeave = () => {
  isHoveringSubmenu.value = false;
  hoverTimeout.value = setTimeout(() => {
    hoveredItemId.value = null;
  }, 200);
};

const handleResize = () => {
  windowWidth.value = window.innerWidth;
  if (windowWidth.value >= 768) {
    sidebarStore.isMobileOpen = false;
  }
};

/* ================= Lifecycle ================= */
onMounted(() => {
  window.addEventListener("resize", handleResize);
});

onUnmounted(() => {
  window.removeEventListener("resize", handleResize);
  clearTimeout(hoverTimeout.value);
});
</script>

<style scoped>
.sidebar {
  transition: all 0.3s ease;
}

.sidebar.collapsed {
  width: 70px !important;
}

/* .sidebar.collapsed .sidebar-text,
.sidebar.collapsed .logo-text {
  display: none;
} */

.nav-item.active {
  background-color: rgba(59, 130, 246, 0.1);
  color: #3b82f6;
  font-weight: 500;
}

.dark .nav-item.active {
  background-color: rgba(59, 130, 246, 0.2);
  color: #60a5fa;
}

/* Hover submenu styles */
.hover-submenu {
  animation: fadeIn 0.15s ease-in-out;
}

.hover-tooltip {
  animation: fadeIn 0.15s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateX(-10px);
  }

  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Mobile */
@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
  }

  .sidebar.open {
    transform: translateX(0);
  }

  .hover-submenu,
  .hover-tooltip {
    display: none !important;
  }
}
</style>