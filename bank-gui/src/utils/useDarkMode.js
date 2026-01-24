import { ref, onMounted } from 'vue';

export const useGlobalDarkMode = () => {
  const isDarkMode = ref(false);

  const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    
    if (isDarkMode.value) {
      document.documentElement.classList.add('dark');
      localStorage.setItem('darkMode', 'true');
    } else {
      document.documentElement.classList.remove('dark');
      localStorage.setItem('darkMode', 'false');
    }
    
    // Dispatch event for other components
    window.dispatchEvent(new CustomEvent('dark-mode-changed', { detail: isDarkMode.value }));
  };

  const initDarkMode = () => {
    const savedDarkMode = localStorage.getItem('darkMode');
    if (savedDarkMode === 'true' || 
        (!savedDarkMode && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      isDarkMode.value = true;
      document.documentElement.classList.add('dark');
    }
  };

  onMounted(() => {
    initDarkMode();
  });

  return {
    isDarkMode,
    toggleDarkMode,
    initDarkMode
  };
};