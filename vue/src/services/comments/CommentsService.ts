import { NotificationType } from '@/models/enums/app/NotificationType'
import { apiClient } from '@/plugins/axios/api'
import { i18n } from '@/plugins/i18n'
import { addNotification } from '../notifications/NotificationService'
import type { AppComment } from '@/models/interfaces/Comment'

export class CommentsService {
  static async getAll(): Promise<AppComment[]> {
    return await apiClient
      .get('/api/app_content_comments')
      .then((response) => response.data['hydra:member'])
  }
  static async createComment(comment: AppComment) {
    try {
      const data = (
        await apiClient.post('/api/app_content_comments', comment, {
          headers: {
            'Content-Type': 'application/ld+json',
            Accept: 'application/ld+json'
          }
        })
      ).data
      addNotification(i18n.t('comments.successPost'), NotificationType.SUCCESS)
      return data as AppComment
    } catch (error) {
      addNotification(
        i18n.t('notifications.common.error.400'),
        NotificationType.ERROR,
        error as string
      )
    }
  }
}
