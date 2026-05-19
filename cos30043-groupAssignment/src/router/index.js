import { createRouter, createWebHistory } from "vue-router"
import Review from '../components/Review.vue'
import AboutUs from '../components/AboutUs.vue'
import Catalogue from '../components/Catalogue.vue'
import ContactUs from '../components/ContactUs.vue'

const routes = [
    { path: '/films', component: Catalogue },
    //{ path: '/account', component: Account }, //Fill in later
    //{ path: '/lists', component: List },
    { path: '/about-us', component: AboutUs },
    { path: '/contact-us', component: ContactUs },

    //{ path: '/login', component: Login }, //Fill in later
    //{ path: '/sign-up', component: SignUp }, //Fill in later

    { path: '/review', component: Review },
]

const router = createRouter({
    history: createWebHistory('/cos30043/s104551544/Assign2/'),
    routes,
})

export default router