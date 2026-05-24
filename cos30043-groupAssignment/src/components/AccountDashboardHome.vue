<script setup>
import AccountWelcomeCard from './AccountWelcomeCard.vue'

defineProps({
  account: {
    type: Object,
    required: true,
  },
})

const favouriteMovies = [
  { title: 'Past Lives', genre: 'Drama', rating: '5.0' },
  { title: 'Dune: Part Two', genre: 'Sci-Fi', rating: '4.0' },
  { title: 'Arrival', genre: 'Sci-Fi', rating: '4.5' },
  { title: 'Whiplash', genre: 'Drama', rating: '4.5' },
  { title: 'The Matrix', genre: 'Action', rating: '4.0' },
  { title: 'Spirited Away', genre: 'Animation', rating: '5.0' },
]
</script>

<template>
  <div class="account-panel">
    <AccountWelcomeCard :account="account" />

    <section>
      <h3 class="h5 mb-3">Liked / Favourite Movies</h3>
      <div class="row account-grid">
        <div v-for="movie in favouriteMovies" :key="movie.title" class="col-sm-6 col-xl-4">
          <article class="card account-card favourite-card h-100">
            <div class="card-body d-flex flex-column">
              <div class="poster-placeholder d-flex align-items-center justify-content-center mb-3">
                <span class="small account-muted">Poster</span>
              </div>

              <div class="d-flex justify-content-between gap-3 mb-2">
                <h4 class="h6 mb-0 text-truncate">{{ movie.title }}</h4>
                <span aria-label="Favourite movie">☆</span>
              </div>

              <div class="d-flex justify-content-between gap-3 mt-auto small account-muted">
                <span>{{ movie.genre }}</span>
                <span>{{ movie.rating }}/5</span>
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
