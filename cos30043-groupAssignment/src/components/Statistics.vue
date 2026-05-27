<script setup>
import {ref, watch, computed} from 'vue'

import GenrePieChart from './charts/GenrePieChart.vue'
import AverageReview from './charts/AverageReviews.vue'
import YearlyFilms from './charts/YearlyMovieTotalChart.vue'

const props = defineProps({
    accountId: {
        type: [Number, String],
        default: null
    }
})

const ratingsPlot = ref([])
const ratingsActing = ref([])
const ratingsPacing = ref([])
const genres = ref([]) 
const movieTotal = ref([]) 

const err=ref(null)

const loadStats = () => {
    if (!props.accountId) {
        return
    }

    err.value = null
    const getSQLApiURL = `${import.meta.env.BASE_URL}api/get_account_reviews.php?account_id=${props.accountId}`

    fetch(getSQLApiURL)
        .then(response => {
            if (!response.ok){
                throw new Error(`HTTP error: ${response.status}`)
            }
            return response.json()
        })
        .then(data => {
            if(!Array.isArray(data)){
                throw new Error('Unexpected data format: expected an array')
                console.error('Received data:', data) //error checking
            }
            else{
                genres.value = []
                ratingsPlot.value = []
                ratingsActing.value = []
                ratingsPacing.value = []
                movieTotal.value = []
                
                console.log('Fetched reviews data:', data)

                data.forEach(review => {
                    genres.value = data.map(r => r.tmdb_movie_id)
                    ratingsPlot.value = data.map(r => Number(r.plot))
                    ratingsActing.value = data.map(r => Number(r.acting))
                    ratingsPacing.value = data.map(r => Number(r.pacing))
                    movieTotal.value = data.map(r => r.created_at)
                })
                
                // error handling will remove soon
                console.log('Genres:', genres.value)
                console.log('Plot Ratings:', ratingsPlot.value)
                console.log('Acting Ratings:', ratingsActing.value)
                console.log('Pacing Ratings:', ratingsPacing.value)
                console.log('Movie Total:', movieTotal.value)
            }
        })
        .catch(error => {
            err.value = error.message
        })
}

watch(
    () => props.accountId,
    loadStats,
    { immediate: true }
)

const hasReviews = computed(() => {
    return (
        ratingsPlot.value.length > 0 || 
        ratingsActing.value.length > 0 || 
        ratingsPacing.value.length > 0
    )
})

</script>

<template>
    <div class="container">
        <h3>Watch Insights</h3>
        <div v-if="err" class="alert alert-danger">{{ err }}</div>
        <div v-else>
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div v-if="genres.length > 0" class="genre-pie-chart">
                        <!--List of Genres-->
                        <GenrePieChart :genres="genres"/>
                    </div>
                    <div v-else class="genre-pie-chart">
                        <p>No genre data available.</p>
                    </div>
                </div>
                <div class="col-12 col-lg-8">
                    <div v-if="hasReviews" class="average-review">
                        <!--List of Average Acting Rating on Films-->
                        <h4>Average Acting Rating</h4>
                        <AverageReview :reviews="ratingsActing"/>

                        <!--List of Average Pacing Rating on Films-->
                        <h4>Average Pacing Rating</h4>
                        <AverageReview :reviews="ratingsPacing"/>

                        <!--List of Average Rating on Films-->
                        <h4>Average Plot Rating</h4>
                        <AverageReview :reviews="ratingsPlot"/>
                    </div>
                    <div v-else class="average-review">
                        <p>No reviews found for this account.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div v-if="movieTotal.length > 0" class="totalMovies">
                    <!--List of Movies throughout Years-->
                    <YearlyFilms :movieData="movieTotal"/>
                </div>
                <div v-else class="totalMovies">
                    <p>No movie data available.</p>
                    <button @click="$router.push('/films')">Head over to our catalogue to Review now!</button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
h3{
    text-align: center;
    margin: 50px 0;
}

.genre-pie-chart, .average-review, .totalMovies{
    margin: 20px 0;
    box-shadow: var(--shadow);
    background-color: var(--bg-primary);
    padding: 10px;
    border-radius: 10px; 
    color: var(--text-primary);
    text-align: center;
}

.average-review h4 {
    margin: 20px;
}

.average-review {
    margin: 20px ;
    width: 100%;
}

@media (max-width: 360px){
    .average-review {
        width: 90%;
        margin-left: 20px;
        margin-right: 20px;
    }
}

button{
    background-color: var(--accent-deep);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover{
    background-color: rgb(255, 195, 195);
}
</style>
