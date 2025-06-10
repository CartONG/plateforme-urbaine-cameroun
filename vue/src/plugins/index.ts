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
import VueTelInput from 'vue-tel-input'
import 'vue-tel-input/vue-tel-input.css'

export const pinia = createPinia()
export function registerPlugins(app: App) {
  app
    .use(vuetify)
    .use(router)
    .use(pinia)
    .use(i18nInstance)
    .use(VueTelInput as any, { mode: 'international', autoFormat: false, defaultCountry: 'cm' })
  initSentry(app, router)
  VeeValidate.init()
}
