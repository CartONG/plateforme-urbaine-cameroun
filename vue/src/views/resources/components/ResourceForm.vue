<template>
  <Modal :title="$t('resources.form.title.' + type)" :show="isShown" @close="$emit('close')">
    <template #content>
      <v-form @submit.prevent="submitForm" id="resource-form" class="Form Form--resource">
        <NewSubmission
          v-if="!isResourceValidated && resource"
          :created-by="resource.createdBy"
          :created-at="resource.createdAt"
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

        <FormSectionTitle :text="$t('resources.form.section.location')" />
        <Geocoding
          :search-type="NominatimSearchType.FREE"
          :osm-type="OsmType.NODE"
          @change="form.osmData.handleChange(form.osmData.value.value)"
          v-model="form.osmData.value.value"
        />

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
      </v-form>
    </template>
    <template #footer-left>
      <span class="text-action" @click="$emit('close')">{{ $t('forms.cancel') }}</span>
    </template>
    <template #footer-right>
      <v-btn type="submit" form="resource-form" color="main-red" :loading="isSubmitting">{{
        $t('forms.' + type)
      }}</v-btn>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import { type Resource, type ResourceSubmission } from '@/models/interfaces/Resource'
import { ResourceFormService } from '@/services/resources/ResourceFormService'
import { useResourceStore } from '@/stores/resourceStore'
import { useThematicStore } from '@/stores/thematicStore'
import { computed, onMounted, watch } from 'vue'
import Modal from '@/components/global/Modal.vue'
import { FormType } from '@/models/enums/app/FormType'
import { nestedObjectsToIri } from '@/services/api/ApiPlatformService'
import { onInvalidSubmit } from '@/services/forms/FormService'
import { ResourceFormat } from '@/models/enums/contents/ResourceFormat'
import FileInput from '@/components/forms/FileInput.vue'
import type { Thematic } from '@/models/interfaces/Thematic'
import { ResourceType } from '@/models/enums/contents/ResourceType'
import FormSectionTitle from '@/components/text-elements/FormSectionTitle.vue'
import Geocoding from '@/components/forms/Geocoding.vue'
import { NominatimSearchType } from '@/models/enums/geo/NominatimSearchType'
import { OsmType } from '@/models/enums/geo/OsmType'
import NewSubmission from '@/views/admin/components/form/NewSubmission.vue'
import DateInput from '@/components/forms/DateInput.vue'

const resourceStore = useResourceStore()
const thematicsStore = useThematicStore()

const props = defineProps<{
  type: FormType
  resource: Resource | null
  isShown: boolean
}>()

const isResourceValidated = computed(() => props.resource?.isValidated)

const hideFileInput = computed(() => {
  if (!form.format.value.value) return false
  return [ResourceFormat.VIDEO, ResourceFormat.WEB].includes(form.format.value.value)
})

const handleDateChange = () => {
  form.startAt.handleChange(form.startAt.value.value)
  form.endAt.handleChange(form.endAt.value.value)
}

const emit = defineEmits(['submitted', 'close'])
const { form, handleSubmit, isSubmitting } = ResourceFormService.getForm(props.resource)
const thematics = computed(() => thematicsStore.thematics)
watch(
  () => form.type.value.value,
  () => {
    if (!resourceFormats.value.includes(form.format.value.value)) {
      form.format.value.value = null
    }
  }
)
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
    const resourceSubmission: ResourceSubmission = nestedObjectsToIri(values)
    if ([FormType.EDIT, FormType.VALIDATE].includes(props.type) && props.resource) {
      resourceSubmission.id = props.resource.id
    }

    const submittedResource = await resourceStore.submitResource(resourceSubmission, props.type)
    emit('submitted', submittedResource)
  },
  () => onInvalidSubmit
)
</script>
