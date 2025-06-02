<template>
  <Modal
    :title="actorToEdit ? $t('actors.form.editTitle') : $t('actors.form.createTitle')"
    :show="appStore.showEditContentDialog"
    @close="actorsStore.resetActorEditionMode()"
  >
    <template #content>
      <NewSubmission
        v-if="actorToEdit && !actorToEdit.isValidated"
        :created-by="actorToEdit.createdBy"
        :created-at="actorToEdit.createdAt"
        :message="actorToEdit.creatorMessage"
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
          <label class="Form__label">{{ $t('actors.form.description') }}</label>
          <v-textarea
            variant="outlined"
            v-model="form.description.value.value"
            :error-messages="form.description.errorMessage.value"
            @blur="form.description.handleChange"
          />
        </div>
        <v-divider color="main-grey" class="border-opacity-100"></v-divider>

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
        <div class="Form__fieldCtn" v-if="form.category.value.value === ActorsCategories.OTHERS">
          <label class="Form__label conditionnal">{{ $t('actors.form.otherCategory') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.otherCategory.value.value"
            :error-messages="form.otherCategory.errorMessage.value"
            @blur="form.otherCategory.handleChange"
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
        <div class="Form__fieldCtn" v-if="otherExpertiseIsSelected">
          <label class="Form__label conditionnal">{{ $t('actors.form.otherExpertise') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.otherExpertise.value.value"
            :error-messages="form.otherExpertise.errorMessage.value"
            @blur="form.otherExpertise.handleChange"
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
        <div class="Form__fieldCtn" v-if="otherThematicIsSelected">
          <label class="Form__label conditionnal">{{ $t('actors.form.otherThematic') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.otherThematic.value.value"
            :error-messages="form.otherThematic.errorMessage.value"
            @blur="form.otherThematic.handleChange"
          />
        </div>

        <v-divider color="main-grey" class="border-opacity-100"></v-divider>
        <FormSectionTitle :text="$t('actors.form.adminScopeSection')" />
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('actors.form.adminScope') }}</label>
          <v-select
            density="compact"
            variant="outlined"
            multiple
            v-model="form.administrativeScopes.value.value as AdministrativeScope[]"
            :items="Object.values(AdministrativeScope)"
            :item-title="(item) => $t('actors.scope.' + item)"
            :item-value="(item) => item"
            :error-messages="form.administrativeScopes.errorMessage.value"
            @blur="form.administrativeScopes.handleChange(form.administrativeScopes.value.value)"
          />
        </div>
        <div class="Form__fieldCtn" v-if="activeAdminLevels && activeAdminLevels.admin1">
          <label class="Form__label">{{ $t('actors.form.admin1') }}</label>
          <v-autocomplete
            multiple
            density="compact"
            :items="adminBoundariesStore.admin1Boundaries"
            item-title="adm1Name"
            item-value="@id"
            variant="outlined"
            v-model="form.admin1List.value.value as Admin1Boundary[]"
            return-object
          ></v-autocomplete>
        </div>
        <div class="Form__fieldCtn" v-if="activeAdminLevels && activeAdminLevels.admin2">
          <label class="Form__label">{{ $t('actors.form.admin2') }}</label>
          <v-autocomplete
            multiple
            density="compact"
            :items="adminBoundariesStore.admin2Boundaries"
            item-title="adm2Name"
            item-value="@id"
            variant="outlined"
            v-model="form.admin2List.value.value as Admin2Boundary[]"
            return-object
          ></v-autocomplete>
        </div>
        <div class="Form__fieldCtn" v-if="activeAdminLevels && activeAdminLevels.admin3">
          <label class="Form__label">{{ $t('actors.form.admin3') }}</label>
          <v-autocomplete
            multiple
            density="compact"
            :items="adminBoundariesStore.admin3Boundaries"
            item-title="adm3Name"
            item-value="@id"
            variant="outlined"
            v-model="form.admin3List.value.value as Admin3Boundary[]"
            return-object
          ></v-autocomplete>
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
            placeholder="+237 652 266 618"
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
        <v-divider color="main-grey" class="border-opacity-100"></v-divider>

        <FormSectionTitle :text="$t('actors.form.location')" />
        <LocationSelector
          @update:model-value="form.geoData.handleChange"
          v-model="form.geoData.value.value as GeoData"
          :error-message="form.geoData.errorMessage.value"
        />

        <v-divider color="main-grey" class="border-opacity-100"></v-divider>
        <FormSectionTitle :text="$t('actors.form.images')" />
        <ImagesLoader @updateFiles="handleImagesUpdate" :existingImages="existingImages" />
      </v-form>
    </template>
    <template #footer-left>
      <span class="text-action" @click="actorsStore.resetActorEditionMode()">{{
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
import ImagesLoader from '@/components/forms/ImagesLoader.vue'
import LocationSelector from '@/components/forms/LocationSelector.vue'
import Modal from '@/components/global/Modal.vue'
import FormSectionTitle from '@/components/text-elements/FormSectionTitle.vue'
import { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { ActorsCategories } from '@/models/enums/contents/actors/ActorsCategories'
import { type Actor, type ActorSubmission } from '@/models/interfaces/Actor'
import type { ActorExpertise } from '@/models/interfaces/ActorExpertise'
import type {
  Admin1Boundary,
  Admin2Boundary,
  Admin3Boundary
} from '@/models/interfaces/AdminBoundaries'
import type { ContentImageFromUserFile } from '@/models/interfaces/ContentImage'
import type { GeoData } from '@/models/interfaces/geo/GeoData'
import type { FileObject } from '@/models/interfaces/object/FileObject'
import type { BaseMediaObject } from '@/models/interfaces/object/MediaObject'
import type { Thematic } from '@/models/interfaces/Thematic'
import { i18n } from '@/plugins/i18n'
import { ActorsFormService } from '@/services/actors/ActorsForm'
import { onInvalidSubmit } from '@/services/forms/FormService'
import { addNotification } from '@/services/notifications/NotificationService'
import { useActorsStore } from '@/stores/actorsStore'
import { useAdminBoundariesStore } from '@/stores/adminBoundariesStore'
import { useApplicationStore } from '@/stores/applicationStore'
import { useThematicStore } from '@/stores/thematicStore'
import NewSubmission from '@/views/admin/components/form/NewSubmission.vue'
import { computed, onMounted, ref, type Ref } from 'vue'

const appStore = useApplicationStore()
const actorsStore = useActorsStore()
const thematicsStore = useThematicStore()
const adminBoundariesStore = useAdminBoundariesStore()

const actorToEdit: Actor | null = actorsStore.actorEdition.actor
const { form, handleSubmit, isSubmitting } = ActorsFormService.getActorsForm(actorToEdit)

const categoryItems = Object.values(ActorsCategories)
const expertisesItems = actorsStore.actorsExpertises
const thematicsItems = computed(() => thematicsStore.thematics)
const submitLabel = computed(() => {
  if (actorToEdit) {
    return !actorToEdit.isValidated ? i18n.t('forms.validate') : i18n.t('forms.edit')
  } else {
    return i18n.t('forms.continue')
  }
})

const otherExpertiseIsSelected = computed(() => {
  if (form.expertises.value?.value && Array.isArray(form.expertises.value?.value)) {
    return (form.expertises.value?.value as ActorExpertise[]).map((x) => x.name).includes('Autre')
  }
  return false
})
const otherThematicIsSelected = computed(() => {
  if (form.thematics.value?.value && Array.isArray(form.thematics.value?.value)) {
    return (form.thematics.value?.value as Thematic[]).map((x) => x.name).includes('Autre')
  }
  return false
})

const activeAdminLevels = computed(() => {
  if (
    form.administrativeScopes.value?.value &&
    Array.isArray(form.administrativeScopes.value?.value)
  ) {
    return {
      admin1: (form.administrativeScopes.value?.value as AdministrativeScope[]).includes(
        AdministrativeScope.REGIONAL
      ),
      admin2: (form.administrativeScopes.value?.value as AdministrativeScope[]).includes(
        AdministrativeScope.STATE
      ),
      admin3: (form.administrativeScopes.value?.value as AdministrativeScope[]).includes(
        AdministrativeScope.CITY
      )
    }
  }
  return false
})

const existingLogo = ref<(FileObject | string)[]>([])
const existingImages = ref<(BaseMediaObject | string)[]>([])
let existingHostedImages: FileObject[] = []
let existingExternalImages: string[] = []

onMounted(async () => {
  await Promise.all([
    thematicsStore.getAll(),
    adminBoundariesStore.getAdmin1(),
    adminBoundariesStore.getAdmin2(),
    adminBoundariesStore.getAdmin3()
  ])

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
      id: actorToEdit ? actorToEdit.id : undefined,
      logoToUpload: newLogo.value[0],
      images: existingHostedImages,
      externalImages: existingExternalImages,
      imagesToUpload: [...imagesToUpload.value]
    }
    actorsStore.createOrEditActor(actorSubmission, actorToEdit !== null)
  },
  ({ errors }) => {
    console.log(errors)
    addNotification(i18n.t('forms.errors'), NotificationType.ERROR)
    onInvalidSubmit()
  }
)
</script>
