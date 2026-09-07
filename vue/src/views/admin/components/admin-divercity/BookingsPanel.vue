<template>
  <div class="AdminPanel">
    <AdminTopBar
      page="Bookings"
      :items="bookingsStore.bookings"
      :sortingListItems="[
        { sortingKey: 'pending', text: $t('divercity.admin.sortPending') },
        { sortingKey: 'submittedAt', text: $t('divercity.admin.sortSubmittedAt') },
        { sortingKey: 'eventDate', text: $t('divercity.admin.sortEventDate') }
      ]"
      searchKey="title"
      @updateSortingKey="sortingKey = $event"
      @update-search-query="searchQuery = $event"
    >
      <template #right-buttons>
        <BookingCalendarFilter v-model="calendarDateFilter" :occupancy="occupancyByDate" />
      </template>
    </AdminTopBar>
    <AdminTable
      :items="filteredItems"
      :table-keys="['title', 'date', 'timeRange', 'submittedAt', 'status.label']"
      :headers="[
        $t('divercity.admin.headers.title'),
        $t('divercity.admin.headers.date'),
        $t('divercity.admin.headers.time'),
        $t('divercity.admin.headers.submittedAt'),
        $t('divercity.admin.headers.status')
      ]"
      :date-keys="['date', 'submittedAt']"
      :column-widths="['28%', '15%', '17%', '20%', '20%']"
      row-clickable
      selectable
      @row-click="openDetail"
    />
    <BookingDetailDialog
      :show="showDetail"
      :booking="selectedBooking"
      @close="showDetail = false"
      @updated="handleUpdated"
    />
  </div>
</template>

<script setup lang="ts">
import AdminTable from '@/components/admin/AdminTable.vue'
import AdminTopBar from '@/components/admin/AdminTopBar.vue'
import BookingCalendarFilter from './BookingCalendarFilter.vue'
import type { Booking } from '@/models/interfaces/divercity/Booking'
import { useBookingsStore } from '@/stores/divercity/bookingsStore'
import { computed, ref } from 'vue'
import BookingDetailDialog from './BookingDetailDialog.vue'

const bookingsStore = useBookingsStore()
const sortingKey = ref('pending')
const searchQuery = ref('')
const calendarDateFilter = ref<string | null>(null)
const showDetail = ref(false)
const selectedBooking = ref<Booking | null>(null)

// Journée de référence pour juger de l'occupation d'une date : 8h30-17h30 (même
// borne que SpaceAvailabilityView / le stepper de réservation), soit 540 minutes.
const WORK_DAY_MINUTES = 540

async function refresh() {
  await bookingsStore.getManagedBookings()
}

function openDetail(item: Booking) {
  selectedBooking.value = item
  showDetail.value = true
}

async function handleUpdated() {
  await refresh()
  showDetail.value = false
}

function timeToMinutes(time: string): number {
  const [hours, minutes] = time.split(':').map(Number)
  return hours * 60 + minutes
}

// Total de minutes réservées par date, à partir des réservations gérées par cet admin.
const occupancyByDate = computed(() => {
  const totalsByDate: Record<string, number> = {}

  for (const booking of bookingsStore.bookings) {
    if (!booking.date || !booking.startTime || !booking.endTime) continue
    const duration = timeToMinutes(booking.endTime) - timeToMinutes(booking.startTime)
    totalsByDate[booking.date] = (totalsByDate[booking.date] || 0) + Math.max(duration, 0)
  }

  const result: Record<string, 'partial' | 'full'> = {}
  for (const [date, minutes] of Object.entries(totalsByDate)) {
    result[date] = minutes >= WORK_DAY_MINUTES ? 'full' : 'partial'
  }
  return result
})

const sortedBookings = computed(() => {
  const list = [...bookingsStore.bookings]

  if (sortingKey.value === 'pending') {
    return list.sort((a, b) => {
      const aPending = (a.status as any)?.code === 'EN_ATTENTE' ? 0 : 1
      const bPending = (b.status as any)?.code === 'EN_ATTENTE' ? 0 : 1
      return aPending - bPending
    })
  }

  if (sortingKey.value === 'submittedAt') {
    return list.sort((a, b) => (a.submittedAt ?? '').localeCompare(b.submittedAt ?? ''))
  }

  return list.sort((a, b) => a.date.localeCompare(b.date)) // eventDate
})

const searchFilteredBookings = computed(() => {
  if (!searchQuery.value) return sortedBookings.value
  return sortedBookings.value.filter((b) =>
    b.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const calendarFilteredBookings = computed(() => {
  if (!calendarDateFilter.value) return searchFilteredBookings.value
  return searchFilteredBookings.value.filter((b) => b.date === calendarDateFilter.value)
})

const filteredItems = computed(() =>
  calendarFilteredBookings.value.map((b) => ({
    ...b,
    timeRange: `${b.startTime} - ${b.endTime}`
  }))
)
</script>