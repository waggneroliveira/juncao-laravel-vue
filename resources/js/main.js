import './assets/css/main.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import persistedState from 'pinia-plugin-persistedstate'

import Toast, { POSITION } from 'vue-toastification'
import 'vue-toastification/dist/index.css'

import App from './App.vue'
import router from './router'

import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

const app = createApp(App)

const pinia = createPinia()

pinia.use(persistedState)

app.use(pinia)

app.use(Toast, {
  position: POSITION.TOP_RIGHT
})

app.use(router)

app.mount('#app')