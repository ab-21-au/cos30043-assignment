<script setup>
import { reactive, watch } from 'vue'
import { useAuth } from '../assets/UseAuth.js'

const auth = useAuth()

const account = reactive({
  id: null,
  name: '',
  username: '',
  email: '',
  created_at: '',
})

const setAccount = (accountData) => {
  account.id = accountData.account_id
  account.username = accountData.username
  account.name = accountData.username
  account.email = accountData.email
  account.created_at = accountData.created_at
}

const getAccount = async () => {
  if (!auth.isAuthenticated.value) {
    setAccount({
      account_id: null,
      username: '',
      email: '',
      created_at: '',
    })
    return
  }

  try {
    const query = auth.accountId.value
      ? `account_id=${encodeURIComponent(auth.accountId.value)}`
      : `username=${encodeURIComponent(auth.username.value)}`

    const response = await fetch(`${import.meta.env.BASE_URL}api/get_account.php?${query}`)
    const result = await response.json()

    if (result.success) {
      setAccount(result.account)
      auth.login(result.account.username, result.account.account_id)
    }
  } catch (error) {
    console.error('Error fetching account details:', error)
  }
}

watch(
  [auth.isAuthenticated, auth.username, auth.accountId],
  getAccount,
  { immediate: true }
)

const navItems = [
  { label: 'Account Dashboard', to: '/account' },
  { label: 'Stats', to: '/account/stats' },
  { label: 'Lists', to: '/account/lists' },
  { label: 'Reviews', to: '/account/reviews' },
  { label: 'Settings', to: '/account/settings' },
]
</script>

<template>
  <main class="account-page">
    <div class="container account-container">
      <h1 class="account-title mb-4">User Account</h1>

      <div v-if="auth.isRestoringSession.value" class="account-access-denied">
        <p class="mb-0">Loading your account...</p>
      </div>

      <div v-else-if="auth.isAuthenticated.value" class="row account-shell g-0">
        <aside class="col-lg-3 account-sidebar">
          <nav class="nav flex-column gap-2" aria-label="Account navigation">
            <router-link
              v-for="item in navItems"
              :key="item.to"
              :to="item.to"
              class="account-nav-link"
              exact-active-class="active"
            >
              {{ item.label }}
            </router-link>
          </nav>
        </aside>

        <section class="col-lg-9 account-content">
          <router-view v-slot="{ Component }">
            <component
              :is="Component"
              :account="account"
              :account-id="account.id"
              @account-updated="setAccount"
            />
          </router-view>
        </section>
      </div>

      <div v-else class="account-access-denied">
        <h2 class="h4">Access Denied</h2>
        <p>Please sign in to view your account dashboard.</p>
        <router-link to="/login" class="btn btn-primary">Go to Sign In</router-link>
      </div>

    </div>
  </main>
</template>

<style scoped>
.account-page {
  min-height: 100vh;
  color: var(--text-primary);
  background-color: var(--bg-primary);
}

.account-container {
  max-width: 1180px;
  padding-top: 2.5rem;
  padding-bottom: 3rem;
}

.account-title {
  font-size: clamp(1.75rem, 3vw, 2.5rem);
  letter-spacing: 0;
}

.account-shell {
  min-height: 640px;
  border: 1px solid var(--border-subtle);
  border-radius: 8px;
  background-color: var(--bg-form);
  box-shadow: var(--shadow);
  overflow: hidden;
}

.account-access-denied {
  border: 1px solid var(--border-subtle);
  border-radius: 8px;
  background-color: var(--bg-form);
  box-shadow: var(--shadow);
  padding: 2rem;
  text-align: center;
}

.account-sidebar {
  border-right: 1px solid var(--border-subtle);
  padding: 1rem;
  background-color: var(--bg-surface);
}

.account-nav-link {
  display: block;
  border: 1px solid var(--border-subtle);
  border-radius: 999px;
  padding: 0.65rem 0.9rem;
  color: var(--text-primary);
  background-color: var(--bg-form);
  text-decoration: none;
  font-weight: 600;
  transition: background-color 0.2s, border-color 0.2s, color 0.2s;
}

.account-nav-link:hover,
.account-nav-link.active {
  color: var(--on-accent);
  background-color: var(--accent);
  border-color: var(--accent);
}

.account-content {
  padding: 1.25rem;
}

.account-content :deep(.account-panel) {
  min-height: 580px;
  border: 1px solid var(--border-subtle);
  border-radius: 8px;
  background-color: var(--bg-form);
  padding: 1.25rem;
}

.account-content :deep(.account-hero) {
  border: 1px solid var(--border-subtle);
  border-radius: 8px;
  padding: 1.25rem;
  margin-bottom: 1.25rem;
}

.account-content :deep(.account-card) {
  border: 1px solid var(--border-subtle);
  border-radius: 8px;
  background-color: var(--bg-primary);
  color: var(--text-primary);
  box-shadow: none;
  margin: 0;
  padding: 0;
}

.account-content :deep(.account-card .card-body) {
  padding: 1rem;
}

.account-content :deep(.account-muted) {
  color: var(--text-secondary);
}

.account-content :deep(.account-grid) {
  --bs-gutter-x: 1rem;
  --bs-gutter-y: 1rem;
}

.account-content :deep(.btn) {
  white-space: nowrap;
}

@media (max-width: 991.98px) {
  .account-shell {
    min-height: auto;
  }

  .account-sidebar {
    border-right: 0;
    border-bottom: 1px solid var(--border-subtle);
  }

  .account-sidebar .nav {
    flex-direction: row !important;
    overflow-x: auto;
    padding-bottom: 0.25rem;
  }

  .account-nav-link {
    white-space: nowrap;
  }

  .account-content :deep(.account-panel) {
    min-height: auto;
  }
}

@media (max-width: 575.98px) {
  .account-container {
    padding-top: 2rem;
    padding-bottom: 2rem;
  }

  .account-content {
    padding: 1rem;
  }
}
</style>
