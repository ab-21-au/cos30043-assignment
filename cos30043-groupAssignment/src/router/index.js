import { createRouter, createWebHistory } from "vue-router"

import TermsOfService from "../components/policies/TermsOfService.vue"
import PrivacyPolicy from "../components/policies/PrivacyPolicy.vue"
import PoliciesLayout from "../components/policies/PoliciesLayout.vue"


const routes = [
    {
        path: '/policies',
        component: PoliciesLayout,
        children: [
            { path: 'terms-and-conditions', component: TermsOfService },
            { path: 'privacy-policy', component: PrivacyPolicy },
            { path: '', redirect: 'policies/terms-and-conditions'}
        ]
    }
]

const router = createRouter({
    history: createWebHistory('/cos30043/s104551544/Assign2/'),
    routes,
})

export default router