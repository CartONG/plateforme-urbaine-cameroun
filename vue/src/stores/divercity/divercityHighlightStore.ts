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
  const mainHighlights: Ref<HighlightedResource[]> = ref([])

  const orderedHighlights = computed(() =>
    highlights.value
      .filter((item) => item.isHighlighted)
      .sort((a, b) => (a.position ?? 0) - (b.position ?? 0))
  )

  const orderedMainHighlights = computed(() => {
    return mainHighlights.value.sort((a, b) => (a.position ?? 0) - (b.position ?? 0))
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

  async function getMainHighlights(): Promise<void> {
    mainHighlights.value = await DiverCityHighlightedResourceService.getMainHighlights()
  }

  const updateHighlightedResource = (updated: HighlightedResource) => {
  const index = highlights.value.findIndex((item) => item.resourceId === updated.resourceId)
  if (index !== -1) {
    highlights.value[index] = updated
  } else {
    highlights.value.push(updated)
  }
}

  return {
    highlights,
    mainHighlights,
    orderedHighlights,
    orderedMainHighlights,
    getAll,
    getMainHighlights,
    updateHighlightedResource
  }
})