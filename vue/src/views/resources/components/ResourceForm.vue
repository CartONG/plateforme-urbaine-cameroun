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
        <template v-if="showLocation">
          <FormSectionTitle :text="$t('resources.form.section.location')" />
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
import { type Resource } from '@/models/interfaces/Resource'
import { ResourceFormService } from '@/services/resources/ResourceFormService'
import { useResourceStore } from '@/stores/resourceStore'
import { useThematicStore } from '@/stores/thematicStore'
import { computed, onMounted, ref, watch } from 'vue'
import Modal from '@/components/global/Modal.vue'
import { FormType } from '@/models/enums/app/FormType'
import { nestedObjectsToIri } from '@/services/api/ApiPlatformService'
import { onInvalidSubmit } from '@/services/forms/FormService'
import { ResourceFormat } from '@/models/enums/contents/ResourceFormat'
import FileInput from '@/components/forms/FileInput.vue'
import type { Thematic } from '@/models/interfaces/Thematic'
import { ResourceType } from '@/models/enums/contents/ResourceType'
import FormSectionTitle from '@/components/text-elements/FormSectionTitle.vue'
import NewSubmission from '@/views/admin/components/form/NewSubmission.vue'
import DateInput from '@/components/forms/DateInput.vue'
import LocationSelector from '@/components/forms/LocationSelector.vue'
import type { FileObject } from '@/models/interfaces/object/FileObject'
import type { ContentImageFromUserFile } from '@/models/interfaces/ContentImage'
import ImagesLoader from '@/components/forms/ImagesLoader.vue'
import type { Ref } from 'vue'
import { useUserStore } from '@/stores/userStore'
import { useI18n } from 'vue-i18n'

const resourceStore = useResourceStore()
const thematicsStore = useThematicStore()
const userStore = useUserStore()

const { t } = useI18n()

const props = defineProps<{
  type: FormType
  resource: Resource | null
  isShown: boolean
}>()

const { form, handleSubmit, isSubmitting } = ResourceFormService.getForm(props.resource)
const submitLabel = computed(() => {
  if (userStore.userIsActorEditor() && props.type === FormType.CREATE) {
    return t('forms.continue')
  }
  return t('forms.' + props.type)
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

onMounted(() => {
  existingImagePreview.value = props.resource?.previewImage ? [props.resource.previewImage] : []
})

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
  return [ResourceType.EVENTS, ResourceType.GUIDES].includes(form.type.value.value)
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

onMounted(async () => {
  await thematicsStore.getAll()
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
  () => onInvalidSubmit
)
</script>
