<script setup>
import {computed} from 'vue'

// root colours
const accent = getComputedStyle(document.documentElement).getPropertyValue('--accent-deep').trim()
const barBackground = getComputedStyle(document.documentElement).getPropertyValue('--barbackground').trim()

const props = defineProps({
    movieData: {
        type: Array,
        default: () => []
    }
})

const monthCounts =  computed(() => {
    
  const counts = Array(12).fill(0) // sets it to be a 12 month array with 0s until it increments
  const currentYear = new Date().getFullYear()

    props.movieData.forEach(timestamp => {
        
      const releaseDate = new Date(timestamp)

        if (releaseDate.getFullYear() === currentYear) {
            const month = releaseDate.getMonth()
            counts[month]++
        }
    })

    console.log('Monthly movie counts:', counts)

    return counts
})

import VChart from 'vue-echarts'
import * as echarts from 'echarts'
import {BarChart} from 'echarts/charts'

echarts.use([BarChart])

const option = {
  xAxis: {
    type: 'value',
    minInterval: 1
  },
  yAxis: {
    type: 'category',
    inverse: true,
    data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
  },
  series: [
    {
      type: 'bar',
      data: monthCounts.value,
      itemStyle: {
        color: accent, 
        borderRadius: 15,
      },
      showBackground: true,
      barWidth: '80%',
      backgroundStyle: {
            color: barBackground, 
            borderRadius: 15
        },
    }
  ]
};

</script>
<template class="col-6">
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