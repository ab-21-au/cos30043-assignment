<script setup>
import { computed, ref, watch } from 'vue'
import { tmdb } from '../services/tmdb.js'
import { useAuth } from '../assets/UseAuth.js'

const props = defineProps({
  accountId: {
    type: [Number, String],
    default: null,
  },
})

const auth = useAuth()

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
      recommendations.value = []
      genreTotals.value = []
      return
    }

    const allFavouriteDetails = await getMovieDetails(favouriteIds)
    const totals = buildGenreTotals(allFavouriteDetails)

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
  <section v-if="auth.isAuthenticated.value" class="card account-card mb-3">
    <div class="p-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4 border-bottom pb-3">
        <div>
          <h2 class="h5 mb-1">Recommended For You</h2>
          <p v-if="genreTotals.length" class="account-muted mb-0">
            Based on your favourite genres:
            <span v-for="genre in genreTotals.slice(0, 3)" :key="genre.id" class="genre-pill d-inline-flex align-items-center rounded-pill px-2 py-1 mt-1 me-1 small">
              {{ genre.name }} {{ genre.count }}
            </span>
          </p>
        </div>
        <button type="button" class="btn btn-outline-secondary flex-shrink-0" @click="loadRecommendations" :disabled="isLoading">
          Refresh
        </button>
      </div>

      <div v-if="isLoading" class="account-muted">Loading recommendations...</div>
      <div v-else-if="error" class="text-danger">{{ error }}</div>
      <div v-else-if="!hasRecommendations" class="account-muted">
        Add favourite movies to get recommendations.
      </div>

      <template v-else>
        <div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3">
          <div
            v-for="movie in recommendations"
            :key="movie.id"
            class="col"
          >
            <router-link
              :to="`/films/${movie.id}`"
              class="card account-card recommendation-card h-100 overflow-hidden text-decoration-none"
            >
              <img class="w-100 d-block object-fit-cover recommendation-poster" :src="posterUrl(movie.poster_path)" :alt="movie.title">
              <div class="p-3">
                <h3 class="h6 mb-1">{{ movie.title }}</h3>
                <p class="small account-muted mb-0">{{ movie.release_date ? movie.release_date.substring(0, 4) : 'TBA' }}</p>
              </div>
            </router-link>
          </div>
        </div>
      </template>
    </div>
  </section>
</template>

<style scoped>
.genre-pill {
  border: 1px solid var(--border-subtle);
  background: var(--bg-form);
  color: var(--text-primary);
}

.recommendation-card {
  transition: transform 0.2s, border-color 0.2s;
}

.recommendation-card:hover {
  transform: translateY(-2px);
  border-color: var(--accent);
}

.recommendation-poster {
  aspect-ratio: 2 / 3;
  background: var(--bg-surface);
}
</style>
