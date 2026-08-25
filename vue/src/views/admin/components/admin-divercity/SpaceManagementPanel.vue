<template>
  <div class="AdminPanel SpaceManagementPanel" v-if="space">
    <h2 class="SpaceManagementPanel__title">{{ $t('admin.panelDiverCity') }}</h2>

    <div class="Form__fieldCtn">
      <label class="Form__label required">{{ $t('divercity.form.name') }}</label>
      <v-text-field density="compact" variant="outlined" v-model="form.name" />
    </div>

    <div class="Form__fieldCtn">
      <label class="Form__label required">{{ $t('divercity.form.description') }}</label>
      <TextEditor v-model:content-model="form.description" :parent-form-error="descriptionError" />
    </div>

    <div class="Form__fieldCtn">
      <label class="Form__label">{{ $t('divercity.form.maxCapacity') }}</label>
      <v-text-field type="number" density="compact" variant="outlined" v-model.number="form.maxCapacity" />
    </div>

    <v-divider class="my-6" />

    <div class="Form__fieldCtn">
      <label class="Form__label">{{ $t('divercity.form.photos') }}</label>
      <ImagesLoader @updateFiles="handlePhotosUpdate" :existingImages="existingPhotos" />
    </div>

    <v-btn color="main-red" class="mt-4" :loading="isSubmittingSpace" @click="submitSpace">
      {{ $t('forms.save') }}
    </v-btn>

    <v-divider class="my-6" />

    <h3 class="SpaceManagementPanel__subtitle">{{ $t('divercity.form.highlightSection', { year: highlightForm.year }) }}</h3>

    <div class="Form__fieldCtn">
      <label class="Form__label">{{ $t('divercity.form.year') }}</label>
      <v-text-field type="number" density="compact" variant="outlined" v-model.number="highlightForm.year" />
    </div>

    <div class="Form__fieldCtn">
    <label class="Form__label">{{ $t('divercity.form.report') }}</label>
    <v-file-input
        density="compact"
        variant="outlined"
        accept=".pdf"
        v-model="reportFile"
        :placeholder="currentHighlight?.report ? $t('divercity.form.existingReportPlaceholder') : undefined"
    />
    </div>

    <div class="Form__fieldCtn">
      <label class="Form__label">{{ $t('divercity.form.statistics') }}</label>
      <div
        v-for="(stat, index) in highlightForm.statistics"
        :key="index"
        class="SpaceManagementPanel__statRow"
      >
        <v-text-field
          density="compact"
          variant="outlined"
          :placeholder="$t('divercity.form.statLabel')"
          v-model="stat.label"
        />
        <v-text-field
          density="compact"
          variant="outlined"
          :placeholder="$t('divercity.form.statValue')"
          v-model="stat.value"
        />
        <v-btn icon="$deleteOutline" density="comfortable" variant="text" @click="removeStat(index)" />
      </div>
      <v-btn variant="text" prepend-icon="$plus" @click="addStat">
        {{ $t('divercity.form.addStatistic') }}
      </v-btn>
    </div>

    <v-btn color="main-red" class="mt-4" :loading="isSubmittingHighlight" @click="submitHighlight">
      {{ $t('forms.save') }}
    </v-btn>
  </div>
</template>

<script setup lang="ts">
import ImagesLoader from '@/components/forms/ImagesLoader.vue'
import TextEditor from '@/components/forms/TextEditor.vue'
import type { ContentImageFromUserFile } from '@/models/interfaces/ContentImage'
import type { BaseMediaObject } from '@/models/interfaces/object/MediaObject'
import type { SpaceStatistic, SpaceHighlightSubmission, Space } from '@/models/interfaces/divercity/Space'
import { SpacesService } from '@/services/divercity/SpacesService'
import FileUploader from '@/services/files/FileUploader'
import { useSpacesStore } from '@/stores/divercity/spacesStore'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { addNotification } from '@/services/notifications/NotificationService'
import { i18n } from '@/plugins/i18n'
import { computed, onMounted, ref } from 'vue'
import { transformSymfonyRelationToIRIs } from '@/services/utils/UtilsService'
import { watch } from 'vue'


const spacesStore = useSpacesStore()
const space = computed(() => spacesStore.mainSpace)
const descriptionError = ref(false)
const form = ref({
  name: '',
  description: '',
  maxCapacity: 0
})

const existingPhotos = ref<BaseMediaObject[]>([])
const photosToUpload = ref<ContentImageFromUserFile[]>([])

function handlePhotosUpdate(list: any) {
  photosToUpload.value = list.selectedFiles
  existingPhotos.value = list.existingImages
}

const currentHighlight = computed(() => {
  if (!space.value?.highlights?.length) return null
  return [...space.value.highlights].sort((a, b) => b.year - a.year)[0]
})

const highlightForm = ref<{ year: number; statistics: SpaceStatistic[] }>({
  year: new Date().getFullYear(),
  statistics: []
})
const reportFile = ref<File | null>(null)

watch(
  space,
  (newSpace) => {
    if (newSpace) {
      form.value.name = newSpace.name
      form.value.description = newSpace.description
      form.value.maxCapacity = newSpace.maxCapacity
      existingPhotos.value = newSpace.photos
    }
  },
  { immediate: true }
)

watch(
  currentHighlight,
  (newHighlight) => {
    if (newHighlight) {
      highlightForm.value.year = newHighlight.year
      highlightForm.value.statistics = newHighlight.statistics.length
        ? [...newHighlight.statistics]
        : []
    }
  },
  { immediate: true }
)

function addStat() {
  highlightForm.value.statistics.push({ label: '', value: '', position: highlightForm.value.statistics.length })
}

function removeStat(index: number) {
  highlightForm.value.statistics.splice(index, 1)
}

const isSubmittingSpace = ref(false)
async function submitSpace() {
  if (!space.value) return
  isSubmittingSpace.value = true
  try {
    const uploadedPhotos = await Promise.all(
      photosToUpload.value.map((img) => FileUploader.uploadMedia(img.file))
    )
   const payload = transformSymfonyRelationToIRIs<Partial<Space>>({
      name: form.value.name,
      description: form.value.description,
      maxCapacity: form.value.maxCapacity,
      photos: [...existingPhotos.value, ...uploadedPhotos]
    })
    await SpacesService.patchSpace(space.value.id, payload)
    await spacesStore.getMainSpace()
    addNotification(i18n.t('divercity.form.submitSuccess'), NotificationType.SUCCESS)
  } catch (error) {
    addNotification(i18n.t('divercity.form.submitError'), NotificationType.ERROR, error as string)
  }
  isSubmittingSpace.value = false
}

const isSubmittingHighlight = ref(false)
async function submitHighlight() {
  if (!space.value) return
  isSubmittingHighlight.value = true
  try {
    let reportIri: string | undefined
    if (reportFile.value) {
      const uploaded = await FileUploader.uploadFile(reportFile.value)
      reportIri = uploaded['@id']
    }

    const payload: SpaceHighlightSubmission = {
      year: highlightForm.value.year,
      statistics: highlightForm.value.statistics,
      ...(reportIri ? { report: reportIri } : {})
    }

    if (currentHighlight.value && currentHighlight.value.year === highlightForm.value.year) {
      await SpacesService.patchHighlight(currentHighlight.value.id, payload)
    } else {
      await SpacesService.postHighlight({ ...payload, space: `/api/spaces/${space.value.id}` })
    }

    await spacesStore.getMainSpace()
    addNotification(i18n.t('divercity.form.submitSuccess'), NotificationType.SUCCESS)
  } catch (error) {
    addNotification(i18n.t('divercity.form.submitError'), NotificationType.ERROR, error as string)
  }
  isSubmittingHighlight.value = false
}
</script>

<style lang="scss" scoped>
.SpaceManagementPanel {
  padding: 2rem;
  background: white;

  &__title {
    font-size: $font-size-h3;
    margin-bottom: 1.5rem;
  }

  &__subtitle {
    font-size: $font-size-h4;
    margin-bottom: 1rem;
  }

  &__statRow {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    margin-bottom: 0.5rem;
  }
}
</style>