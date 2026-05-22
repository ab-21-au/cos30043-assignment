<script setup>
import {ref, computed, onMounted} from 'vue'

const props = defineProps({
    reviews: {
        type: Array,
        default: () => [] // default to empty array if no reviews are passed
    }
})

const averageRating = computed(() => {
    const reviews = props.reviews 

    const sum = reviews.reduce((acc, rating) =>  { return acc + (rating ?? 0) }, 0)

    return sum / reviews.length
})

const accent = getComputedStyle(document.documentElement).getPropertyValue('--accent-deep').trim()


</script>
<template class="average-rating">
    <v-rating
        half-increments
        readonly
        :length="5"
        size="45"
        :model-value="averageRating"
        active-color="accent"
        color="accent"
        background-color="yellow darken-3"
    />
    <h4>{{ averageRating.toFixed(1) }}</h4>
</template>
<style>

.average-rating {
    display: block;
    margin: 0 auto;
}
</style>