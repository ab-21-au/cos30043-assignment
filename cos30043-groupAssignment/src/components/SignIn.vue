<template>
    <div id="centered-box">
        <form @submit.prevent="submitForm">
            <label for="Credential">Username or Email</label>
            <input type="text" name="Credential" id="Credential" v-model="credential">
            <br>
            <label for="Password">Password</label>
            <input type="password" name="Password" id="Password" v-model="password">
            <br>
            <button type="submit">Sign in</button>
            <p class="form-error" v-if="errorMessage">{{ errorMessage }}</p>
            <p class="form-success" v-if="successMessage">{{ successMessage }}</p>
        </form>
    </div>
</template>

<style>
#centered-box {
    display: flex;
  justify-content: center; 
  align-items: center; 
  height: 100vh;
  border: 4px;
  border-color: grey; 
}

.form-error {
  color: #c00;
  margin-top: 0.75rem;
}

.form-success {
  color: #090;
  margin-top: 0.75rem;
}
</style>

<script>
export default {
    data() {
        return {
            credential: '',
            password: '',
            errorMessage: '',
            successMessage: ''
        }
    },
    methods: {
        async submitForm() {
            this.errorMessage = '';
            this.successMessage = '';

            try {
                const apiUrl = import.meta.env.DEV
                    ? '/api/signin.php'
                    : `${import.meta.env.BASE_URL}api/signin.php`;

                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
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
                    this.successMessage = 'Signed in successfully!';
                    this.credential = '';
                    this.password = '';
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
