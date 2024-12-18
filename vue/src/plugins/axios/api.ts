import { DialogKey } from '@/models/enums/app/DialogKey'
import router from '@/router'
import axios, { AxiosError, type AxiosInstance } from 'axios'
import * as Sentry from '@sentry/vue'
import { addNotification } from '@/services/notifications/NotificationService'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { i18n } from '@/plugins/i18n'

const axiosInstance = axios.create({
  baseURL: 'https://' + import.meta.env.VITE_DOMAIN
})

axiosInstance.interceptors.response.use(undefined, (error: AxiosError) => {
  switch (error.response?.status) {
    case 401:
      router.push({ query: { ...router.currentRoute.value.query, dialog: DialogKey.AUTH_SIGN_IN } })
      break
    case 422:
      addNotification(
        i18n.t('notifications.common.error.411'),
        NotificationType.ERROR,
        `${i18n.t('notifications.common.error.moreDetails')} ${(error.response.data as any)?.description ?? ''}`
      )
      break
    case 500:
      Sentry.captureException(error)
      break
  }
  return Promise.reject(error)
})

axiosInstance.interceptors.request.use(function (config) {
  config.headers.Accept = 'application/ld+json'
  if (config.method === 'post') {
    config.headers['Content-Type'] = 'application/ld+json'
  } else if (config.method === 'patch') {
    config.headers['Content-Type'] = 'application/merge-patch+json'
  }
  return config
})

export const apiClient: AxiosInstance = axiosInstance
