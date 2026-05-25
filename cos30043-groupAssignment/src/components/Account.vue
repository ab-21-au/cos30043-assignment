<template>
  <div class="account-dashboard">
    <div class="dashboard-layout">
      
      <header class="dashboard-header">
        <div class="user-meta">
          
          <div>
            <h1>Welcome back, {{ displayUsername }}!</h1>
            
          </div>
        </div>
        <button @click="handleLogout" class="btn-secondary">Sign Out</button>
      </header>

      <section class="stats-grid">
        <div class="stat-card">
          <h3>Films Watched</h3>
          <p class="stat-number">{{ filmsWatchedCount }}</p>
        </div>
        <div class="stat-card">
          <h3>Reviews Written</h3>
          <p class="stat-number">{{ reviews.length }}</p>
        </div>
      </section>

      <div class="dashboard-body">
        
        <main class="activity-section">
          <h2>Your Written Reviews</h2>
          
          <div v-if="loadingReviews" class="loading-placeholder">
            Loading your film reviews...
          </div>

          <div v-else-if="reviews.length === 0" class="empty-reviews">
            <p>You haven't reviewed any films yet!</p>
            <button @click="$router.push('/films')" class="shortcut-link" style="text-align: center; margin-top: 10px;">
              Browse Films to Write a Review
            </button>
          </div>

          <div v-else class="activity-list">
            <div v-for="review in reviews" :key="review.review_id" class="activity-item">
              <span class="activity-icon">
                FILM
              </span>
              <div class="activity-details">
                <p>
                  <strong>Movie ID: {{ review.tmdb_movie_id }}</strong> — 
                  <span class="badge-status">{{ review.rewatch_status }}</span>
                </p>
                
                <h4 v-if="review.review_title || review.title" class="user-review-title">
                  {{ review.review_title || review.title }}
                </h4>
                
                <p :class="{ 'spoiler-text': review.contains_spoilers }" class="user-review-body">
                  {{ review.review_text || review.content }}
                </p>
                
                <div class="metrics-row">
                  <span>Verdict: {{ review.rating }}</span> | 
                  <span>Plot: {{ '★'.repeat(review.rating_plot || review.plot || 0) }}</span> | 
                  <span>Acting: {{ '★'.repeat(review.rating_acting || review.acting || 0) }}</span> | 
                  <span>Pacing: {{ '★'.repeat(review.rating_pacing || review.pacing || 0) }}</span>
                </div>
                
                <small>Reviewed on: {{ new Date(review.created_at).toLocaleDateString() }}</small>
              </div>
            </div>
          </div>
        </main>

        <aside class="actions-sidebar">
          <h2>Account Shortcuts</h2>
          <nav class="shortcuts-nav">
            <button @click="$router.push('/account/settings')" class="shortcut-link">
                Account Settings
            </button>
            <button @click="$router.push('/films')" class="shortcut-link">
               Browse Catalogue
            </button>
            
          </nav>
        </aside>

      </div>
    </div>

  </div>
</template>

<style scoped>
.account-dashboard {
  max-width: 1100px;
  margin: 2rem auto;
  padding: 0 1.5rem;
  font-family: system-ui, -apple-system, sans-serif;
  color: #111;
}

/* Dashboard Header Styles */
.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #f8f9fa;
  padding: 2rem;
  border-radius: 12px;
  border: 1px solid #e9ecef;
  margin-bottom: 2rem;
}

.user-meta {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}



.dashboard-header h1 {
  margin: 0 0 0.25rem 0;
  font-size: 1.75rem;
}



/* Quick Stats Widgets */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: white;
  border: 1px solid #dee2e6;
  border-radius: 10px;
  padding: 1.5rem;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

.stat-card h3 {
  margin: 0 0 0.5rem 0;
  font-size: 1rem;
  color: #495057;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.stat-number {
  margin: 0;
  font-size: 2.25rem;
  font-weight: bold;
  color: #e50914; 
}

/* Layout Split Body */
.dashboard-body {
  display: flex;
  gap: 2rem;
}

@media (max-width: 768px) {
  .dashboard-body {
    flex-direction: column;
  }
}

.activity-section {
  flex: 2;
  background: white;
  border: 1px solid #dee2e6;
  border-radius: 10px;
  padding: 1.5rem;
}

.actions-sidebar {
  flex: 1;
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 10px;
  padding: 1.5rem;
  height: fit-content;
}

.activity-section h2, .actions-sidebar h2 {
  margin-top: 0;
  font-size: 1.3rem;
  margin-bottom: 1.25rem;
  border-bottom: 2px solid #f1f3f5;
  padding-bottom: 0.5rem;
}

/* Activity Feed Items */
.activity-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.activity-item {
  display: flex;
  gap: 1rem;
  align-items: flex-start;
  padding-bottom: 1rem;
  border-bottom: 1px dashed #dee2e6;
}

.activity-item:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.activity-icon {
  font-size: 1.25rem;
  background: #e9ecef;
  padding: 0.4rem;
  border-radius: 6px;
}

.activity-details p {
  margin: 0 0 0.25rem 0;
  font-size: 0.95rem;
}

.activity-details small {
  color: #868e96;
}

.rating-stars {
  color: #ffc107;
}

/* Shortcut Links */
.shortcuts-nav {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.shortcut-link {
  width: 100%;
  text-align: left;
  background: white;
  border: 1px solid #ced4da;
  padding: 0.75rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.95rem;
  font-weight: 500;
  transition: all 0.2s;
}

.shortcut-link:hover {
  background: #e9ecef;
  border-color: #adb5bd;
}

/* Button UI Definitions */
.btn-primary {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 0.6rem 1.2rem;
  border-radius: 6px;
  cursor: pointer;
  font-size: 1rem;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.9rem;
}

.btn-primary:hover { background-color: #0056b3; }
.btn-secondary:hover { background-color: #5a6268; }

.access-denied {
  text-align: center;
  margin-top: 5rem;
}
.access-denied p {
  margin-bottom: 1.5rem;
  color: #6c757d;
}
</style>

<script>
import { useAuth } from '../assets/UseAuth.js'

export default {
  name: 'AccountSummary',
  props: {
    account: {
      type: Object,
      default: () => ({})
    },
    accountId: {
      type: [Number, String],
      default: null
    }
  },
  data() {
    return {
      auth: useAuth(),
      reviews: [],
      filmsWatchedCount: 0,          
      loadingReviews: true, 
      errorMsg: ''
    }
  },
  computed: {
    displayUsername() {
      return this.account?.username || this.auth.username.value || 'there'
    }
  },
  watch: {
    accountId(newValue) {
      if (newValue) {
        this.fetchUserStats()
      }
    }
  },
  methods: {
    async fetchUserStats() {
      if (!this.accountId) {
        this.loadingReviews = false
        return
      }

      this.loadingReviews = true;
      try {
        const query = `account_id=${encodeURIComponent(this.accountId)}`
        const [reviewRes, watchRes] = await Promise.all([
          fetch(`${import.meta.env.BASE_URL}api/get_account_reviews.php?${query}`),
          fetch(`${import.meta.env.BASE_URL}api/get_account_movies.php?${query}&status=watched`)
        ]);

        const reviewData = await reviewRes.json();
        const watchData = await watchRes.json();

        if (Array.isArray(reviewData)) {
          this.reviews = reviewData;
        }
        if (watchData.success && Array.isArray(watchData.movies)) {
          this.filmsWatchedCount = watchData.movies.length;
        }

      } catch (err) {
        this.errorMsg = 'Network error loading dashboard statistics.';
        console.error(err);
      } finally {
        this.loadingReviews = false;
      }
    },
    handleLogout() {
      this.auth.logout()
      this.$router.push('/films')
    },
    alert(msg) {
      window.alert(msg)
    }
  },
  mounted() {
    
    if (this.accountId) {
      this.fetchUserStats();
    } else {
      this.loadingReviews = false;
    }
}
}
</script>
