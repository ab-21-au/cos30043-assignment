<script setup>
import {ref, onMounted} from 'vue'

const averageRating = ref(3.5) // testing

// set up logic so that it fetches from database all ratings and calculates average

</script>
<template class="average-rating">
    <v-rating
        half-increments
        readonly
        :length="5"
        size="40"
        :model-value="averageRating"
        active-color="yellow"
        color="yellow"
        background-color="yellow darken-3"
    />
    <p>Your average rating of the last year!!</p>
</template>
<style>

.average-rating {
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>