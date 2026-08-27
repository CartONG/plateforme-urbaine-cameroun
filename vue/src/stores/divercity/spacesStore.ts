import { defineStore } from 'pinia'
import { ref } from 'vue'
import { SpacesService } from '@/services/divercity/SpacesService'
import type { Space } from '@/models/interfaces/divercity/Space'
import type { PublicBooking } from '@/models/interfaces/divercity/Booking'
import { StoresList } from '@/models/enums/app/StoresList'

export const useSpacesStore = defineStore(StoresList.DIVERCITY_SPACES, () => {
  const mainSpace = ref<Space | null>(null)
  const publicBookings = ref<PublicBooking[]>([])
  const preselectedSlot = ref<{ date: string; startTime: string; endTime: string } | null>(null)


  async function getMainSpace(): Promise<void> {
    const spaces = await SpacesService.getSpaces()
    mainSpace.value = spaces.length > 0 ? spaces[0] : null
  }

  async function getPublicBookings(): Promise<void> {
    publicBookings.value = await SpacesService.getPublicBookings()
  }

  return {
    mainSpace,
    publicBookings,
    preselectedSlot,
    getMainSpace,
    getPublicBookings
  }
})