import { createRouter, createWebHistory } from "vue-router"

import Review from '../components/Review.vue'
import AboutUs from '../components/AboutUs.vue'
import Catalogue from '../components/Catalogue.vue'
import ContactUs from '../components/ContactUs.vue'
import ThankYou from '../components/redirects/ThankYou.vue'
import error from '../components/redirects/Error404.vue'

const routes = [
    
    // Nav bar for a guest on the webpage
    { path: '', redirect: '/films'},
    { path: '/films', component: Catalogue},  
    { path: '/about-us', component: AboutUs},
    { path: '/contact-us', component: ContactUs},  
    //{ path: '/login', component: AboutUs},
    //{ path: '/sign-up', component: AboutUs},
    { path: '/thankyou', component: ThankYou},
    
    // Nav bar for a user with an account
    {
        path: '/user', //edit later to match the users details? (i think its ok to leave it)
        children: [
            { path: '', redirect: '/films'},
            { path: '/films', component: Catalogue },
            //{ path: '/account', component: Account }, //Fill in later
            //{ path: '/lists', component: List },
            { path: '/about-us', component: AboutUs },
            { path: '/contact-us', component: ContactUs },
        ]
    },
    
    // User Account Dashboard routing
    {
        path: '/account',
        //component: '' -- not sure if there will be a component that holds all of the routing for user account so i left this blank, can be deleted if not used
        children: [
            //{ path: 'stats', component: Stats },
            //{ path: 'lists', component: Lists },
            //{ path: 'user-reviews', component: UserReviews },
            //{ path: 'settings', component: Settings },
        ]
    },

    // For the footer
    //{ path: '/login', component: Login }, //Fill in later
    //{ path: '/sign-up', component: SignUp }, //Fill in later

    // Review from clicking a movie
    { path: '/films/:id', component: Review }, //could replace with title if its more appropriate lol
    { path: '/:pathMatch(.*)*', component: error }, // catch-all route for 404 errors
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
})

export default router