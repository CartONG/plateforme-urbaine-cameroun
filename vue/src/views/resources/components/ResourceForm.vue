<template>
  <Modal :title="$t('resources.form.title.' + type)" :show="isShown" @close="$emit('close')">
    <template #content>
      <v-form @submit.prevent="submitForm" id="resource-form" class="Form Form--resource">
        <NewSubmission
          v-if="!isResourceValidated && resource"
          :created-by="resource.createdBy"
          :created-at="resource.createdAt"
          :message="resource.creatorMessage"
        />

        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('resources.form.fields.type.label') }}</label>
          <v-select
            density="compact"
            variant="outlined"
            chips
            v-model="form.type.value.value as ResourceType"
            :items="Object.values(ResourceType)"
            :placeholder="$t('resources.form.fields.type.label')"
            :item-title="(item) => $t('resources.resourceType.' + item)"
            :item-value="(item) => item"
            :error-messages="form.type.errorMessage.value"
            @blur="form.type.handleChange(form.type.value.value)"
          />
        </div>

        <div class="Form__fieldCtn" v-if="otherRessourceTypeIsSelected">
          <label class="Form__label conditionnal">{{
            $t('resources.form.fields.otherType.label')
          }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            :placeholder="$t('resources.form.fields.otherType.placeholder')"
            v-model="form.otherType.value.value"
            :error-messages="form.otherType.errorMessage.value"
            @blur="form.otherType.handleChange"
          />
        </div>

        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('resources.form.fields.imagePreview.label') }}</label>
          <ImagesLoader
            @updateFiles="handleImagePreviewUpdate"
            :existingImages="existingImagePreview"
            :uniqueImage="true"
            :externalImagesLoader="false"
          />
        </div>

        <div class="Form__fieldCtn" v-if="form.type.value.value === ResourceType.EVENTS">
          <label class="Form__label required">{{ $t('resources.form.fields.date.label') }}</label>
          <DateInput
            v-model:start-at="form.startAt.value.value"
            v-model:end-at="form.endAt.value.value"
            :error-messages="form.startAt.errorMessage.value || form.endAt.errorMessage.value"
            @change="handleDateChange"
          />
        </div>

        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('resources.form.fields.name.label') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.name.value.value"
            :error-messages="form.name.errorMessage.value"
            :placeholder="$t('resources.form.fields.name.label')"
            @blur="form.name.handleChange"
          />
        </div>

        <div class="Form__fieldCtn">
          <label class="Form__label required">{{
            $t('resources.form.fields.description.label')
          }}</label>
          <v-textarea
            variant="outlined"
            :placeholder="$t('resources.form.fields.description.label')"
            v-model="form.description.value.value"
            :error-messages="form.description.errorMessage.value"
            @blur="form.description.handleChange"
          />
        </div>

        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('resources.form.fields.author.label') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.author.value.value"
            :placeholder="$t('resources.form.fields.author.placeholder')"
            :error-messages="form.author.errorMessage.value"
            @blur="form.author.handleChange"
          />
        </div>

        <FormSectionTitle :text="$t('resources.form.section.resource')" />

        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('resources.form.fields.format.label') }}</label>
          <v-select
            density="compact"
            variant="outlined"
            v-model="form.format.value.value as ResourceFormat"
            :items="resourceFormats"
            :placeholder="$t('resources.form.fields.format.label')"
            :item-title="(item) => $t('resources.resourceFormat.' + item)"
            :item-value="(item) => item"
            :error-messages="form.format.errorMessage.value"
            @blur="form.format.handleChange(form.format.value.value)"
          />
        </div>
        <div class="Form__fieldCtn" v-if="form.format.value.value">
          <label class="Form__label">{{ $t('resources.form.fields.link.label') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.link.value.value"
            :placeholder="$t('resources.form.fields.link.placeholder')"
            :error-messages="form.link.errorMessage.value"
            @blur="form.link.handleChange"
          />
        </div>
        <v-divider v-if="!hideFileInput && form.format.value.value">{{ $t('forms.or') }}</v-divider>
        <div class="Form__fieldCtn" v-if="!hideFileInput && form.format.value.value">
          <label class="Form__label">{{ $t('resources.form.fields.file.label') }}</label>
          <FileInput
            v-model="form.file.value.value"
            :error-messages="form.file.errorMessage.value"
            @update:model-value="form.file.handleChange(form.file.value.value)"
          />
        </div>
        <template v-if="showAdminScope">
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
        </template>
        <template v-if="showLocation || showAdminScope">
          <FormSectionTitle v-if="showLocation" :text="$t('resources.form.section.location')" />
          <label class="Form__label" v-if="showAdminScope">{{
            $t('resources.form.section.centroids')
          }}</label>
          <LocationSelector
            @update:model-value="form.geoData.handleChange"
            v-model="form.geoData.value.value"
            :error-message="form.geoData.errorMessage.value"
          />
        </template>
        <FormSectionTitle :text="$t('resources.form.section.thematics')" />
        <v-select
          density="compact"
          variant="outlined"
          multiple
          v-model="form.thematics.value.value as Thematic[]"
          :items="thematics"
          :placeholder="$t('resources.form.section.thematics')"
          item-title="name"
          item-value="@id"
          :error-messages="form.thematics.errorMessage.value"
          @blur="form.thematics.handleChange(form.thematics.value.value)"
          return-object
        />
        <div class="Form__fieldCtn" v-if="otherThematicIsSelected">
          <label class="Form__label conditionnal">{{
            $t('resources.form.fields.otherThematics.label')
          }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.otherThematic.value.value"
            :placeholder="$t('resources.form.fields.otherThematics.placeholder')"
            :error-messages="form.otherThematic.errorMessage.value"
            @blur="form.otherThematic.handleChange"
          />
        </div>
      </v-form>
    </template>
    <template #footer-left>
      <span class="text-action" @click="$emit('close')">{{ $t('forms.cancel') }}</span>
    </template>
    <template #footer-right>
      <v-btn type="submit" form="resource-form" color="main-red" :loading="isSubmitting">
        {{ submitLabel }}
      </v-btn>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import DateInput from '@/components/forms/DateInput.vue'
import FileInput from '@/components/forms/FileInput.vue'
import ImagesLoader from '@/components/forms/ImagesLoader.vue'
import LocationSelector from '@/components/forms/LocationSelector.vue'
import Modal from '@/components/global/Modal.vue'
import FormSectionTitle from '@/components/text-elements/FormSectionTitle.vue'
import { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import { FormType } from '@/models/enums/app/FormType'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { ResourceFormat } from '@/models/enums/contents/ResourceFormat'
import { ResourceType } from '@/models/enums/contents/ResourceType'
import type {
  Admin1Boundary,
  Admin2Boundary,
  Admin3Boundary
} from '@/models/interfaces/AdminBoundaries'
import type { ContentImageFromUserFile } from '@/models/interfaces/ContentImage'
import type { FileObject } from '@/models/interfaces/object/FileObject'
import { type Resource } from '@/models/interfaces/Resource'
import type { Thematic } from '@/models/interfaces/Thematic'
import { i18n } from '@/plugins/i18n'
import { nestedObjectsToIri } from '@/services/api/ApiPlatformService'
import { onInvalidSubmit } from '@/services/forms/FormService'
import { addNotification } from '@/services/notifications/NotificationService'
import { ResourceFormService } from '@/services/resources/ResourceFormService'
import { useAdminBoundariesStore } from '@/stores/adminBoundariesStore'
import { useResourceStore } from '@/stores/resourceStore'
import { useThematicStore } from '@/stores/thematicStore'
import { useUserStore } from '@/stores/userStore'
import NewSubmission from '@/views/admin/components/form/NewSubmission.vue'
import type { Ref } from 'vue'
import { computed, onMounted, ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'

const resourceStore = useResourceStore()
const thematicsStore = useThematicStore()
const userStore = useUserStore()
const adminBoundariesStore = useAdminBoundariesStore()

const { t } = useI18n()

const props = defineProps<{
  type: FormType
  resource: Resource | null
  isShown: boolean
}>()

onMounted(async () => {
  await Promise.all([
    thematicsStore.getAll(),
    adminBoundariesStore.getAdmin1(),
    adminBoundariesStore.getAdmin2(),
    adminBoundariesStore.getAdmin3()
  ])
  existingImagePreview.value = props.resource?.previewImage ? [props.resource.previewImage] : []
})

const { form, handleSubmit, isSubmitting } = ResourceFormService.getForm(props.resource)
const submitLabel = computed(() => {
  if (userStore.userIsActorEditor() && props.type === FormType.CREATE) {
    return t('forms.continue')
  }
  return t('forms.' + props.type)
})

const showAdminScope = computed(() => {
  return [ResourceType.RAPPORTS, ResourceType.REGULATIONS, ResourceType.OTHERS].includes(
    form.type.value.value
  )
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

const isResourceValidated = computed(() => props.resource?.isValidated)

const otherRessourceTypeIsSelected = computed(() => {
  if (form.type.value?.value) {
    return form.type.value?.value === ResourceType.OTHERS
  }
  return false
})
const otherThematicIsSelected = computed(() => {
  if (form.thematics.value?.value && Array.isArray(form.thematics.value?.value)) {
    return (form.thematics.value?.value as Thematic[]).map((x) => x.name).includes('Autre')
  }
  return false
})

const existingImagePreview = ref<(FileObject | string)[]>([])

const newImagePreview: Ref<ContentImageFromUserFile[]> = ref([])
const handleImagePreviewUpdate = (list: any) => {
  newImagePreview.value = list.selectedFiles
}

const hideFileInput = computed(() => {
  if (!form.format.value.value) return false
  return [ResourceFormat.VIDEO, ResourceFormat.WEB].includes(form.format.value.value)
})

const handleDateChange = () => {
  form.startAt.handleChange(form.startAt.value.value)
  form.endAt.handleChange(form.endAt.value.value)
}

const emit = defineEmits(['submitted', 'close'])
const thematics = computed(() => thematicsStore.thematics)
watch(
  () => form.type.value.value,
  () => {
    if (!resourceFormats.value.includes(form.format.value.value)) {
      form.format.value.value = null
    }
  }
)
const showLocation = computed(() => {
  return [ResourceType.EVENTS, ResourceType.GUIDES, ResourceType.OTHERS].includes(
    form.type.value.value
  )
})
const resourceFormats = computed(() => {
  switch (form.type.value.value) {
    case ResourceType.EVENTS:
      return [ResourceFormat.WEB, ResourceFormat.PDF, ResourceFormat.IMAGE, ResourceFormat.VIDEO]
    case ResourceType.GUIDES:
      return [
        ResourceFormat.WEB,
        ResourceFormat.PDF,
        ResourceFormat.IMAGE,
        ResourceFormat.VIDEO,
        ResourceFormat.XLSX
      ]
    case ResourceType.RAPPORTS:
    case ResourceType.REGULATIONS:
    default:
      return [ResourceFormat.WEB, ResourceFormat.PDF, ResourceFormat.IMAGE]
  }
})

const submitForm = handleSubmit(
  async (values) => {
    const resourceSubmission: Resource = nestedObjectsToIri(values)
    if ([FormType.EDIT, FormType.VALIDATE].includes(props.type) && props.resource) {
      resourceSubmission.id = props.resource.id
    }
    resourceSubmission.previewImageToUpload = newImagePreview.value[0] ?? null
    resourceSubmission.previewImage = props.resource?.previewImage

    const submittedResource = await resourceStore.submitResource(resourceSubmission, props.type)
    emit('submitted', submittedResource)
  },
  () => {
    addNotification(i18n.t('forms.errors'), NotificationType.ERROR)
    onInvalidSubmit()
  }
)
</script>
