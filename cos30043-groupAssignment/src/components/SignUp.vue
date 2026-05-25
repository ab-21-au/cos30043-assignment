<template>
    <div id="centered-box">
        <form @submit.prevent="submitForm">
            <label for="Username">Username</label>
            <input type="text" name="Username" id="Username" v-model="Username">
            <br>
            <label for="Password">Password</label>
            <input type="password" name="Password" id="Password" v-model="Password">
            <br>
            <label for="Email">Email</label>
            <input type="email" name="Email" id="Email" v-model="Email">
            <br>
            <button type="submit">Sign up</button>
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
export default{
    data(){
        return{
            Username: '',
            Password: '',
            Email: '',
            errorMessage: '',
            successMessage: ''
        }
    },
    methods: {
        async submitForm(){
            this.errorMessage = '';
            this.successMessage = '';
            
                       
            if (!this.Username.trim() || !this.Password.trim() || !this.Email.trim()) {
                this.errorMessage = 'All fields are required.';
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

            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(this.Email.trim())) {
                this.errorMessage = 'Please enter a valid email address (e.g., name@domain.com).';
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
                    this.Username = '';
                    this.Password = '';
                    this.Email = '';
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