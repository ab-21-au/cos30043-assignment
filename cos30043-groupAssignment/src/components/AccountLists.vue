<script setup>
import { computed, ref, watch } from 'vue'
import AccountWelcomeCard from './AccountWelcomeCard.vue'

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

    movies.value = result.movies
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
            <div class="card account-card list-movie-card h-100">
              <div class="card-body d-flex flex-column justify-content-between">
                <div class="poster-placeholder d-flex align-items-center justify-content-center mb-3">
                  <span class="small account-muted">Poster</span>
                </div>
                <div class="d-flex justify-content-between align-items-center gap-2">
                  <span class="small fw-semibold text-truncate">TMDB #{{ movie.tmdb_movie_id }}</span>
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
.list-movie-card {
  min-height: 190px;
}

.poster-placeholder {
  min-height: 70px;
  border: 1px dashed var(--border-subtle);
  border-radius: 8px;
}
</style>
