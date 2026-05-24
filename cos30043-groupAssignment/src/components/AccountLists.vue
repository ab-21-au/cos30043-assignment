<script setup>
import AccountWelcomeCard from './AccountWelcomeCard.vue'

defineProps({
  account: {
    type: Object,
    required: true,
  },
})

const lists = [
  { name: 'Create List #1', movies: ['Arrival', 'Past Lives', 'Whiplash', 'Her', 'Nope', 'Alien'] },
  { name: 'Create List #2', movies: ['Heat', 'Jaws', 'Moonlight', 'Ratatouille', 'Scream', 'Se7en'] },
  { name: 'Create List #3', movies: ['Dune', 'Akira', 'Memento', 'Minari', 'Roma', 'Up'] },
]
</script>

<template>
  <div class="account-panel">
    <AccountWelcomeCard :account="account" />

    <section v-for="list in lists" :key="list.name" class="mb-4">
      <h3 class="h6 mb-3">{{ list.name }}</h3>
      <div class="row account-grid">
        <div v-for="movie in list.movies" :key="`${list.name}-${movie}`" class="col-6 col-md-4 col-xl-2">
          <div class="card account-card list-movie-card h-100">
            <div class="card-body d-flex flex-column justify-content-between">
              <div class="poster-placeholder d-flex align-items-center justify-content-center mb-3">
                <span class="small account-muted">Poster</span>
              </div>
              <div class="d-flex justify-content-between align-items-center gap-2">
                <span class="small fw-semibold text-truncate">{{ movie }}</span>
                <span aria-hidden="true">☆</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.list-movie-card {
  min-height: 138px;
}

.poster-placeholder {
  min-height: 70px;
  border: 1px dashed var(--border-subtle);
  border-radius: 8px;
}
</style>
