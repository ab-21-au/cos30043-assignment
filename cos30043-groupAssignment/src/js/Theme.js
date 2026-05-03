import { ref, watchEffect } from 'vue'

const isDark = ref(true) 

export function useTheme() {
  function toggleTheme() {
    isDark.value = !isDark.value
  }

  watchEffect(() => {
    if (isDark.value) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  })

  return { isDark, toggleTheme }
}