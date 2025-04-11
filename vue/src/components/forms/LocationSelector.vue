<template>
  <div class="LocationSelector">
    <div class="Form__fieldCtn">
      <label class="Form__label">{{ $t('inputs.locationSelector.searchAddress') }}</label>
      <Geocoding
        :search-type="NominatimSearchType.FREE"
        :osm-type="OsmType.NODE"
        v-model="geoData"
        :error-messages="errorMessages"
      />
    </div>
    <v-divider>{{ $t('forms.or') }}</v-divider>
    <div class="Form__fieldCtn">
      <label class="Form__label">{{ $t('inputs.locationSelector.enterGpsCoords') }}</label>
      <CoordinatesSelector
        v-model:lat="lat"
        v-model:lng="lng"
        @update-coords="resetGeodata"
        :error-message="errorMessages"
      />
    </div>
    <span v-if="errorMessage">{{ errorMessage }}</span>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref, watch, type ModelRef } from 'vue'
import Geocoding from '@/components/forms/Geocoding.vue'
import { NominatimSearchType } from '@/models/enums/geo/NominatimSearchType'
import { OsmType } from '@/models/enums/geo/OsmType'
import CoordinatesSelector from './CoordinatesSelector.vue'
import type { GeoData } from '@/models/interfaces/geo/GeoData'
import type { Ref } from 'vue'
import { computed } from 'vue'
const geoData: ModelRef<GeoData> = defineModel({
  default: {
    osmId: null,
    osmType: null,
    name: null,
    latitude: null,
    longitude: null
  } as GeoData
})

const lat: Ref<number | null | undefined> = ref(null)
const lng: Ref<number | null | undefined> = ref(null)

const props = defineProps<{
  errorMessage?: string
}>()

const errorMessages = computed(() => (props.errorMessage ? ' ' : undefined))

onMounted(async () => {
  updateCoords()
})

watch(
  () => geoData.value,
  () => {
    updateCoords()
  },
  { deep: true }
)

watch(
  () => [lat.value, lng.value],
  () => {
    if (geoData.value) {
      geoData.value = {
        ...geoData.value,
        latitude: lat.value,
        longitude: lng.value
      }
    }
  }
)

const updateCoords = () => {
  if (!geoData.value) {
    return
  }
  lat.value = geoData.value?.latitude ?? geoData.value?.coords?.lat ?? null
  lng.value = geoData.value?.longitude ?? geoData.value?.coords?.lng ?? null
}

const resetGeodata = () => {
  geoData.value.osmId = null
  geoData.value.osmType = null
  geoData.value.name = ''
}
</script>

<style lang="scss">
.LocationSelector {
  display: flex;
  flex-flow: column nowrap;
  gap: 1rem;
}
</style>
