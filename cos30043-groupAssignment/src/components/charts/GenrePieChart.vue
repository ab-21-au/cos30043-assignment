<script setup>
import {ref, computed, onMounted} from 'vue'
import { tmdb, GENRES } from '../../services/tmdb.js'

const props = defineProps({
    genres: {
        type: Array,
        default: () => [] // default to empty array if no genres are passed
    }
})

const chartData = ref([])

const genreMap = Object.fromEntries(
    GENRES.map(g => [g.id, g.name])
)

onMounted(async () => {
    const genreCounts = {}

    const movies = await Promise.all(props.genres.map(id => tmdb.getMovieById(id)))

    movies.forEach(movie => {
        const movieGenres = movie.genres || []

        movieGenres.forEach(g => {
            const genreName = genreMap[g.id] || g.name || 'Unknown'
            genreCounts[genreName] = (genreCounts[genreName] || 0) + 1
        })
    })

    chartData.value = Object.entries(genreCounts).map(([name, value]) => ({ name, value }))
    console.log('Genre counts:', chartData.value)
})


// listing pie chart colours from Root
const accent_1 = getComputedStyle(document.documentElement).getPropertyValue('--piechart-colors').split(',')[0].trim()
const accent_2 = getComputedStyle(document.documentElement).getPropertyValue('--piechart-colors').split(',')[1].trim()
const accent_3 = getComputedStyle(document.documentElement).getPropertyValue('--piechart-colors').split(',')[2].trim()
const accent_4 = getComputedStyle(document.documentElement).getPropertyValue('--piechart-colors').split(',')[3].trim()
const accent_5 = getComputedStyle(document.documentElement).getPropertyValue('--piechart-colors').split(',')[4].trim()

// Data analytics package
import VChart from 'vue-echarts'
import * as echarts from 'echarts'    
import {PieChart} from 'echarts/charts'
import {
    TitleComponent,
    TooltipComponent,
    LegendComponent
} from 'echarts/components'

import {CanvasRenderer} from 'echarts/renderers'

echarts.use([
    PieChart,
    TitleComponent,
    TooltipComponent,
    LegendComponent,
    CanvasRenderer
])

const option = computed(() => ({
    tooltip:{
        trigger: 'item' //change??
    },
    series: [
        {
        type: 'pie',
        radius: ['50%', '70%'],
        avoidLabelOverlap: false,
        label: {
            show: false,
            position: 'center'
        },
        labelLine: {
            show: false
        },
        emphasis: {
            label: {
            show: true,
            fontSize: '30',
            fontWeight: 'bold'
            }
        },
        data: chartData.value,
        color: [accent_5, accent_2, accent_3, accent_4, accent_1] // change to root colours later
        }
    ]
}))
</script>
<template>
    <p>Most watched genres</p>
    <VChart
    class="pie-chart"
    :option="option"
    autoresize/>
    <p>so far this year</p>
</template>
<style scoped>
.pie-chart{
    height: 250px;
    width: 100%;
    /*Add more later*/
    display: block;
    margin: 0 auto;
}
</style>