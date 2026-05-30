<script setup>
import { computed, ref, watch } from 'vue'
import AccountWelcomeCard from './AccountWelcomeCard.vue'
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

const movies = ref([])
const isLoading = ref(false)
const error = ref('')
const updatingMovieId = ref(null)

const statusOptions = [
  { label: 'Want to Watch', value: 'want_to_watch' },
  { label: 'Watching', value: 'watching' },
  { label: 'Watched', value: 'watched' },
]

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

const listGroups = computed(() => {
  return [
    {
      name: 'Want to Watch',
      movies: movies.value.filter(movie => movie.status === 'want_to_watch'),
    },
    {
      name: 'Watching',
      movies: movies.value.filter(movie => movie.status === 'watching'),
    },
    {
      name: 'Watched',
      movies: movies.value.filter(movie => movie.status === 'watched'),
    },
  ]
})

const getMovies = async () => {
  if (!props.accountId) {
    return
  }

  isLoading.value = true
  error.value = ''

  try {
    const response = await fetch(apiUrl(`get_account_movies.php?account_id=${props.accountId}`))
    const result = await response.json()

    if (!result.success) {
      throw new Error(result.error || 'Unable to load movie lists')
    }

    movies.value = await addMovieDetails(result.movies || [])
  } catch (fetchError) {
    error.value = fetchError.message
    console.error('Error fetching account movie lists:', fetchError)
  } finally {
    isLoading.value = false
  }
}

const updateMovieStatus = async (movie, status) => {
  if (movie.status === status) {
    return
  }

  updatingMovieId.value = movie.user_movie_id
  error.value = ''

  try {
    const response = await fetch(apiUrl('update_movie_status.php'), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({
        user_movie_id: movie.user_movie_id,
        status,
      }),
    })
    const result = await response.json()

    if (!result.success) {
      throw new Error(result.error || 'Unable to update movie status')
    }

    movie.status = status
  } catch (updateError) {
    error.value = updateError.message
    console.error('Error updating movie status:', updateError)
  } finally {
    updatingMovieId.value = null
  }
}

watch(
  () => props.accountId,
  getMovies,
  { immediate: true }
)
</script>

<template>
  <div class="account-panel">
    <AccountWelcomeCard :account="account" />

    <div v-if="isLoading" class="account-muted">Loading movie lists...</div>
    <div v-else-if="error" class="alert alert-danger">{{ error }}</div>
    <template v-else>
      <section v-for="list in listGroups" :key="list.name" class="mb-4">
        <h3 class="h6 mb-3">{{ list.name }}</h3>
        <p v-if="list.movies.length === 0" class="account-muted">No movies in this list.</p>
        <div v-else class="row account-grid">
          <div v-for="movie in list.movies" :key="movie.user_movie_id" class="col-6 col-md-4 col-xl-2">
            <div class="card account-card h-100">
              <div class="card-body d-flex flex-column justify-content-between">
                <router-link :to="`/films/${movie.tmdb_movie_id}`" class="d-block ratio poster-ratio overflow-hidden rounded mb-3">
                  <img class="w-100 h-100 object-fit-cover poster-image" :src="posterUrl(movie.poster_path)" :alt="`${movie.title} poster`">
                </router-link>
                <div class="d-flex justify-content-between align-items-center gap-2">
                  <router-link :to="`/films/${movie.tmdb_movie_id}`" class="movie-title small fw-semibold text-truncate text-decoration-none">
                    {{ movie.title }}
                  </router-link>
                  <span v-if="movie.is_favourite" aria-label="Favourite movie">☆</span>
                </div>
                <label class="small account-muted mt-3 mb-1" :for="`movie-status-${movie.user_movie_id}`">
                  Status
                </label>
                <select
                  :id="`movie-status-${movie.user_movie_id}`"
                  class="form-select form-select-sm"
                  :value="movie.status"
                  :disabled="updatingMovieId === movie.user_movie_id"
                  @change="updateMovieStatus(movie, $event.target.value)"
                >
                  <option
                    v-for="option in statusOptions"
                    :key="option.value"
                    :value="option.value"
                  >
                    {{ option.label }}
                  </option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </section>
    </template>
  </div>
</template>

<style scoped>
.poster-ratio {
  --bs-aspect-ratio: 150%;
}

.poster-image {
  background: var(--bg-surface);
}

.movie-title {
  color: var(--text-primary);
}

.movie-title:hover {
  color: var(--accent);
}
</style>
