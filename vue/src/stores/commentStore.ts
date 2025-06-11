import { NotificationType } from '@/models/enums/app/NotificationType'
import { StoresList } from '@/models/enums/app/StoresList'
import type {
  AdminCommentsSortingValues,
  AppComment,
  CommentOrigin
} from '@/models/interfaces/Comment'
import { i18n } from '@/plugins/i18n'
import { CommentService } from '@/services/comments/CommentService'
import { addNotification } from '@/services/notifications/NotificationService'
import { defineStore } from 'pinia'
import { ref, type Ref } from 'vue'
import { useApplicationStore } from './applicationStore'

export const useCommentStore = defineStore(StoresList.COMMENT, () => {
  const comments: Ref<AppComment[]> = ref([])
  const isAppCommentActive = ref(false)
  const applicationStore = useApplicationStore()

  async function getAll(force = false): Promise<void> {
    if (comments.value.length === 0 || force) {
      comments.value = await CommentService.getAll()
    }
  }
  async function createComment(commentSubmission: AppComment) {
    applicationStore.isLoading = true
    try {
      const comment = await CommentService.createComment(commentSubmission)
      isAppCommentActive.value = false
      if (comment) {
        comments.value.push(comment)
      }
    } catch (error) {
      addNotification(
        i18n.t('notifications.common.error.500'),
        NotificationType.ERROR,
        error as string
      )
    }
    applicationStore.isLoading = false
  }

  const markAsRead = (ids: string[]) => {
    applicationStore.isLoading = true
    try {
      CommentService.markAsRead(ids).then(() => {
        getAll(true)
      })
    } catch (error) {
      addNotification(
        i18n.t('notifications.common.error.500'),
        NotificationType.ERROR,
        error as string
      )
    }
    applicationStore.isLoading = false
  }

  const deleteComments = (ids: string[]) => {
    applicationStore.isLoading = true
    try {
      CommentService.deleteComments(ids).then(() => {
        getAll(true)
      })
    } catch (error) {
      addNotification(
        i18n.t('notifications.common.error.500'),
        NotificationType.ERROR,
        error as string
      )
    }
    applicationStore.isLoading = false
  }

  const getSortedComments = (origin: CommentOrigin, sortingMethod: AdminCommentsSortingValues) => {
    const filteredComments = comments.value.filter((x) => x.origin === origin)

    if (sortingMethod === 'readByAdmin') {
      return filteredComments.sort((a, b) => {
        if (a.readByAdmin !== b.readByAdmin) {
          return Number(a.readByAdmin) - Number(b.readByAdmin)
        }
        return a.createdBy.lastName?.localeCompare(b.createdBy.lastName)
      })
    }

    if (sortingMethod === 'nameAZ') {
      return filteredComments.sort((a, b) => {
        return a.createdBy.lastName?.localeCompare(b.createdBy.lastName)
      })
    }

    if (sortingMethod === 'nameZA') {
      return filteredComments.sort((a, b) => {
        return b.createdBy.lastName?.localeCompare(a.createdBy.lastName)
      })
    }
    return filteredComments
  }
  return {
    comments,
    isAppCommentActive,
    getAll,
    createComment,
    markAsRead,
    deleteComments,
    getSortedComments
  }
})
