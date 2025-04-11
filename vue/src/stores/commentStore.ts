import { StoresList } from '@/models/enums/app/StoresList'
import type {
  AdminCommentsSortingValues,
  AppComment,
  CommentOrigin
} from '@/models/interfaces/Comment'
import { CommentService } from '@/services/comments/CommentService'
import { defineStore } from 'pinia'
import { ref, type Ref } from 'vue'

export const useCommentStore = defineStore(StoresList.COMMENT, () => {
  const comments: Ref<AppComment[]> = ref([])
  const isAppCommentActive = ref(false)

  async function getAll(force = false): Promise<void> {
    if (comments.value.length === 0 || force) {
      comments.value = await CommentService.getAll()
    }
  }
  async function createComment(commentSubmission: AppComment) {
    const comment = await CommentService.createComment(commentSubmission)
    isAppCommentActive.value = false
    if (comment) {
      comments.value.push(comment)
    }
  }

  const markAsRead = (ids: string[]) => {
    CommentService.markAsRead(ids).then(() => {
      getAll(true)
    })
  }

  const deleteComments = (ids: string[]) => {
    CommentService.deleteComments(ids).then(() => {
      getAll(true)
    })
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
