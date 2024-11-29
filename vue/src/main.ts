import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import { initSentry } from './assets/plugins/sentry'
import { vuetify } from './assets/plugins/vuetify'
import { i18nInstance } from './assets/plugins/i18n'

const pinia = createPinia()
const app = createApp(App)

app.use(pinia)
app.use(router)
initSentry(app, router)
app.use(vuetify)
app.use(i18nInstance)
app.mount('#app')
