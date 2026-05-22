<template>
   <!-- User Reviews -->
      <section class="user-reviews">
      <h3 class="section-label">User Reviews</h3>
 
      <!-- checker 0 reviews -->
      <div v-if="reviews.length === 0" class="user-reviews-warning">
          <p>You haven't written any reviews, go make some!!!</p>
      </div>


      <div v-else class="row g-4 mx-auto">
        <div v-for="review in paginatedReviews" :key="review.review_id" class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">{{ review.title }}</h5>
              <p class="meta-text-review">{{ review.created_at ? review.created_at.substring(0, 4) : '' }}</p>
              <hr>
              <p class="card-text text-secondary">{{ review.content }}</p>
            </div>
            <div class="card-footer bg-transparent border-top-0">
              <p class="meta-text-review">
               Rating: {{ review.rating }} • Rewatch: {{ review.rewatch }} • Meet Expectations? {{ review.expectations }}
              </p>
              <p class="meta-text-review"> Plot: {{ review.plot }} • Acting:  {{ review.acting }} • Pacing: {{  review.pacing }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
          <button 
            @click="prevPage" 
            class="pagination-btn" 
            :disabled="currentPage === 1"
          >
            Prev
          </button>
          
          <span class="pagination-info">
            Page {{ currentPage }} of {{ totalPages }}
          </span>
          
          <button 
            @click="nextPage" 
            class="pagination-btn" 
            :disabled="currentPage === totalPages"
          >
            Next
          </button>
        </div>

    </section>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';

const props = defineProps({
  accountId: {
    type: [Number, String],
    required: true
  }
});

const reviews = ref([]);
const currentPage = ref(1);
const reviewsPerPage = 4;

// pagination
const totalPages = computed(() => {
  return Math.ceil(reviews.value.length / reviewsPerPage) || 1;
});

const paginatedReviews = computed(() => {
  const startIndex = (currentPage.value - 1) * reviewsPerPage;
  return reviews.value.slice(startIndex, startIndex + reviewsPerPage);
});

const nextPage = () => { if (currentPage.value < totalPages.value) currentPage.value++; };
const prevPage = () => { if (currentPage.value > 1) currentPage.value--; };


//get reviews
const getUserReviews = async () => {
  try {
//testing only
// const activeAccountId = props.accountId || 1;
// console.log("Testing user reviews for Account ID:", activeAccountId);

    const response = await fetch(`../api/get_account_reviews.php?account_id=${props.accountId}`);
    // const response = await fetch(`../api/get_account_reviews.php?account_id=${activeAccountId}`);
    reviews.value = await response.json();
  } catch (error) {
    console.error("Error fetching account review records:", error);
  }
};

onMounted(() => {
  getUserReviews();
});
</script>
