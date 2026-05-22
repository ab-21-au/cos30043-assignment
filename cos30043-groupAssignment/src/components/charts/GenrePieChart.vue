<script setup>
import {computed} from 'vue'

const props = defineProps({
    genres: Array // define better when set up database
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
        data: [
            { value: 335, name: 'A' },
            { value: 310, name: 'B' },
            { value: 234, name: 'C' },
            { value: 135, name: 'D' },
            { value: 1548, name: 'E' }
        ],
        color: [accent_1, accent_2, accent_3, accent_4, accent_5] // change to root colours later
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