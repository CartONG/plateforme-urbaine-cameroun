<template>
  <div class="BookingFormView" v-if="space">
    <div class="BookingFormView__header">
      <PageTitle :title="isEditMode ? $t('divercity.booking.editTitle') : $t('divercity.booking.title')" />
      <v-btn variant="text" color="main-red" @click="handleCancelClick">
        {{ $t('divercity.form.cancel') }}
      </v-btn>
    </div>

    <v-stepper v-if="!isDone" v-model="currentStep" class="BookingFormView__stepper" flat non-linear>
      <v-stepper-header>
        <v-stepper-item
          :title="$t('divercity.booking.step1')"
          :value="1"
          :complete="currentStep > 1"
          :color="currentStep > 1 ? 'success' : 'main-blue'"
        />
        <v-divider />
        <v-stepper-item
          :title="$t('divercity.booking.step2')"
          :value="2"
          :complete="currentStep > 2"
          :color="currentStep > 2 ? 'success' : 'main-blue'"
        />
        <v-divider />
        <v-stepper-item
          :title="$t('divercity.booking.step3')"
          :value="3"
          :color="currentStep > 3 ? 'success' : 'main-blue'"
        />
      </v-stepper-header>

      <v-form @submit.prevent>
        <v-stepper-window v-model="currentStep">
          <!-- Étape 1 : Identification du demandeur -->
          <v-stepper-window-item :value="1">
            <div class="Form Form--booking">
              <div class="Form__fieldCtn">
                <label class="Form__label required">{{ $t('divercity.booking.fields.lastName') }}</label>
                <v-text-field
                  density="compact"
                  variant="outlined"
                  :placeholder="$t('divercity.booking.placeholders.lastName')"
                  v-model="form.lastName.value.value"
                  :error-messages="form.lastName.errorMessage.value"
                  @blur="form.lastName.handleChange"
                />
              </div>
              <div class="Form__fieldCtn">
                <label class="Form__label">{{ $t('divercity.booking.fields.firstName') }}</label>
                <v-text-field
                  density="compact"
                  variant="outlined"
                  :placeholder="$t('divercity.booking.placeholders.firstName')"
                  v-model="form.firstName.value.value"
                  :error-messages="form.firstName.errorMessage.value"
                  @blur="form.firstName.handleChange"
                />
              </div>
              <div class="Form__fieldCtn">
                <label class="Form__label required">{{ $t('divercity.booking.fields.organization') }}</label>
                <v-text-field
                  density="compact"
                  variant="outlined"
                  :placeholder="$t('divercity.booking.placeholders.organization')"
                  v-model="form.organization.value.value"
                  :error-messages="form.organization.errorMessage.value"
                  @blur="form.organization.handleChange"
                />
              </div>
              <div class="Form__fieldCtn">
                <label class="Form__label required">{{ $t('divercity.booking.fields.role') }}</label>
                <v-text-field
                  density="compact"
                  variant="outlined"
                  :placeholder="$t('divercity.booking.placeholders.role')"
                  v-model="form.role.value.value"
                  :error-messages="form.role.errorMessage.value"
                  @blur="form.role.handleChange"
                />
              </div>
              <div class="Form__fieldCtn">
                <label class="Form__label required">{{ $t('divercity.booking.fields.email') }}</label>
                <v-text-field
                  density="compact"
                  variant="outlined"
                  :placeholder="$t('divercity.booking.placeholders.email')"
                  v-model="form.email.value.value"
                  :error-messages="form.email.errorMessage.value"
                  @blur="form.email.handleChange"
                />
              </div>
              <div class="Form__fieldCtn">
                <label class="Form__label required">{{ $t('divercity.booking.fields.phone') }}</label>
                <v-text-field
                  density="compact"
                  variant="outlined"
                  :placeholder="$t('divercity.booking.placeholders.phone')"
                  v-model="form.phone.value.value"
                  :error-messages="form.phone.errorMessage.value"
                  @blur="form.phone.handleChange"
                />
              </div>
            </div>
          </v-stepper-window-item>

          <!-- Étape 2 : Informations sur l'évènement / activité -->
          <v-stepper-window-item :value="2">
            <div class="Form Form--booking">
              <div class="Form__fieldCtn">
                <label class="Form__label required">{{ $t('divercity.booking.fields.title') }}</label>
                <v-text-field
                  density="compact"
                  variant="outlined"
                  :placeholder="$t('divercity.booking.placeholders.title')"
                  v-model="form.title.value.value"
                  :error-messages="form.title.errorMessage.value"
                  @blur="form.title.handleChange"
                />
              </div>
              <div class="Form__fieldCtn">
                <label class="Form__label">{{ $t('divercity.booking.fields.eventActivityType') }}</label>
                <v-select
                  density="compact"
                  variant="outlined"
                  :placeholder="$t('divercity.booking.placeholders.eventActivityType')"
                  v-model="form.eventActivityType.value.value"
                  :items="eventActivityTypes"
                  item-title="label"
                  item-value="@id"
                  :error-messages="form.eventActivityType.errorMessage.value"
                  @blur="form.eventActivityType.handleChange(form.eventActivityType.value.value)"
                  clearable
                />
              </div>

              <!-- Pièces jointes existantes, affichées uniquement en mode édition -->
              <div class="Form__fieldCtn" v-if="isEditMode && existingAgenda">
                <label class="Form__label">{{ $t('divercity.booking.fields.currentAgenda') }}</label>
                <a :href="getAttachmentUrl(existingAgenda)" target="_blank" class="BookingFormView__existingFile">
                  <v-icon icon="$folder" size="small" class="mr-1" />
                  {{ $t('divercity.admin.detail.agendaFile') }}
                </a>
              </div>
              <div class="Form__fieldCtn">
                <label class="Form__label" :class="{ required: !isEditMode }">
                  {{ isEditMode ? $t('divercity.booking.fields.replaceAgenda') : $t('divercity.booking.fields.agenda') }}
                </label>
                <v-file-input
                  density="compact"
                  variant="outlined"
                  accept=".pdf"
                  :placeholder="$t('divercity.booking.placeholders.agenda')"
                  v-model="form.agenda.value.value"
                  :error-messages="form.agenda.errorMessage.value"
                  @update:model-value="form.agenda.handleChange(form.agenda.value.value)"
                />
              </div>

              <div class="Form__fieldCtn" v-if="isEditMode && existingResourceDocuments.length">
                <label class="Form__label">{{ $t('divercity.booking.fields.currentResourceDocuments') }}</label>
                <div class="BookingFormView__attachedFiles">
                  <a
                    v-for="doc in existingResourceDocuments"
                    :key="doc.id"
                    :href="getAttachmentUrl(doc)"
                    target="_blank"
                    class="BookingFormView__existingFile mr-2 mb-2"
                  >
                    <v-icon icon="$folder" size="small" class="mr-1" />
                    {{ $t('divercity.admin.detail.resourceFile') }}
                  </a>
                </div>
              </div>
              <div class="Form__fieldCtn">
                <label class="Form__label" :class="{ required: !isEditMode }">
                  {{ isEditMode ? $t('divercity.booking.fields.replaceResourceDocument') : $t('divercity.booking.fields.resourceDocument') }}
                </label>
                <v-file-input
                  density="compact"
                  variant="outlined"
                  accept=".pdf"
                  multiple
                  :placeholder="$t('divercity.booking.placeholders.resourceDocument')"
                  v-model="form.resourceDocument.value.value"
                  :error-messages="form.resourceDocument.errorMessage.value"
                  @update:model-value="form.resourceDocument.handleChange(form.resourceDocument.value.value)"
                />
              </div>

              <div class="Form__fieldCtn">
                <label class="Form__label">{{ $t('divercity.booking.fields.bookingPurpose') }}</label>
                <v-textarea
                  variant="outlined"
                  :placeholder="$t('divercity.booking.placeholders.bookingPurpose')"
                  v-model="form.bookingPurpose.value.value"
                  :error-messages="form.bookingPurpose.errorMessage.value"
                  @blur="form.bookingPurpose.handleChange"
                />
              </div>

              <div class="Form__fieldCtn">
                <label class="Form__label">{{ $t('divercity.booking.fields.additionalInformation') }}</label>
                <v-textarea
                  variant="outlined"
                  :placeholder="$t('divercity.booking.placeholders.additionalInformation')"
                  v-model="form.additionalInformation.value.value"
                  :error-messages="form.additionalInformation.errorMessage.value"
                  append-inner-icon="$paperclip"
                  @click:append-inner="triggerOtherDocumentInput"
                  @blur="form.additionalInformation.handleChange"
                />
                <input
                  ref="otherDocumentInput"
                  type="file"
                  multiple
                  accept=".pdf"
                  class="d-none"
                  @change="handleOtherDocumentChange"
                />
                <div class="BookingFormView__attachedFiles" v-if="otherDocumentFiles.length">
                  <v-chip
                    v-for="(file, index) in otherDocumentFiles"
                    :key="index"
                    closable
                    size="small"
                    class="mr-2 mb-2"
                    @click:close="removeOtherDocumentFile(index)"
                  >
                    {{ file.name }}
                  </v-chip>
                </div>
              </div>

              <div class="Form__fieldCtn">
                <label class="Form__label required">{{ $t('divercity.booking.fields.participantCount') }}</label>
                <v-text-field
                  type="number"
                  density="compact"
                  variant="outlined"
                  :placeholder="$t('divercity.booking.placeholders.participantCount')"
                  v-model.number="form.participantCount.value.value"
                  :error-messages="form.participantCount.errorMessage.value"
                  @blur="form.participantCount.handleChange"
                />
              </div>
            </div>
          </v-stepper-window-item>

          <!-- Étape 3 : Créneau souhaité -->
          <v-stepper-window-item :value="3">
            <div class="Form Form--booking">
              <div class="Form__fieldCtn">
                <label class="Form__label required">{{ $t('divercity.booking.fields.date') }}</label>
                <v-text-field
                  type="date"
                  density="compact"
                  variant="outlined"
                  v-model="form.date.value.value"
                  :error-messages="form.date.errorMessage.value"
                  @blur="form.date.handleChange"
                />
              </div>

              <div class="BookingFormView__availability" v-if="form.date.value.value">
                <v-progress-circular v-if="isCheckingAvailability" indeterminate size="20" color="main-blue" />
                <template v-else-if="dayAvailability.length">
                  <p class="BookingFormView__availabilityTitle">
                    {{ $t('divercity.booking.availability.title') }}
                  </p>
                  <ul>
                    <li v-for="slot in dayAvailability" :key="slot.id">
                      {{ slot.startTime }} - {{ slot.endTime }}
                      <span v-if="slot.type === 'blocked_period'">
                        ({{ $t('divercity.booking.availability.blocked') }})
                      </span>
                    </li>
                  </ul>
                </template>
                <p v-else class="text-success">{{ $t('divercity.booking.availability.free') }}</p>
              </div>

              <div class="Form__fieldCtn">
                <label class="Form__label required">{{ $t('divercity.booking.fields.startTime') }}</label>
                <v-text-field
                  type="time"
                  density="compact"
                  variant="outlined"
                  v-model="form.startTime.value.value"
                  :error-messages="form.startTime.errorMessage.value"
                  @blur="form.startTime.handleChange"
                />
              </div>
              <div class="Form__fieldCtn">
                <label class="Form__label">{{ $t('divercity.booking.fields.endTime') }}</label>
                <v-text-field
                  type="time"
                  density="compact"
                  variant="outlined"
                  v-model="form.endTime.value.value"
                  :error-messages="form.endTime.errorMessage.value"
                  @blur="form.endTime.handleChange"
                />
              </div>

              <v-alert v-if="hasAvailabilityConflict" type="warning" variant="tonal" density="compact">
                {{ $t('divercity.booking.availability.conflict') }}
              </v-alert>

              <div class="Form__fieldCtn">
                <label class="Form__label">{{ $t('divercity.booking.fields.duration') }}</label>
                <v-text-field density="compact" variant="outlined" :model-value="duration" readonly disabled />
              </div>
            </div>
          </v-stepper-window-item>
        </v-stepper-window>
      </v-form>

      <div class="BookingFormView__actions">
        <v-btn v-if="currentStep > 1" variant="outlined" color="main-blue" @click="currentStep--">
          {{ $t('divercity.form.previous') }}
        </v-btn>
        <v-btn v-if="currentStep < 3" color="main-red" @click="goNext">
          {{ $t('divercity.form.next') }}
        </v-btn>
        <v-btn
          v-else
          color="main-red"
          :loading="isSubmitting"
          :disabled="hasAvailabilityConflict"
          @click="isEditMode ? submitEdit() : submitBooking()"
        >
          {{ isEditMode ? $t('divercity.form.save') : $t('divercity.form.finish') }}
        </v-btn>
      </div>
    </v-stepper>

    <!-- Écran "Terminé" (uniquement pour une création) -->
    <div v-else class="BookingFormView__done">
      <h2>{{ $t('divercity.booking.done.title') }}</h2>
      <p>{{ $t('divercity.booking.done.message') }}</p>

      <div class="Form Form--booking">
        <label class="Form__label required">{{ $t('divercity.booking.done.informationSourceQuestion') }}</label>
        <v-radio-group v-model="sourceForm.informationSource.value.value">
          <v-radio
            v-for="source in informationSources"
            :key="source['@id']"
            :label="source.label"
            :value="source['@id']"
          />
        </v-radio-group>
      </div>

      <v-btn color="main-red" :loading="isSourceSubmitting" @click="submitInformationSource">
        {{ $t('divercity.form.close') }}
      </v-btn>
    </div>

    <v-dialog v-model="showCancelConfirm" max-width="480">
      <v-card>
        <v-card-title>{{ $t('divercity.booking.cancelConfirm.title') }}</v-card-title>
        <v-card-text>{{ $t('divercity.booking.cancelConfirm.message') }}</v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn variant="text" @click="showCancelConfirm = false">
            {{ $t('divercity.booking.cancelConfirm.stay') }}
          </v-btn>
          <v-btn color="main-red" variant="elevated" @click="confirmCancel">
            {{ $t('divercity.booking.cancelConfirm.leave') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup lang="ts">
import PageTitle from '@/components/text-elements/PageTitle.vue'
import type { Booking, BookingSubmission, EventActivityType, InformationSource, BookingAttachment } from '@/models/interfaces/divercity/Booking'
import { i18n } from '@/plugins/i18n'
import { addNotification } from '@/services/notifications/NotificationService'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { BookingFormService } from '@/services/divercity/BookingFormService'
import { SpacesService } from '@/services/divercity/SpacesService'
import FileUploader from '@/services/files/FileUploader'
import { useApplicationStore } from '@/stores/applicationStore'
import { useSpacesStore } from '@/stores/divercity/spacesStore'
import { useUserStore } from '@/stores/userStore'
import { useRoute, useRouter } from 'vue-router'
import type { SpaceAvailability } from '@/models/interfaces/divercity/Booking'
import { computed, onMounted, ref, watch } from 'vue'



const applicationStore = useApplicationStore()
const spacesStore = useSpacesStore()
const userStore = useUserStore()
const router = useRouter()
const route = useRoute()

const space = computed(() => spacesStore.mainSpace)
const currentStep = ref(1)
const isDone = ref(false)
const createdBookingId = ref<string | null>(null)

// Mode édition : présence de ?edit={id} dans l'URL.
const editingBookingId = computed(() => (route.query.edit as string | undefined) ?? null)
const isEditMode = computed(() => !!editingBookingId.value)
const editingBooking = ref<Booking | null>(null)

const eventActivityTypes = ref<EventActivityType[]>([])
const informationSources = ref<InformationSource[]>([])
const showCancelConfirm = ref(false)

const { form, handleSubmit, isSubmitting, stepFields, setValues } = BookingFormService.getBookingForm(
  {
    lastName: userStore.currentUser?.lastName ?? '',
    firstName: userStore.currentUser?.firstName ?? '',
    email: userStore.currentUser?.email ?? '',
    organization: userStore.currentUser?.organisation ?? '',
  },
  isEditMode.value
)

const { form: sourceForm, isSubmitting: isSourceSubmitting } =
  BookingFormService.getInformationSourceForm()

const hasUnsavedChanges = computed(() => Object.values(form).some((field) => field.meta.dirty))

const otherDocumentInput = ref<HTMLInputElement | null>(null)
const otherDocumentFiles = ref<globalThis.File[]>([])

const existingAgenda = computed(() =>
  editingBooking.value?.bookingAttachments?.find((a: any) => a.type === 'AGENDA') ?? null
)
const existingResourceDocuments = computed(
  () => editingBooking.value?.bookingAttachments?.filter((a: any) => a.type === 'RESOURCE_DOCUMENT') ?? []
)

function handleCancelClick() {
  if (hasUnsavedChanges.value) {
    showCancelConfirm.value = true
  } else {
    router.push({ name: isEditMode.value ? 'myDiverCityBookings' : 'divercitySpace' })
  }
}

function confirmCancel() {
  showCancelConfirm.value = false
  router.push({ name: isEditMode.value ? 'myDiverCityBookings' : 'divercitySpace' })
}

function triggerOtherDocumentInput() {
  otherDocumentInput.value?.click()
}

function handleOtherDocumentChange(event: Event) {
  const target = event.target as HTMLInputElement
  const newFiles = Array.from(target.files ?? [])
  otherDocumentFiles.value = [...otherDocumentFiles.value, ...newFiles]
  form.otherDocument.value.value = otherDocumentFiles.value as unknown as File[]
  form.otherDocument.handleChange(otherDocumentFiles.value as unknown as File[])
  target.value = ''
}

function removeOtherDocumentFile(index: number) {
  otherDocumentFiles.value.splice(index, 1)
  form.otherDocument.value.value = otherDocumentFiles.value as unknown as File[]
  form.otherDocument.handleChange(otherDocumentFiles.value as unknown as File[])
}

function getAttachmentUrl(attachment: BookingAttachment): string {
  const fileObject = attachment.fileObject
  return typeof fileObject === 'object' ? fileObject.contentUrl : ''
}

onMounted(async () => {
  const promises: Promise<any>[] = [
    SpacesService.getEventActivityTypes(),
    SpacesService.getInformationSources()
  ]

  if (isEditMode.value && editingBookingId.value) {
    promises.push(SpacesService.getBooking(editingBookingId.value))
  }

  const results = await Promise.all(promises)
  eventActivityTypes.value = results[0]
  informationSources.value = results[1]

  if (isEditMode.value && results[2]) {
    const booking = results[2] as Booking
    editingBooking.value = booking

    // Sécurité UX : si la réservation n'est plus EN_ATTENTE (modifiée entre
    // l'affichage de la liste et l'ouverture du formulaire), on bloque l'édition.
    if ((booking.status as any)?.code !== 'EN_ATTENTE') {
      addNotification(i18n.t('divercity.booking.errors.notEditable'), NotificationType.ERROR)
      router.push({ name: 'myDiverCityBookings' })
      return
    }

    setValues({
      lastName: booking.lastName,
      firstName: booking.firstName,
      organization: booking.organization ?? '',
      role: booking.role,
      email: booking.email,
      phone: booking.phone,
      title: booking.title,
      eventActivityType:
        booking.eventActivityType && typeof booking.eventActivityType === 'object'
          ? (booking.eventActivityType as any)['@id']
          : booking.eventActivityType,
      bookingPurpose: booking.bookingPurpose,
      additionalInformation: booking.additionalInformation ?? '',
      participantCount: booking.participantCount,
      date: booking.date,
      startTime: booking.startTime,
      endTime: booking.endTime
    })
  } else if (spacesStore.preselectedSlot) {
    form.date.value.value = spacesStore.preselectedSlot.date
    form.startTime.value.value = spacesStore.preselectedSlot.startTime
    form.endTime.value.value = spacesStore.preselectedSlot.endTime
    spacesStore.preselectedSlot = null
  }

  applicationStore.isLoading = false
})

const duration = computed(() => {
  if (!form.startTime.value.value || !form.endTime.value.value) return ''
  const [sh, sm] = (form.startTime.value.value as string).split(':').map(Number)
  const [eh, em] = (form.endTime.value.value as string).split(':').map(Number)
  const minutes = eh * 60 + em - (sh * 60 + sm)
  if (minutes <= 0) return ''
  return `${Math.floor(minutes / 60)}h${String(minutes % 60).padStart(2, '0')}`
})

async function goNext() {
  const fieldsToValidate = stepFields[currentStep.value]
  const results = await Promise.all(fieldsToValidate.map((key) => form[key].validate()))
  if (results.every((r) => r.valid)) {
    currentStep.value++
  }
}

const submitBooking = handleSubmit(
  async (values: any) => {
    try {
      const resourceFilesToUpload: File[] = values.resourceDocument ?? []
      const otherFilesToUpload: File[] = values.otherDocument ?? []

      const [agendaFile, resourceFiles, otherFiles] = await Promise.all([
        FileUploader.uploadFile(values.agenda),
        Promise.all(resourceFilesToUpload.map((f: File) => FileUploader.uploadFile(f))),
        Promise.all(otherFilesToUpload.map((f: File) => FileUploader.uploadFile(f)))
      ])

      const bookingAttachments: BookingSubmission['bookingAttachments'] = [
        { fileObject: agendaFile['@id'], type: 'AGENDA' },
        ...resourceFiles.map((f) => ({ fileObject: f['@id'], type: 'RESOURCE_DOCUMENT' as const })),
        ...otherFiles.map((f) => ({ fileObject: f['@id'], type: 'OTHER' as const }))
      ]

      const booking = await SpacesService.postBooking({
        space: space.value!['@id'] as string,
        eventActivityType: values.eventActivityType || null,
        title: values.title,
        lastName: values.lastName,
        firstName: values.firstName,
        organization: values.organization,
        role: values.role,
        email: values.email,
        phone: values.phone,
        bookingPurpose: values.bookingPurpose,
        date: values.date,
        startTime: values.startTime,
        endTime: values.endTime,
        participantCount: values.participantCount,
        additionalInformation: values.additionalInformation,
        bookingAttachments
      })

      createdBookingId.value = (booking['@id'] as string).split('/').pop() ?? null
      isDone.value = true
    } catch (error) {
      addNotification(i18n.t('divercity.booking.errors.submit'), NotificationType.ERROR, error as string)
    }
  },
  (errors) => {
    console.error('Booking form submission errors:', errors)
    addNotification(i18n.t('forms.errors'), NotificationType.ERROR)
  }
)

// Soumission en mode édition : seuls les fichiers réellement resélectionnés
// sont uploadés puis inclus dans le payload, pour ne pas écraser les pièces
// jointes existantes avec une collection vide.
const submitEdit = handleSubmit(
  async (values: any) => {
    if (!editingBookingId.value) return
    try {
      const payload: Record<string, any> = {
        eventActivityType: values.eventActivityType || null,
        title: values.title,
        lastName: values.lastName,
        firstName: values.firstName,
        organization: values.organization,
        role: values.role,
        email: values.email,
        phone: values.phone,
        bookingPurpose: values.bookingPurpose,
        date: values.date,
        startTime: values.startTime,
        endTime: values.endTime,
        participantCount: values.participantCount,
        additionalInformation: values.additionalInformation
      }

      const hasNewAgenda = values.agenda instanceof File
      const hasNewResourceDocuments = Array.isArray(values.resourceDocument) && values.resourceDocument.length > 0
      const hasNewOtherDocuments = Array.isArray(values.otherDocument) && values.otherDocument.length > 0

      if (hasNewAgenda || hasNewResourceDocuments || hasNewOtherDocuments) {
        const [agendaFile, resourceFiles, otherFiles] = await Promise.all([
          hasNewAgenda ? FileUploader.uploadFile(values.agenda) : Promise.resolve(null),
          hasNewResourceDocuments
            ? Promise.all((values.resourceDocument as File[]).map((f) => FileUploader.uploadFile(f)))
            : Promise.resolve([]),
          hasNewOtherDocuments
            ? Promise.all((values.otherDocument as File[]).map((f) => FileUploader.uploadFile(f)))
            : Promise.resolve([])
        ])

        // Remplace l'agenda si un nouveau a été fourni, sinon conserve l'existant.
        const keptAgenda = hasNewAgenda
          ? []
          : (editingBooking.value?.bookingAttachments?.filter((a: any) => a.type === 'AGENDA') ?? []).map(
              (a: any) => ({ fileObject: a.fileObject['@id'] ?? a.fileObject, type: 'AGENDA' })
            )
        const keptResourceDocuments = hasNewResourceDocuments
          ? []
          : (editingBooking.value?.bookingAttachments?.filter((a: any) => a.type === 'RESOURCE_DOCUMENT') ?? []).map(
              (a: any) => ({ fileObject: a.fileObject['@id'] ?? a.fileObject, type: 'RESOURCE_DOCUMENT' })
            )
        const keptOtherDocuments = hasNewOtherDocuments
          ? []
          : (editingBooking.value?.bookingAttachments?.filter((a: any) => a.type === 'OTHER') ?? []).map(
              (a: any) => ({ fileObject: a.fileObject['@id'] ?? a.fileObject, type: 'OTHER' })
            )

        payload.bookingAttachments = [
          ...keptAgenda,
          ...(agendaFile ? [{ fileObject: agendaFile['@id'], type: 'AGENDA' }] : []),
          ...keptResourceDocuments,
          ...resourceFiles.map((f: any) => ({ fileObject: f['@id'], type: 'RESOURCE_DOCUMENT' })),
          ...keptOtherDocuments,
          ...otherFiles.map((f: any) => ({ fileObject: f['@id'], type: 'OTHER' }))
        ]
      }
      // Si aucun nouveau fichier : on n'inclut pas bookingAttachments dans le
      // payload, donc l'API ne touche pas à la collection existante.

      await SpacesService.patchBookingEdit(editingBookingId.value, payload as any)
      addNotification(i18n.t('divercity.booking.editSuccess'), NotificationType.SUCCESS)
      router.push({ name: 'myDiverCityBookings' })
    } catch (error) {
      addNotification(i18n.t('divercity.booking.errors.submit'), NotificationType.ERROR, error as string)
    }
  },
  (errors) => {
    console.error('Booking edit submission errors:', errors)
    addNotification(i18n.t('forms.errors'), NotificationType.ERROR)
  }
)

async function submitInformationSource() {
  if (!createdBookingId.value || !sourceForm.informationSource.value.value) {
    router.push({ name: 'divercitySpace' })
    return
  }
  try {
    await SpacesService.patchBookingInformationSource(
      createdBookingId.value,
      sourceForm.informationSource.value.value as string
    )
  } finally {
    router.push({ name: 'divercitySpace' })
  }
}

const dayAvailability = ref<SpaceAvailability[]>([])
const isCheckingAvailability = ref(false)

watch(
  () => form.date.value.value,
  async (newDate) => {
    if (!newDate || !space.value) {
      dayAvailability.value = []
      return
    }
    isCheckingAvailability.value = true
    try {
      dayAvailability.value = await SpacesService.getSpaceAvailability(
        space.value.id,
        newDate as string,
        newDate as string
      )
    } catch (error) {
      dayAvailability.value = []
    } finally {
      isCheckingAvailability.value = false
    }
  }
)

const conflictingSlot = computed(() => {
  const start = form.startTime.value.value as string
  const end = form.endTime.value.value as string
  if (!start || !end) return null

  return (
    dayAvailability.value.find((slot) => {
      // L'id d'un créneau de type "booking" est préfixé "booking-{uuid}" côté
      // SpaceAvailabilityProvider. En édition, on exclut ainsi la réservation
      // en cours de modification pour qu'elle ne se signale pas en conflit
      // avec elle-même.
      if (
        isEditMode.value &&
        editingBookingId.value &&
        slot.type === 'booking' &&
        slot.id === `booking-${editingBookingId.value}`
      ) {
        return false
      }
      return start < slot.endTime && slot.startTime < end
    }) ?? null
  )
})

const hasAvailabilityConflict = computed(() => conflictingSlot.value !== null)
</script>

<style lang="scss">
.BookingFormView {
  max-width: $dim-container-w;
  margin: 4rem auto;

  &__attachedFiles {
    display: flex;
    flex-wrap: wrap;
    margin-top: 0.5rem;
  }

  &__existingFile {
    display: flex;
    align-items: center;
    color: rgb(var(--v-theme-main-blue));
    text-decoration: none;
    font-size: $font-size-sm;

    &:hover {
      text-decoration: underline;
    }
  }

  &__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  &__actions {
    display: flex;
    justify-content: space-between;
    margin-top: 2rem;
  }

  &__done {
    text-align: center;
    max-width: 30rem;
    margin: 0 auto;
  }
}
</style>