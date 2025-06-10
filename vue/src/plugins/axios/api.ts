import { DialogKey } from '@/models/enums/app/DialogKey'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { i18n } from '@/plugins/i18n'
import router from '@/router'
import { addNotification } from '@/services/notifications/NotificationService'
import { AuthenticationService } from '@/services/userAndAuth/AuthenticationService'
import * as Sentry from '@sentry/vue'
import axios, { AxiosError, type AxiosInstance, type AxiosRequestConfig } from 'axios'

let isRefreshing = false
let refreshSubscribers: Array<(error: any) => void> = []

const addRefreshSubscriber = (callback: (error: any) => void) => {
  refreshSubscribers.push(callback)
}

const onRefreshed = () => {
  refreshSubscribers.forEach((callback) => callback(null))
  refreshSubscribers = []
}

const onFailedRefresh = (error: any) => {
  refreshSubscribers.forEach((callback) => callback(error))
  refreshSubscribers = []
}

const axiosInstance = axios.create({
  baseURL: 'https://' + window.location.hostname
})

axiosInstance.interceptors.response.use(
  (response) => response,
  async (error: AxiosError) => {
    const originalRequest = error.config as AxiosRequestConfig & { _retry?: boolean }

    // Verify if it's an authentication error (401) and that it's not already a refresh attempt
    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true

      // If a refresh is already in progress, queue this request
      if (isRefreshing) {
        return new Promise((resolve, reject) => {
          addRefreshSubscriber((refreshError) => {
            if (refreshError) {
              reject(refreshError)
            } else {
              resolve(axiosInstance(originalRequest))
            }
          })
        })
      }

      isRefreshing = true

      try {
        await AuthenticationService.refreshAuthToken()
        onRefreshed()
        return axiosInstance(originalRequest)
      } catch (refreshError) {
        onFailedRefresh(refreshError)
        isRefreshing = false

        router.push({
          query: { ...router.currentRoute.value.query, dialog: DialogKey.AUTH_SIGN_IN }
        })
        return Promise.reject(refreshError)
      } finally {
        isRefreshing = false
      }
    }

    switch (error.response?.status) {
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
  }
)

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
