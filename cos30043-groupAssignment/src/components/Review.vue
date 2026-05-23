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

/* .user-review-form {
  background: var(--bg-surface);
  padding: 15%;
  margin-left: 5%;
  margin-right: 5%;
  margin-top: 2rem;
  margin-bottom: 2rem;
} */
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

.review-card, .card {
  background: var(--bg-surface);
  padding: 2rem;
  margin: 0 3rem;
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

  .fav-button {
  background-color: transparent;
  border: none;
  padding: 0;
  cursor: pointer;
  display: flex;
  align-items: center;
  transition: transform 0.2s ease;
}

.fav-button:hover {
  transform: scale(1.1);
  background-color: transparent;
}

.fav-button img {
  filter: var(--img-inverse); 
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
</style>

<template>
  <main class="review-page">

    <!-- Film Info || hero  -->
    <section class="review-hero">
      <div class="d-flex align-items-center gap-3">
        <h1 class="hero-title"> {{ movieHardcoded.title }} </h1>
        <button class="fav-button" @click="toggleFavourite">
          <img :src="isFavourited ? heartFull : heartEmpty" alt="Favourite">
        </button>
        <br>
      </div>
      <div>
        <p class="hero-desc">
          {{ movieHardcoded.year }} • {{ movieHardcoded.genre }} • {{ movieHardcoded.runtime }}
        </p>
        <p class="hero-rating">
          <!-- tmp -->
           Global Rating 88 || User Rating 90
        </p>
      </div>

      <div class="hero-desc-long">
        <p class="text-desc-long">
          {{ movieHardcoded.desc }}
        </p>
      </div>

      <div>
        <span v-for="tag in movieHardcoded.tags" v-bind:key="tag">{{ tag }} • </span>
      </div>
    </section>


    <!-- Review form -->
    <section class="user-review-form" v-if="currentUser">
      <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
          <div class="rating-box">
            <h2 class="star-rating"> Rate Plot: {{ newReview.plotRating }}/5</h2>
            <!-- <StarRating v-model="newReview.plotRating" /> -->
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
              <!-- <StarRating v-model="newReview.actRating" /> -->
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
            <!-- <StarRating v-model="newReview.paceRating" /> -->
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
            <select v-model="newReview.rating" class="form-select">
              <option disabled value="">Choose rating</option>
              <option>Peak</option>
              <option>So bad it's good</option>
              <option>Mid at best</option>
              <option>Trash</option>
            </select>
          </div>
          <div class="col-12 col-md-4">
            <select v-model="newReview.rewatch" class="form-select">
              <option disabled value="">Rewatch?</option>
              <option>First time watch</option>
              <option>Rewatch</option>
            </select>
          </div>
          <div class="col-12 col-md-4">
            <select v-model="newReview.expectations" class="form-select">
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
      <p class="login-prompt">
        You're not signed in! Please
        <!-- TMP UPDATE PROPER -->
        <RouterLink to="/login" class="login-link">login</RouterLink>
        to write a review.
      </p>
    </section>

    <!-- User Reviews -->
      <section class="user-reviews">
      <h3 class="section-label">User Reviews</h3>
 
      <!-- checker 0 reviews -->
      <div v-if="reviews.length === 0" class="user-reviews-warning">
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

  </main>
</template>


<script setup>
import { ref, onMounted, computed} from 'vue';
import heartEmpty from '../assets/heart.png';
import heartFull from '../assets/heart_fav_true.png';
// import StarRating from '../components/StarRating.vue'
// import { tmdb } from '../services/tmdb.js';

const isFavourited = ref(false);
// const userRating = ref(null);

//set true for now, change later when have account login system
const currentUser = ref(true)


const props = defineProps({
  id: {
    type: String,
    required: true
  }
});

// Tmp Film Data change later
const movieHardcoded = ref({
  id: 27205,
  title: "Inception",
  year: "2010",
  genre: "Sci-Fi",
  runtime: "123",
  globalRating: "8.8",
  desc: "A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.",
  tags: ["testing123","Mind-bending", "Classic", "Must Watch"]
});

const movie = ref({
  id: null,
  title: "Loading...",
  year: "",
  genre: "",
  runtime: "",
  desc: "",
  tags: []
});

// // Form data
// const newReview = ref({
//   text: '',
//   rating: '',
//   rewatch: '',
//   expectations: ''
// });

// // Tmp Reviews
// const reviews = ref([
//   { 
//     username: "tmpUser123", 
//     content: "This is indeed a movie. Yes it is. I can confirm that this movie is a movie.",
//     rating: "Peak", 
//     rewatch: "Rewatch", 
//     expectations: "Yes" 
//   },
//   { 
//     username: "tmpUser456", 
//     content: "There were no birds in the movie.",
//     rating: "Mid at best", 
//     rewatch: "First time watch", 
//     expectations: "No" 
//   }
// ]);

const reviews = ref([]);
const newReview = ref({ 
  text: '', rating: '', 
  rewatch: '', 
  expectations: '',
  plotRating: 0,
  actRating: 0,
  paceRating: 0
});



//pagination
const currentPage = ref(1);
const reviewsPerPage = 10;

const totalPages = computed(() => {
  return Math.ceil(reviews.value.length / reviewsPerPage) || 1;
});

const paginatedReviews = computed(() => {
  const startIndex = (currentPage.value - 1) * reviewsPerPage;
  const endIndex = startIndex + reviewsPerPage;
  return reviews.value.slice(startIndex, endIndex);
});

// Navigation controls
const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
};

const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};


// fetch reviews here
const getReviews = async () => {
  try {
    const response = await fetch(`${import.meta.env.BASE_URL}api/get_reviews.php?movie_id=${movie.value.id}`);
    reviews.value = await response.json();
  } catch (error) {
    console.error("Error fetching reviews:", error);
  }
};

onMounted(() => {
  getReviews();
});

const submitReview = async () => {

  try {
    const response = await fetch(`${import.meta.env.BASE_URL}api/post_reviews.php`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        account_id: 3, // TMP UPDATE WITH ACCOUNT DETAILS LATER
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

</script>
