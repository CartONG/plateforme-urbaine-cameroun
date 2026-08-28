<template>
  <div class="AdminPanel">
    <AdminTopBar
      page="Bookings"
      :items="bookingsStore.bookings"
      :sortingListItems="[
        { sortingKey: 'pending', text: $t('divercity.admin.sortPending') },
        { sortingKey: 'date', text: $t('divercity.admin.sortDate') }
      ]"
      searchKey="title"
      @updateSortingKey="sortingKey = $event"
      @update-search-query="searchQuery = $event"
    />
    <AdminTable
      :items="filteredItems"
      :table-keys="['title', 'date', 'timeRange', 'status.label']"
      :date-keys="['date']"
      :column-widths="['28%', '16%', '18%', '14%', '24%']"
      row-clickable
      @row-click="openDetail"
    >
      <template #editContentCell="{ item }">
        <BookingDecisionActions :booking="item as Booking" @updated="refresh" />
      </template>
    </AdminTable>
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
import type { Booking } from '@/models/interfaces/divercity/Booking'
import { useBookingsStore } from '@/stores/divercity/bookingsStore'
import BookingDecisionActions from './BookingDecisionActions.vue'
import { computed, ref } from 'vue'
import BookingDetailDialog from './BookingDetailDialog.vue'

const bookingsStore = useBookingsStore()
const sortingKey = ref('pending')
const searchQuery = ref('')
const showDetail = ref(false)
const selectedBooking = ref<Booking | null>(null)

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

const sortedBookings = computed(() => {
  const list = [...bookingsStore.bookings]
  if (sortingKey.value === 'pending') {
    return list.sort((a, b) => {
      const aPending = (a.status as any)?.code === 'EN_ATTENTE' ? 0 : 1
      const bPending = (b.status as any)?.code === 'EN_ATTENTE' ? 0 : 1
      return aPending - bPending
    })
  }
  return list.sort((a, b) => a.date.localeCompare(b.date))
})

const searchFilteredBookings = computed(() => {
  if (!searchQuery.value) return sortedBookings.value
  return sortedBookings.value.filter((b) =>
    b.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

// AdminTable ne sait afficher que des champs plats/imbriqués existants sur l'item ;
// on ajoute donc un champ dérivé "timeRange" pour la colonne heure début-fin.
const filteredItems = computed(() =>
  searchFilteredBookings.value.map((b) => ({
    ...b,
    timeRange: `${b.startTime} - ${b.endTime}`
  }))
)
</script>