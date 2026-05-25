import { createRouter, createWebHistory } from "vue-router"

import Review from '../components/Review.vue'
import AboutUs from '../components/AboutUs.vue'
import Catalogue from '../components/Catalogue.vue'
import ContactUs from '../components/ContactUs.vue'
import ThankYou from '../components/redirects/ThankYou.vue'
import error from '../components/redirects/Error404.vue'
import TermsOfService from "../components/policies/TermsOfService.vue"
import PrivacyPolicy from "../components/policies/PrivacyPolicy.vue"
import PoliciesLayout from "../components/policies/PoliciesLayout.vue"
import SignIn from '../components/SignIn.vue'
import SignUp from '../components/SignUp.vue'
import AccountSettings from '../components/AccountSettings.vue'
import Account from '../components/Account.vue'
import Statistics from "../components/Statistics.vue"
import ReviewAccount_Component from "../components/ReviewAccount_Component.vue"

const routes = [

    // Nav bar for a guest on the webpage
    { path: '', redirect: '/films' },
    { path: '/films', component: Catalogue },
    { path: '/about-us', component: AboutUs },
    { path: '/contact-us', component: ContactUs },
    { path: '/login', component: SignIn },
    { path: '/sign-up', component: SignUp },
    { path: '/thankyou', component: ThankYou },
    { path: '/:pathMatch(.*)*', component: error }, // catch-all route for 404 errors

    // Nav bar for a user with an account
    {
        path: '/user', 
        children: [
            { path: '', redirect: '/films' },
            { path: '/films', component: Catalogue },
            { path: '/account', component: Account },
            { path: '/about-us', component: AboutUs },
            { path: '/contact-us', component: ContactUs },
        ]
    },

    // User Account Dashboard routing
    {
        path: '/account',
        children: [
            { path: 'stats', component: Statistics },
            { path: 'user-reviews', component: ReviewAccount_Component },
            { path: 'settings', component: AccountSettings },
        ]
    },

    {
        path: '/policies',
        component: PoliciesLayout,
        children: [
            { path: 'terms-and-conditions', component: TermsOfService },
            { path: 'privacy-policy', component: PrivacyPolicy },
            { path: '', redirect: 'policies/terms-and-conditions' }
        ]
    },

    // For the footer
    { path: '/login', component: SignIn }, 
    { path: '/sign-up', component: SignUp }, 

    // Review from clicking a movie
    { path: '/films/:id', component: Review }, //could replace with title if its more appropriate lol
    { path: '/:pathMatch(.*)*', component: error }, // catch-all route for 404 errors
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
})

export default router