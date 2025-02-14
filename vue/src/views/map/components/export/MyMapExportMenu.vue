<template>
  <div class="MyMapExport">
    <div class="MyMapExport__title">
      <v-btn
        size="small"
        icon="mdi-arrow-left"
        class="text-dark-grey"
        elevation="4"
        @click="mapStore.isMapExportActive = false"
      ></v-btn>
      <span class="ml-2"> {{ $t('myMap.export.title') }}</span>
    </div>
    <div class="MyMapExport__content">
      <v-form @submit.prevent="submitForm" id="map-export-form" class="Form">
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('myMap.export.form.title.label') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.title.value.value"
            :error-messages="form.title.errorMessage.value"
            :placeholder="$t('myMap.export.form.title.placeholder')"
            @blur="form.title.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('myMap.export.form.description.label') }}</label>
          <v-textarea
            density="compact"
            variant="outlined"
            v-model="form.description.value.value"
            :error-messages="form.description.errorMessage.value"
            :placeholder="$t('myMap.export.form.description.placeholder')"
            @blur="form.description.handleChange"
          />
        </div>
      </v-form>
      <div class="MyMapExport__footer">
        <span class="text-action" @click="mapStore.isMapExportActive = false">{{
          $t('forms.cancel')
        }}</span>
        <v-btn type="submit" form="map-export-form" color="main-red" :loading="isSubmitting">{{
          $t('myMap.export.form.submit')
        }}</v-btn>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { LayerType } from '@/models/enums/geo/LayerType'
import { onInvalidSubmit } from '@/services/forms/FormService'
import { MapExportService } from '@/services/map/MapExportService'
import { fetchImageAsBase64 } from '@/services/utils/UtilsService'
import { useMyMapStore } from '@/stores/myMapStore'
const mapStore = useMyMapStore()
const { form, handleSubmit, isSubmitting } = MapExportService.getExportForm()

const submitForm = handleSubmit(
  async (values) => {
    const legendToPrint = await Promise.all(
      mapStore.legendList.map(async (item) => {
        if (item.layerType === LayerType.APP_LAYER) {
          return {
            ...item,
            icon: await fetchImageAsBase64(item.icon)
          }
        }
        return item
      })
    )

    const logo = await fetchImageAsBase64('/img/Logo.png')
    const footer = await fetchImageAsBase64('/img/pdf_footer.png')
    const response = await fetch('/api/export-map', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        title: values.title,
        description: values.description,
        mapImage: mapStore.mapCanvasToDataUrl,
        legendList: legendToPrint,
        logo: logo,
        footer: footer,
        scaleDenominator: mapStore.scaleDenominator
      })
    })

    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    window.open(url)
  },
  () => onInvalidSubmit
)
</script>
<style>
.MyMapExport {
  display: flex;
  flex-flow: column nowrap;
  height: 100%;
  width: 100%;
  gap: 2rem;
}
.MyMapExport__title {
  display: flex;
  flex-flow: row nowrap;
  align-items: center;
  color: rgb(var(--v-theme-main-blue));
  font-weight: 600;
}
.MyMapExport__content {
  display: flex;
  flex-flow: column nowrap;
  flex-grow: 1;
  height: 100%;
  justify-content: space-between;
}
.MyMapExport__footer {
  display: flex;
  flex-flow: row nowrap;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 0;
  border-top: 1px solid rgb(var(--v-theme-main-grey));
}
</style>
