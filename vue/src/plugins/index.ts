/**
 * plugins/index.ts
 *
 * Automatically included in `./src/main.ts`
 */

// Plugins
import vuetify from './vuetify'
import router from '../router'
import { createPinia } from 'pinia'
import { initSentry } from '@/plugins/sentry'

// Types
import type { App } from 'vue'
import { i18nInstance } from '@/plugins/i18n'

const pinia = createPinia()
export function registerPlugins(app: App) {
  app.use(vuetify).use(router).use(pinia).use(i18nInstance)
  initSentry(app, router)
}
