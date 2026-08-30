<template>
  <v-dialog :model-value="show" max-width="480" @update:model-value="$emit('close')">
    <v-card v-if="booking">
      <v-card-title>{{ $t('divercity.myBookings.cancelDialogTitle') }}</v-card-title>
      <v-card-text>
        <p class="mb-4">
          {{ $t('divercity.myBookings.cancelDialogConfirm', { title: booking.title }) }}
        </p>
        <v-textarea
          variant="outlined"
          v-model="cancellationReason"
          :label="$t('divercity.myBookings.cancellationReasonOptional')"
        />
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn variant="text" @click="$emit('close')">{{ $t('forms.cancel') }}</v-btn>
        <v-btn color="main-red" :loading="isSubmitting" @click="confirmCancel">
          {{ $t('divercity.myBookings.confirmCancel') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import type { Booking } from '@/models/interfaces/divercity/Booking'
import { useMyBookingsStore } from '@/stores/divercity/myBookingsStore'
import { addNotification } from '@/services/notifications/NotificationService'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { i18n } from '@/plugins/i18n'
import { ref, watch } from 'vue'

const props = defineProps<{
  show: boolean
  booking: Booking | null
}>()
const emit = defineEmits(['close', 'cancelled'])

const myBookingsStore = useMyBookingsStore()
const cancellationReason = ref('')
const isSubmitting = ref(false)

watch(
  () => props.show,
  (isShown) => {
    if (isShown) cancellationReason.value = ''
  }
)

async function confirmCancel() {
  if (!props.booking) return
  isSubmitting.value = true
  try {
    await myBookingsStore.cancelBooking(props.booking.id, cancellationReason.value || undefined)
    addNotification(i18n.t('divercity.myBookings.cancelSuccess'), NotificationType.SUCCESS)
    emit('cancelled')
  } catch (error) {
    addNotification(i18n.t('divercity.myBookings.cancelError'), NotificationType.ERROR, error as string)
  }
  isSubmitting.value = false
}
</script>