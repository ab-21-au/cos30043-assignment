<script setup>
import {computed} from 'vue'

const props = defineProps({
    genres: Array // define better when set up database
})

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
        ]
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
    height: 150px;
    width: 50%;
    /*Add more later*/
}
</style>