<script setup>
import HelloWorld from './components/SignIn.vue'

import { useTheme } from './js/Theme.js';

const { isDark, toggleTheme } = useTheme();
</script>

<template>
  <HelloWorld />
</template>
