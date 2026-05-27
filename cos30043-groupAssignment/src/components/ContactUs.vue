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
            message: '',
            msg: ''
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
                    alert('Form submitted successfully!')
                    this.$router.push({
                        path: '/thankyou' // redirect to ThankYou.vue
                    })
                } else {
                    alert('Failed to submit form. Please try again.')
                    this.msg = "Failed to submit form. Pleas try again";
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
        <label for="fName">First Name: </label><br>
        <input type="text" name="fName" id="fName" v-model="fName"/><br>
        <label for="lName">Last Name: </label><br>
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
<style>

/*ContactUs.Vue*/
/* Contact Us -- Laptop view*/
@media (min-width: 481px) {
  .contactus-container {
    border: 1px solid var(--border);
    border-radius: 25px;
    padding: 30px;
    max-width: 500px;
    margin: auto;
    margin-bottom: 60px;
    background-color: var(--bg-form);
    box-shadow: var(--shadow);
    color: var(--text-primary);
    input, textarea {
      padding: 10px;
      border: 0px solid var(--border);
      box-sizing: border-box;
      margin: 10px 0;
      border-radius: 25px;
      text-align: left;
      color: var(--bg-form-text);
      background-color: var(--bg-input);
    }

    #nameRow{
      margin-bottom: 10px;
      border: 0 solid var(--border);
    }

    #email, #ponumber{
      width: 100%;
      margin-bottom: 20px;
    }
    textarea {
      height: 200px;
      width: 100%;
      margin: 20px 0;
    }
    button{
      background-color: var(--accent);
      color: var(--accent-text-muted);
      border: none;
      padding: 10px 50px;
      border-radius: 25px;
      cursor: pointer;
      font-weight: bold;
      display: block;
      margin: 0 auto;
      transition: background-color 0.3s;

      &:hover {
        background-color: var(--accent-deep);
      }
    }
  }  
  #intro{
    text-align: center;
    padding-top: 60px;
    padding-bottom: 20px;
    padding-left: 10px;
    padding-right: 10px;
        h1, p {
      color: var(--text-primary);
    }
  }
}

/*Phone (default)*/
@media (max-width: 480px) {
  .contactus-container {
    border: 1px solid var(--border);
    border-radius: 25px;
    padding: 20px;
    width: 90%;
    max-width: 100%;
    min-height: unset;
    height: auto;
    max-height: unset;
    margin: 0 auto;
    background-color: var(--bg-form);
    box-shadow: var(--shadow);
    color: var(--text-primary);
    input, textarea {
      padding: 10px;
      width: 100%;
      margin-bottom: 20px;
      border: 0px solid var(--border);
      box-sizing: border-box;
      border-radius: 25px;
      text-align: left;
      font-family: var(--sans);
      color:var(--bg-form-text);
      background-color: var(--bg-input);
    }

    textarea {
      height: 200px;
      width: 100%;
      margin: 20px 0;
    }
    button{
      background-color: var(--accent);
      color: var(--text-primary);
      border: none;
      padding: 10px 50px;
      border-radius: 25px;
      cursor: pointer;
      font-weight: bold;
      display: block;
      margin: 0 auto;
      transition: background-color 0.3s;

      &:hover {
        background-color: var(--accent-deeper);
      }
    }
  }
  #intro{
    text-align: center;
    padding-top: 60px;
    padding-bottom: 20px;
    padding-left: 10px;
    padding-right: 10px;
        h1, p {
      color: var(--text-primary);
    }
  }
}

</style>