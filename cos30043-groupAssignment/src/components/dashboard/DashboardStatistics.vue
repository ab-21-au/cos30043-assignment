<script setup>
import {ref, onMounted} from 'vue'

import GenrePieChart from './charts/GenrePieChart.vue'
import AverageReview from './charts/AverageReviews.vue'
import YearlyFilms from './charts/YearlyMovieTotalChart.vue'

const genres = ref([])
const loading = ref(true)
const err=ref(null)
const msg=ref('')

// need to come back to this

onMounted(() => {
    const getSQLApiURL = 'resources/apis.php/accountid/' + this.accountid // idk if this is correct will change maybe later

    const requestOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            //
        })
    }
    fetch(getSQLApiURL, requestOptions)
        .then(response => {
            if (!response.ok){
                throw new Error(`HTTP error: ${response.status}`)
            }
            return response.json()
        })
        .then(data => {
            if (parseInt(data) === 0){
                this.msg = 'Start!'
            }
            else{
                ///
                genres.value = response.data //??? proper set up later
            }
        })
        .catch(error => {
            err.value = error.message
        })
})

</script>

<template>
    <div class="container">
        <h3>Watch Insights</h3>
        <div class="row">
            <div class="col-6">
                <div class="genre-pie-chart">
                    <!--List of Genres-->
                    <GenrePieChart/>
                </div>
            </div>
            <div class="col-6">
                <div class="average-review">
                    <!--List of Average Rating on Films-->
                    <AverageReview/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="totalMovies">
                <!--List of Movies throughout Years-->
                <YearlyFilms/>
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
    padding: 20px;
    border-radius: 10px; 
    color: var(--text-primary);
}
</style>