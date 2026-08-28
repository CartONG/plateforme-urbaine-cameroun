import { defineStore } from 'pinia'
import { ref } from 'vue'
import { SpacesService } from '@/services/divercity/SpacesService'
import type { Booking, BookingStatus } from '@/models/interfaces/divercity/Booking'
import { StoresList } from '@/models/enums/app/StoresList'

export const useBookingsStore = defineStore(StoresList.DIVERCITY_BOOKINGS, () => {
  const bookings = ref<Booking[]>([])
  const statuses = ref<BookingStatus[]>([])

  async function getManagedBookings(): Promise<void> {
    bookings.value = await SpacesService.getManagedBookings()
  }

  async function getStatuses(): Promise<void> {
    if (statuses.value.length === 0) {
      statuses.value = await SpacesService.getStatuses()
    }
  }

  function findStatusIri(code: string): string | undefined {
    return statuses.value.find((s) => s.code === code)?.['@id']
  }

  return {
    bookings,
    statuses,
    getManagedBookings,
    getStatuses,
    findStatusIri
  }
})