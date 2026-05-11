<script setup>
import HelloWorld from './components/ThankYou.vue'

import { useTheme } from './js/Theme.js';

const { isDark, toggleTheme } = useTheme();
</script>

<template>
  <!-- Temporary Toggle Button -->
    <button @click="toggleTheme" class="theme-toggle">
      <span class="toggle-icon"> 
        {{ isDark ? '☀️' : '🌙' }}
      </span>
      <span class="toggle-text">
        {{ isDark ? 'Light' : 'Dark' }} Mode
      </span>
    </button>
  <HelloWorld />
</template>
