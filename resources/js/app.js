import './bootstrap';
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

// Registro automático de componentes Vue 3 + Vite (com nome em kebab-case e sufixo -component)
function toKebabCase(str) {
  return str
    .replace(/([a-z])([A-Z])/g, '$1-$2')
    .replace(/([A-Z])([A-Z][a-z])/g, '$1-$2')
    .toLowerCase();
}
const modules = import.meta.glob('./components/*.vue', { eager: true });
Object.entries(modules).forEach(([path, module]) => {
  let name = path.split('/').pop().replace('.vue', '');
  name = toKebabCase(name) + '-component';
  app.component(name, module.default);
});

app.mount('#app')
