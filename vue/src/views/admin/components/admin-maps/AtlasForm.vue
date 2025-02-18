<template>
  <Modal
    :title="$t('atlas.form.title.' + type)"
    :show="isShown"
    @close="atlasStore.isFormShown = false"
  >
    <template #content>
      <v-form @submit.prevent="submitForm" id="atlas-form" class="Form Form--qgis-map">
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
          <label class="Form__label required">{{ $t('atlas.form.fields.name.label') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.name.value.value"
            :error-messages="form.name.errorMessage.value"
            :placeholder="$t('atlas.form.fields.name.label')"
            @blur="form.name.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('atlas.form.fields.maps.label') }}</label>
          <v-select
            multiple
            density="compact"
            variant="outlined"
            v-model="form.maps.value.value as QgisMap[]"
            :items="qgisMaps"
            item-title="name"
            item-value="@id"
            :error-messages="form.maps.errorMessage.value"
            @blur="form.maps.handleChange(form.maps.value.value)"
            return-object
          />
        </div>
      </v-form>
    </template>
    <template #footer-left>
      <span class="text-action" @click="atlasStore.isFormShown = false">{{
        $t('forms.cancel')
      }}</span>
    </template>
    <template #footer-right>
      <v-btn type="submit" form="atlas-form" color="main-red" :loading="isSubmitting">{{
        $t('forms.' + type)
      }}</v-btn>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/global/Modal.vue'
import { FormType } from '@/models/enums/app/FormType'
import { onInvalidSubmit } from '@/services/forms/FormService'
import { AtlasFormService } from '@/services/atlas/AtlasFormService'
import type { Atlas, AtlasSubmission } from '@/models/interfaces/Atlas'
import { AtlasGroup } from '@/models/enums/geo/AtlasGroup'
import { useQgisMapStore } from '@/stores/qgisMapStore'
import type { QgisMap } from '@/models/interfaces/QgisMap'
import { computed, onMounted, ref, type Ref } from 'vue'
import { useAtlasStore } from '@/stores/atlasStore'
import type { ContentImageFromUserFile } from '@/models/interfaces/ContentImage'
import type { FileObject } from '@/models/interfaces/object/FileObject'
import ImagesLoader from '@/components/forms/ImagesLoader.vue'
import FileUploader from '@/services/files/FileUploader'
import { nestedObjectsToIri } from '@/services/api/ApiPlatformService'

const props = defineProps<{
  atlas: Atlas | null
  type: FormType
  atlasGroup: AtlasGroup
}>()

const atlasStore = useAtlasStore()
const isShown = computed(() => atlasStore.isFormShown)
// const atlasGroups = Object.values(AtlasGroup)
const qgisStore = useQgisMapStore()
const qgisMaps = computed(() => {
  return qgisStore.qgisMaps
})

const { form, handleSubmit, isSubmitting } = AtlasFormService.getForm(props.atlas)

const existingLogo = ref<(FileObject | string)[]>([])
onMounted(async () => {
  if (props.atlas) {
    existingLogo.value = props.atlas.logo ? [props.atlas.logo] : []
  }
  if (props.atlasGroup) form.atlasGroup.value.value = props.atlasGroup
})
const newLogo: Ref<ContentImageFromUserFile[]> = ref([])
function handleLogoUpdate(list: any) {
  newLogo.value = list.selectedFiles
}

const submitForm = handleSubmit(
  async (values) => {
    const atlasSubmissionRaw: AtlasSubmission = {
      ...(values as any),
      atlasGroup: props.atlasGroup,
      logoToUpload: newLogo.value[0]
    }
    if (atlasSubmissionRaw.logoToUpload) {
      const newLogo = await FileUploader.uploadFile(atlasSubmissionRaw.logoToUpload.file)
      atlasSubmissionRaw.logo = newLogo['@id']
      delete atlasSubmissionRaw.logoToUpload
    }
    const atlasSubmission: Atlas = nestedObjectsToIri(atlasSubmissionRaw)

    if (FormType.CREATE === props.type) {
      atlasSubmission.position =
        atlasStore.atlasList.filter((x) => x.atlasGroup === props.atlasGroup).length + 1
    }
    if ([FormType.EDIT, FormType.VALIDATE].includes(props.type) && props.atlas) {
      atlasSubmission.id = props.atlas.id
    }
    await atlasStore.submitAtlas(atlasSubmission, props.type)
    atlasStore.isFormShown = false
  },
  () => onInvalidSubmit
)
</script>
