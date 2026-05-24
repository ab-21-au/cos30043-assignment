<script setup>
import { reactive } from 'vue'

const settings = reactive({
  displayName: 'Demo User',
  email: 'demo.user@example.com',
  favouriteGenre: 'Drama',
  currentPassword: '',
  newPassword: '',
  confirmPassword: '',
})

const saveSettings = () => {
  alert('Settings saved for scaffold demo.')
}
</script>

<template>
  <div class="account-panel">
    <section class="text-center mb-4">
      <h2 class="h3 mb-2">Account Settings</h2>
      <p class="account-muted mb-0">
        Update profile details, preferences, and account security.
      </p>
    </section>

    <form @submit.prevent="saveSettings">
      <div class="row account-grid justify-content-center">
        <section class="col-lg-8">
          <div class="card account-card mb-4">
            <div class="card-body">
              <h3 class="h5 mb-3">Change Account Information</h3>

              <div class="row g-3">
                <div class="col-md-6">
                  <label for="displayName" class="form-label">Preferred name</label>
                  <input
                    id="displayName"
                    v-model="settings.displayName"
                    type="text"
                    class="form-control"
                    placeholder="Preferred name"
                  >
                </div>

                <div class="col-md-6">
                  <label for="email" class="form-label">Email address</label>
                  <input
                    id="email"
                    v-model="settings.email"
                    type="email"
                    class="form-control"
                    placeholder="Email address"
                  >
                </div>

                <div class="col-md-6">
                  <label for="favouriteGenre" class="form-label">Favourite genre</label>
                  <select id="favouriteGenre" v-model="settings.favouriteGenre" class="form-select">
                    <option>Action</option>
                    <option>Comedy</option>
                    <option>Drama</option>
                    <option>Horror</option>
                    <option>Sci-Fi</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="username" class="form-label">Username</label>
                  <input
                    id="username"
                    type="text"
                    class="form-control"
                    placeholder="Username"
                  >
                </div>
              </div>
            </div>
          </div>

          <div class="card account-card mb-4">
            <div class="card-body">
              <h3 class="h5 mb-3">Security</h3>

              <div class="d-grid gap-3">
                <div>
                  <label for="currentPassword" class="form-label">Current password</label>
                  <input
                    id="currentPassword"
                    v-model="settings.currentPassword"
                    type="password"
                    class="form-control"
                  >
                </div>

                <div>
                  <label for="newPassword" class="form-label">New password</label>
                  <input
                    id="newPassword"
                    v-model="settings.newPassword"
                    type="password"
                    class="form-control"
                  >
                </div>

                <div>
                  <label for="confirmPassword" class="form-label">Confirm password</label>
                  <input
                    id="confirmPassword"
                    v-model="settings.confirmPassword"
                    type="password"
                    class="form-control"
                  >
                </div>
              </div>
            </div>
          </div>

          <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
            <button type="submit" class="btn btn-primary settings-save-btn">Change Details</button>
            <button type="button" class="btn btn-danger">Delete Account</button>
          </div>
        </section>
      </div>
    </form>
  </div>
</template>

<style scoped>
.form-label,
.form-check-label {
  color: var(--text-primary);
}

.form-control,
.form-select {
  color: var(--bg-form-text);
  background-color: var(--bg-input);
  border-color: var(--border-subtle);
}

.form-check-input:checked {
  background-color: var(--accent);
  border-color: var(--accent);
}

.settings-save-btn {
  background-color: #5aa9ff;
  border-color: #5aa9ff;
  color: #0A100D;
}
</style>
