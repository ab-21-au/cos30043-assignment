<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  account: {
    type: Object,
    default: () => ({
      username: '',
      email: '',
      created_at: '',
    }),
  },
  accountId: {
    type: [Number, String],
    default: 1,
  },
})

const emit = defineEmits(['account-updated'])

const settings = reactive({
  username: '',
  email: '',
  currentPassword: '',
  newPassword: '',
  confirmPassword: '',
})

const syncSettings = () => {
  settings.username = props.account?.username || ''
  settings.email = props.account?.email || ''
}

watch(() => props.account, syncSettings, { immediate: true, deep: true })

const saveSettings = async () => {
  if (settings.newPassword !== settings.confirmPassword) {
    alert('New password and confirm password must match.')
    return
  }

  try {
    const response = await fetch(`${import.meta.env.BASE_URL}api/update_account.php`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        account_id: props.accountId,
        username: settings.username,
        email: settings.email,
        current_password: settings.currentPassword,
        new_password: settings.newPassword,
      }),
    })

    const result = await response.json()

    if (!result.success) {
      alert(result.error || 'Unable to update account.')
      return
    }

    emit('account-updated', {
      account_id: Number(props.accountId),
      username: settings.username,
      email: settings.email,
      created_at: props.account?.created_at || '',
    })

    settings.currentPassword = ''
    settings.newPassword = ''
    settings.confirmPassword = ''
    alert('Account details updated.')
  } catch (error) {
    console.error('Error updating account details:', error)
    alert('Unable to update account details.')
  }
}

const deleteAccount = async () => {
  if (!confirm('Delete this account? This will also delete related reviews and movie list entries.')) {
    return
  }

  try {
    const response = await fetch(`${import.meta.env.BASE_URL}api/delete_account.php`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        account_id: props.accountId,
      }),
    })

    const result = await response.json()

    if (!result.success) {
      alert(result.error || 'Unable to delete account.')
      return
    }

    alert('Account deleted.')
  } catch (error) {
    console.error('Error deleting account:', error)
    alert('Unable to delete account.')
  }
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
                  <label for="username" class="form-label">Username</label>
                  <input
                    id="username"
                    v-model="settings.username"
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
            <button type="button" class="btn btn-danger" @click="deleteAccount">Delete Account</button>
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
