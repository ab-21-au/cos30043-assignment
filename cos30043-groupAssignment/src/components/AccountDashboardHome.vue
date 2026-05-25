<script setup>
import { onMounted, ref } from 'vue'
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

const favouriteMovies = ref([])
const isLoading = ref(false)
const error = ref('')

const formatStatus = (status) => {
  return status.replaceAll('_', ' ')
}

const getFavouriteMovies = async () => {
  isLoading.value = true
  error.value = ''

  try {
    const response = await fetch(`${import.meta.env.BASE_URL}api/get_account_movies.php?account_id=${props.accountId}&favourites_only=1`)
    const result = await response.json()

    if (!result.success) {
      throw new Error(result.error || 'Unable to load favourite movies')
    }

    favouriteMovies.value = result.movies
  } catch (fetchError) {
    error.value = fetchError.message
    console.error('Error fetching favourite movies:', fetchError)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  getFavouriteMovies()
})
</script>

<template>
  <div class="account-panel">
    <AccountWelcomeCard :account="account" />

    <section>
      <h3 class="h5 mb-3">Liked / Favourite Movies</h3>
      <div v-if="isLoading" class="account-muted">Loading favourite movies...</div>
      <div v-else-if="error" class="alert alert-danger">{{ error }}</div>
      <div v-else-if="favouriteMovies.length === 0" class="account-muted">
        No favourite movies found.
      </div>
      <div v-else class="row account-grid">
        <div v-for="movie in favouriteMovies" :key="movie.user_movie_id" class="col-sm-6 col-xl-4">
          <article class="card account-card favourite-card h-100">
            <div class="card-body d-flex flex-column">
              <div class="poster-placeholder d-flex align-items-center justify-content-center mb-3">
                <span class="small account-muted">Poster</span>
              </div>

              <div class="d-flex justify-content-between gap-3 mb-2">
                <h4 class="h6 mb-0 text-truncate">TMDB #{{ movie.tmdb_movie_id }}</h4>
                <span aria-label="Favourite movie">☆</span>
              </div>

              <div class="d-flex justify-content-between gap-3 mt-auto small account-muted">
                <span>{{ formatStatus(movie.status) }}</span>
                <span>{{ movie.created_at ? movie.created_at.substring(0, 10) : '' }}</span>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.favourite-card {
  min-height: 220px;
}

.poster-placeholder {
  min-height: 120px;
  border: 1px dashed var(--border-subtle);
  border-radius: 8px;
}
</style>
