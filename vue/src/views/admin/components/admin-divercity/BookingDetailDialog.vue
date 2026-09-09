<template>
  <Modal :title="booking?.title ?? ''" :show="show" @close="$emit('close')">
    <template #content>
      <div class="BookingDetailDialog" v-if="booking">
        <div class="BookingDetailDialog__header">
          <v-chip :color="statusColor" variant="flat" size="large" class="BookingDetailDialog__statusChip">
            <v-icon :icon="statusIcon" start size="small" />
            {{ statusLabel }}
          </v-chip>
          <span class="BookingDetailDialog__submittedAt">
            {{ $t('divercity.admin.detail.submittedAt', { date: formattedSubmittedAt }) }}
          </span>
        </div>

        <section class="BookingDetailDialog__section">
          <h3 class="BookingDetailDialog__sectionTitle">
            <v-icon icon="$accountOutline" size="small" />
            {{ $t('divercity.admin.detail.requester') }}
          </h3>
          <div class="BookingDetailDialog__fieldsGrid">
            <div v-for="field in requesterFields" :key="field.label" class="FieldCard">
              <div class="FieldCard__header">
                <v-icon :icon="field.icon" size="14" class="FieldCard__icon" />
                <span class="FieldCard__label">{{ field.label }}</span>
              </div>
              <span class="FieldCard__value">{{ field.value }}</span>
            </div>
          </div>
        </section>

        <v-divider class="BookingDetailDialog__divider" />

        <section class="BookingDetailDialog__section">
          <h3 class="BookingDetailDialog__sectionTitle">
            <v-icon icon="$calendarOutline" size="small" />
            {{ $t('divercity.admin.detail.event') }}
          </h3>
          <div class="BookingDetailDialog__fieldsGrid">
            <div v-for="field in eventFields" :key="field.label" class="FieldCard">
              <div class="FieldCard__header">
                <v-icon :icon="field.icon" size="14" class="FieldCard__icon" />
                <span class="FieldCard__label">{{ field.label }}</span>
              </div>

              <v-menu
              v-if="field.truncatable"
              location="bottom"
              open-on-hover
              :open-delay="150"
              :close-delay="150"
              max-width="360"
              content-class="FieldCard__menu"
            >
              <template #activator="{ props: menuProps }">
                <span v-bind="menuProps" class="FieldCard__value FieldCard__value--clamp">{{ field.value }}</span>
              </template>
              <div class="FieldCard__menuContent">{{ field.value }}</div>
            </v-menu>
            <span v-else class="FieldCard__value">{{ field.value }}</span>
            </div>
          </div>
        </section>

        <v-divider class="BookingDetailDialog__divider" />

        <section class="BookingDetailDialog__section">
          <h3 class="BookingDetailDialog__sectionTitle">
            <v-icon icon="$clockOutline" size="small" />
            {{ $t('divercity.admin.detail.slot') }}
          </h3>
          <div class="BookingDetailDialog__fieldsGrid">
            <div v-for="field in slotFields" :key="field.label" class="FieldCard">
              <div class="FieldCard__header">
                <v-icon :icon="field.icon" size="14" class="FieldCard__icon" />
                <span class="FieldCard__label">{{ field.label }}</span>
              </div>
              <span class="FieldCard__value">{{ field.value }}</span>
            </div>
          </div>
        </section>

        <template v-if="booking.bookingAttachments?.length">
          <v-divider class="BookingDetailDialog__divider" />
          <section class="BookingDetailDialog__section">
            <h3 class="BookingDetailDialog__sectionTitle">
              <v-icon icon="$paperclip" size="small" />
              {{ $t('divercity.admin.detail.attachments') }}
            </h3>
            <div class="BookingDetailDialog__attachmentsGrid">
              <a
                v-for="attachment in booking.bookingAttachments"
                :key="attachment.id"
                :href="attachmentUrl(attachment)"
                target="_blank"
                rel="noopener noreferrer"
                class="AttachmentCard"
              >
                <div class="AttachmentCard__icon">
                  <v-icon icon="$fileDocumentOutline" size="20" />
                </div>
                <span class="AttachmentCard__label">{{ attachmentLabel(attachment.type) }}</span>
                <v-icon icon="$openInNew" size="16" class="AttachmentCard__external" />
              </a>
            </div>
          </section>
        </template>

        <v-alert
          v-if="booking.refusalReason"
          type="error"
          variant="tonal"
          density="comfortable"
          :title="$t('divercity.admin.detail.refusalReason')"
          :text="booking.refusalReason"
          class="BookingDetailDialog__alert"
        />

        <v-alert
          v-if="booking.cancellationReason"
          type="warning"
          variant="tonal"
          density="comfortable"
          :title="$t('divercity.admin.detail.cancellationReason')"
          :text="booking.cancellationReason"
          class="BookingDetailDialog__alert"
        />
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
const statusIcon = computed(() => {
  switch (statusCode.value) {
    case 'ACCEPTEE':
      return '$checkCircleOutline'
    case 'REFUSEE':
    case 'ANNULEE':
      return '$closeCircleOutline'
    default:
      return '$clockOutline'
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

const requesterFields = computed(() => {
  if (!props.booking) return []
  const fields = [
    { icon: '$accountOutline', label: i18n.t('divercity.admin.detail.name'), value: `${props.booking.firstName} ${props.booking.lastName}` },
    { icon: '$briefcaseOutline', label: i18n.t('divercity.admin.detail.role'), value: props.booking.role },
  ]
  if (props.booking.organization) {
    fields.push({ icon: '$domain', label: i18n.t('divercity.admin.detail.organization'), value: props.booking.organization })
  }
  fields.push(
    { icon: '$emailOutline', label: i18n.t('divercity.admin.detail.email'), value: props.booking.email },
    { icon: '$phoneOutline', label: i18n.t('divercity.admin.detail.phone'), value: props.booking.phone },
  )
  return fields
})

const eventFields = computed(() => {
  if (!props.booking) return []
  const fields: { icon: string; label: string; value: string; truncatable?: boolean }[] = [
    { icon: '$textBoxOutline', label: i18n.t('divercity.admin.detail.title'), value: props.booking.title },
  ]
  if (eventActivityTypeLabel.value) {
    fields.push({ icon: '$tagOutline', label: i18n.t('divercity.admin.detail.activityType'), value: eventActivityTypeLabel.value })
  }
  if (props.booking.additionalInformation) {
    fields.push({
      icon: '$informationOutline',
      label: i18n.t('divercity.admin.detail.additionalInfo'),
      value: props.booking.additionalInformation,
      truncatable: true
    })
  }
  return fields
})

const slotFields = computed(() => {
  if (!props.booking) return []
  return [
    { icon: '$calendarOutline', label: i18n.t('divercity.admin.detail.date'), value: formattedDate.value },
    { icon: '$clockOutline', label: i18n.t('divercity.admin.detail.time'), value: `${props.booking.startTime} - ${props.booking.endTime}` },
    { icon: '$accountGroupOutline', label: i18n.t('divercity.admin.detail.participants'), value: String(props.booking.participantCount) },
  ]
})

function attachmentUrl(attachment: BookingAttachment): string {
  const fileObject = attachment.fileObject
  const url = typeof fileObject === 'object' ? (fileObject as FileObject)?.contentUrl : ''

  if (!url) return '#'

  if (url.startsWith('http://') || url.startsWith('https://')) {
    return url
  }

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
  &__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
  }

  &__statusChip {
    font-weight: 600;
  }

  &__submittedAt {
    font-size: $font-size-xs;
    color: rgb(var(--v-theme-dark-grey));
  }

  &__section {
    margin-bottom: 0.5rem;
  }

  &__sectionTitle {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: $font-size-h5;
    color: rgb(var(--v-theme-main-blue));
    margin-bottom: 1rem;
  }

  &__divider {
    margin: 1.5rem 0;
  }

  // Demandeur / Événement / Créneau : une seule colonne, une carte par ligne
  &__fieldsGrid {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
  }

  // Pièces jointes : seule section qui reste en grille colonnes
  &__attachmentsGrid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 0.75rem;
  }

  &__alert {
    margin-top: 1.5rem;
  }
}

.FieldCard,
.AttachmentCard {
  display: flex;
  border: 1px solid rgb(var(--v-theme-main-grey));
  border-radius: 10px;
  transition: box-shadow 0.15s ease, transform 0.15s ease;

  &:hover {
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
  }
}

.FieldCard {
  flex-direction: column;
  gap: 0.25rem;
  padding: 0.65rem 1rem;

  &__header {
    display: flex;
    align-items: center;
    gap: 0.4rem;
  }

  &__icon {
    flex: none;
    color: rgb(var(--v-theme-main-blue));
  }

  &__label {
    font-size: 0.7rem;
    color: rgb(var(--v-theme-dark-grey));
    line-height: 1;
  }

  &__value {
    font-size: $font-size-sm;
    font-weight: 500;
    word-break: break-word;
  }

  &__value--clamp {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    cursor: help;
  }

  &__tooltipContent {
    max-height: 200px;
    overflow-y: auto;
    white-space: pre-wrap;
    font-size: $font-size-sm;
    line-height: 1.4;
  }
}

:deep(.FieldCard__menu) {
  background: rgb(var(--v-theme-surface-variant));
  border-radius: 4px;
  padding: 0.5rem 1rem;
  opacity: 0.9;
  box-shadow: none;
}

.FieldCard__menuContent {
  max-height: 200px;
  overflow-y: auto;
  white-space: pre-wrap;
  font-size: 0.875rem;
  line-height: 1.5;
  color: rgb(var(--v-theme-on-surface-variant));
}

.AttachmentCard {
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  text-decoration: none;
  color: rgb(var(--v-theme-main-blue));
  font-size: $font-size-sm;
  font-weight: 500;

  &__icon {
    flex: none;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--v-theme-main-blue), 0.08);
  }

  &__label {
    flex: 1 1 auto;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  &__external {
    flex: none;
    opacity: 0.5;
  }
}
</style>