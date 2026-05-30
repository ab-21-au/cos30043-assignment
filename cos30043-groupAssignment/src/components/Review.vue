<template>
  <main class="review-page">

    <!-- Film Info || hero  -->
    <section class="review-hero" aria-label="Movie details">
      <div class="d-flex align-items-center gap-3">
        <h1 class="hero-title"> {{ movie.title }} </h1>
        <button
          v-if="currentUser"
          type="button"
          class="favourite-btn"
          :disabled="isFavourited || isFavouriteSubmitting"
          @click="addFavouriteMovie"
        >
          {{ isFavourited ? 'Favourite Added' : 'Add Favourite' }}
        </button>
        <select
          v-if="currentUser"
          v-model="movieListStatus"
          class="status-select"
          :disabled="isStatusSubmitting"
          @change="saveMovieStatus"
          aria-label="Movie list status"
        >
          <option disabled value="">Set status</option>
          <option value="want_to_watch">Want to Watch</option>
          <option value="watching">Watching</option>
          <option value="watched">Watched</option>
        </select>
        <br>
      </div>
      <div>
        <p class="hero-desc">
          {{ movie.year }} • Runtime: {{ movie.runtime }}m
        </p>
        <p class="hero-rating">
          Global Rating: {{ movie.globalRating }}
        </p>
      </div>

      <div class="hero-desc-long">
        <p class="text-desc-long">
          {{ movie.desc }}
        </p>
      </div>

      <div>
        <span v-for="tag in movie.tags" v-bind:key="tag">{{ tag }} • </span>
      </div>
    </section>


    <!-- Review form -->
    <section class="user-review-form" v-if="currentUser" aria-label="Post a review">
      <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
          <div class="rating-box">
            <h2 class="star-rating"> Rate Plot: {{ newReview.plotRating }}/5</h2>
             <v-rating
              v-model="newReview.plotRating"
              hover
              :length="5"
              :size="32"
              color="var(--accent)"
              active-color="var(--accent)"
            />
          </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="rating-box">
              <h2 class="star-rating"> Rate Acting: {{ newReview.actRating }}/5</h2>
               <v-rating
                v-model="newReview.actRating"
                hover
                :length="5"
                :size="32"
                color="var(--accent)"
                active-color="var(--accent)"
              />
            </div>
        </div>

        <div class="col-12 col-md-4">
          <div class="rating-box">
            <h2 class="star-rating"> Rate Pacing: {{ newReview.paceRating }}/5</h2>
             <v-rating
              v-model="newReview.paceRating"
              hover
              :length="5"
              :size="32"
              color="var(--accent)"
              active-color="var(--accent)"
            />
          </div>
        </div>
      </div>

      <form @submit.prevent="submitReview">
        <div class="mb-3">
          <label for="reviewText" class="form-label"></label>
          <textarea id="reviewText" v-model="newReview.text" class="form-control" rows="4" placeholder="Share your thoughts on the film" required></textarea>
        </div>

        <div class="row g-3 mb-4">
          <div class="col-12 col-md-4">
            <select v-model="newReview.rating" class="form-select" aria-label="Overall movie rating" required>
              <option disabled value="">Choose rating</option>
              <option>Peak</option>
              <option>So bad it's good</option>
              <option>Mid at best</option>
              <option>Trash</option>
            </select>
          </div>
          <div class="col-12 col-md-4">
            <select v-model="newReview.rewatch" class="form-select" aria-label="If it's a rewatch" required>
              <option disabled value="">Rewatch?</option>
              <option>First time watch</option>
              <option>Rewatch</option>
            </select>
          </div>
          <div class="col-12 col-md-4">
            <select v-model="newReview.expectations" class="form-select" aria-label="If it met expectations" required>
              <option disabled value="">Met expectations?</option>
              <option>Yes</option>
              <option>No</option>
            </select>
          </div>
          <div>
            <button type="submit" class="post-btn">Post Review</button>
          </div>
        </div>
      </form>
    </section>

    <!-- Not logged in state -->
    <section class="user-review-form not-logged-in" v-else>
      <p class="login-prompt" aria-label="Login notice, need to login to review">
        You're not signed in! Please
        <RouterLink to="/login" class="login-link">login</RouterLink>
        to write a review.
      </p>
    </section>

    <!-- Rating Timeline -->
     <ReviewTimeline />

    <!-- User Reviews -->
      <section class="user-reviews">
      <h3 class="section-label">User Reviews</h3>
 
      <!-- checker 0 reviews -->
      <div v-if="reviews.length === 0" class="user-reviews-warning" aria-label="No reviews for this movie">
          <p>No reviews yet for this movie. Be the first!</p>
      </div>


      <div v-else class="row g-4 mx-auto">
        <div v-for="review in paginatedReviews" :key="review.review_id" class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">{{ review.username }}</h5>
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

          <div class="d-flex flex-column align-items-start gap-2">
            <label for="pageSizeSelect" id="pageSizeLabel" class="mb-0">Reviews per page:</label>
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
        </div>

    </section>

  </main>
</template>


<script setup>
import { ref, onMounted, computed, provide, watch} from 'vue';
import { useRoute } from 'vue-router';
import { tmdb } from '../services/tmdb.js';
import ReviewTimeline from './ReviewTimeline.vue';
import { useAuth } from '../assets/UseAuth.js';


const route = useRoute()
const auth = useAuth()
const isFavourited = ref(false);
const isFavouriteSubmitting = ref(false);
const isStatusSubmitting = ref(false);
const movieListStatus = ref('');

const currentUser = computed(() => auth.isAuthenticated.value)

const movie = ref({
  id: route.params.id,
  title: "Loading...",
  year: "",
  genre: "",
  runtime: "",
  globalRating: "0.0",
  desc: "",
  tags: []
});

const reviews = ref([]);

const newReview = ref({ 
  text: '', rating: '', 
  rewatch: '', 
  expectations: '',
  plotRating: 0,
  actRating: 0,
  paceRating: 0
});

const apiUrl = (endpoint) => import.meta.env.DEV
  ? `/api/${endpoint}`
  : `${import.meta.env.BASE_URL}api/${endpoint}`;

//fetch movie details from TMDB
const fetchMovieDetails = async () => {
  try {
    const movieId = route.params.id;
    const data = await tmdb.getMovieDetails(movieId);
    
    if (data) {
      movie.value = {
        id: data.id,
        title: data.title,
        year: data.release_date ? data.release_date.split('-')[0] : 'N/A',
        genre: data.genres?.map(g => g.name).join(', ') || 'N/A',
        runtime: data.runtime || 'Unknown',
        globalRating: data.vote_average ? data.vote_average.toFixed(1) : 'N/A',
        desc: data.overview || 'No overview available.',
        tags: data.genres?.map(g => g.name) || []
      };
    }
  } catch (error) {
    console.error("Error pulling TMDB details:", error);
    movie.value.title = "Failed to load movie info";
  }
};

//pagination
const currentPage = ref(1);
const reviewsPerPage = ref(5);

//reset if 
watch(reviewsPerPage, () => {
  currentPage.value = 1;
});

const totalPages = computed(() => {
  return Math.ceil(reviews.value.length / reviewsPerPage.value) || 1;
});

const paginatedReviews = computed(() => {
  const startIndex = (currentPage.value - 1) * reviewsPerPage.value;
  const endIndex = startIndex + reviewsPerPage.value;
  return reviews.value.slice(startIndex, endIndex);
});

const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
};

const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};


// fetch reviews here
const getReviews = async () => {
  try {
    const response = await fetch(apiUrl(`get_reviews.php?movie_id=${movie.value.id}`));
    //fixing error, if not json, will go to error
    const data = await response.json();
    // Just ensure the data is an array so pagination doesn't break
    reviews.value = Array.isArray(data) ? data : [];
  } catch (error) {
    console.error("Error fetching reviews:", error);
    reviews.value = []; 
  }
};

//submit review here
const submitReview = async () => {
  if (!auth.accountId.value) {
    alert("Please sign in again before posting a review.");
    return;
  }

  if (!newReview.value.rating || !newReview.value.rewatch || !newReview.value.expectations || !newReview.value.text.trim()) {
    alert("Please complete the whole review form before submitting.");
    return;
  }

  try {
    const response = await fetch(apiUrl('post_reviews.php'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        account_id: auth.accountId.value,
        tmdb_movie_id: movie.value.id,
        rating_plot: newReview.value.plotRating,
        rating_acting: newReview.value.actRating,
        rating_pacing: newReview.value.paceRating,
        rating: newReview.value.rating,
        review_text: newReview.value.text,
        rewatch_status: newReview.value.rewatch,
        met_expectations: newReview.value.expectations
      })
    });

    const result = await response.json();

    if (result.success) {
      // reset vars
      newReview.value = { text: '', rating: '', rewatch: '', expectations: '', plotRating: 0, actRating: 0, paceRating: 0 };
      
      // refresh review feed
      await getReviews(); 
    } else {
      alert("Submission failed: " + result.error);
    }
  } catch (error) {
    console.error("Network submission error:", error);
    alert("An error occurred while transmitting your review.");
  }
};

const addFavouriteMovie = async () => {
  if (!auth.isAuthenticated.value) {
    alert("Please sign in to add favourites.");
    return;
  }

  isFavouriteSubmitting.value = true;

  try {
    const response = await fetch(apiUrl('add_favourite.php'), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({ tmdb_movie_id: movie.value.id })
    });

    const data = await response.json();

    if (data.success) {
      isFavourited.value = true;
    } else {
      alert(data.error || "Could not add this movie to favourites.");
    }
  } catch (error) {
    console.error("Error adding favourite movie:", error);
    alert("Network error while adding favourite movie.");
  } finally {
    isFavouriteSubmitting.value = false;
  }
};

const saveMovieStatus = async () => {
  if (!auth.isAuthenticated.value || !movieListStatus.value) {
    return;
  }

  isStatusSubmitting.value = true;

  try {
    const response = await fetch(apiUrl('save_movie_list_status.php'), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({
        tmdb_movie_id: movie.value.id,
        status: movieListStatus.value
      })
    });

    const data = await response.json();

    if (!data.success) {
      alert(data.error || "Could not save movie status.");
    }
  } catch (error) {
    console.error("Error saving movie status:", error);
    alert("Network error while saving movie status.");
  } finally {
    isStatusSubmitting.value = false;
  }
};

provide('movie', movie) //COME BACK
provide('reviews', reviews)

onMounted(async() => {
  await fetchMovieDetails();
  await getReviews();
  console.log('reviews:', reviews.value)
  console.log('movie year:', movie.value.year)
  console.log('snapshots:', snapshots.value)
});
</script>


<style>
  .review-page {
    background-color: var(--bg-primary);
    color: var(--text-primary);
    padding-bottom: 3rem;
  }

  .hero-title {
  font-family: freight-sans-pro-ultra, sans-serif;
  font-size: 3.5rem;
  color: var(--text-primary);
  line-height: 0.9;
  text-shadow: 3px 3px 0px rgba(0,0,0,0.1);
  }

.review-hero {
  background: var(--accent);
  padding: 5rem 2.5rem;
  border-bottom: 8px solid var(--accent-deep);
  color: var(--on-accent);
}

.favourite-btn {
  border: 1px solid var(--on-accent);
  border-radius: 6px;
  background: var(--on-accent);
  color: var(--accent-deep);
  padding: 0.55rem 0.9rem;
  font-weight: 700;
  cursor: pointer;
}

.favourite-btn:disabled {
  opacity: 0.7;
  cursor: default;
}

.status-select {
  min-width: 150px;
  border: 1px solid var(--on-accent);
  border-radius: 6px;
  background: var(--bg-primary);
  color: var(--text-primary);
  padding: 0.55rem 0.75rem;
  font-weight: 700;
}

/* style font section label */
.section-label {
  color: var(--accent);
  border-left: 4px solid var(--accent);
  padding-left: 0.75rem;
}

.user-review-form {
  background: var(--bg-surface);
  width: 90%;
  margin-left: 5%;
  padding : 2.5rem;
  margin-top: 2rem;
  margin-bottom: 2rem;
}

.review-card, .card {
  background: var(--bg-surface);
  padding: 2rem;
  margin: 0 3rem;
}

.form-select, .form-control {
  background: var(--bg-primary);
  color: var(--text-primary);
  border: 1px solid var(--border-subtle);
}

.form-control::placeholder {
  color: var(--text-secondary);
}


.user-reviews-warning {
  background: var(--bg-surface);
  width: 90%;
  margin-left: 5%;
  padding : 2.5rem;
}

.rating-box {
  background: var(--bg-primary);
  padding: 1rem;
  padding-bottom: 5rem;
}

  .hero-desc {
    font-size: 1.2rem;
    color: var(--on-accent-muted);
  }
  .hero-rating {
    font-size: 1.2rem;
    color: var(--on-accent-muted);
  }
  .hero-desc-long {
    margin-top: 20px;
  }
  .text-desc-long {
    font-size: 1rem;
    color: var(--on-accent);
  }

  .card-title {
    color: var(--accent)
  }
  
  .star-rating {
    font-size: 1.5rem;
    margin-bottom: 10px;
  }
  .post-btn {
    background-color: var(--accent);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
  }
  .post-btn:hover {
    background-color: var(--accent-deeper);
  }
  .section-label {
    font-size: 1.8rem;
    margin-bottom: 20px;
    margin-left: 5%;
  }
  .meta-text-review {
    font-size: 0.9rem;
    color: var(--text-muted);
  }



  .not-logged-in {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 120px;
}

.login-prompt {
  font-size: 1rem;
  color: var(--text-secondary);
  margin: 0;
}

.login-link {
  color: var(--accent);
  font-weight: 600;
  text-decoration: none;
}

.login-link:hover {
  text-decoration: underline;
}



.pagination-btn {
  background-color: var(--bg-surface);
  color: var(--text-primary);
  border: 1px solid var(--border-subtle);
  padding: 0.5rem 1.25rem;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

.pagination-btn:hover:not(:disabled) {
  background-color: var(--accent);
  color: white;
  border-color: var(--accent);
}

.pagination-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.pagination-info {
  font-size: 1rem;
  color: var(--text-secondary);
  font-weight: 500;
}

#pageSizeLabel {
  display: block;
  font-size: 12px;
  font-family: var(--bs-body-font-family);
  color: var(--text-secondary);
}
</style>
