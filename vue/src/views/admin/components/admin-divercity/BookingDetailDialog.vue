<template>
  <Modal :title="booking?.title ?? ''" :show="show" @close="$emit('close')">
    <template #content>
      <div class="BookingDetailDialog" v-if="booking">
        <v-chip :color="statusColor" class="mb-4">{{ statusLabel }}</v-chip>

        <h3 class="BookingDetailDialog__sectionTitle">{{ $t('divercity.admin.detail.requester') }}</h3>
        <p><strong>{{ $t('divercity.admin.detail.name') }} :</strong> {{ booking.firstName }} {{ booking.lastName }}</p>
        <p><strong>{{ $t('divercity.admin.detail.role') }} :</strong> {{ booking.role }}</p>
        <p v-if="booking.organization">
          <strong>{{ $t('divercity.admin.detail.organization') }} :</strong> {{ booking.organization }}
        </p>
        <p><strong>{{ $t('divercity.admin.detail.email') }} :</strong> {{ booking.email }}</p>
        <p><strong>{{ $t('divercity.admin.detail.phone') }} :</strong> {{ booking.phone }}</p>

        <h3 class="BookingDetailDialog__sectionTitle">{{ $t('divercity.admin.detail.event') }}</h3>
        <p><strong>{{ $t('divercity.admin.detail.title') }} :</strong> {{ booking.title }}</p>
        <p v-if="eventActivityTypeLabel">
          <strong>{{ $t('divercity.admin.detail.activityType') }} :</strong> {{ eventActivityTypeLabel }}
        </p>
        <p><strong>{{ $t('divercity.admin.detail.purpose') }} :</strong> {{ booking.bookingPurpose }}</p>
        <p v-if="booking.additionalInformation">
          <strong>{{ $t('divercity.admin.detail.additionalInfo') }} :</strong> {{ booking.additionalInformation }}
        </p>

        <h3 class="BookingDetailDialog__sectionTitle">{{ $t('divercity.admin.detail.slot') }}</h3>
        <p><strong>{{ $t('divercity.admin.detail.date') }} :</strong> {{ formattedDate }}</p>
        <p><strong>{{ $t('divercity.admin.detail.time') }} :</strong> {{ booking.startTime }} - {{ booking.endTime }}</p>
        <p><strong>{{ $t('divercity.admin.detail.participants') }} :</strong> {{ booking.participantCount }}</p>

        <h3 class="BookingDetailDialog__sectionTitle">{{ $t('divercity.admin.detail.attachments') }}</h3>
        <div class="BookingDetailDialog__attachments">
          
          <div class="BookingDetailDialog__attachments">
            <a
              v-for="attachment in booking.bookingAttachments"
              :key="attachment.id"
              :href="attachmentUrl(attachment)"
              target="_blank"
              rel="noopener noreferrer"
              class="BookingDetailDialog__attachmentLink"
            >
              <v-icon icon="$folder" size="small" class="mr-1" />
              {{ attachmentLabel(attachment.type) }}
            </a>
          </div>
        </div>

        <template v-if="booking.refusalReason">
          <h3 class="BookingDetailDialog__sectionTitle">{{ $t('divercity.admin.detail.refusalReason') }}</h3>
          <p>{{ booking.refusalReason }}</p>
        </template>

        <template v-if="booking.cancellationReason">
          <h3 class="BookingDetailDialog__sectionTitle">{{ $t('divercity.admin.detail.cancellationReason') }}</h3>
          <p>{{ booking.cancellationReason }}</p>
        </template>

        <p class="BookingDetailDialog__meta">
          {{ $t('divercity.admin.detail.submittedAt', { date: formattedSubmittedAt }) }}
        </p>
      </div>
    </template>
    <template #footer-right>
      <BookingDecisionActions v-if="booking" :booking="booking" @updated="$emit('updated')" />
    </template>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/global/Modal.vue'
import type { Booking, BookingAttachment } from '@/models/interfaces/divercity/Booking'
import type { FileObject } from '@/models/interfaces/object/FileObject'
import BookingDecisionActions from './BookingDecisionActions.vue'
import { i18n } from '@/plugins/i18n'
import { localizeDate } from '@/services/utils/UtilsService'
import { computed } from 'vue'

const props = defineProps<{
  show: boolean
  booking: Booking | null
}>()
defineEmits(['close', 'updated'])

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

const eventActivityTypeLabel = computed(() => {
  const type = props.booking?.eventActivityType
  return type && typeof type === 'object' ? type.label : null
})

const formattedDate = computed(() =>
  props.booking?.date ? localizeDate(props.booking.date) : ''
)
const formattedSubmittedAt = computed(() =>
  props.booking?.submittedAt ? localizeDate(props.booking.submittedAt) : ''
)

function attachmentUrl(attachment: BookingAttachment): string {
  const fileObject = attachment.fileObject
  const url = typeof fileObject === 'object' ? (fileObject as FileObject)?.contentUrl : ''

  if (!url) return '#'

  // Si l'URL est déjà absolue (commence par http:// ou https://), on la renvoie directement
  if (url.startsWith('http://') || url.startsWith('https://')) {
    return url
  }

  // Si c'est un chemin relatif, on ajoute le domaine de l'API / Backend
  // Remplacez import.meta.env.VITE_API_URL par votre variable d'environnement ou domaine
  const baseUrl = import.meta.env.VITE_API_URL || ''
  return `${baseUrl}${url.startsWith('/') ? '' : '/'}${url}`
}

function attachmentLabel(type: string): string {
  switch (type) {
    case 'AGENDA':
      return i18n.t('divercity.admin.detail.agendaFile')
    case 'RESOURCE_DOCUMENT':
      return i18n.t('divercity.admin.detail.resourceFile')
    default:
      return i18n.t('divercity.admin.detail.otherFile')
  }
}
</script>

<style lang="scss" scoped>
.BookingDetailDialog {
  &__sectionTitle {
    font-size: $font-size-h5;
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
  }

  &__attachments {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  &__attachmentLink {
    display: flex;
    align-items: center;
    color: rgb(var(--v-theme-main-blue));
    text-decoration: none;

    &:hover {
      text-decoration: underline;
    }
  }

  &__meta {
    margin-top: 1.5rem;
    font-size: $font-size-xs;
    color: rgb(var(--v-theme-dark-grey));
  }
}
</style>