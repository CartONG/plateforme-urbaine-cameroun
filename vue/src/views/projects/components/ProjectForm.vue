<template>
  <Modal :title="$t('projects.form.title.' + type)" :show="isShown" @close="$emit('close')">
    <template #content>
      <v-form @submit.prevent="submitForm" id="project-form" class="Form Form--project">
        <NewSubmission
          v-if="type === FormType.VALIDATE && project"
          :created-by="project.createdBy"
          :created-at="project.createdAt"
          :message="project.creatorMessage"
        />
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('projects.form.fields.name.label') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.name.value.value"
            :error-messages="form.name.errorMessage.value"
            :placeholder="$t('projects.form.fields.name.label')"
            @blur="form.name.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('projects.form.fields.description.label') }}</label>
          <v-textarea
            variant="outlined"
            :placeholder="$t('projects.form.fields.description.label')"
            v-model="form.description.value.value"
            :error-messages="form.description.errorMessage.value"
            @blur="form.description.handleChange"
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
          <label class="Form__label">{{ $t('projects.form.fields.deliverables.label') }}</label>
          <v-textarea
            variant="outlined"
            :rows="1"
            :placeholder="$t('projects.form.fields.deliverables.label')"
            v-model="form.deliverables.value.value"
            :error-messages="form.deliverables.errorMessage.value"
            @blur="form.deliverables.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('projects.form.fields.calendar.label') }}</label>
          <v-textarea
            variant="outlined"
            :rows="1"
            :placeholder="$t('projects.form.fields.calendar.label')"
            v-model="form.calendar.value.value"
            :error-messages="form.calendar.errorMessage.value"
            @blur="form.calendar.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('projects.form.fields.website.label') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.website.value.value"
            :placeholder="$t('projects.form.fields.website.label')"
            :error-messages="form.website.errorMessage.value"
            @blur="form.website.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('projects.form.fields.status.label') }}</label>
          <v-select
            density="compact"
            variant="outlined"
            chips
            v-model="form.status.value.value as Status"
            :items="Object.values(Status)"
            :placeholder="$t('projects.form.fields.status.label')"
            :item-title="(item) => $t('projects.status.' + item)"
            :item-value="(item) => item"
            :error-messages="form.status.errorMessage.value"
            @blur="form.status.handleChange(form.status.value.value)"
          />
        </div>

        <FormSectionTitle :text="$t('projects.form.section.localization')" />
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{
            $t('projects.form.fields.interventionZone.label')
          }}</label>
          <v-select
            density="compact"
            variant="outlined"
            v-model="form.interventionZone.value.value as AdministrativeScope"
            :items="Object.values(AdministrativeScope)"
            :placeholder="$t('projects.form.fields.interventionZone.label')"
            :item-title="(item) => $t('projects.scope.' + item)"
            :item-value="(item) => item"
            :error-messages="form.interventionZone.errorMessage.value"
            @blur="form.interventionZone.handleChange(form.interventionZone.value.value)"
          />
        </div>
        <Geocoding
          :search-type="NominatimSearchType.FREE"
          :osm-type="OsmType.NODE"
          @change="form.osmData.handleChange(form.osmData.value.value)"
          v-model="form.osmData.value.value as OsmData"
          :error-messages="form.osmData.errorMessage.value"
        />

        <FormSectionTitle :text="$t('projects.form.section.thematics')" />
        <v-select
          density="compact"
          variant="outlined"
          multiple
          v-model="form.thematics.value.value as Thematic[]"
          :items="thematics"
          :placeholder="$t('projects.form.section.thematics')"
          item-title="name"
          item-value="@id"
          :error-messages="form.thematics.errorMessage.value"
          @blur="form.thematics.handleChange(form.thematics.value.value)"
          return-object
        />

        <FormSectionTitle :text="$t('projects.form.section.beneficiaryTypes')" />
        <v-select
          density="compact"
          variant="outlined"
          multiple
          v-model="form.beneficiaryTypes.value.value as BeneficiaryType[]"
          :items="Object.values(BeneficiaryType)"
          :placeholder="$t('projects.form.section.beneficiaryTypes')"
          :item-title="(item) => $t('beneficiaryType.' + item)"
          item-value="@id"
          :error-messages="form.beneficiaryTypes.errorMessage.value"
          @blur="form.beneficiaryTypes.handleChange(form.beneficiaryTypes.value.value)"
          return-object
        />

        <FormSectionTitle :text="$t('projects.form.section.financial')" />
        <v-select
          density="compact"
          variant="outlined"
          multiple
          v-model="form.donors.value.value as Organisation[]"
          :items="projectStore.donors"
          :placeholder="$t('projects.form.section.financial')"
          :item-title="(item) => item.name"
          item-value="@id"
          :error-messages="form.donors.errorMessage.value"
          @blur="form.donors.handleChange(form.donors.value.value)"
          return-object
        />

        <FormSectionTitle :text="$t('projects.form.section.contractingOrganisation')" />
        <v-select
          density="compact"
          variant="outlined"
          v-model="form.contractingOrganisation.value.value as Organisation"
          :items="projectStore.contractingOrganisations"
          :placeholder="$t('projects.form.section.contractingOrganisation')"
          :item-title="(item) => item.name"
          item-value="@id"
          :error-messages="form.contractingOrganisation.errorMessage.value"
          @blur="
            form.contractingOrganisation.handleChange(form.contractingOrganisation.value.value)
          "
          return-object
        />

        <FormSectionTitle :text="$t('projects.form.section.projectOwner')" />
        <v-select
          density="compact"
          variant="outlined"
          v-model="form.actor.value.value as Actor"
          :items="actors"
          item-title="name"
          item-value="@id"
          :error-messages="form.actor.errorMessage.value"
          @blur="form.actor.handleChange(form.actor.value.value)"
          return-object
        />
        <FormSectionTitle :text="$t('projects.form.section.focalPoint')" />

        <div class="Form__fieldCtn">
          <label class="Form__label required">{{
            $t('projects.form.fields.focalPointName.label')
          }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.focalPointName.value.value"
            :placeholder="$t('projects.form.fields.focalPointName.label')"
            :error-messages="form.focalPointName.errorMessage.value"
            @blur="form.focalPointName.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{
            $t('projects.form.fields.focalPointPosition.label')
          }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.focalPointPosition.value.value"
            :placeholder="$t('projects.form.fields.focalPointPosition.label')"
            :error-messages="form.focalPointPosition.errorMessage.value"
            @blur="form.focalPointPosition.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('projects.form.fields.focalPointEmail.label') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.focalPointEmail.value.value"
            :placeholder="$t('projects.form.fields.focalPointEmail.label')"
            :error-messages="form.focalPointEmail.errorMessage.value"
            @blur="form.focalPointEmail.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('projects.form.fields.focalPointTel.label') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.focalPointTel.value.value"
            :placeholder="$t('projects.form.fields.focalPointTel.label')"
            :error-messages="form.focalPointTel.errorMessage.value"
            @blur="form.focalPointTel.handleChange"
            type="tel"
          />
        </div>

        <v-divider color="main-grey" class="border-opacity-100"></v-divider>
        <FormSectionTitle :text="$t('actors.form.images')" />
        <ImagesLoader @updateFiles="handleImagesUpdate" :existingImages="existingImages" />
        <FormSectionTitle :text="$t('actors.form.partnerImages')" />
        <ImagesLoader
          @updateFiles="handleImagesPartnerUpdate"
          :existingImages="existingPartnerImages"
          :externalImagesLoader="false"
        />
      </v-form>
    </template>
    <template #footer-left>
      <span class="text-action" @click="$emit('close')">{{ $t('forms.cancel') }}</span>
    </template>
    <template #footer-right>
      <v-btn type="submit" form="project-form" color="main-red" :loading="isSubmitting">
        {{ submitLabel }}
      </v-btn>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import { type Project, type ProjectSubmission } from '@/models/interfaces/Project'
import { ProjectFormService } from '@/services/projects/ProjectFormService'
import { useProjectStore } from '@/stores/projectStore'
import FormSectionTitle from '@/components/text-elements/FormSectionTitle.vue'
import { computed, onMounted, type Ref, ref } from 'vue'
import Modal from '@/components/global/Modal.vue'
import { useThematicStore } from '@/stores/thematicStore'
import { FormType } from '@/models/enums/app/FormType'
import type { Thematic } from '@/models/interfaces/Thematic'
import { useActorsStore } from '@/stores/actorsStore'
import type { Actor } from '@/models/interfaces/Actor'
import { nestedObjectsToIri } from '@/services/api/ApiPlatformService'
import { NominatimSearchType } from '@/models/enums/geo/NominatimSearchType'
import Geocoding from '@/components/forms/Geocoding.vue'
import { OsmType } from '@/models/enums/geo/OsmType'
import { Status } from '@/models/enums/contents/Status'
import { BeneficiaryType } from '@/models/enums/contents/BeneficiaryType'
import type { Organisation } from '@/models/interfaces/Organisation'
import { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import NewSubmission from '@/views/admin/components/form/NewSubmission.vue'
import { onInvalidSubmit } from '@/services/forms/FormService'
import type { OsmData } from '@/models/interfaces/geo/OsmData'
import ImagesLoader from '@/components/forms/ImagesLoader.vue'
import type { BaseMediaObject } from '@/models/interfaces/object/MediaObject'
import type { ContentImageFromUserFile } from '@/models/interfaces/ContentImage'
import { useUserStore } from '@/stores/userStore'
import { useI18n } from 'vue-i18n'

const projectStore = useProjectStore()
const actorsStore = useActorsStore()
const thematicsStore = useThematicStore()
const userStore = useUserStore()

const { t } = useI18n()

const props = defineProps<{
  type: FormType
  project: Project | null
  isShown: boolean
}>()

const existingLogo = ref<(BaseMediaObject | string)[]>([])
const existingImages = ref<(BaseMediaObject | string)[]>([])
const existingPartnerImages = ref<BaseMediaObject[]>([])
let existingHostedImages: BaseMediaObject[] = []
let existingExternalImages: string[] = []
let existingHostedPartnerImages: BaseMediaObject[] = []
const imagesToUpload: Ref<ContentImageFromUserFile[]> = ref([])
const imagesPartnerToUpload: Ref<ContentImageFromUserFile[]> = ref([])
const newLogo: Ref<ContentImageFromUserFile[]> = ref([])

const submitLabel = computed(() => {
  if (userStore.userIsActorEditor() && props.type === FormType.CREATE) {
    return t('forms.continue')
  }
  return t('forms.' + props.type)
})

const thematics = computed(() => thematicsStore.thematics)
const actors = computed(() => actorsStore.actorsList)

const emit = defineEmits(['submitted', 'close'])

const { form, handleSubmit, isSubmitting } = ProjectFormService.getForm(props.project)

onMounted(async () => {
  await thematicsStore.getAll()
  await projectStore.getAllDonors()
  await projectStore.getAllContractingOrganisations()
  await actorsStore.getAll()
  if (props.project) {
    existingLogo.value = props.project.logo ? [props.project.logo] : []
    existingImages.value = [...props.project.images, ...props.project.externalImages]
    existingHostedImages = props.project.images
    existingExternalImages = props.project.externalImages

    existingPartnerImages.value = props.project.partners
    existingHostedPartnerImages = props.project.partners
  }
})

const submitForm = handleSubmit(
  async (values: Partial<Project | ProjectSubmission>) => {
    let projectSubmission: ProjectSubmission = nestedObjectsToIri(values)
    if ([FormType.EDIT, FormType.VALIDATE].includes(props.type) && props.project) {
      projectSubmission.id = props.project.id
    }

    projectSubmission = {
      ...projectSubmission,
      images: existingHostedImages,
      partners: existingHostedPartnerImages,
      externalImages: existingExternalImages,
      logoToUpload: newLogo.value[0],
      imagesToUpload: [...imagesToUpload.value],
      imagesPartnerToUpload: [...imagesPartnerToUpload.value]
    }

    const submittedProject = await projectStore.submitProject(projectSubmission, props.type)
    emit('submitted', submittedProject)
  },
  () => onInvalidSubmit
)

function handleImagesUpdate(lists: any) {
  imagesToUpload.value = lists.selectedFiles
  existingExternalImages = []
  existingHostedImages = []
  lists.existingImages.forEach((image: BaseMediaObject | string) => {
    if (typeof image === 'string') {
      existingExternalImages.push(image)
    } else {
      existingHostedImages.push(image)
    }
  })
}

function handleImagesPartnerUpdate(lists: any) {
  imagesPartnerToUpload.value = lists.selectedFiles
  existingHostedPartnerImages = []
  lists.existingImages.forEach((image: BaseMediaObject) => {
    existingHostedPartnerImages.push(image)
  })
}

function handleLogoUpdate(list: any) {
  newLogo.value = list.selectedFiles
}
</script>
