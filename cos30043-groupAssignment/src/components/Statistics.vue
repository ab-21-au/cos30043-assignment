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
        <h3>Watching Insights</h3>
        <div class="row">
            <div class="col-6">
                <GenrePieChart/>
            </div>
            <div class="col-6">
                <!--List of Average Rating on Films-->
                <AverageReview/>
            </div>
        </div>
        <div class="row">
            <!--List of Movies throughout Years-->
            <!--<YearlyFilms/>-->
        </div>
    </div>
</template>
<style>
</style>