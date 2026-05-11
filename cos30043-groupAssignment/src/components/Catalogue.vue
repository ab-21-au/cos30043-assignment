<script setup>
import { ref, onMounted } from 'vue'

const movies = ref([])
const loading = ref(true)

const API_KEY = import.meta.env.VITE_TMDB_API_KEY

onMounted(async () => {
  const response = await fetch(
    `https://api.themoviedb.org/3/movie/popular?api_key=${API_KEY}&page=1`
  )
  const data = await response.json()
  movies.value = data.results
  loading.value = false
})
</script>

<template>
  <div>
    <h1>Movie Catalogue</h1>
    
    <div v-if="loading">Loading...</div>
    
    <div v-else class="movie-grid">
      <div v-for="movie in movies" :key="movie.id" class="movie-card">
        <img 
          :src="`https://image.tmdb.org/t/p/w342${movie.poster_path}`" 
          :alt="movie.title"
          style="width:100%"
        >
        <h3>{{ movie.title }}</h3>
        <p>{{ movie.release_date?.split('-')[0] }}</p>
        <p>{{ movie.vote_average }}/10</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.movie-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 20px;
}
.movie-card {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 10px;
  text-align: center;
}
</style>