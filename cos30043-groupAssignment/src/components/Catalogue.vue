<script setup>
import { ref, onMounted } from 'vue'
import { tmdb, GENRES } from '../services/tmdb.js'
import { useRouter } from 'vue-router'

// State
const recentMovies = ref([])
const genreRows = ref([])
const searchQuery = ref('')
const selectedGenre = ref('')
const loading = ref(true)
const currentIndex = ref(0)

//router
const router = useRouter()

// Load all data
async function loadData() {
  loading.value = true
  
  try {
    const recentData = await tmdb.getRecentMovies()
    recentMovies.value = recentData.slice(0, 15)  

    genreRows.value = await tmdb.getAllGenresWithRandomMovies(20)  

  } catch (error) {
    console.error('Error loading data:', error)
  } finally {
    loading.value = false
  }
}

// Carousel navigation
function goPrev() {
  console.log('← Button clicked')
  if (recentMovies.value.length == 0) return
  let newIndex = currentIndex.value - 1
  if (newIndex < 0) newIndex = recentMovies.value.length - 1
  currentIndex.value = newIndex
  console.log('New index:', currentIndex.value)
}

function goNext() {
  console.log('→ Button clicked')
  if (recentMovies.value.length == 0) return
  let newIndex = currentIndex.value + 1
  if (newIndex >= recentMovies.value.length) newIndex = 0
  currentIndex.value = newIndex
  console.log('New index:', currentIndex.value)
}

// Style for each movie (Carousel)
function getStyle(index) {
  // Calculate position relative to center (-2, -1, 0, 1, 2...)
  let position = index - currentIndex.value
  const len = recentMovies.value.length
  
  // Handle wrap-around
  if (position > len / 2) position = position - len
  if (position < -len / 2) position = position + len
  
  // Center movie gets scale 1, others get smaller
  const scale = position == 0 ? 1 : 0.7
  const opacity = position == 0 ? 1 : 0.5
  const translateX = position * 200
  
  return {
    transform: `translateX(${translateX}px) scale(${scale})`,
    opacity: opacity,
    zIndex: position == 0 ? 10 : 1,
    transition: 'all 0.4s ease'
  }
}

// Scroll genre rows
function scrollLeft(rowIndex) {
  const el = document.getElementById(`scroll-${rowIndex}`)
  if (el) el.scrollBy({ left: -500, behavior: 'smooth' })
}

function scrollRight(rowIndex) {
  const el = document.getElementById(`scroll-${rowIndex}`)
  if (el) el.scrollBy({ left: 500, behavior: 'smooth' })
}

// Search
async function doSearch() {
  if (!searchQuery.value.trim()) {
    loadData()
    return
  }
  loading.value = true
  try {
    const results = await tmdb.searchMovies(searchQuery.value)
    genreRows.value = [{
      genre: { name: `Search: "${searchQuery.value}"` },
      movies: results.slice(0, 20),
      useGrid: true  // Single flag for grid layout
    }]
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
}

// Filter
async function doFilter() {
  if (!selectedGenre.value) {
    loadData()
    return
  }
  loading.value = true
  try {
    const genre = GENRES.find(g => g.name == selectedGenre.value)
    const movies = await tmdb.getRandomMoviesByGenre(genre.id, 20)
    genreRows.value = [{
      genre: { name: selectedGenre.value },
      movies: movies,
      useGrid: true  // Same flag for grid layout
    }]
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
}

function clearAll() {
  searchQuery.value = ''
  selectedGenre.value = ''
  loadData()
}

onMounted(() => loadData())

// Nav to review page w/ movie id
function goToReview(movieId) {
  router.push({ path: `/films/${movieId}` })
}
</script>

<template>
  <div class="catalogue">
    <header class="header">
      <div class="logo">Better Than Letterboxd</div>
      <div class="header-right">
        <span class="icon">Login</span>
      </div>
    </header>

    <section class="hero-section">
      <div class="hero-content">
        <h1>Recent Releases</h1>
        <p>Discover the latest movies now playing in theaters</p>
      </div>

      <div class="carousel-wrapper">
        <button class="carousel-btn prev" @click="goPrev">◀</button>
        
        <div class="carousel-container">
          <div class="carousel-track">
            <div 
              v-for="(movie, i) in recentMovies" 
              :key="movie.id"
              class="carousel-movie"
              :style="getStyle(i)"
              :class="{ center: i == currentIndex }"
              @click="goToReview(movie.id)"
            >
              <img 
                :src="movie.poster_path ? `https://image.tmdb.org/t/p/w342${movie.poster_path}` : 'https://via.placeholder.com/342x513?text=No+Poster'" 
                :alt="movie.title"
              >
              <div class="movie-badge" v-if="i == currentIndex">
                {{ movie.vote_average?.toFixed(2) }}/10
              </div>
            </div>
          </div>
        </div>
        
        <button class="carousel-btn next" @click="goNext">▶</button>
      </div>
      
      <div class="center-movie-info" v-if="recentMovies[currentIndex]">
        <h2 @click="goToReview(recentMovies[currentIndex].id)">{{ recentMovies[currentIndex].title }}</h2>
        <p class="year">{{ recentMovies[currentIndex].release_date?.split('-')[0] }}</p>
        <p class="overview">{{ recentMovies[currentIndex].overview?.substring(0, 150) }}...</p>
        <div class="rating">★ {{ recentMovies[currentIndex].vote_average?.toFixed(2) }}/10</div>
      </div>
    </section>

    <section class="search-section">
      <div class="search-group">
        <label>Search Movie</label>
        <div class="search-wrapper">
          <input v-model="searchQuery" type="text" placeholder="Enter movie title..." @keyup.enter="doSearch" />
          <button @click="doSearch">Search</button>
        </div>
      </div>
      <div class="filter-group">
        <label>Filter Genre Movie</label>
        <select v-model="selectedGenre" @change="doFilter">
          <option value="">All Genres</option>
          <option v-for="g in GENRES" :key="g.id" :value="g.name">{{ g.name }}</option>
        </select>
      </div>
      <button v-if="searchQuery || selectedGenre" class="clear-btn" @click="clearAll">Clear</button>
    </section>

    <div v-if="loading" class="loading">Loading movies...</div>

    <div v-else class="genre-rows">
      <section v-for="(row, idx) in genreRows" :key="idx" class="genre-row">
        <div class="genre-header">
          <h2 class="genre-title">{{ row.genre.name }}</h2>
        </div>
    
        <div v-if="row.useGrid" class="results-grid">
          <div v-for="movie in row.movies" :key="movie.id" class="movie-card" @click="goToReview(movie.id)">
            <img :src="movie.poster_path ? `https://image.tmdb.org/t/p/w200${movie.poster_path}` : 'https://via.placeholder.com/200x300?text=No+Poster'" :alt="movie.title">
            <div class="movie-info">
              <h4>{{ movie.title }}</h4>
              <span class="rating">★ {{ movie.vote_average?.toFixed(1) }}/10</span>
            </div>
          </div>
        </div>
        
        <div v-else class="movie-scroll" :id="`scroll-${idx}`">
          <div v-for="movie in row.movies" :key="movie.id" class="movie-card" @click="goToReview(movie.id)">
            <img :src="movie.poster_path ? `https://image.tmdb.org/t/p/w200${movie.poster_path}` : 'https://via.placeholder.com/200x300?text=No+Poster'" :alt="movie.title">
            <div class="movie-info">
              <h4>{{ movie.title }}</h4>
              <span class="rating">★ {{ movie.vote_average?.toFixed(1) }}/10</span>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background: white;
}

.catalogue {
  width: 100%;
  min-height: 100vh;
  background: white;
  color: black;
  overflow-x: hidden;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 5%;
  position: sticky;
  top: 0;
  background: white;
  border-bottom: 1px solid #eee;
  z-index: 100;
}

.logo {
  font-size: 24px;
  font-weight: bold;
  color: black;
}

.header-right {
  display: flex;
  gap: 20px;
}

.icon {
  font-size: 20px;
  cursor: pointer;
  color: black;
}

.hero-section {
  padding: 40px 5%;
  text-align: center;
}

.hero-content h1 {
  font-size: clamp(28px, 5vw, 48px);
  margin-bottom: 10px;
  color: black;
}

.hero-content p {
  font-size: clamp(14px, 3vw, 18px);
  color: #555;
}

/* Carousel */
.carousel-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  margin: 40px 0;
  position: relative;
  z-index: 50;
}

.carousel-container {
  overflow: visible;
  width: 100%;
  display: flex;
  justify-content: center;
  position: relative;
  z-index: 1;
}

.carousel-track {
  position: relative;
  height: 380px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.carousel-movie {
  position: absolute;
  width: 220px;
  cursor: pointer;
  border-radius: 12px;
  overflow: hidden;
  background: #f0f0f0;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.carousel-movie img {
  width: 100%;
  height: 330px;
  object-fit: cover;
}

.movie-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  background: #333;
  color: white;
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 12px;
}

.carousel-btn {
  background: #ddd;
  color: black;
  border: none;
  width: 45px;
  height: 45px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  z-index: 100;
  /* These ensure the whole button is clickable */
  pointer-events: auto;
  user-select: none;
}

.carousel-btn:hover {
  background: #ccc;
}

.center-movie-info {
  text-align: center;
  padding: 30px;
  background: #f5f5f5;
  border-radius: 20px;
  margin-top: 20px;
}

.center-movie-info h2 {
  font-size: clamp(24px, 4vw, 36px);
  margin-bottom: 8px;
  color: black;
}

.center-movie-info .year {
  font-size: 14px;
  color: #666;
  margin-bottom: 12px;
}

.center-movie-info .overview {
  font-size: 14px;
  color: #444;
  line-height: 1.6;
  max-width: 600px;
  margin: 0 auto 15px;
}

.center-movie-info .rating {
  font-size: 18px;
  color: #e50914;
  font-weight: bold;
}

.search-section {
  display: flex;
  gap: 20px;
  padding: 30px 5%;
  flex-wrap: wrap;
  background: #f5f5f5;
}

.search-group, .filter-group {
  flex: 1;
  min-width: 200px;
}

.search-group label, .filter-group label {
  display: block;
  font-size: 14px;
  margin-bottom: 8px;
  color: black;
}

.search-wrapper {
  display: flex;
  gap: 10px;
}

.search-wrapper input, .filter-group select {
  flex: 1;
  padding: 14px;
  background: white;
  border: 1px solid #ddd;
  border-radius: 10px;
  font-size: 14px;
  color: black;
}

.search-wrapper button {
  padding: 14px 24px;
  background: #333;
  color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
}

.results-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 20px;
  padding: 10px 0;
}

@media (max-width: 768px) {
  .results-grid {
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 15px;
  }
}

@media (max-width: 480px) {
  .results-grid {
    grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
    gap: 12px;
  }
}

.clear-btn {
  padding: 14px 24px;
  background: #ddd;
  color: black;
  border: none;
  border-radius: 10px;
  cursor: pointer;
}

.genre-rows {
  padding: 20px 5% 50px;
}

.genre-row {
  margin-bottom: 40px;
}

.genre-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.genre-title {
  font-size: clamp(20px, 3vw, 28px);
  padding-left: 15px;
  color: black;
  border-left: 4px solid #e50914;
}

.movie-scroll {
  display: flex;
  overflow-x: auto;
  gap: 20px;
  padding-bottom: 15px;
}

.movie-scroll::-webkit-scrollbar {
  height: 6px;
}

.movie-scroll::-webkit-scrollbar-track {
  background: #eee;
}

.movie-scroll::-webkit-scrollbar-thumb {
  background: #ccc;
  border-radius: 10px;
}

.movie-card {
  flex: 0 0 auto;
  width: 160px;
  background: white;
  border-radius: 12px;
  overflow: hidden;
  cursor: pointer;
  border: 1px solid #eee;
  transition: transform 0.3s;
}

.movie-card:hover {
  transform: scale(1.05);
}

.movie-card img {
  width: 100%;
  height: 240px;
  object-fit: cover;
}

.movie-info {
  padding: 12px;
}

.movie-info h4 {
  font-size: 14px;
  margin-bottom: 6px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  color: black;
}

.movie-info .rating {
  font-size: 12px;
  color: #e50914;
}

.loading {
  text-align: center;
  padding: 60px;
  font-size: 18px;
  color: #666;
}

/* Responsive */
@media (max-width: 768px) {
  .carousel-movie { width: 160px; }
  .carousel-movie img { height: 240px; }
  .carousel-track { height: 280px; }
  .carousel-btn { width: 35px; height: 35px; font-size: 16px; }
  .movie-card { width: 130px; }
  .movie-card img { height: 195px; }
  .search-section { flex-direction: column; }
  .search-group, .filter-group, .clear-btn { width: 100%; }
}

@media (max-width: 480px) {
  .carousel-movie { width: 130px; }
  .carousel-movie img { height: 195px; }
  .carousel-track { height: 230px; }
  .movie-card { width: 110px; }
  .movie-card img { height: 165px; }
}

@media (min-width: 1200px) {
  .carousel-movie { width: 260px; }
  .carousel-movie img { height: 390px; }
  .carousel-track { height: 440px; }
  .movie-card { width: 200px; }
  .movie-card img { height: 300px; }
}
</style>