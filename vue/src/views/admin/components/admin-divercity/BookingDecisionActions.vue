<template>
  <div class="BookingDecisionActions">
    <template v-if="statusCode === 'EN_ATTENTE'">
      <v-btn size="small" color="main-green" variant="tonal" class="mr-2" @click="accept">
        {{ $t('divercity.admin.accept') }}
      </v-btn>
      <v-btn size="small" color="main-red" variant="tonal" @click="showRefuseDialog = true">
        {{ $t('divercity.admin.refuse') }}
      </v-btn>
    </template>
    <template v-else-if="statusCode === 'ACCEPTEE'">
      <v-btn size="small" color="main-red" variant="outlined" @click="showCancelDialog = true">
        {{ $t('divercity.admin.cancel') }}
      </v-btn>
    </template>

    <v-dialog v-model="showRefuseDialog" max-width="480">
      <v-card>
        <v-card-title>{{ $t('divercity.admin.refuseDialogTitle') }}</v-card-title>
        <v-card-text>
          <v-textarea
            variant="outlined"
            v-model="refusalReason"
            :label="$t('divercity.admin.refusalReason')"
          />
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn variant="text" @click="showRefuseDialog = false">{{ $t('forms.cancel') }}</v-btn>
          <v-btn color="main-red" :loading="isSubmitting" @click="refuse">{{ $t('forms.save') }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="showCancelDialog" max-width="480">
      <v-card>
        <v-card-title>{{ $t('divercity.admin.cancelDialogTitle') }}</v-card-title>
        <v-card-text>
          <v-textarea
            variant="outlined"
            v-model="cancellationReason"
            :label="$t('divercity.admin.cancellationReason')"
          />
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn variant="text" @click="showCancelDialog = false">{{ $t('forms.cancel') }}</v-btn>
          <v-btn color="main-red" :loading="isSubmitting" @click="cancel">{{ $t('forms.save') }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup lang="ts">
import type { Booking } from '@/models/interfaces/divercity/Booking'
import { SpacesService } from '@/services/divercity/SpacesService'
import { useBookingsStore } from '@/stores/divercity/bookingsStore'
import { addNotification } from '@/services/notifications/NotificationService'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { i18n } from '@/plugins/i18n'
import { computed, ref } from 'vue'

const props = defineProps<{ booking: Booking }>()
const emit = defineEmits(['updated'])

const bookingsStore = useBookingsStore()
const statusCode = computed(() => (props.booking.status as any)?.code)

const showRefuseDialog = ref(false)
const showCancelDialog = ref(false)
const refusalReason = ref('')
const cancellationReason = ref('')
const isSubmitting = ref(false)

async function accept() {
  const iri = bookingsStore.findStatusIri('ACCEPTEE')
  if (!iri) return
  isSubmitting.value = true
  try {
    await SpacesService.patchBookingDecision(props.booking.id, iri)
    addNotification(i18n.t('divercity.admin.decisionSuccess'), NotificationType.SUCCESS)
    emit('updated')
  } catch (error) {
    addNotification(i18n.t('divercity.admin.decisionError'), NotificationType.ERROR, error as string)
  }
  isSubmitting.value = false
}

async function refuse() {
  const iri = bookingsStore.findStatusIri('REFUSEE')
  if (!iri || !refusalReason.value) return
  isSubmitting.value = true
  try {
    await SpacesService.patchBookingDecision(props.booking.id, iri, refusalReason.value)
    addNotification(i18n.t('divercity.admin.decisionSuccess'), NotificationType.SUCCESS)
    showRefuseDialog.value = false
    emit('updated')
  } catch (error) {
    addNotification(i18n.t('divercity.admin.decisionError'), NotificationType.ERROR, error as string)
  }
  isSubmitting.value = false
}

async function cancel() {
  isSubmitting.value = true
  try {
    await SpacesService.patchBookingCancellation(props.booking.id, cancellationReason.value)
    addNotification(i18n.t('divercity.admin.cancelSuccess'), NotificationType.SUCCESS)
    showCancelDialog.value = false
    emit('updated')
  } catch (error) {
    addNotification(i18n.t('divercity.admin.cancelError'), NotificationType.ERROR, error as string)
  }
  isSubmitting.value = false
}
</script>

<style lang="scss" scoped>
.BookingDecisionActions {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 6px;
  min-width: 0;

  :deep(.v-btn) {
    flex-shrink: 0; // le bouton garde sa taille naturelle, jamais tronqué
  }
}
</style>