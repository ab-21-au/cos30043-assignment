<script setup>
import {computed} from 'vue'

const props = defineProps({
    yearlyMovieTotal: Number // define better when set up database
})

import VChart from 'vue-echarts'
import * as echarts from 'echarts'
import {BarChart} from 'echarts/charts'

echarts.use([BarChart])

const option = {
  xAxis: {},
  yAxis: {
    data: ['December', 'November', 'October', 'September', 'August', 'July', 'June', 'May', 'April', 'March', 'February', 'January']
  },
  series: [
    {
      type: 'bar',
      data: [
        10,
        22,
        28,
        {
          value: 43,

          itemStyle: {
            color: '#91cc75',
            shadowColor: '#91cc75',
            borderType: 'dashed',
            opacity: 0.5
          }
        },
        49
      ],
      itemStyle: {
        barBorderRadius: 5,
        borderWidth: 1,
        borderType: 'solid',
        borderColor: '#73c0de',
        shadowColor: '#5470c6',
        shadowBlur: 3
      }
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
    height: 400px;
}  
</style>