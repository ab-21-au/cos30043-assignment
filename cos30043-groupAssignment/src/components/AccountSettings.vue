<template>
  <div class="account-panel">
    <div v-if="auth.isAuthenticated.value">
      <section class="account-hero d-flex flex-column flex-md-row justify-content-between gap-3">
        <div>
          <h2 class="h3 mb-1">Account Settings</h2>
          <p class="account-muted mb-0">Manage your profile details and display preference.</p>
        </div>
      </section>

      <div class="row account-grid">
        <div class="col-12 col-xl-5">
          <article class="card account-card h-100">
            <div class="card-body">
              <h3 class="h5 mb-3">Display</h3>

              <div class="d-flex align-items-center justify-content-between gap-3 setting-row">
                <div>
                  <p class="fw-semibold mb-1">Theme</p>
                  <p class="account-muted small mb-0">Switch between light and dark mode.</p>
                </div>

                <button
                  type="button"
                  class="btn btn-outline-secondary"
                  @click="theme.toggleTheme()"
                >
                  {{ theme.isDark.value ? 'Dark' : 'Light' }}
                </button>
              </div>
            </div>
          </article>
        </div>

        <div class="col-12 col-xl-7">
          <article class="card account-card h-100">
            <div class="card-body">
              <h3 class="h5 mb-3">Profile Details</h3>

              <form @submit.prevent="updateUsername" class="settings-form">
                <div class="row">
                  <div class="col-12 col-md-6 mb-3">
                    <label for="settings-first-name" class="form-label">First Name</label>
                    <input
                      id="settings-first-name"
                      type="text"
                      v-model="profile.first_name"
                      class="form-control"
                    >
                  </div>

                  <div class="col-12 col-md-6 mb-3">
                    <label for="settings-last-name" class="form-label">Last Name</label>
                    <input
                      id="settings-last-name"
                      type="text"
                      v-model="profile.last_name"
                      class="form-control"
                    >
                  </div>
                </div>

                <div class="mb-3">
                  <label for="settings-username" class="form-label">Username</label>
                  <input
                    id="settings-username"
                    type="text"
                    v-model="profile.username"
                    class="form-control"
                  >
                </div>

                <div class="mb-4">
                  <label for="settings-email" class="form-label">Email Address</label>
                  <input
                    id="settings-email"
                    type="email"
                    v-model="profile.email"
                    class="form-control"
                  >
                </div>

                <div class="d-flex flex-column flex-sm-row gap-2">
                  <button type="submit" class="btn btn-primary">
                    Save Changes
                  </button>

                  <button type="button" @click="handleDeleteAccount" class="btn btn-outline-danger">
                    Delete Account
                  </button>
                </div>
              </form>
            </div>
          </article>
        </div>

        <div class="col-12">
          <article class="card account-card">
            <div class="card-body">
              <h3 class="h5 mb-1">Favourite Genre</h3>
              <p class="account-muted small mb-3">Choose the genre that best matches your taste.</p>

              <div class="genre-grid">
                <button
                  v-for="genre in genres"
                  :key="genre.id"
                  type="button"
                  :class="['genre-option', { active: profile.favourite_genre === genre.name }]"
                  @click="profile.favourite_genre = genre.name"
                >
                  {{ genre.name }}
                </button>
              </div>
            </div>
          </article>
        </div>
      </div>
    </div>

    <div v-else class="settings-empty-state text-center">
      <h2 class="h4">Access Denied</h2>
      <p class="account-muted">Please sign in to view your account settings.</p>
      <router-link to="/login" class="btn btn-primary">Go to Sign In</router-link>
    </div>
  </div>
</template>

<style scoped>

.account-hero{
  background-color: var(--bg-primary);
}

.setting-row {
  min-height: 78px;
}

.settings-form {
  max-width: 520px;
}

.form-label {
  color: var(--text-primary);
  font-weight: 600;
}

.form-control {
  color: var(--text-primary);
  background-color: var(--bg-primary);
  border-color: var(--border-subtle);
}

.form-control:focus {
  color: var(--text-primary);
  background-color: var(--bg-primary);
  border-color: var(--accent);
  box-shadow: 0 0 0 0.2rem color-mix(in srgb, var(--accent) 20%, transparent);
}

.settings-empty-state {
  min-height: 360px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.genre-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
  gap: 0.75rem;
}

.genre-option {
  border: 1px solid var(--border-subtle);
  border-radius: 8px;
  background: var(--bg-primary);
  color: var(--text-primary);
  padding: 0.75rem;
  text-align: center;
  font-weight: 700;
  cursor: pointer;
}

.genre-option.active,
.genre-option:hover {
  border-color: var(--accent);
  background: var(--accent);
  color: var(--on-accent);
}
</style>

<script>
import { useAuth } from '../assets/UseAuth.js';
import { useTheme } from '../js/Theme.js';
import { GENRES } from '../services/tmdb.js';

export default {
  setup() {
    return {
      auth: useAuth(),
      theme: useTheme()
    };
  },
  data() {
    return {
      profile: {
        username: '',
        email: '',
        first_name: '',
        last_name: '',
        favourite_genre: ''
      },
      genres: GENRES
    };
  },
  mounted() {
    if (this.auth.isAuthenticated.value) {
      this.fetchCurrentProfileDetails();
    }
  },
  methods: {
    apiUrl(endpoint) {
      return import.meta.env.DEV
        ? `/api/${endpoint}`
        : `${import.meta.env.BASE_URL}api/${endpoint}`;
    },
    async fetchCurrentProfileDetails() {
      try {
        const response = await fetch(this.apiUrl('update_profile.php'), {
          method: 'GET',
          credentials: 'include'
        });
        const data = await response.json();

        if (data.success) {
          this.profile.username = data.username;
          this.profile.email = data.email;
          this.profile.first_name = data.first_name || '';
          this.profile.last_name = data.last_name || '';
          this.profile.favourite_genre = data.favourite_genre || '';
        }
      } catch (err) {
        console.error('Failed to sync account profile info:', err);
      }
    },
    async updateUsername() {
      try {
        const response = await fetch(this.apiUrl('update_profile.php'), {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'include',
          body: JSON.stringify({
            username: this.profile.username,
            email: this.profile.email,
            first_name: this.profile.first_name,
            last_name: this.profile.last_name,
            favourite_genre: this.profile.favourite_genre
          })
        });

        const data = await response.json();

        if (data.success) {
          alert('Account information updated successfully!');
          this.auth.login(this.profile.username);
        } else {
          alert(data.error || 'Failed to update profile details.');
        }
      } catch (err) {
        alert('Network error encountered while saving details.');
        console.error(err);
      }
    },
    async handleDeleteAccount() {
      const confirmed = confirm('Are you absolutely sure you want to permanently delete your account?');
      if (!confirmed) return;

      try {
        const response = await fetch(this.apiUrl('delete_account.php'), {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'include',
          body: JSON.stringify({ username: this.auth.username.value })
        });

        const data = await response.json();

        if (data.success) {
          alert('Your account has been successfully deleted.');
          await this.auth.logout();
          this.$router.push('/films');
        } else {
          alert(data.error || 'Failed to complete account deletion.');
        }
      } catch (err) {
        alert('Network connection error encountered while deleting account.');
        console.error(err);
      }
    }
  }
};
</script>
