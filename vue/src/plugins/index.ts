/**
 * plugins/index.ts
 *
 * Automatically included in `./src/main.ts`
 */

// Plugins
import { initSentry } from '@/plugins/sentry'
import { createPinia } from 'pinia'
import router from '../router'
import vuetify from './vuetify'

// Types
import { i18nInstance } from '@/plugins/i18n'
import { VeeValidate } from '@/plugins/veeValidate'
import type { App } from 'vue'

export const pinia = createPinia()
export function registerPlugins(app: App) {
  app.use(vuetify).use(router).use(pinia).use(i18nInstance)
  initSentry(app, router)
  VeeValidate.init()
}
