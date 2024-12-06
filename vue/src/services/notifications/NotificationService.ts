import { useApplicationStore } from '@/stores/applicationStore'
import { NotificationType } from '@/models/enums/app/NotificationType'
import type { Notification } from '@/models/interfaces/Notification'

export const addNotification = (
  message: string,
  notificationType = NotificationType.INFO,
  description?: string
) => {
  const appStore = useApplicationStore()
  const notification: Notification = {
    id: Date.now(),
    message,
    description,
    type: notificationType
  }
  appStore.notificationPile.push(notification)
}
