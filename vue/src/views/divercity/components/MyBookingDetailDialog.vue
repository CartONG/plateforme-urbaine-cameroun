<template>
  <Modal :title="booking?.title ?? ''" :show="show" @close="$emit('close')">
    <template #content>
      <div class="MyBookingDetailDialog" v-if="booking">
        <v-chip :color="statusColor" class="mb-4">{{ statusLabel }}</v-chip>

        <h3 class="MyBookingDetailDialog__sectionTitle">{{ $t('divercity.admin.detail.event') }}</h3>
        <p><strong>{{ $t('divercity.admin.detail.title') }} :</strong> {{ booking.title }}</p>
        <p v-if="eventActivityTypeLabel">
          <strong>{{ $t('divercity.admin.detail.activityType') }} :</strong> {{ eventActivityTypeLabel }}
        </p>
        <p><strong>{{ $t('divercity.admin.detail.purpose') }} :</strong> {{ booking.bookingPurpose }}</p>
        <p v-if="booking.additionalInformation">
          <strong>{{ $t('divercity.admin.detail.additionalInfo') }} :</strong> {{ booking.additionalInformation }}
        </p>

        <h3 class="MyBookingDetailDialog__sectionTitle">{{ $t('divercity.admin.detail.slot') }}</h3>
        <p><strong>{{ $t('divercity.admin.detail.date') }} :</strong> {{ formattedDate }}</p>
        <p><strong>{{ $t('divercity.admin.detail.time') }} :</strong> {{ booking.startTime }} - {{ booking.endTime }}</p>
        <p><strong>{{ $t('divercity.admin.detail.participants') }} :</strong> {{ booking.participantCount }}</p>

        <template v-if="booking.refusalReason">
          <h3 class="MyBookingDetailDialog__sectionTitle">{{ $t('divercity.admin.detail.refusalReason') }}</h3>
          <p>{{ booking.refusalReason }}</p>
        </template>

        <template v-if="booking.cancellationReason">
          <h3 class="MyBookingDetailDialog__sectionTitle">{{ $t('divercity.admin.detail.cancellationReason') }}</h3>
          <p>{{ booking.cancellationReason }}</p>
        </template>

        <p class="MyBookingDetailDialog__meta">
          {{ $t('divercity.admin.detail.submittedAt', { date: formattedSubmittedAt }) }}
        </p>
      </div>
    </template>
    <template #footer-right>
      <v-btn variant="text" @click="$emit('close')">{{ $t('forms.close') }}</v-btn>
      <v-btn v-if="isEditable" color="main-blue" variant="outlined" class="mr-2" @click="$emit('edit')">
        {{ $t('divercity.myBookings.edit') }}
      </v-btn>
      <v-btn v-if="isCancellable" color="main-red" variant="tonal" @click="$emit('cancel')">
        {{ $t('divercity.myBookings.cancel') }}
      </v-btn>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/global/Modal.vue'
import type { Booking } from '@/models/interfaces/divercity/Booking'
import { localizeDate } from '@/services/utils/UtilsService'
import { computed } from 'vue'

const props = defineProps<{
  show: boolean
  booking: Booking | null
}>()
defineEmits(['close', 'edit', 'cancel'])

const statusCode = computed(() => (props.booking?.status as any)?.code)
const statusLabel = computed(() => (props.booking?.status as any)?.label ?? statusCode.value)
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

// Modifiable uniquement tant qu'EN_ATTENTE (règle confirmée).
const isEditable = computed(() => statusCode.value === 'EN_ATTENTE')
const isCancellable = computed(() => ['EN_ATTENTE', 'ACCEPTEE'].includes(statusCode.value))

const eventActivityTypeLabel = computed(() => {
  const type = props.booking?.eventActivityType
  return type && typeof type === 'object' ? type.label : null
})

const formattedDate = computed(() => (props.booking?.date ? localizeDate(props.booking.date) : ''))
const formattedSubmittedAt = computed(() =>
  props.booking?.submittedAt ? localizeDate(props.booking.submittedAt) : ''
)
</script>

<style lang="scss" scoped>
.MyBookingDetailDialog {
  &__sectionTitle {
    font-size: $font-size-h5;
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
  }

  &__meta {
    margin-top: 1.5rem;
    font-size: $font-size-xs;
    color: rgb(var(--v-theme-dark-grey));
  }
}
</style>