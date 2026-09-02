import { defineStore } from 'pinia'
import { computed, ref, type Ref } from 'vue'
import { SpacesService } from '@/services/divercity/SpacesService'
import type { Space } from '@/models/interfaces/divercity/Space'
import type { PublicBooking } from '@/models/interfaces/divercity/Booking'
import { StoresList } from '@/models/enums/app/StoresList'
import type { HighlightedResource } from '@/models/interfaces/divercity/HighlightedResource'
import { DiverCityHighlightedResourceService } from '@/services/divercity/DiverCityHighlightedResourceService'

export const useSpacesStore = defineStore(StoresList.DIVERCITY_SPACES, () => {
  const mainSpace = ref<Space | null>(null)
  const mainHighlights: Ref<HighlightedResource[]> = ref([])
  const publicBookings = ref<PublicBooking[]>([])
  const preselectedSlot = ref<{ date: string; startTime: string; endTime: string } | null>(null)


  async function getMainSpace(): Promise<void> {
    const spaces = await SpacesService.getSpaces()
    mainSpace.value = spaces.length > 0 ? spaces[0] : null
  }

  async function getPublicBookings(): Promise<void> {
    publicBookings.value = await SpacesService.getPublicBookings()
  }

  async function getMainHighlights(): Promise<void> {
    mainHighlights.value = await DiverCityHighlightedResourceService.getMainHighlights()
  }

  const orderedMainHighlights = computed(() => {
    return mainHighlights.value.sort((a, b) => (a.position ?? 0) - (b.position ?? 0))
  })

  return {
    mainSpace,
    publicBookings,
    preselectedSlot,
    orderedMainHighlights,
    getMainSpace,
    getPublicBookings,
    getMainHighlights
    
  }
})