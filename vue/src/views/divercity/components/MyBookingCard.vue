<template>
  <div class="MyBookingCard" @click="$emit('view')">
    <div class="MyBookingCard__main">
      <div class="MyBookingCard__header">
        <h4 class="MyBookingCard__title">{{ booking.title }}</h4>
        <v-chip :color="statusColor" size="small">{{ statusLabel }}</v-chip>
      </div>
      <p class="MyBookingCard__space">{{ spaceName }}</p>
      <p class="MyBookingCard__slot">
        {{ formattedDate }} · {{ booking.startTime }} - {{ booking.endTime }}
      </p>
    </div>
    <div class="MyBookingCard__actions" @click.stop>
        <v-btn
            v-if="isEditable"
            size="small"
            color="main-blue"
            variant="outlined"
            class="mr-2"
            @click="$emit('edit')"
        >
            {{ $t('divercity.myBookings.edit') }}
        </v-btn>
        <v-btn
            v-if="isCancellable"
            size="small"
            color="main-red"
            variant="outlined"
            @click="$emit('cancel')"
        >
            {{ $t('divercity.myBookings.cancel') }}
        </v-btn>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Booking } from '@/models/interfaces/divercity/Booking'
import { localizeDate } from '@/services/utils/UtilsService'
import { computed } from 'vue'

const props = defineProps<{ booking: Booking }>()
defineEmits(['view', 'edit', 'cancel'])


const statusCode = computed(() => (props.booking.status as any)?.code)
const statusLabel = computed(() => (props.booking.status as any)?.label ?? statusCode.value)

const statusColor = computed(() => {
  switch (statusCode.value) {
    case 'ACCEPTEE':
      return 'main-green'
    case 'REFUSEE':
    case 'ANNULEE':
      return 'main-red'
    default:
      return 'main-yellow'
  }
})

// Seules les réservations EN_ATTENTE ou ACCEPTEE restent annulables
// (cohérent avec la règle appliquée côté BookingCancellationProcessor).
const isEditable = computed(() => statusCode.value === 'EN_ATTENTE')
const isCancellable = computed(() => ['EN_ATTENTE', 'ACCEPTEE'].includes(statusCode.value))

const spaceName = computed(() => {
  const space = props.booking.space
  return space && typeof space === 'object' ? (space as any).name : ''
})


const formattedDate = computed(() => (props.booking.date ? localizeDate(props.booking.date) : ''))
</script>

<style lang="scss" scoped>
.MyBookingCard {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  padding: 1rem 1.25rem;
  border: 1px solid rgb(var(--v-theme-main-grey));
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.15s ease;

  &:hover {
    background-color: rgb(var(--v-theme-light-yellow));
  }

  &__main {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    min-width: 0;
  }

  &__header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
  }

  &__title {
    font-size: $font-size-h5;
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  &__space,
  &__slot {
    margin: 0;
    color: rgb(var(--v-theme-dark-grey));
    font-size: $font-size-sm;
  }

  &__actions {
    flex-shrink: 0;
  }

  @media (max-width: 600px) {
    flex-direction: column;
    align-items: stretch;

    &__actions {
      display: flex;
      justify-content: flex-end;
    }
  }
}
</style>