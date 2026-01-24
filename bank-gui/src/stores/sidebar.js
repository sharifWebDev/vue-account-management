import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useSidebarStore = defineStore('sidebar', () => {
  const isCollapsed = ref(localStorage.getItem('sidebarCollapsed') === 'true');
  const isMobileOpen = ref(false);

  const toggleCollapse = () => {
    isCollapsed.value = !isCollapsed.value;
    localStorage.setItem('sidebarCollapsed', isCollapsed.value);
  };

  const toggleMobile = () => {
    isMobileOpen.value = !isMobileOpen.value;
  };

  const closeMobile = () => {
    isMobileOpen.value = false;
  };

  const openMobile = () => {
    isMobileOpen.value = true;
  };

  return {
    isCollapsed,
    isMobileOpen,
    toggleCollapse,
    toggleMobile,
    closeMobile,
    openMobile
  };
});