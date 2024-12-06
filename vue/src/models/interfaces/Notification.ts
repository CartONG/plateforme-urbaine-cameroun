import type { NotificationType } from '@/models/enums/app/NotificationType'

export interface Notification {
  id: number
  message: string
  description?: string
  type: NotificationType
}
