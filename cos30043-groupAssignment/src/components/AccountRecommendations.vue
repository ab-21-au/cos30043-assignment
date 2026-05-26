<script setup>
import { computed, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import { tmdb } from '../services/tmdb.js'
import { useAuth } from '../assets/UseAuth.js'

const props = defineProps({
  accountId: {
    type: [Number, String],
    default: null,
  },
})

const auth = useAuth()
const router = useRouter()

const favouriteSample = ref([])
const recommendations = ref([])
const genreTotals = ref([])
const isLoading = ref(false)
const error = ref('')

const hasRecommendations = computed(() => recommendations.value.length > 0)

const apiUrl = (endpoint) => import.meta.env.DEV
  ? `/api/${endpoint}`
  : `${import.meta.env.BASE_URL}api/${endpoint}`

const posterUrl = (posterPath) => {
  return posterPath
    ? `https://image.tmdb.org/t/p/w342${posterPath}`
    : `${import.meta.env.BASE_URL}assets/error-placeholder.jpg`
}

const shuffle = (items) => {
  const copy = [...items]

  for (let i = copy.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1))
    ;[copy[i], copy[j]] = [copy[j], copy[i]]
  }

  return copy
}

const getFavouriteRows = async () => {
  const response = await fetch(apiUrl(`get_account_movies.php?account_id=${props.accountId}&favourites_only=1`))
  const result = await response.json()

  if (!result.success) {
    throw new Error(result.error || 'Unable to load favourite movies')
  }

  return result.movies || []
}

const getAccountMovieRows = async () => {
  const response = await fetch(apiUrl(`get_account_movies.php?account_id=${props.accountId}`))
  const result = await response.json()

  if (!result.success) {
    throw new Error(result.error || 'Unable to load account movies')
  }

  return result.movies || []
}

const getMovieDetails = async (movieIds) => {
  const detailResults = await Promise.allSettled(
    movieIds.map(movieId => tmdb.getMovieById(movieId))
  )

  return detailResults
    .filter(result => result.status === 'fulfilled' && result.value && !result.value.status_code)
    .map(result => result.value)
}

const buildGenreTotals = (movies) => {
  const totals = new Map()

  movies.forEach((movie) => {
    ;(movie.genres || []).forEach((genre) => {
      const current = totals.get(genre.id) || { id: genre.id, name: genre.name, count: 0 }
      current.count += 1
      totals.set(genre.id, current)
    })
  })

  return Array.from(totals.values()).sort((a, b) => b.count - a.count)
}

const buildRecommendations = async (topGenres, favouriteIds) => {
  const collected = []
  const seen = new Set(favouriteIds)

  for (const genre of topGenres.slice(0, 3)) {
    const movies = await tmdb.getRandomMoviesByGenre(genre.id, 12)

    for (const movie of movies) {
      if (!seen.has(movie.id)) {
        seen.add(movie.id)
        collected.push(movie)
      }

      if (collected.length === 5) {
        return collected
      }
    }
  }

  return collected.slice(0, 5)
}

const loadRecommendations = async () => {
  if (!auth.isAuthenticated.value || !props.accountId) {
    favouriteSample.value = []
    recommendations.value = []
    genreTotals.value = []
    return
  }

  isLoading.value = true
  error.value = ''

  try {
    const [favourites, accountMovies] = await Promise.all([
      getFavouriteRows(),
      getAccountMovieRows(),
    ])
    const favouriteIds = favourites.map(movie => Number(movie.tmdb_movie_id)).filter(Boolean)
    const accountMovieIds = accountMovies.map(movie => Number(movie.tmdb_movie_id)).filter(Boolean)

    if (favouriteIds.length === 0) {
      favouriteSample.value = []
      recommendations.value = []
      genreTotals.value = []
      return
    }

    const sampleIds = shuffle(favouriteIds).slice(0, 5)
    const allFavouriteDetails = await getMovieDetails(favouriteIds)
    const sampleDetails = allFavouriteDetails.filter(movie => sampleIds.includes(movie.id))
    const totals = buildGenreTotals(allFavouriteDetails)

    favouriteSample.value = sampleDetails.slice(0, 5)
    genreTotals.value = totals
    recommendations.value = await buildRecommendations(totals, accountMovieIds)
  } catch (loadError) {
    error.value = loadError.message || 'Unable to load recommendations'
    console.error('Error loading account recommendations:', loadError)
  } finally {
    isLoading.value = false
  }
}

watch(
  () => [props.accountId, auth.isAuthenticated.value],
  loadRecommendations,
  { immediate: true }
)
</script>

<template>
  <section v-if="auth.isAuthenticated.value" class="recommendations-section">
    <div class="recommendations-header">
      <div>
        <h2>Recommended For You</h2>
        <p v-if="genreTotals.length" class="recommendations-muted">
          Based on your favourite genres:
          <span v-for="genre in genreTotals.slice(0, 3)" :key="genre.id" class="genre-pill">
            {{ genre.name }} {{ genre.count }}
          </span>
        </p>
      </div>
      <button type="button" class="refresh-button" @click="loadRecommendations" :disabled="isLoading">
        Refresh
      </button>
    </div>

    <div v-if="isLoading" class="recommendations-muted">Loading recommendations...</div>
    <div v-else-if="error" class="recommendations-error">{{ error }}</div>
    <div v-else-if="!hasRecommendations" class="recommendations-muted">
      Add favourite movies to get recommendations.
    </div>

    <template v-else>
      <div v-if="favouriteSample.length" class="favourite-basis">
        <h3>Recent Favourite Signals</h3>
        <div class="basis-list">
          <span v-for="movie in favouriteSample" :key="movie.id" class="basis-chip">
            {{ movie.title }}
          </span>
        </div>
      </div>

      <div class="recommendation-grid">
        <article
          v-for="movie in recommendations"
          :key="movie.id"
          class="recommendation-card"
          @click="router.push(`/films/${movie.id}`)"
        >
          <img :src="posterUrl(movie.poster_path)" :alt="movie.title">
          <div class="recommendation-details">
            <h3>{{ movie.title }}</h3>
            <p>{{ movie.release_date ? movie.release_date.substring(0, 4) : 'TBA' }}</p>
          </div>
        </article>
      </div>
    </template>
  </section>
</template>

<style scoped>
.recommendations-section {
  background: white;
  border: 1px solid #dee2e6;
  border-radius: 10px;
  padding: 1.5rem;
  margin-bottom: 2rem;
}

.recommendations-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  margin-bottom: 1.25rem;
  border-bottom: 2px solid #f1f3f5;
  padding-bottom: 0.75rem;
}

.recommendations-header h2 {
  margin: 0 0 0.35rem;
  font-size: 1.3rem;
}

.recommendations-muted {
  color: #6c757d;
  margin: 0;
}

.recommendations-error {
  color: #b42318;
  margin: 0;
}

.genre-pill,
.basis-chip {
  display: inline-flex;
  align-items: center;
  border: 1px solid #ced4da;
  border-radius: 999px;
  padding: 0.2rem 0.55rem;
  margin: 0.25rem 0.25rem 0 0;
  background: #f8f9fa;
  color: #343a40;
  font-size: 0.85rem;
}

.refresh-button {
  border: 1px solid #ced4da;
  border-radius: 6px;
  background: white;
  padding: 0.5rem 0.9rem;
  cursor: pointer;
  font-weight: 600;
}

.refresh-button:disabled {
  cursor: wait;
  opacity: 0.65;
}

.favourite-basis {
  margin-bottom: 1rem;
}

.favourite-basis h3 {
  font-size: 1rem;
  margin: 0 0 0.5rem;
}

.basis-list {
  display: flex;
  flex-wrap: wrap;
}

.recommendation-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 1rem;
}

.recommendation-card {
  border: 1px solid #dee2e6;
  border-radius: 8px;
  background: #fff;
  overflow: hidden;
  cursor: pointer;
  transition: transform 0.2s, border-color 0.2s;
}

.recommendation-card:hover {
  transform: translateY(-2px);
  border-color: #adb5bd;
}

.recommendation-card img {
  width: 100%;
  aspect-ratio: 2 / 3;
  object-fit: cover;
  display: block;
  background: #f1f3f5;
}

.recommendation-details {
  padding: 0.75rem;
}

.recommendation-details h3 {
  font-size: 0.95rem;
  margin: 0 0 0.25rem;
}

.recommendation-details p {
  margin: 0;
  color: #6c757d;
  font-size: 0.85rem;
}

@media (max-width: 768px) {
  .recommendations-header {
    flex-direction: column;
  }

  .refresh-button {
    width: 100%;
  }
}
</style>
