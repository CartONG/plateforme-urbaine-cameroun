import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { SpacesService } from '@/services/divercity/SpacesService'
import type { Booking } from '@/models/interfaces/divercity/Booking'
import { StoresList } from '@/models/enums/app/StoresList'

const ITEMS_PER_PAGE = 20

export const useMyBookingsStore = defineStore(StoresList.DIVERCITY_MY_BOOKINGS, () => {
  const bookings = ref<Booking[]>([])
  const currentPage = ref(1)
  const totalItems = ref(0)

  const totalPages = computed(() => Math.max(1, Math.ceil(totalItems.value / ITEMS_PER_PAGE)))
  const hasNextPage = computed(() => currentPage.value < totalPages.value)
  const hasPreviousPage = computed(() => currentPage.value > 1)

  async function getMyBookings(page = 1): Promise<void> {
    const result = await SpacesService.getMyBookings(page)
    bookings.value = result.items
    totalItems.value = result.totalItems
    currentPage.value = result.currentPage
  }

  async function goToPage(page: number): Promise<void> {
    if (page < 1 || page > totalPages.value) return
    await getMyBookings(page)
  }

  async function cancelBooking(bookingId: string, cancellationReason?: string): Promise<void> {
    await SpacesService.patchBookingCancellation(bookingId, cancellationReason)
    // On recharge la page courante (et non forcément la page 1) pour que
    // l'utilisateur ne perde pas sa position dans la liste après annulation.
    await getMyBookings(currentPage.value)
  }

  return {
    bookings,
    currentPage,
    totalItems,
    totalPages,
    hasNextPage,
    hasPreviousPage,
    getMyBookings,
    goToPage,
    cancelBooking
  }
})