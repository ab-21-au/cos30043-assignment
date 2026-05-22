import { createApp } from 'vue'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'
import {createVuetify} from 'vuetify'
import './style.css'
import App from './App.vue'
import router from './router'

const vuetify = createVuetify()

createApp(App)
    .use(vuetify)
    .mount('#app')
