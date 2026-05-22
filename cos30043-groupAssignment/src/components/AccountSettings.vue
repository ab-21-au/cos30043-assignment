<template>
  <div class="test-center-wrapper">
    <div v-if="auth.isAuthenticated" class="test-content-box">
      
      <aside>
        <h3>Settings</h3>
        <ul>
          <li>Profile Information</li>
          <li>Security & Password</li>
        </ul>
      </aside>

      <main>
        
        <section>
          <h2>Profile Information</h2>
          <p>Your public account identification details.</p>
          <hr />
          <form @submit.prevent="updateUsername">
            <div>
              <label for="username-input">Username</label>
              <input type="text" id="username-input" v-model="profile.username" />
            </div>
            <button type="submit">Update Username</button>
          </form>
        </section>

        <section>
          <h2>Security & Password</h2>
          <p>Ensure your account is using a secure password.</p>
          <hr />
          <form @submit.prevent="updatePassword">
            <div>
              <label for="current-password">Current Password</label>
              <input type="password" id="current-password" v-model="security.currentPassword" />
            </div>
            <div>
              <label for="new-password">New Password</label>
              <input type="password" id="new-password" v-model="security.newPassword" />
            </div>
            <button type="submit">Update Password</button>
          </form>
        </section>

        <hr />

        <button @click="handleDeleteAccount" class="btn-delete">Delete Account</button>

      </main>
    </div>

    <div v-else class="test-content-box">
      <h2>Access Denied</h2>
      <p>Please sign in to view your account settings.</p>
      <button @click="$router.push('/login')">Go to Sign In</button>
    </div>
  </div>
</template>

<style scoped>
/* Centering wrapper layouts to maximize testing visibility */
.test-center-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80vh;
  width: 100%;
  font-family: sans-serif;
}

.test-content-box {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 20px;
  max-width: 500px;
  width: 100%;
  padding: 20px;
}

form {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
}

div {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 5px;
}

ul {
  list-style: none;
  padding: 0;
}

/* 💡 Kept the button red but dropped the dashed border box entirely */
.btn-delete {
  background-color: #dc3545;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  margin-top: 15px;
}

.btn-delete:hover {
  background-color: #bd2130;
}
</style>

<script>
import { useAuth } from '../assets/UseAuth.js'; 

export default {
  data() {
    return {
      auth: useAuth(),
      profile: {
        username: ''
      },
      security: {
        currentPassword: '',
        newPassword: ''
      }
    };
  },
  mounted() {
    if (this.auth.isAuthenticated) {
      this.profile.username = this.auth.username;
    }
  },
  methods: {
    updateUsername() {
      alert(`Username skeleton trigger working! Intended destination value: ${this.profile.username}\n(Full database integration is paused for now)`);
    },
    updatePassword() {
      alert('Updating password...');
    },
    async handleDeleteAccount() {
      const confirmed = confirm("Are you absolutely sure you want to permanently delete your account? This action cannot be undone.");
      if (!confirmed) return;

      try {
        const response = await fetch('/api/delete_account.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username: this.auth.username })
        });

        const data = await response.json();

        if (data.success) {
          alert("Your account has been successfully deleted.");
          this.auth.logout();
          this.$router.push('/films');
        } else {
          alert(data.error || "Failed to complete account deletion.");
        }
      } catch (err) {
        alert("Network connection error encountered while deleting account.");
        console.error(err);
      }
    }
  }
};
</script>