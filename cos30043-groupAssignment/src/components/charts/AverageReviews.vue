<script setup>
import {ref, onMounted} from 'vue'

const averageRating = ref(3.5) // testing

const accent = getComputedStyle(document.documentElement).getPropertyValue('--accent-deep').trim()

// set up logic so that it fetches from database all ratings and calculates average

</script>
<template class="average-rating">
    <v-rating
        half-increments
        readonly
        :length="5"
        size="40"
        :model-value="averageRating"
        active-color="accent"
        color="accent"
        background-color="yellow darken-3"
    />
    <h4>{{ averageRating.toFixed(1) }}</h4>
    <!-- set up with rooting colours -->
    <p>Your average rating of the last year!!</p>
</template>
<style>

.average-rating {
    display: block;
    margin: 0 auto;
}
</style>