<template>
  <Modal
    :title="actorToEdit ? $t('actors.form.editTitle') : $t('actors.form.createTitle')"
    :show="appStore.showEditContentDialog"
    @close="actorsStore.actorEdition.active = false"
  >
    <template #content>
      <NewSubmission
        v-if="actorToEdit && !actorToEdit.isValidated"
        :created-by="actorToEdit.createdBy"
        :created-at="actorToEdit.createdAt"
      />
      <v-form @submit.prevent="submitForm" id="actor-form" class="Form Form--actor">
        <!-- General infos -->
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('actors.form.name') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.name.value.value"
            :error-messages="form.name.errorMessage.value"
            @blur="form.name.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('actors.form.acronym') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.acronym.value.value"
            :error-messages="form.acronym.errorMessage.value"
            @blur="form.acronym.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('actors.form.logo') }}</label>
          <ImagesLoader
            @updateFiles="handleLogoUpdate"
            :existingImages="existingLogo"
            :uniqueImage="true"
            :externalImagesLoader="false"
          />
        </div>

        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('actors.form.category') }}</label>
          <v-select
            density="compact"
            variant="outlined"
            v-model="form.category.value.value as ActorsCategories"
            :items="categoryItems"
            :error-messages="form.category.errorMessage.value"
            @blur="form.category.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('actors.form.expertise') }}</label>
          <v-select
            density="compact"
            variant="outlined"
            multiple
            v-model="form.expertises.value.value as ActorExpertise[]"
            :items="expertisesItems"
            item-title="name"
            item-value="@id"
            :error-messages="form.expertises.errorMessage.value"
            @blur="form.expertises.handleChange(form.expertises.value.value)"
            return-object
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('actors.form.thematic') }}</label>
          <v-select
            density="compact"
            variant="outlined"
            multiple
            v-model="form.thematics.value.value as Thematic[]"
            :items="thematicsItems"
            item-title="name"
            item-value="@id"
            :error-messages="form.thematics.errorMessage.value"
            @blur="form.thematics.handleChange(form.thematics.value.value)"
            return-object
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('actors.form.adminScope') }}</label>
          <v-select
            density="compact"
            variant="outlined"
            multiple
            v-model="form.administrativeScopes.value.value as AdministrativeScope[]"
            :items="administrativeScopesItems"
            item-title="name"
            item-value="@id"
            :error-messages="form.administrativeScopes.errorMessage.value"
            @blur="form.administrativeScopes.handleChange(form.administrativeScopes.value.value)"
            return-object
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('actors.form.description') }}</label>
          <v-textarea
            variant="outlined"
            v-model="form.description.value.value"
            :error-messages="form.description.errorMessage.value"
            @blur="form.description.handleChange"
          />
        </div>
        <v-divider color="main-grey" class="border-opacity-100"></v-divider>

        <!-- Contact infos -->
        <FormSectionTitle :text="$t('actors.form.contact')" />
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('actors.form.website') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.website.value.value"
            :error-messages="form.website.errorMessage.value"
            @blur="form.website.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('actors.form.email') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.email.value.value"
            :error-messages="form.email.errorMessage.value"
            @blur="form.email.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('actors.form.phone') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.phone.value.value"
            :error-messages="form.phone.errorMessage.value"
            @blur="form.phone.handleChange"
            type="tel"
          />
        </div>

        <FormSectionTitle :text="$t('actorPage.contact')" />
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('actors.form.contactName') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.contactName.value.value"
            :error-messages="form.contactName.errorMessage.value"
            @blur="form.contactName.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('actors.form.contactPosition') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.contactPosition.value.value"
            :error-messages="form.contactPosition.errorMessage.value"
            @blur="form.contactPosition.handleChange"
          />
        </div>

        <v-divider color="main-grey" class="border-opacity-100"></v-divider>
        <FormSectionTitle :text="$t('actors.form.office')" />
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('actors.form.officeName') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.officeName.value.value"
            :error-messages="form.officeName.errorMessage.value"
            @blur="form.officeName.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('actors.form.officeAddress') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.officeAddress.value.value"
            :error-messages="form.officeAddress.errorMessage.value"
            @blur="form.officeAddress.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('actors.form.officeLocation') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.officeLocation.value.value"
            :error-messages="form.officeLocation.errorMessage.value"
            @blur="form.officeLocation.handleChange"
            :placeholder="$t('actors.form.officeLocationPlaceholder')"
          />
        </div>

        <v-divider color="main-grey" class="border-opacity-100"></v-divider>
        <FormSectionTitle :text="$t('actors.form.images')" />
        <ImagesLoader @updateFiles="handleImagesUpdate" :existingImages="existingImages" />
      </v-form>
    </template>
    <template #footer-left>
      <span class="text-action" @click="actorsStore.actorEdition.active = false">{{
        $t('forms.cancel')
      }}</span>
    </template>
    <template #footer-right>
      <v-btn type="submit" form="actor-form" color="main-red" :loading="isSubmitting">{{
        submitLabel
      }}</v-btn>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import { type Actor, type ActorSubmission } from '@/models/interfaces/Actor'
import { ActorsFormService } from '@/services/actors/ActorsForm'
import { useActorsStore } from '@/stores/actorsStore'
import { useApplicationStore } from '@/stores/applicationStore'
import FormSectionTitle from '@/components/text-elements/FormSectionTitle.vue'
import { computed, onMounted, ref, type Ref } from 'vue'
import type { ContentImageFromUserFile } from '@/models/interfaces/ContentImage'
import { ActorsCategories } from '@/models/enums/contents/actors/ActorsCategories'
import type { ActorExpertise } from '@/models/interfaces/ActorExpertise'
import type { Thematic } from '@/models/interfaces/Thematic'
import type { AdministrativeScope } from '@/models/interfaces/AdministrativeScope'
import Modal from '@/components/global/Modal.vue'
import type { FileObject } from '@/models/interfaces/object/FileObject'
import ImagesLoader from '@/components/forms/ImagesLoader.vue'
import { useThematicStore } from '@/stores/thematicStore'
import { onInvalidSubmit } from '@/services/forms/FormService'
import NewSubmission from '@/views/admin/components/form/NewSubmission.vue'
import { i18n } from '@/plugins/i18n'
import { addNotification } from '@/services/notifications/NotificationService'
import { NotificationType } from '@/models/enums/app/NotificationType'
import type { BaseMediaObject } from '@/models/interfaces/object/MediaObject'

const appStore = useApplicationStore()
const actorsStore = useActorsStore()
const thematicsStore = useThematicStore()

const actorToEdit: Actor | null = actorsStore.actorEdition.actor
const { form, handleSubmit, isSubmitting } = ActorsFormService.getActorsForm(actorToEdit)

const categoryItems = Object.values(ActorsCategories)
const expertisesItems = actorsStore.actorsExpertises
const thematicsItems = computed(() => thematicsStore.thematics)
const submitLabel = computed(() => {
  if (actorToEdit) {
    return !actorToEdit.isValidated ? i18n.t('forms.validate') : i18n.t('forms.edit')
  } else {
    return i18n.t('forms.create')
  }
})
const administrativeScopesItems = actorsStore.actorsAdministrativesScopes

const existingLogo = ref<(FileObject | string)[]>([])
const existingImages = ref<(BaseMediaObject | string)[]>([])
let existingHostedImages: FileObject[] = []
let existingExternalImages: string[] = []

onMounted(async () => {
  await thematicsStore.getAll()
  if (actorToEdit) {
    existingLogo.value = actorToEdit.logo ? [actorToEdit.logo] : []
    existingImages.value = [...actorToEdit.images, ...actorToEdit.externalImages]
    existingHostedImages = actorToEdit.images
    existingExternalImages = actorToEdit.externalImages
  }
})

const newLogo: Ref<ContentImageFromUserFile[]> = ref([])
function handleLogoUpdate(list: any) {
  newLogo.value = list.selectedFiles
}

const imagesToUpload: Ref<ContentImageFromUserFile[]> = ref([])
function handleImagesUpdate(lists: any) {
  imagesToUpload.value = lists.selectedFiles
  existingHostedImages = []
  existingExternalImages = []
  lists.existingImages.forEach((image: FileObject | string) => {
    if (typeof image === 'string') {
      existingExternalImages.push(image)
    } else {
      existingHostedImages.push(image)
    }
  })
}

const submitForm = handleSubmit(
  (values) => {
    const actorSubmission: ActorSubmission = {
      ...(values as any),
      logoToUpload: newLogo.value[0],
      images: existingHostedImages,
      externalImages: existingExternalImages,
      imagesToUpload: [...imagesToUpload.value]
    }
    actorsStore.createOrEditActor(actorSubmission, actorToEdit !== null)
  },
  () => {
    addNotification(i18n.t('forms.errors'), NotificationType.ERROR)
    onInvalidSubmit()
  }
)
</script>
