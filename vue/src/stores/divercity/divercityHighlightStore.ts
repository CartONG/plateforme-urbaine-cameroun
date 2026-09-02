import { NotificationType } from '@/models/enums/app/NotificationType'
import { StoresList } from '@/models/enums/app/StoresList'
import type { HighlightedResource } from '@/models/interfaces/divercity/HighlightedResource'
import { i18n } from '@/plugins/i18n'
import { DiverCityHighlightedResourceService } from '@/services/divercity/DiverCityHighlightedResourceService'
import { addNotification } from '@/services/notifications/NotificationService'
import { debounce } from '@/services/utils/UtilsService'
import { defineStore } from 'pinia'
import { computed, ref, type Ref } from 'vue'
import { useApplicationStore } from '../applicationStore'

export const useDiverCityHighlightStore = defineStore(StoresList.DIVERCITY_HIGHLIGHTS, () => {
  const highlights: Ref<HighlightedResource[]> = ref([])

  const orderedHighlights = computed(() => {
    return highlights.value
      .filter((highlightedItem) => highlightedItem?.isHighlighted)
      .sort((a, b) => {
        return (a.position ?? 0) - (b.position ?? 0)
      })
  })


  async function getAll(force = false): Promise<void> {
    if (force) {
      highlights.value = await DiverCityHighlightedResourceService.getAll()
    } else {
      debouncedGetAll()
    }
  }

  const debouncedGetAll = debounce(async () => {
    if (highlights.value.length === 0) {
      highlights.value = await DiverCityHighlightedResourceService.getAll()
    }
  }, 100)


  const updateHighlightedResource = (updatedHighlightedResource: HighlightedResource) => {
    useApplicationStore().isLoading = true
    try {
      let found = false
      highlights.value.forEach((resource, key) => {
        if (resource.resourceId === updatedHighlightedResource.resourceId) {
          highlights.value[key] = updatedHighlightedResource
          found = true
        }
      })
      if (!found) {
        highlights.value.push(updatedHighlightedResource)
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
    updateHighlightedResource
  }
})