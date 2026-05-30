<script setup>
import { useTheme } from '../js/Theme.js'

defineProps({
  account: {
    type: Object,
    required: true,
  },
})

const { logo } = useTheme()
</script>

<template>
  <section class="account-hero d-flex flex-column flex-md-row justify-content-between gap-3">
    <div>
      <h2 class="h3 mb-0">Welcome {{ account.name }}!</h2>
    </div>

    <div class="account-hero-logo d-flex align-items-center justify-content-center">
      <img :src="logo" alt="Film review site logo">
    </div>
  </section>
</template>

<style scoped>
.account-hero{
  background-color: var(--bg-primary);
  display: flex;
  justify-content: center;
  align-items: center;
}

.account-hero-logo {
  width: 160px;
  min-height: 80px;
  background-color: var(--bg-surface);
  border-radius: 10px;
  padding: 0.5rem;
}

.account-hero-logo img {
  display: block;
  max-width: 100%;
  max-height: 72px;
  object-fit: contain;
}
</style>
