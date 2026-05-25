<template>
  <div :class="['account-settings-panel', { 'dark-mode': theme.isDark.value }]">
    <div v-if="auth.isAuthenticated.value" class="inner-settings-card">
      <div class="settings-inner-container">
        <h3 class="settings-title">Account Settings</h3>

        <div class="settings-row flex-row">
          <span class="label-text">Theme:</span>
          <div class="theme-btn-group">
            <button 
              type="button"
              @click="theme.toggleTheme()" 
              class="theme-pill-btn unified-toggle-btn"
            >
              {{ theme.isDark.value ? 'Dark' : 'Light' }}
            </button>
          </div>
        </div>

        <h4 class="section-subheading">Change Account Information:</h4>

        <form @submit.prevent class="info-modification-form">
          <div class="settings-row form-grid-row">
            <label class="label-text">Username:</label>
            <div class="input-pill-wrapper">
              <input type="text" v-model="profile.username" class="figma-input" />
            </div>
          </div>

          <div class="settings-row form-grid-row">
            <label class="label-text">Email Address:</label>
            <div class="input-pill-wrapper">
              <input type="email" v-model="profile.email" class="figma-input" />
            </div>
          </div>

          <div class="actions-button-group">
            <button type="button" @click="updateUsername" class="btn-figma btn-change-details">
              Change Details
            </button>
            
            <button type="button" @click="handleDeleteAccount" class="btn-figma btn-delete-account">
              Delete Account
            </button>
          </div>
        </form>
      </div>
    </div>

    <div v-else class="inner-settings-card error-card">
      <h3 class="settings-title">Access Denied</h3>
      <p class="label-text">Please sign in to view your account settings.</p>
      <button @click="$router.push('/login')" class="btn-figma btn-change-details" style="margin-top: 20px;">
        Go to Sign In
      </button>
    </div>
  </div>
</template>

<style scoped>
.theme-btn-group {
  display: flex;
  gap: 15px;
}

.theme-pill-btn {
  box-sizing: border-box;
  height: 48px;
  padding: 0 35px;
  background: #FFFFFF;
  border: 3px solid #000000;
  border-radius: 25px;
  font-family: 'Inter', sans-serif;
  font-size: 24px;
  line-height: 30px;
  color: #000000;
  cursor: pointer;
  transition: background-color 0.3s ease, color 0.3s ease, transform 0.1s;
}

.theme-pill-btn:hover {
  background-color: #ededed;
  transform: scale(0.98);
}

.account-settings-panel {
  box-sizing: border-box;
  width: 100%;
  background: #FFFFFF;
  font-family: 'Inter', sans-serif;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.inner-settings-card {
  box-sizing: border-box;
  width: 100%;
  background: #FFFFFF;
  border: 3px solid #000000;
  border-radius: 45px;
  padding: 60px;
  transition: background-color 0.3s ease, border-color 0.3s ease;
}

.settings-inner-container {
  box-sizing: border-box;
  width: 100%;
  background: #FFFFFF;
  border: 3px solid #000000;
  border-radius: 45px;
  padding: 60px;
  display: flex;
  flex-direction: column;
  transition: background-color 0.3s ease, border-color 0.3s ease;
}

.settings-title {
  font-family: 'Inter', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 50px;
  line-height: 61px;
  color: #000000;
  margin: 0 0 40px 0;
  text-align: center;
  transition: color 0.3s ease;
}

.settings-row {
  margin-bottom: 30px;
}

.flex-row {
  display: flex;
  align-items: center;
  gap: 40px;
  justify-content: center;
}

.form-grid-row {
  display: grid;
  grid-template-columns: 280px 1fr;
  align-items: center;
  gap: 20px;
  max-width: 800px;
  width: 100%;
  margin-left: auto;
  margin-right: auto;
}

.label-text {
  font-family: 'Inter', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 30px;
  line-height: 36px;
  color: #000000;
  transition: color 0.3s ease;
}

.section-subheading {
  font-family: 'Inter', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 30px;
  line-height: 36px;
  color: #000000;
  margin: 20px 0 35px 0;
  text-align: center;
  transition: color 0.3s ease;
}

.info-modification-form {
  display: flex;
  flex-direction: column;
  width: 100%;
}

.input-pill-wrapper {
  box-sizing: border-box;
  width: 324px; 
  height: 48px;
  background: #FFFFFF;
  border: 3px solid #000000;
  border-radius: 25px;
  display: flex;
  align-items: center;
  overflow: hidden;
  transition: background-color 0.3s ease, border-color 0.3s ease;
}

.figma-input {
  width: 100%;
  height: 100%;
  border: none;
  background: transparent;
  padding: 0 20px;
  font-family: 'Inter', sans-serif;
  font-size: 25px;
  line-height: 30px;
  color: #000000;
  outline: none;
  transition: color 0.3s ease;
}

.actions-button-group {
  display: flex;
  justify-content: center;
  gap: 40px;
  margin-top: 40px;
  width: 100%;
}

.btn-figma {
  box-sizing: border-box;
  width: 287px; 
  height: 57px;
  border: 3px solid #000000;
  border-radius: 25px;
  font-family: 'Inter', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 25px;
  line-height: 30px;
  color: #000000;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: opacity 0.2s, transform 0.1s;
}

.btn-figma:hover {
  opacity: 0.9;
  transform: scale(0.98);
}

.btn-change-details {
  background: #7EC1FF;
}

.btn-delete-account {
  background: #FF7D7D;
}

.error-card {
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 400px;
}

.account-settings-panel.dark-mode {
  background: #121212;
}

.dark-mode .settings-title,
.dark-mode .label-text,
.dark-mode .section-subheading,
.dark-mode .figma-input {
  color: #FFFFFF;
}

.dark-mode .inner-settings-card,
.dark-mode .settings-inner-container,
.dark-mode .input-pill-wrapper {
  background: #1E1E1E;
  border-color: #FFFFFF;
}

.dark-mode .theme-pill-btn {
  background: #1E1E1E;
  border-color: #FFFFFF;
  color: #FFFFFF;
}

.dark-mode .theme-pill-btn:hover {
  background-color: #2c2c2c;
}

@media screen and (max-width: 1150px) {
  .inner-settings-card,
  .settings-inner-container {
    padding: 20px;
    border-radius: 30px;
  }

  .settings-title {
    font-size: 32px;
    line-height: 40px;
    margin-bottom: 20px;
  }

  .section-subheading,
  .label-text {
    font-size: 20px;
    line-height: 26px;
  }

  .theme-btn-group {
    gap: 10px;
  }

  .theme-pill-btn {
    height: 40px;
    padding: 0 15px;
    font-size: 16px;
    border-radius: 15px;
  }

  .form-grid-row {
    grid-template-columns: 1fr;
    gap: 10px;
    margin-bottom: 20px;
  }

  .input-pill-wrapper {
    width: 100%; 
    border-radius: 15px;
  }

  .figma-input {
    font-size: 18px;
  }

  .actions-button-group {
    flex-direction: column;
    gap: 15px;
    align-items: center;
  }

  .btn-figma {
    width: 100%; 
    max-width: 324px;
    height: 50px;
    font-size: 18px;
    border-radius: 15px;
  }
}
</style>

<script>
import { useAuth } from '../assets/UseAuth.js';
import { useTheme } from '../js/Theme.js'; 

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
        email: ''
      }
    };
  },
  mounted() {
    
    if (this.auth.isAuthenticated.value) {
      this.fetchCurrentProfileDetails();
    }
    this.currentIsDark = !!this.theme.isDark.value;
  },
  methods: {
    handleThemeToggle() {
      this.theme.toggleTheme();
    },
    
    async fetchCurrentProfileDetails() {
      try {
        const response = await fetch('/api/update_profile.php', {
          method: 'GET',
          credentials: 'include'
        });
        const data = await response.json();
        
        if (data.success) {
          this.profile.username = data.username;
          this.profile.email = data.email;
        }
      } catch (err) {
        console.error("Failed to sync account profile info:", err);
      }
    },
    async updateUsername() {
      try {
        const response = await fetch('/api/update_profile.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'include',
          body: JSON.stringify({
            username: this.profile.username,
            email: this.profile.email
          })
        });

        const data = await response.json();

        if (data.success) {
          alert("Account information updated successfully!");
          this.auth.login(this.profile.username);
        } else {
          alert(data.error || "Failed to update profile details.");
        }
      } catch (err) {
        alert("Network error encountered while saving details.");
        console.error(err);
      }
    },
    async handleDeleteAccount() {
      const confirmed = confirm("Are you absolutely sure you want to permanently delete your account?");
      if (!confirmed) return;

      try {
        const response = await fetch('/api/delete_account.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'include', 
          body: JSON.stringify({ username: this.auth.username.value })
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
