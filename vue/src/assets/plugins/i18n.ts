import { createI18n } from 'vue-i18n'
import commonFR from '@/assets/translations/fr/common.json'
import mainPagesFR from '@/assets/translations/fr/main_pages.json'
import adminFR from '@/assets/translations/fr/admin.json'
import authFR from '@/assets/translations/fr/auth.json'

export const i18nInstance = createI18n({
  legacy: false,
  locale: 'fr',
  fallbackLocale: 'fr',
  messages: {
    fr: {...commonFR, ...mainPagesFR, ...adminFR, ...authFR }
  }
})

export const i18n = i18nInstance.global //Workaround for translate text outside components