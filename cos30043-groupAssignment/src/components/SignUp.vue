<template>
    <div id="centered-box">
        <form @submit.prevent="submitForm" class="figma-signup-form">
            <h2 class="form-main-title">Register</h2>

            <div class="form-scroll-container">
                <h3 class="form-section-subtitle">Personal Details</h3>

                <div class="form-row-split">
                    <div class="input-group">
                        <label for="FirstName">First Name</label>
                        <input type="text" name="FirstName" id="FirstName" v-model="FirstName">
                    </div>
                    <div class="input-group">
                        <label for="LastName">Last Name</label>
                        <input type="text" name="LastName" id="LastName" v-model="LastName">
                    </div>
                </div>

                <div class="input-group">
                    <label for="Email">Email</label>
                    <input type="email" name="Email" id="Email" v-model="Email">
                </div>

                <div class="form-row-split">
                    <div class="input-group">
                        <label for="DateOfBirth">Date of Birth</label>
                        <input type="date" name="DateOfBirth" id="DateOfBirth" v-model="DateOfBirth">
                    </div>
                    <div class="input-group">
                        <label for="Gender">Gender</label>
                        <select name="Gender" id="Gender" v-model="Gender" class="figma-select">
                            <option value="">-- Select --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Non-binary">Non-binary</option>
                            <option value="Prefer not to say">Prefer not to say</option>
                        </select>
                    </div>
                </div>

                <h3 class="form-section-subtitle">Account Information</h3>

                <div class="input-group">
                    <label for="Username">Username</label>
                    <input type="text" name="Username" id="Username" v-model="Username">
                </div>

                <div class="input-group">
                    <label for="Password">Password</label>
                    <input type="password" name="Password" id="Password" v-model="Password">
                </div>

                <div class="input-group">
                    <label for="ConfirmPassword">Confirm Password</label>
                    <input type="password" name="ConfirmPassword" id="ConfirmPassword" v-model="ConfirmPassword">
                </div>

                <div class="input-group">
                    <label for="FavouriteGenre">Favourite Genre</label>
                    <select name="FavouriteGenre" id="FavouriteGenre" v-model="FavouriteGenre" class="figma-select">
                        <option value="">-- Optional --</option>
                        <option value="Action">Action</option>
                        <option value="Comedy">Comedy</option>
                        <option value="Drama">Drama</option>
                        <option value="Horror">Horror</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <option value="Thriller">Thriller</option>
                    </select>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="AgreeTerms" v-model="AgreeTerms" class="large-checkbox">
                    <label for="AgreeTerms">
                        Please agree to our 
                        <router-link to="/terms-of-service" class="terms-link">Terms of Service</router-link> 
                        before continuing your account creation
                    </label>
                </div>
            </div>
            
            <button type="submit" class="signup-btn">Sign up</button>
            <p class="form-error" v-if="errorMessage">{{ errorMessage }}</p>
            <p class="form-success" v-if="successMessage">{{ successMessage }}</p>
        </form>
    </div>
</template>

<style scoped>
#centered-box {
  display: flex;
  justify-content: center; 
  align-items: center; 
  min-height: 100vh;
  padding: 20px;
  box-sizing: border-box;
  background-color: #fafafa; 
}

.figma-signup-form {
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 24px;
  gap: 12px;
  position: relative;
  width: 440px;
  min-width: 440px;
  height: 600px;
  max-height: calc(100vh - 40px);
  background: #FFFFFF;
  border: 1px solid #000000;
  border-radius: 23px;
}

.form-main-title {
  font-family: 'Inter', sans-serif;
  font-size: 1.6rem;
  font-weight: 600;
  margin: 0 0 2px 0;
  color: #333;
  width: 100%;
  text-align: center;
  flex-shrink: 0;
}

.form-section-subtitle {
  font-family: 'Inter', sans-serif;
  font-size: 0.95rem;
  font-weight: 600;
  margin: 4px 0 2px 0;
  color: #555;
  width: 100%;
}

.form-scroll-container {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 10px;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 4px 8px 4px 4px;
  box-sizing: border-box;
  flex-grow: 1;
}

.form-row-split {
  display: flex;
  width: 100%;
  gap: 12px;
}

.input-group {
  display: flex;
  flex-direction: column;
  width: 100%;
  gap: 4px;
}

.input-group label {
  font-family: 'Inter', sans-serif;
  font-size: 0.85rem;
  font-weight: 500;
}

.input-group input, .figma-select {
  width: 100%;
  padding: 6px 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
  box-sizing: border-box;
  font-size: 0.9rem;
}

.figma-select {
  background-color: white;
  height: 32px;
}

.checkbox-group {
  display: flex;
  align-items: center;
  gap: 10px;
  width: 100%;
  margin-top: 2px;
}

.large-checkbox {
  width: 18px !important;
  height: 18px !important;
  min-width: 18px;
  min-height: 18px;
  cursor: pointer;
  margin: 0;
}

.checkbox-group label {
  font-family: 'Inter', sans-serif;
  font-size: 0.8rem;
  line-height: 1.3;
  color: #555;
  cursor: pointer;
  user-select: none;
}

.terms-link {
  color: #0066cc;
  text-decoration: none;
  font-weight: 500;
}

.terms-link:hover {
  text-decoration: underline;
}

.signup-btn {
  width: 100%;
  padding: 8px;
  background-color: #333;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  font-size: 0.95rem;
  margin-top: 6px; 
  flex-shrink: 0;
}

.signup-btn:hover {
  background-color: #444;
}

.form-error {
  color: #c00;
  font-size: 0.8rem;
  margin: 0;
  width: 100%;
  text-align: center;
  flex-shrink: 0;
}

.form-success {
  color: #090;
  font-size: 0.8rem;
  margin: 0;
  width: 100%;
  text-align: center;
  flex-shrink: 0;
}

@media screen and (max-height: 640px) {
  #centered-box {
    align-items: flex-start;
    padding: 10px;
  }
  .figma-signup-form {
    height: auto;
    max-height: none;
  }
  .form-scroll-container {
    overflow-y: visible;
    flex-grow: 0;
  }
}
</style>

<script>
export default {
    data() {
        return {
            FirstName: '',
            LastName: '',
            DateOfBirth: '',
            Gender: '',
            FavouriteGenre: '',
            Username: '',
            Password: '',
            ConfirmPassword: '',
            Email: '',
            AgreeTerms: false,
            errorMessage: '',
            successMessage: ''
        }
    },
    methods: {
        async submitForm() {
            this.errorMessage = '';
            this.successMessage = '';
            
            if (!this.FirstName.trim() || !this.LastName.trim() || !this.DateOfBirth || !this.Gender || !this.Username.trim() || !this.Password.trim() || !this.ConfirmPassword.trim() || !this.Email.trim()) {
                this.errorMessage = 'All required fields must be completed.';
                return;
            }

            if (this.Username.trim().length < 3) {
                this.errorMessage = 'Username must be at least 3 characters long.';
                return;
            }

            if (this.Password.length < 6) {
                this.errorMessage = 'Password must be at least 6 characters long.';
                return;
            }

            if (this.Password !== this.ConfirmPassword) {
                this.errorMessage = 'Passwords do not match.';
                return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(this.Email.trim())) {
                this.errorMessage = 'Please enter a valid email address (e.g., name@domain.com).';
                return;
            }

            if (!this.AgreeTerms) {
                this.errorMessage = 'You must agree to the Terms of Service to create an account.';
                return;
            }

            try {
                const apiUrl = import.meta.env.DEV
                    ? '/api/signup.php'
                    : `${import.meta.env.BASE_URL}api/signup.php`;

                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        first_name: this.FirstName,
                        last_name: this.LastName,
                        date_of_birth: this.DateOfBirth,
                        gender: this.Gender,
                        favourite_genre: this.FavouriteGenre || null,
                        username: this.Username,
                        password: this.Password,
                        email: this.Email
                    })
                });

                const text = await response.text();
                let result;

                try {
                    result = JSON.parse(text);
                } catch (jsonError) {
                    this.errorMessage = `Server returned invalid JSON (${response.status}): ${text}`;
                    console.error('Invalid JSON response from /api/signup.php:', text);
                    return;
                }

                if (!response.ok) {
                    this.errorMessage = result.error || `Server error: ${response.status}`;
                    return;
                }

                if (result.success) {
                    this.successMessage = 'Account created!';
                    this.FirstName = '';
                    this.LastName = '';
                    this.DateOfBirth = '';
                    this.Gender = '';
                    this.FavouriteGenre = '';
                    this.Username = '';
                    this.Password = '';
                    this.ConfirmPassword = '';
                    this.Email = '';
                    this.AgreeTerms = false;
                } else {
                    this.errorMessage = result.error || 'Unable to create account.';
                }
            } catch (error) {
                this.errorMessage = error.message || 'Network error while creating account.';
                console.error('Signup fetch error:', error);
            }
        }
    }
}
</script>