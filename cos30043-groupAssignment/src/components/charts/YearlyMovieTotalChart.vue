<script setup>
import {computed} from 'vue'

const accent = getComputedStyle(document.documentElement).getPropertyValue('--accent-deep').trim()
const barBackground = getComputedStyle(document.documentElement).getPropertyValue('--barbackground').trim()

const props = defineProps({
    yearlyMovieTotal: {
        type: Array,
        default: () => []
    }
})

import VChart from 'vue-echarts'
import * as echarts from 'echarts'
import {BarChart} from 'echarts/charts'

echarts.use([BarChart])

const option = {
  xAxis: {},
  yAxis: {
    inverse: true,
    data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
  },
  series: [
    {
      type: 'bar',
      data: [
        10,
        22,
        28,
        43,
        49
      ],
      itemStyle: {
        color: accent, // insert root
        borderRadius: 15,
      },
      showBackground: true,
      barWidth: '80%',
      backgroundStyle: {
            color: barBackground, // insert root
            borderRadius: 15
        },
    }
  ]
};

</script>
<template>
    <p>Number of movies watched each month throughout the year</p>
    <VChart
    class="bar-chart"
    :option="option"
    />
</template>
<style>
.bar-chart {
    width: 100%;
    height: 500px;
    display: block;
    margin: 0 auto;
}  
</style>