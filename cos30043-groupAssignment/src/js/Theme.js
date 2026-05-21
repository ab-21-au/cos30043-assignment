import { computed, ref, watchEffect } from 'vue'

import hamburgerLight from '../assets/hamburgerLightMode.svg'
import hamburgerDark from '../assets/hamburgerDarkMode.svg'
import crossLight from '../assets/crossLightMode.svg'
import crossDark from '../assets/crossDarkMode.svg'
import logoLight from '../assets/logoWhite.png'
import logoDark from '../assets/logoBlack.png'

const isDark = ref(true)

// Light/Dark Mode for some icons and the logo
const hamburgerIcon = computed(() => {
  if (isDark.value) {
    return hamburgerDark
  } else {
    return hamburgerLight
  }
})

const crossIcon = computed(() => {
  if (isDark.value) {
    return crossDark
  } else {
    return crossLight
  }
})

const logo = computed(() => {
  if (isDark.value) {
    return logoDark
  } else {
    return logoLight
  }
})

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

  return { isDark, hamburgerIcon, crossIcon, logo, toggleTheme }
}

