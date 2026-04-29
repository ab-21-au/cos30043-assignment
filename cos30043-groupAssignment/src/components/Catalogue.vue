<script setup>
import { ref, onMounted } from 'vue'

const movie = ref(null)
const error = ref(null)
const loading = ref(true)

const API_KEY = import.meta.env.VITE_OMDB_API_KEY

onMounted(async () => {
  try {
    const response = await fetch(`https://www.omdbapi.com/?t=Inception&apikey=${API_KEY}`) // replace for specific queries
    const data = await response.json()
    if (data.Response === 'True') {
      movie.value = data
    } else {
      error.value = data.Error
    }
  } catch (err) {
    error.value = 'Failed to fetch movie data.'
  } finally {
    loading.value = false
  }
})
</script>

<template>
    <!--Removed for actual catalogue lol-->
  <div>
    <h1>Movie Details</h1>
    <div v-if="loading">Loading...</div>
    <div v-else-if="error" style="color:red">{{ error }}</div>
    
    <div v-else-if="movie">
      <h2>{{ movie.Title }}</h2>
      <p>Year: {{ movie.Year }}</p>
      <p>Director: {{ movie.Director }}</p>
      <img :src="movie.Poster" alt="Movie Poster" style="width:200px" />
    </div>
  </div>
</template>
