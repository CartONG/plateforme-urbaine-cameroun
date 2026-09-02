<template>
  <Modal :title="$t('divercity.admin.resources.title')" :show="isShown" @close="handleClose">
    <template #content>
      <v-form @submit.prevent="submitForm" id="booking-resource-form" class="Form">
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('divercity.admin.resources.selectBooking') }}</label>
          <v-select
            density="compact"
            variant="outlined"
            v-model="selectedBookingId"
            :items="bookingsStore.bookings"
            item-title="title"
            item-value="id"
            :placeholder="$t('divercity.admin.resources.selectBookingPlaceholder')"
          />
        </div>

        <div class="Form__fieldCtn" v-if="selectedBookingId">
          <label class="Form__label">{{ $t('divercity.admin.resources.selectResources') }}</label>
          <v-select
            density="compact"
            variant="outlined"
            multiple
            chips
            closable-chips
            v-model="selectedResourceIris"
            :items="resourceStore.resources"
            item-title="name"
            item-value="@id"
          />
        </div>
      </v-form>
    </template>

    <template #footer-left>
      <span class="text-action" @click="handleClose">{{ $t('forms.cancel') }}</span>
      <span v-show="isSubmitting" class="text-warning ml-3">{{ $t('forms.submitting') }}</span>
    </template>

    <template #footer-right>
      <v-btn
        type="submit"
        form="booking-resource-form"
        color="main-red"
        :loading="isSubmitting"
        :disabled="!selectedBookingId"
      >
        {{ $t('forms.save') }}
      </v-btn>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/global/Modal.vue'
import { useBookingsStore } from '@/stores/divercity/bookingsStore'
import { useResourceStore } from '@/stores/resourceStore'
import { useDiverCityHighlightStore } from '@/stores/divercity/divercityHighlightStore'
import { SpacesService } from '@/services/divercity/SpacesService'
import { addNotification } from '@/services/notifications/NotificationService'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { i18n } from '@/plugins/i18n'
import { onMounted, ref, watch } from 'vue'

const props = defineProps<{
  isShown: boolean
}>()

const emit = defineEmits(['close', 'saved'])

const bookingsStore = useBookingsStore()
const resourceStore = useResourceStore()
const highlightStore = useDiverCityHighlightStore()

const selectedBookingId = ref<string | null>(null)
const selectedResourceIris = ref<string[]>([])
const isSubmitting = ref(false)

onMounted(async () => {
  await Promise.all([bookingsStore.getManagedBookings(), resourceStore.getAll()])
})

// Pré-remplit la sélection des ressources en fonction de la réservation choisie
watch(selectedBookingId, () => {
  const booking = bookingsStore.bookings.find((b) => b.id === selectedBookingId.value)
  selectedResourceIris.value = (booking?.resources ?? []).map((r) =>
    typeof r === 'object' ? (r as any)['@id'] : r
  )
})

const resetForm = () => {
  selectedBookingId.value = null
  selectedResourceIris.value = []
}

const handleClose = () => {
  resetForm()
  emit('close')
}

// Extrait le message d'erreur métier renvoyé par l'API (problem+json /
// hydra), pour afficher une notification précise plutôt qu'un message générique.
const extractErrorMessage = (error: unknown): string | null => {
  const response = (error as any)?.response
  return response?.data?.detail ?? response?.data?.['hydra:description'] ?? null
}

const submitForm = async () => {
  if (!selectedBookingId.value) return
  isSubmitting.value = true
  try {
    await SpacesService.patchBookingResources(selectedBookingId.value, selectedResourceIris.value)

    // Rechargement direct des données
    await Promise.all([bookingsStore.getManagedBookings(), highlightStore.getAll(true)])

    addNotification(i18n.t('divercity.admin.resources.saveSuccess'), NotificationType.SUCCESS)
    resetForm()
    emit('saved')
    emit('close')
  } catch (error) {
    const backendMessage = extractErrorMessage(error)
    addNotification(
      backendMessage ?? i18n.t('divercity.admin.resources.saveError'),
      NotificationType.ERROR,
      error instanceof Error ? error.message : String(error)
    )
  } finally {
    isSubmitting.value = false
  }
}
</script>