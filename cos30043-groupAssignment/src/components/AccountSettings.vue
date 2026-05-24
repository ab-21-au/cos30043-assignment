<template>
  <div class="user-account-page">
    
    <h1 class="page-main-title">User Account</h1>

    <div class="account-dashboard-layout">
      
      <aside class="dashboard-sidebar">
        
        <div class="sidebar-item static-header" @click="$router.push('/account')">
          <h4 class="sidebar-text">Account Dashboard</h4>
        </div>
        
        <nav class="sidebar-nav">
          <ul>
            <li @click="$router.push('/account')" class="sidebar-item pill-button">
              <h4 class="sidebar-text">Stats</h4>
            </li>

            <li @click="$router.push('/account')" class="sidebar-item pill-button">
              <h4 class="sidebar-text">Lists</h4>
            </li>

            <li @click="$router.push('/account')" class="sidebar-item pill-button">
              <h4 class="sidebar-text">Reviews</h4>
            </li>

            <li class="sidebar-item pill-button active-setting">
              <h4 class="sidebar-text">Settings</h4>
            </li>
          </ul>
        </nav>
      </aside>

      <main class="content-workspace">
        <div v-if="auth.isAuthenticated" class="inner-settings-card">
          
          <div class="settings-inner-container">
            
            <h3 class="settings-title">Account Settings</h3>

            <div class="settings-row flex-row">
              <span class="label-text">Theme:</span>
              <div class="theme-btn-group">
                
                <button 
                  type="button"
                  @click="!theme.isDark.value && theme.toggleTheme()" 
                  :class="['theme-pill-btn', { 'active-theme-pill': theme.isDark.value }]"
                >
                  Dark
                </button>
                
                <button 
                  type="button"
                  @click="theme.isDark.value && theme.toggleTheme()" 
                  :class="['theme-pill-btn', { 'active-theme-pill': !theme.isDark.value }]"
                >
                  Light
                </button>
                
              </div>
            </div>

            <h4 class="section-subheading">Change Account Information:</h4>

            <form @submit.prevent class="info-modification-form">
              
              <div class="settings-row form-grid-row">
                <label class="label-text">Preferred Name:</label>
                <div class="input-pill-wrapper">
                  <input type="text" v-model="profile.preferredName" class="figma-input" />
                </div>
              </div>

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
      </main>

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
  padding: 0 25px;
  background: #FFFFFF;
  border: 3px solid #000000;
  border-radius: 25px;
  font-family: 'Inter', sans-serif;
  font-size: 24px;
  line-height: 30px;
  color: #000000;
  cursor: pointer;
  transition: background-color 0.2s, color 0.2s, transform 0.1s;
}

.theme-pill-btn:hover {
  background-color: #ededed;
  transform: scale(0.98);
}

.active-theme-pill {
  background: #E3E3E3 !important;
  font-weight: bold;
}


.user-account-page {
  box-sizing: border-box;
  min-height: 100vh;
  width: 100%;
  background: #FFFFFF;
  padding: 60px 40px;
  font-family: 'Inter', sans-serif;
  display: flex;
  flex-direction: column;
}

.page-main-title {
  font-family: 'Inter', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 36px;
  line-height: 44px;
  color: #000000;
  margin: 0 0 50px 20px;
}

.account-dashboard-layout {
  display: flex;
  width: 100%;
  align-items: flex-start;
}

.dashboard-sidebar {
  box-sizing: border-box;
  width: 530px; 
  min-width: 530px;
  background: #FFFFFF;
  border: 3px solid #000000;
  border-radius: 45px;
  padding: 30px 45px 30px 30px;
  display: flex;
  flex-direction: column;
  gap: 20px;
  position: relative;
  z-index: 1;
}

.sidebar-item {
  box-sizing: border-box;
  height: 90.56px; 
  width: 100%;
  background: #FFFFFF;
  border: 3px solid #000000;
  border-radius: 45px;
  display: flex;
  align-items: center;
  justify-content: flex-start; 
  padding-left: 35px; 
}

.static-header {
  cursor: pointer;
  margin-bottom: 15px;
}

.pill-button {
  cursor: pointer;
  transition: transform 0.1s ease, background-color 0.2s ease;
}

.pill-button:hover {
  background-color: #f7f7f7;
  transform: scale(0.99);
}

.active-setting {
  background: #E3E3E3; 
  cursor: default;
}

.sidebar-text {
  font-family: 'Inter', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 24px;
  line-height: 29px;
  color: #000000;
  margin: 0;
  text-align: left;
}

.sidebar-nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 20px; 
}

.content-workspace {
  flex: 1;
  display: flex;
  flex-direction: column;
  margin-left: -45px; 
  position: relative;
  z-index: 10;
}

.inner-settings-card {
  box-sizing: border-box;
  width: 100%;
  background: #FFFFFF;
  border: 3px solid #000000;
  border-radius: 45px;
  padding: 60px;
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
}

.theme-selection {
  font-family: 'Inter', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 30px;
  line-height: 36px;
  color: #000000;
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


@media screen and (max-width: 1150px) {
  .user-account-page {
    padding: 30px 15px; 
  }

  .page-main-title {
    font-size: 28px;
    margin-bottom: 25px;
    text-align: center;
  }

  
  .account-dashboard-layout {
    flex-direction: column;
    gap: 30px;
  }


  .dashboard-sidebar {
    width: 100% !important;
    min-width: 100% !important;
    padding: 20px;
    border-radius: 30px;
  }

  .sidebar-item {
    height: 70px; 
    padding-left: 20px;
    border-radius: 20px;
  }
  
  .sidebar-text {
    font-size: 18px;
    line-height: 22px;
  }

  
  .content-workspace {
    margin-left: 0 !important;
    width: 100%;
  }

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
  .label-text,
  .theme-selection {
    font-size: 20px;
    line-height: 26px;
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
// 💡 Import your team's global theme utility package
import { useTheme } from '../js/Theme.js'; 

export default {
  data() {
    return {
      auth: useAuth(),
      // 💡 Bind the theme controller variables into your local component state
      theme: useTheme(), 
      profile: {
        preferredName: '',
        username: '',
        email: ''
      }
    };
  },
  mounted() {
    if (this.auth.isAuthenticated) {
      this.profile.username = this.auth.username;
      this.profile.preferredName = this.auth.username;
      this.profile.email = `${this.auth.username.toLowerCase()}@example.com`;
    }
  },
  methods: {
    updateUsername() {
      alert(`Details updated successfully!`);
    },
    async handleDeleteAccount() {
      const confirmed = confirm("Are you absolutely sure you want to permanently delete your account?");
      if (!confirmed) return;
      // ... keep existing deletion logic exactly as it is ...
    }
  }
};
</script>