<template>
    <div id="centered-box">
        
        <form v-if="!auth.isAuthenticated.value" @submit.prevent="submitForm" class="figma-login-form">
            <div class="input-group">
                <label for="Credential">Username or Email</label>
                <input type="text" name="Credential" id="Credential" v-model="credential">
            </div>
            
            <div class="input-group">
                <label for="Password">Password</label>
                <input type="password" name="Password" id="Password" v-model="password">
            </div>
            
            <button type="submit" class="signin-btn">Sign in</button>
            
            <div class="register-navigation-prompt">
                <span>No account? </span>
                <router-link to="/sign-up" class="register-link">register here</router-link>
            </div>

            <p class="form-error" v-if="errorMessage">{{ errorMessage }}</p>
        </form>

        <div v-else class="welcome-box">
            <p>You are already signed in as <strong>{{ auth.username.value }}</strong>.</p>
            <button @click="auth.logout()">Sign Out</button>
        </div>
    </div>
</template>

<style scoped>

#centered-box {
  display: flex;
  justify-content: center; 
  align-items: center; 
  height: 100vh;
  background-color: #fafafa; 
}

.figma-login-form {
  box-sizing: border-box;

  display: flex;
  flex-direction: column;
  align-items: flex-start;
  padding: 24px;
  gap: 24px;

  position: relative;
  width: 320px;
  min-width: 320px;
  height: 355px; 

  background: #FFFFFF;
  border: 1px solid #000000;
  border-radius: 23px;
}

.input-group {
  display: flex;
  flex-direction: column;
  width: 100%;
  gap: 6px;
}

.input-group input {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.signin-btn {
  width: 100%;
  padding: 10px;
  background-color: #333;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
}


.register-navigation-prompt {
  width: 100%;
  text-align: center;
  font-family: 'Inter', sans-serif;
  font-size: 0.9rem;
  color: #555;
  margin-top: -8px; 
}

.register-link {
  color: #0066cc;
  text-decoration: none;
  font-weight: 500;
}

.register-link:hover {
  text-decoration: underline;
}

.welcome-box {
  text-align: center;
}

.form-error {
  color: #c00;
  font-size: 0.85rem;
  width: 100%;
  text-align: center;
  margin: 0;
}
</style>

<script>
import { useAuth } from '../assets/UseAuth.js';
export default {
    data() {
        return {
            credential: '',
            password: '',
            errorMessage: '',
            auth: useAuth()
        }
    },
    methods: {
        async submitForm() {
            this.errorMessage = '';
            
            try {
                const apiUrl = import.meta.env.DEV
                    ? '/api/signin.php'
                    : `${import.meta.env.BASE_URL}api/signin.php`;

                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'include',
                    body: JSON.stringify({
                        credential: this.credential,
                        password: this.password
                    })
                });

                const text = await response.text();
                let result;

                try {
                    result = JSON.parse(text);
                } catch (jsonError) {
                    this.errorMessage = `Server returned invalid JSON (${response.status}): ${text}`;
                    console.error('Invalid JSON response from /api/signin.php:', text);
                    return;
                }

                if (!response.ok) {
                    this.errorMessage = result.error || `Server error: ${response.status}`;
                    return;
                }

                if (result.success) {
                    this.auth.login(result.username, result.account_id);
                    
                    // Clear form inputs
                    this.credential = '';
                    this.password = '';

                    this.$router.push('/account');
                } else {
                    this.errorMessage = result.error || 'Unable to sign in.';
                }
            } catch (error) {
                this.errorMessage = error.message || 'Network error while signing in.';
                console.error('Signin fetch error:', error);
            }
        }
    }
}
</script>