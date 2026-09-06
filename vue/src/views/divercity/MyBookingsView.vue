<template>
  <div class="MyBookingsView">
    <PageBanner :title="$t('divercity.myBookings.title')" class="mt-10" />

    <div class="MyBookingsView__topBar mt-6">
      <p class="MyBookingsView__count">
        {{ $t('divercity.myBookings.count', { count: myBookingsStore.totalItems }, myBookingsStore.totalItems) }}
      </p>
      <v-btn color="main-red" variant="flat" @click="goToBookingForm">
        {{ $t('divercity.myBookings.newBooking') }}
      </v-btn>
    </div>

    <div v-if="isLoading" class="MyBookingsView__loading">
      <v-progress-circular indeterminate color="main-blue" />
    </div>

    <div v-else-if="myBookingsStore.bookings.length === 0" class="MyBookingsView__empty">
      <p>{{ $t('divercity.myBookings.empty') }}</p>
      <v-btn color="main-red" variant="tonal" @click="goToBookingForm">
        {{ $t('divercity.myBookings.newBooking') }}
      </v-btn>
    </div>

    <template v-else>
      <div class="MyBookingsView__list">
        <MyBookingCard
          v-for="booking in myBookingsStore.bookings"
          :key="booking.id"
          :booking="booking"
          :event-type="booking.eventActivityType"
          @view="openDetail(booking)"
        />
      </div>

      <div class="MyBookingsView__pagination" v-if="myBookingsStore.totalPages > 1">
        <v-btn
          icon="$chevronLeft"
          variant="text"
          density="comfortable"
          :disabled="!myBookingsStore.hasPreviousPage"
          @click="changePage(myBookingsStore.currentPage - 1)"
        />
        <span class="MyBookingsView__pageIndicator">
          {{
            $t('divercity.myBookings.pageIndicator', {
              current: myBookingsStore.currentPage,
              total: myBookingsStore.totalPages
            })
          }}
        </span>
        <v-btn
          icon="$chevronRight"
          variant="text"
          density="comfortable"
          :disabled="!myBookingsStore.hasNextPage"
          @click="changePage(myBookingsStore.currentPage + 1)"
        />
      </div>
    </template>

    <MyBookingDetailDialog
      :show="showDetail"
      :booking="selectedBooking"
      @close="showDetail = false"
      @edit="handleEditFromDetail"
      @cancel="handleCancelFromDetail"
    />

    <MyBookingCancelDialog
      :show="showCancelDialog"
      :booking="bookingToCancel"
      @close="showCancelDialog = false"
      @cancelled="handleCancelled"
    />
  </div>
</template>

<script setup lang="ts">
import PageBanner from '@/components/banners/PageBanner.vue'
import MyBookingCard from './components/MyBookingCard.vue'
import MyBookingDetailDialog from './components/MyBookingDetailDialog.vue'
import MyBookingCancelDialog from './components/MyBookingCancelDialog.vue'
import type { Booking } from '@/models/interfaces/divercity/Booking'
import { useMyBookingsStore } from '@/stores/divercity/myBookingsStore'
import { useApplicationStore } from '@/stores/applicationStore'
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const myBookingsStore = useMyBookingsStore()
const applicationStore = useApplicationStore()
const router = useRouter()

const isLoading = ref(true)
const showDetail = ref(false)
const showCancelDialog = ref(false)
const selectedBooking = ref<Booking | null>(null)
const bookingToCancel = ref<Booking | null>(null)

onMounted(async () => {
  isLoading.value = true
  try {
    await myBookingsStore.getMyBookings(1)
  } finally {
    isLoading.value = false
    applicationStore.isLoading = false
  }
})

async function changePage(page: number) {
  isLoading.value = true
  try {
    await myBookingsStore.goToPage(page)
  } finally {
    isLoading.value = false
  }
}

function openDetail(booking: Booking) {
  selectedBooking.value = booking
  showDetail.value = true
}

function openCancelDialog(booking: Booking) {
  bookingToCancel.value = booking
  showCancelDialog.value = true
}

function handleCancelFromDetail() {
  if (!selectedBooking.value) return
  showDetail.value = false
  openCancelDialog(selectedBooking.value)
}

function handleEditFromDetail() {
  if (!selectedBooking.value) return
  showDetail.value = false
  editBooking(selectedBooking.value)
}

async function handleCancelled() {
  showCancelDialog.value = false
}

function editBooking(booking: Booking) {
  router.push({ name: 'divercitySpaceBooking', query: { edit: booking.id } })
}

function goToBookingForm() {
  router.push({ name: 'divercitySpaceBooking' })
}
</script>

<style lang="scss" scoped>
.MyBookingsView {
  display: flex;
  flex-direction: column;
  width: 100%;

  &__topBar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
  }

  &__count {
    color: rgb(var(--v-theme-dark-grey));
    font-weight: 500;
  }

  &__loading {
    display: flex;
    justify-content: center;
    padding: 3rem 0;
  }

  &__empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    padding: 3rem 0;
    color: rgb(var(--v-theme-dark-grey));
  }

  &__list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1.5rem;
  }

  &__pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-top: 2rem;
  }

  &__pageIndicator {
    color: rgb(var(--v-theme-dark-grey));
    font-size: $font-size-sm;
  }
}
</style>