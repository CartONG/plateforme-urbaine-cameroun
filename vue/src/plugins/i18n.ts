import { createI18n } from 'vue-i18n'
import commonFR from '@/assets/translations/fr/common.json'
import actorsFR from '@/assets/translations/fr/actors.json'
import adminFR from '@/assets/translations/fr/admin.json'
import authFR from '@/assets/translations/fr/auth.json'
import projectsFR from '@/assets/translations/fr/projects.json'
import resourcesFR from '@/assets/translations/fr/resources.json'
import homeFR from '@/assets/translations/fr/home.json'
import mapsFR from '@/assets/translations/fr/qgisMapAndAtlas.json'
import myMapFR from '@/assets/translations/fr/myMap.json'

export const i18nInstance = createI18n({
  legacy: false,
  locale: 'fr',
  fallbackLocale: 'fr',
  messages: {
    fr: {
      ...commonFR,
      ...actorsFR,
      ...adminFR,
      ...authFR,
      ...projectsFR,
      ...homeFR,
      ...resourcesFR,
      ...mapsFR,
      ...myMapFR
    }
  }
})

export const i18n = i18nInstance.global //Workaround for translate text outside components
