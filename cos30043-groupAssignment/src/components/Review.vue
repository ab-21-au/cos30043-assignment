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


</style>

<template>
  <main class="review-page">

    <!-- Film Info || hero  -->
    <section class="review-hero">
      <div class="d-flex align-items-center gap-3">
        <h1 class="hero-title"> {{ movie.title }} </h1>
        <button class="fav-button" @click="toggleFavourite">
          <img :src="isFavourited ? heartFull : heartEmpty" alt="Favourite">
        </button>
        <br>
      </div>
      <div>
        <p class="hero-desc">
          {{ movie.year }} • {{ movie.genre }} • {{ movie.runtime }}
        </p>
        <p class="hero-rating">
          <!-- tmp -->
           Global Rating 88 || User Rating 90
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
    <section class="user-review-form" v-if="currentUser">
      <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
          <div class="rating-box">
            <h2 class="star-rating"> Rate Plot:</h2>
            <!-- tmp add stars here -->
          </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="rating-box">
              <h2 class="star-rating"> Rate Acting:</h2>
              <!-- tmp add stars here -->
            </div>
        </div>

        <div class="col-12 col-md-4">
          <div class="rating-box">
            <h2 class="star-rating"> Rate Pacing:</h2>
            <!-- tmp add stars here -->
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
        <RouterLink to="/login" class="login-link">login</RouterLink>
        to write a review.
      </p>
    </section>





      <section class="user-reviews">
      <h3 class="section-label">User Reviews</h3>

        
      <!-- checker 0 reviews -->
      <div v-if="reviews.length === 0" class="user-reviews-warning">
          <p>No reviews yet for this movie. Be the first!</p>
      </div>


      <div v-else class="row g-4 mx-auto">
        <div v-for="review in reviews" :key="review.review_id" class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">{{ review.username }}</h5>
              <p class="meta-text-review">{{ review.created_at ? review.created_at.substring(0, 4) : '' }}</p>
              <hr>
              <p class="card-text text-secondary">{{ review.content }}</p>
            </div>
            <div class="card-footer bg-transparent border-top-0">
              <p class="meta-text-review">
                {{ review.rating }} | {{ review.rewatch }} | Expectations: {{ review.expectations }}
              </p>
            </div>
          </div>
        </div>
      </div>

    </section>

  </main>
</template>


<script setup>
import { ref, onMounted} from 'vue';
import heartEmpty from '../assets/heart.png';
import heartFull from '../assets/heart_fav_true.png';

const isFavourited = ref(false);
const userRating = ref(null);

//set true for now, change later when have account login system
const currentUser = ref(true)




// Tmp Film Data change later
const movie = ref({
  id: 27205,
  title: "Inception",
  year: "2010",
  genre: "Sci-Fi",
  runtime: "123",
  globalRating: "8.8",
  desc: "A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.",
  tags: ["testing123","Mind-bending", "Classic", "Must Watch"]
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
const newReview = ref({ text: '', rating: '', rewatch: '', expectations: '' });




// fetch reviews here
const getReviews = async () => {
  try {
    const response = await fetch(`../api/get_reviews.php?movie_id=${movie.value.id}`);
    reviews.value = await response.json();
  } catch (error) {
    console.error("Error fetching reviews:", error);
  }
};

onMounted(() => {
  getReviews();
});


</script>
