
<template>
    <div>
        <h1>Terms and Conditions</h1>
            <p id="last-update">Last updated: May 2026</p>

            <p>By using 'Retrospect', you are accepting and agreeing to our Terms and Conditions. If you do not agree with these, you do not have to use our Service. We also advise that 
                you read through our Privacy Policy, which can be found <router-link to="privacy-policy">here</router-link>, to understand how we collect, store and distribute your data.
            </p>

        <h2>1. License to Use</h2>
            <p>Subject to these Terms and Conditions, the team behind 'Retrospect' grants users of the site, a non-exclusive, non-transferable, non-sharable, revocable, limited license to 
                use our service and site solely for personal, non-commercial use in accordance with the Terms made in this agreement.
            </p>

        <h2>2. Website Ownership</h2>
            <p>This Service is owned by its creators; Sofia, Theodore, Alessandra, Anabelle, Cole and Matt, who developed this site. The movies displayed on our site was provided
                by the API on 'TMDB' found at 'developer.themoviedb.org'. Our Privacy Policy uses and includes many areas of 'Business Victoria's Privacy Policy template'. 
                This was done to ensure our Site follows applicable Privacy Policy Laws.
            </p>

        <h2>3. Prohibited Conduct</h2>
            <p>By agreeing to these Terms, you are agreeing that you will not violate any applicable laws, and you are responsible for your actions while using our Site. Alongside
                these conditions, you also must not:</p>
                <ul>
                    <li>
                        Engage in any form of harassment, discrimination, hate, violence, threatening, predatory or stalking behavior, towards anyone including based on race, age,
                        disability, gender and gender identity, ethnicity, religion or any other personal information.
                    </li>
                    <li>
                        Create multiple accounts which may misleading others or misinterpret your identity.
                    </li>
                    <li>
                        Review bomb movies based on your personal opinion or the opinion of others. Please review a movie based on your honest thoughts
                    </li>
                    <li>
                        Create an account under the age of 16
                    </li>
                    <li>
                        Impersonate another user or person. Similarly, do not attempt to use another person's account without authorization for that user or attempt to 
                        sign into an account which you are not allowed to access, in other words, you are un-authorized
                    </li>
                    <li>
                        Attempt to recreate our site by using our Terms, Conditions, Privacy Policy and source code, to act as a competitor or to make your own business
                    </li>
                    <li>
                        Directly or indirectly attempt to go against the stated Terms and Conditions
                    </li>
                </ul>

            <br>

        <h2>4. User Accounts</h2>
            <p>Users of this Service can create an account, allowing them to write reviews for movies. These reviews are visible in a user's personal dashboard 
                (generated upon account creation). Users who have not made or signed into their account (guests) cannot leave reviews, but are still able to navigate 
                the Service's contents, including but not limited to; a large library of films, information about the site and its developers, contacting us, login in 
                and signing up.
            </p>

        <h4>4.1. Creating an Account</h4> 
            <p>Users have the option of creating an account, which they can use to leave reviews, view their movie viewing statistics and reviews and create movie lists. A 
                user can create an account by navigating to the 'Sign Up' page. This can be found in the pop out navigation bar, on the top right of the screen or in the footer
                under the title 'Create an Account'. A user is required to provide first name, last name, email address, date of birth. They are also asked to provide their 
                gender, and essential account information including; a username, password and a favorite genre. By creating an account, you are also required to agree to our 
                Terms and Conditions.<br>
                After an account has been created, users can sign in any time by navigating to the 'Login' page, found in the pop out navigation bar, or in the footer 'Login'.
            </p>
            
        <h4>4.2. Deleting an Account</h4> 
            <p>Users who have made an account also have the ability to delete (terminate) their account. This can be done by navigating to your individual account, and going into
                'Settings' where a 'Delete Account' button will be visible.<br>
                After a user has given confirmation that they want their account deleted, all content made by the user under that account name will also be terminated. 
            </p>

        <h2>5. Account Termination</h2>
            <p>By agreeing to these Terms, you provide us with the rights to terminate your account if we feel you are indirectly or directly breaching our Terms or Prohibited
                Conduct. We are not held responsible for any damage, loss or harm relating to your inability to access your account due to a termination from our team.
            </p>

        <h2>6. User Generated Content</h2>
            <p>'Retrospect' doesn't claim ownership of any content which you post to our site which is publicly accessible (your reviews). However, when posting a review, you are 
                granting us a worldwide, non-exclusive license to copy, distribute or publish this content on our platform or any social media. We will contact a user via email if we 
                want to use their content to promote our site using third-parties, and will not use their content until permission has been granted. If a user doesn't want their content 
                used, we will abide by their word.

            </p>

        <h2>7. Privacy Policy</h2>
            <p>We understand that your personal information should be kept private, so we do our best to keep it that way. You can read more about how we manage your data, 
                and what we do with it on our <router-link to="privacy-policy">Privacy Policy</router-link> page. By using our site, you have agreed to our Terms and Conditions, as well as our Privacy Policy, so please
                read through these policies <router-link to="privacy-policy">here</router-link>.
            </p>

        <h2>8. Contact Us</h2>
            <p>If you have any questions regarding what has been documented in our Terms and Conditions, you can contact us <router-link to="contact-us">here</router-link>.
            We will do our best to get back to you in a timely manner.
            </p>      
    </div>
</template>


<style scoped>

@media (min-width: 320px) {
    #last-update {
        font-style: italic;
        color: var(--text-muted);
    }

    h1 {
        border-bottom: 1px solid var(--border-subtle);
        font-size: 22px;
    }

    h2 {
        font-size: 20px;
    }

    h1, h2 {
        color: var(--text-primary);
    }

    h4 {
        color: var(--text-secondary);
    }

    p, ul {
        color: var(--text-secondary);
        font-size: 14px;
    }
}

@media (min-width: 1024px) {
    #last-update {
        font-style: italic;
        color: var(--text-muted);
    }

    h1 {
        border-bottom: 1px solid var(--border-subtle);
        font-size: 30px;
    }

    h2 {
        font-size: 23px;
    }

    h1, h2 {
        color: var(--text-primary);
    }

    p, ul {
        color: var(--text-secondary);
    }
}

</style>