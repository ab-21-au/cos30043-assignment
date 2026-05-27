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
            <div class="del-button">
              <button @click="deleteReview(review.review_id)" class="btn btn-outline-danger btn-sm">
                Delete Review
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-center align-items-center gap-3 mt-4" aria-label="Reviews pages controls">
          <button 
            @click="prevPage" 
            class="pagination-btn" 
            :disabled="currentPage === 1"
            aria-label="Go to previous page"
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
            aria-label="Go to next page"
          >
            Next
          </button>

          <select 
          id="pageSizeSelect" 
          v-model="reviewsPerPage" 
          class="form-select form-select-sm" 
          style="width: auto;"
          >
          <option :value="2">2</option>
          <option :value="5">5</option>
          <option :value="10">10</option>
        </select>
        </div>

    </section>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  accountId: {
    type: [Number, String],
    required: true
  }
});

const reviews = ref([]);
const currentPage = ref(1);
const reviewsPerPage = ref(5);

// pagination

//reset if 
watch(reviewsPerPage, () => {
  currentPage.value = 1;
});
const totalPages = computed(() => {
  return Math.ceil(reviews.value.length / reviewsPerPage.value) || 1;
});

const paginatedReviews = computed(() => {
  const startIndex = (currentPage.value - 1) * reviewsPerPage.value;
  return reviews.value.slice(startIndex, startIndex + reviewsPerPage.value);
});

const nextPage = () => { if (currentPage.value < totalPages.value) currentPage.value++; };
const prevPage = () => { if (currentPage.value > 1) currentPage.value--; };


//get reviews
const getUserReviews = async () => {
  if (!props.accountId) {
    return;
  }

  try {
    const response = await fetch(`${import.meta.env.BASE_URL}api/get_account_reviews.php?account_id=${props.accountId}`);
    reviews.value = await response.json();
  } catch (error) {
    console.error("Error fetching account review records:", error);
  }
};

const deleteReview = async (reviewId) => {
  if (!confirm("Are you sure you want to permanently delete this film review?")) {
    return;
  }

  try {
    const response = await fetch('../api/del_review.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        review_id: reviewId,
        account_id: props.accountId // TMP CHANGE IF/WHEN ACCOUNT IMPLEMENTED
      })
    });

    const result = await response.json();

    if (result.success) {
      //update reviews
      await getUserReviews();
    } else {
      alert("Could not complete delete action: " + result.error);
    }
  } catch (error) {
    console.error("Network system error deleting review:", error);
    alert("An error occurred trying to connect to the server.");
  }
};


watch(
  () => props.accountId,
  getUserReviews,
  { immediate: true }
);
</script>


<style>
  .user-reviews-warning {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 120px;
  width: 80%;
  color: var(--text-secondary);
}


</style>
