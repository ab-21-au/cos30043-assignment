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
<template>
    <v-rating
        half-increments
        readonly
        :length="5"
        size="45"
        :model-value="averageRating"
        active-color="accent"
        color="accent"
        background-color="yellow darken-3"
        class="responsive-rating"
    />
    <h4>{{ averageRating.toFixed(1) }}</h4>
</template>
<style>

.responsive-rating {
    transform: scale(1);
}

@media (max-width: 768px) {
    .responsive-rating {
        transform: scale(0.8);
    }
}

@media (max-width: 360px){
    /* .responsive-rating {
        transform: scale(0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    } */

    .average-rating h4 {
        font-size: 1rem;
    }
}
</style>