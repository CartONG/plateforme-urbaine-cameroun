import { NotificationType } from '@/models/enums/app/NotificationType'
import { StoresList } from '@/models/enums/app/StoresList'
import type { HighlightedItem } from '@/models/interfaces/HighlightedItem'
import { i18n } from '@/plugins/i18n'
import { HighlightedItemService } from '@/services/highlight/HighlightedItemService'
import { addNotification } from '@/services/notifications/NotificationService'
import { debounce } from '@/services/utils/UtilsService'
import { defineStore } from 'pinia'
import { computed, ref, type Ref } from 'vue'
import { useApplicationStore } from './applicationStore'

export const useHighlightStore = defineStore(StoresList.HIGHLIGHTS, () => {
  const highlights: Ref<HighlightedItem[]> = ref([])

  const orderedHighlights = computed(() => {
    return highlights.value
      .filter((highlightedItem) => highlightedItem?.isHighlighted)
      .sort((a, b) => {
        return (a.position ?? 0) - (b.position ?? 0)
      })
  })

  async function getAll(force = false): Promise<void> {
    if (force) {
      highlights.value = await HighlightedItemService.getAll()
    } else {
      debouncedGetAll()
    }
  }

  const debouncedGetAll = debounce(async () => {
    if (highlights.value.length === 0) {
      highlights.value = await HighlightedItemService.getAll()
    }
  }, 100)

  const updateHighlightedItem = (updatedHighlightedItem: HighlightedItem) => {
    useApplicationStore().isLoading = true
    try {
      let found = false
      highlights.value.forEach((project, key) => {
        if (project.id === updatedHighlightedItem.id) {
          highlights.value[key] = updatedHighlightedItem
          found = true
        }
      })
      if (!found) {
        highlights.value.push(updatedHighlightedItem)
      }
    } catch (error) {
      addNotification(
        i18n.t('notifications.common.error.500'),
        NotificationType.ERROR,
        error as string
      )
    }
    useApplicationStore().isLoading = false
  }

  return {
    highlights,
    orderedHighlights,
    getAll,
    updateHighlightedItem
  }
})
