<script>
import { ref } from 'vue'

const ACCESS_KEY = import.meta.env.VITE_FORM_ACCESS_KEY // important to have in .evn file for security

export default {
    data(){
        return {
            fName: '',
            lName: '',
            email: '',
            poNumber: '',
            message: ''
        }
    },
    methods: {
        async submitForm() {

            const isValid = await this.validate();
            
            if (!ACCESS_KEY) return console.error("Internal Error: Invalid Access key");

            if (!isValid) return; // stops here to return error if validation fails

            try {
                const response = await fetch('https://api.web3forms.com/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        access_key: ACCESS_KEY,
                        name: `${this.fName} ${this.lName}`,
                        email: this.email,
                        phone: this.poNumber,
                        message: this.message
                    })
                })

                const result = await response.json()
                if (result.success) {
                    // temp: redirect to new component after routing is set
                    alert('Form submitted successfully!')
                } else {
                    alert('Failed to submit form. Please try again.')
                }

            } catch (error) {
                console.error('An error occurred while submitting the form.', error)
            }
        },

        async validate() {

            if (!this.fName || !this.lName || !this.email || !this.message) { // Required
                alert('Please fill in all required fields.')
                return false
            }

            if (this.fName.length < 2 || this.lName.length < 2) {   // Character length min
                alert('First and Last name must be at least 2 characters long.')
                return false
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
            
            if (!emailPattern.test(this.email)) {   // email pattern check
                alert('Please enter a valid email address.')
                return false
            }

            const phonePattern = /^(0[2-478]\d{8})$/ // Australian phone number pattern + Landlines (idk we include landlines though)

            if (this.poNumber && !phonePattern.test(this.poNumber)) {
                alert('Please enter a valid phone number.')
                return false
            }

            if (this.message.length < 10) { // message length check
                alert('Message must be at least 10 characters long.')
                return false
            }

            return true
        }
    }
}
</script>
<template>
<main class="contactUs-page">
    <div id="intro">
        <h1>Contact Us!</h1>
        <p>For any questions or concerns you may have. <br> Fill out the form below and we will keep in touch!</p>
    </div>
    <form method="post" action="https://api.web3forms.com/submit" @submit.prevent="submitForm" class="contactus-container">
        <label for="fName">First Name: </label>
        <input type="text" name="fName" id="fName" v-model="fName"/><br>
        <label for="lName">Last Name: </label>
        <input type="text" name="lName" id="lName" v-model="lName"/><br>

        <label for="email">Email Address: </label><br>
        <input type="text" name="email" id="email" v-model="email"><br>

        <label for="ponumber">Phone Number: (optional)</label><br>
        <input type="text" name="ponumber" id="ponumber" v-model="poNumber"/><br></br>

        <label for="message">Message: </label><br>
        <textarea name="message" id="message" v-model="message"></textarea><br></br>

        <button type="submit">Submit</button>
    </form>
</main>
</template>