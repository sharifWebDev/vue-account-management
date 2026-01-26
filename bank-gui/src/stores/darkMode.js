import { defineStore } from 'pinia'
import { ref, watch } from 'vue'

export const useDarkModeStore = defineStore('darkMode', () => {
  // Load from localStorage (default: light)
  const isDark = ref(localStorage.getItem('darkMode') === 'true')

  const applyDarkMode = (value) => {
    if (value) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  }

  // Watch changes and persist automatically
  watch(isDark, (value) => {
    localStorage.setItem('darkMode', value.toString())
    applyDarkMode(value)
  }, { immediate: true })

  const toggle = () => {
    isDark.value = !isDark.value
  }

  return {
    isDark,
    toggle,
  }
})
