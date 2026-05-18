<template>
  <div class="star-container">
    <span
      v-for="i in maxStars"
      :key="i"
      class="star"
      :class="{ 'filled': i <= (hoverValue || modelValue) }"
      @click="setRating(i)"
      @mouseover="hoverValue = i"
      @mouseleave="hoverValue = 0"
    >
    ★
    </span>
  </div>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
  modelValue: {
    type: Number,
    default: 0
  },
  maxStars: {
    type: Number,
    default: 5
  }
});

const emit = defineEmits(['update:modelValue']);
const hoverValue = ref(0);

const setRating = (value) => {
  emit('update:modelValue', value);
};
</script>

<style scoped>
.star-container {
  display: flex;
  gap: 0.25rem;
  margin-top: 0.5rem;
}

.star {
  font-size: 2.5rem;
  color: var(--bg-surface);
  user-select: none;

}

.star.filled {
  color: var(--accent);
}

.star:hover {
  transform: scale(1.1);
}
</style>