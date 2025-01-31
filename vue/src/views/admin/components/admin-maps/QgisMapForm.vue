<template>
  <Modal
    :title="$t('qgisMap.form.title.' + type)"
    :show="qgisMapStore.isQgisMapFormShown"
    @close="qgisMapStore.isQgisMapFormShown = false"
  >
    <template #content>
      <v-form @submit.prevent="submitForm" id="qgis-map-form" class="Form Form--qgis-map">
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('qgisMap.form.fields.logo') }}</label>
          <ImagesLoader
            @updateFiles="handleLogoUpdate"
            :existingImages="existingLogo"
            :uniqueImage="true"
            :externalImagesLoader="false"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('qgisMap.form.fields.name.label') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.name.value.value"
            :error-messages="form.name.errorMessage.value"
            :placeholder="$t('qgisMap.form.fields.name.label')"
            @blur="form.name.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('qgisMap.form.fields.description.label') }}</label>
          <v-textarea
            density="compact"
            variant="outlined"
            v-model="form.description.value.value"
            :error-messages="form.description.errorMessage.value"
            :placeholder="$t('qgisMap.form.fields.description.label')"
            @blur="form.description.handleChange"
          />
        </div>
        <div class="Form__fieldCtn" v-if="props.type === FormType.CREATE">
          <label class="Form__label">{{ $t('qgisMap.form.fields.file.label') }}</label>
          <FileInput
            v-model="form.qgisProject.value.value"
            :error-messages="form.qgisProject.errorMessage.value"
            @update:model-value="form.qgisProject.handleChange(form.qgisProject.value.value)"
          />
        </div>
        <div v-else class="Form__fieldCtn">
          <v-tooltip location="start" :text="$t('qgisMap.form.fields.loadedFile.disclaimer')">
            <template v-slot:activator="{ props }">
              <label class="Form__label" v-bind="props">{{
                $t('qgisMap.form.fields.loadedFile.label')
              }}</label>
            </template>
          </v-tooltip>
          <v-text-field disabled :placeholder="qgisMap?.qgisProject.name"></v-text-field>
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{
            $t('qgisMap.form.fields.needsToBeVisualiseAsPlainImageInsteadOfWMS.label')
          }}</label>
          <v-switch
            v-model="form.needsToBeVisualiseAsPlainImageInsteadOfWMS.value.value"
            :error-messages="form.needsToBeVisualiseAsPlainImageInsteadOfWMS.errorMessage.value"
            :label="switchLabel"
            color="main-blue"
            class="custom-label"
          ></v-switch>
        </div>
      </v-form>
    </template>
    <template #footer-left>
      <span class="text-action" @click="qgisMapStore.isQgisMapFormShown = false">{{
        $t('forms.cancel')
      }}</span>
    </template>
    <template #footer-right>
      <v-btn type="submit" form="qgis-map-form" color="main-red" :loading="isSubmitting">{{
        $t('forms.' + type)
      }}</v-btn>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import { QgisMapFormService } from '@/services/qgisMap/QgisMapFormService'
import Modal from '@/components/global/Modal.vue'
import { FormType } from '@/models/enums/app/FormType'
import { nestedObjectsToIri } from '@/services/api/ApiPlatformService'
import { onInvalidSubmit } from '@/services/forms/FormService'
import FileInput from '@/components/forms/FileInput.vue'
import { useQgisMapStore } from '@/stores/qgisMapStore'
import type { QgisMap, QgisMapSubmission } from '@/models/interfaces/QgisMap'
import { i18n } from '@/plugins/i18n'
import { computed, onMounted, ref, type Ref } from 'vue'
import type { ContentImageFromUserFile } from '@/models/interfaces/ContentImage'
import type { MediaObject } from '@/models/interfaces/MediaObject'
import FileUploader from '@/services/files/FileUploader'
import ImagesLoader from '@/components/forms/ImagesLoader.vue'

const props = defineProps<{
  type: FormType
  qgisMap: QgisMap | null
}>()

const qgisMapStore = useQgisMapStore()
const { form, handleSubmit, isSubmitting } = QgisMapFormService.getForm(props.qgisMap)

const existingLogo = ref<(MediaObject | string)[]>([])
onMounted(async () => {
  if (props.qgisMap) {
    existingLogo.value = props.qgisMap.logo ? [props.qgisMap.logo] : []
  }
})
const newLogo: Ref<ContentImageFromUserFile[]> = ref([])
function handleLogoUpdate(list: any) {
  newLogo.value = list.selectedFiles
}

const switchLabel = computed(() => {
  if (form.needsToBeVisualiseAsPlainImageInsteadOfWMS.value.value) {
    return i18n.t('qgisMap.form.fields.needsToBeVisualiseAsPlainImageInsteadOfWMS.yes')
  }
  return i18n.t('qgisMap.form.fields.needsToBeVisualiseAsPlainImageInsteadOfWMS.no')
})

const submitForm = handleSubmit(
  async (values) => {
    const qgisMapSubmissionRaw: QgisMapSubmission = {
      ...(values as any),
      logoToUpload: newLogo.value[0]
    }
    if ([FormType.EDIT, FormType.VALIDATE].includes(props.type) && props.qgisMap) {
      qgisMapSubmissionRaw.id = props.qgisMap.id
    }
    if (qgisMapSubmissionRaw.logoToUpload) {
      const newLogo = await FileUploader.uploadFile(qgisMapSubmissionRaw.logoToUpload.file)
      qgisMapSubmissionRaw.logo = newLogo['@id']
      delete qgisMapSubmissionRaw.logoToUpload
    }
    const qgisMapSubmission: QgisMap = nestedObjectsToIri(qgisMapSubmissionRaw)
    await qgisMapStore.submitQgisMap(qgisMapSubmission, props.type)
    qgisMapStore.isQgisMapFormShown = false
  },
  () => onInvalidSubmit
)
</script>
<style>
.custom-label .v-label {
  font-size: 0.875rem;
}
</style>
