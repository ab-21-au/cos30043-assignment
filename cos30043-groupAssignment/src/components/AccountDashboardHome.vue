<script setup>
import { ref, watch } from 'vue'
import AccountWelcomeCard from './AccountWelcomeCard.vue'
import AccountRecommendations from './AccountRecommendations.vue'
import errorPlaceholder from '../assets/error-placeholder.jpg'
import { tmdb } from '../services/tmdb.js'

const props = defineProps({
  account: {
    type: Object,
    required: true,
  },
  accountId: {
    type: [Number, String],
    required: true,
  },
})

const favouriteMovies = ref([])
const isLoading = ref(false)
const error = ref('')

const apiUrl = (endpoint) => import.meta.env.DEV
  ? `/api/${endpoint}`
  : `${import.meta.env.BASE_URL}api/${endpoint}`

const posterUrl = (posterPath) => {
  return posterPath
    ? `https://image.tmdb.org/t/p/w342${posterPath}`
    : errorPlaceholder
}

const addMovieDetails = async (movieRows) => {
  const detailResults = await Promise.allSettled(
    movieRows.map(movie => tmdb.getMovieById(movie.tmdb_movie_id))
  )

  return movieRows.map((movie, index) => {
    const result = detailResults[index]
    const details = result.status === 'fulfilled' && !result.value.status_code
      ? result.value
      : {}

    return {
      ...movie,
      title: details.title || `TMDB #${movie.tmdb_movie_id}`,
      poster_path: details.poster_path || null,
    }
  })
}

const formatStatus = (status) => {
  return status.replaceAll('_', ' ')
}

const getFavouriteMovies = async () => {
  if (!props.accountId) {
    return
  }

  isLoading.value = true
  error.value = ''

  try {
    const response = await fetch(apiUrl(`get_account_movies.php?account_id=${props.accountId}&favourites_only=1`))
    const result = await response.json()

    if (!result.success) {
      throw new Error(result.error || 'Unable to load favourite movies')
    }

    favouriteMovies.value = await addMovieDetails(result.movies || [])
  } catch (fetchError) {
    error.value = fetchError.message
    console.error('Error fetching favourite movies:', fetchError)
  } finally {
    isLoading.value = false
  }
}

watch(
  () => props.accountId,
  getFavouriteMovies,
  { immediate: true }
)
</script>

<template>
  <div class="account-panel">
    <AccountWelcomeCard :account="account" />

    <AccountRecommendations :account-id="accountId" />

    <section>
      <h3 class="h5 mb-3">Favourite Movies</h3>
      <div v-if="isLoading" class="account-muted">Loading favourite movies...</div>
      <div v-else-if="error" class="alert alert-danger">{{ error }}</div>
      <div v-else-if="favouriteMovies.length === 0" class="account-muted">
        No favourite movies found.
      </div>
      <div v-else class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3">
        <div v-for="movie in favouriteMovies" :key="movie.user_movie_id" class="col">
          <article class="card-body">
            <div class="card account-card list-card h-100 overflow-hidden text-decoration-none">
              <router-link :to="`/films/${movie.tmdb_movie_id}`" class="d-block ratio poster-ratio overflow-hidden rounded mb-2">
                <img class="w-100 d-block object-fit-cover poster-image" :src="posterUrl(movie.poster_path)" :alt="`${movie.title} poster`">
              </router-link>
              <div class="p-3">
                <div class="d-flex justify-content-between gap-3 mb-2">
                  <router-link :to="`/films/${movie.tmdb_movie_id}`" class="movie-title h6 mb-0 text-truncate text-decoration-none">
                    {{ movie.title }}
                  </router-link>
                  <span aria-label="Favourite movie">☆</span>
                </div>

                <div class="d-flex justify-content-between gap-2 mt-auto account-muted small">
                  <span class="movie-status">{{ formatStatus(movie.status) }}</span>
                  <span>{{ movie.created_at ? movie.created_at.substring(0, 10) : '' }}</span>
                </div>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.poster-ratio {
  aspect-ratio: 2 / 3;
}

.poster-image {
  background: var(--bg-surface);
  margin-top: 0;
  margin-bottom: 20px;
}

.movie-title {
  color: var(--text-primary);
}

.movie-title:hover {
  color: var(--accent);
}

.movie-status {
  color: var(--accent-deeper);
  font-style: bold;
}

.list-card {
  transition: transform 0.2s, border-color 0.2s;
}

.list-card:hover {
  transform: translateY(-2px);
  border-color: var(--accent);
}
</style>
