<style>
  .review-page {
    padding: 20px;
    background-color: var(--bg-primary);
    color: var(--text-primary);
  }

  .hero-title {
    font-size: 2.5rem;
    font-weight: bold;
    display: flex;
    align-items: center;
  }
  .hero-desc {
    font-size: 1.2rem;
    color: var(--text-secondary);
  }
  .hero-rating {
    font-size: 1.2rem;
    color: var(--text-muted);
  }
  .hero-desc-long {
    margin-top: 20px;
  }
  .text-desc-long {
    font-size: 1rem;
    color: var(--text-primary);
  }
  .fav-button {
    background-color: var(--accent);
    color: var(--text-primary);
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
  }
  .fav-button:hover {
    background-color: var(--accent-deeper);
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
  }
  .meta-text-review {
    font-size: 0.9rem;
    color: var(--text-muted);
  }
</style>
<template>
  <main class="review-page">

    <!-- Film Info || hero  -->
    <section class="review-hero">
      <div>
        <h1 class="hero-title"> {{ movie.title }} </h1>
        <!-- tmp -->
         <div>
           <button class="fav-button">[tmp] Add to favs</button>
         </div>
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
    <section class="user-review-form">

        <div>
            <h2 class="star-rating"> Rate Plot:</h2>
            <!-- tmp add stars here -->
        </div>

        <div>
            <h2 class="star-rating"> Rate Acting:</h2>
            <!-- tmp add stars here -->
        </div>

        <div>
            <h2 class="star-rating"> Rate Pacing:</h2>
            <!-- tmp add stars here -->
        </div>

      <form>
        <div class="mb-3">
          <label for="reviewText" class="form-label"></label>
          <textarea id="reviewText" v-model="newReview.text" class="form-control" rows="4" placeholder="Share your thoughts on the film" required></textarea>
        </div>

        <div class="row g-3 mb-4">
          <div>
            <select v-model="newReview.rating" class="form-select">
              <option disabled value="">Choose rating</option>
              <option>Peak</option>
              <option>So bad it's good</option>
              <option>Mid at best</option>
              <option>Trash</option>
            </select>
          </div>
          <div>
            <select v-model="newReview.rewatch" class="form-select">
              <option disabled value="">Rewatch?</option>
              <option>First time watch</option>
              <option>Rewatch</option>
            </select>
          </div>
          <div>
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



      <section class="user-reviews">
      <h2 class="section-label">User Reviews</h2>
      <div class="row g-4">
        <div v-for="(review, index) in reviews" :key="index" class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">{{ review.username }}</h5>
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
import { ref } from 'vue';
const isFavorited = ref(false);
const userRating = ref(null);


// Tmp Film Data change later
const movie = ref({
  title: "Inception",
  year: "2010",
  genre: "Sci-Fi",
  runtime: "123",
  globalRating: "8.8",
  desc: "A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.",
  tags: ["testing123","Mind-bending", "Classic", "Must Watch"]
});

// Form data
const newReview = ref({
  text: '',
  rating: '',
  rewatch: '',
  expectations: ''
});

// Tmp Reviews
const reviews = ref([
  { 
    username: "tmpUser123", 
    content: "This is indeed a movie. Yes it is. I can confirm that this movie is a movie.",
    rating: "Peak", 
    rewatch: "Rewatch", 
    expectations: "Yes" 
  },
  { 
    username: "tmpUser456", 
    content: "There were no birds in the movie.",
    rating: "Mid at best", 
    rewatch: "First time watch", 
    expectations: "No" 
  }
]);


</script>

<style>
.review-page {
  padding: 20px;
}

</style>
