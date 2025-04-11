<template>
  <div class="CoordinatesSelector">
    <div class="CoordinatesSelector__coordsPickerCtn">
      <div class="CoordinatesSelector__coordsCtn">
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('inputs.location.lat') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model.number="lat"
            @update:model-value="isSelecting ? null : $emit('updateCoords')"
            placeholder="4.31375500"
            :error-messages="errorMessage"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('inputs.location.lng') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model.number="lng"
            @update:model-value="isSelecting ? null : $emit('updateCoords')"
            placeholder="12.1018020"
            :error-messages="errorMessage"
          />
        </div>
      </div>
      <v-btn
        variant="text"
        class="CoordinatesSelector__selectBtn"
        density="comfortable"
        :prepend-icon="isSelecting ? 'mdi mdi-crosshairs-gps' : 'mdi mdi-crosshairs'"
        color="main-blue"
        @click="isSelecting = !isSelecting"
      >
        {{ $t('inputs.coordsSelector.buttons.select') }}
      </v-btn>
    </div>
    <Map class="CoordinatesSelector__map" ref="coords-map" :small="true" map-id="coords-map">
      <span class="CoordinatesSelector__moveMap" v-if="isSelecting">{{
        $t('inputs.coordsSelector.moveMap')
      }}</span>
      <v-icon
        v-if="isSelecting"
        size="1.5rem"
        icon="mdi mdi-crosshairs-gps"
        class="CoordinatesSelector__coordsIcon"
        color="main-blue"
      ></v-icon>
      <div class="CoordinatesSelector__coordsValidateBtns" v-if="isSelecting">
        <v-btn :elevation="2" prepend-icon="mdi-check" @click="validate" color="main-blue">{{
          $t('inputs.coordsSelector.buttons.validate')
        }}</v-btn>
        <v-btn :elevation="2" prepend-icon="mdi-close" @click="cancel">{{
          $t('inputs.coordsSelector.buttons.cancel')
        }}</v-btn>
      </div>
    </Map>
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted, ref, watch, type ModelRef } from 'vue'
import Map from '../map/Map.vue'
import { useTemplateRef } from 'vue'
import coordsIcon from '@/assets/images/icons/map/project_icon.png'
import MapService from '@/services/map/MapService'

const lat: ModelRef<number | undefined> = defineModel('lat')
const lng: ModelRef<number | undefined> = defineModel('lng')
type MapType = InstanceType<typeof Map>
const coordsMap = useTemplateRef<MapType>('coords-map')
const emits = defineEmits(['updateCoords'])
const isSelecting = ref(false)
const sources = {
  coords: 'coords'
}
const coordsSourceName = sources.coords,
  coordsLayerName = sources.coords,
  coordsImageName = sources.coords

const initialCoords = { lat: lat.value, lng: lng.value }
const map = computed(() => coordsMap.value?.map)
const geojson = computed(() => MapService.getGeojson([{ geoData: { coords: coords.value } }]))

defineProps<{
  errorMessage?: string
}>()

onMounted(async () => {
  if (map.value == null) return
  map.value.on('load', async () => {
    initSearchedPin()
  })

  map.value.on('moveend', () => {
    if (!isSelecting.value) return
    const center = map.value?.getCenter()
    lat.value = center?.lat
    lng.value = center?.lng
  })
})

watch(
  () => isSelecting.value,
  (newValue) => {
    if (newValue) {
      initialCoords.lat = lat.value
      initialCoords.lng = lng.value
    }
  }
)

watch(
  () => [lat.value, lng.value],
  () => {
    if (!isSelecting.value) {
      refreshCoordsLayer()
    }
  }
)

const cancel = () => {
  isSelecting.value = false
  lat.value = initialCoords.lat
  lng.value = initialCoords.lng
}

const validate = () => {
  isSelecting.value = false
  refreshCoordsLayer()
  emits('updateCoords')
}

const coords = computed(() => {
  if (lat.value == null || lng.value == null) return
  return { lat: lat.value, lng: lng.value }
})

const initSearchedPin = () => {
  setSearchedPinLayer()
}

const setSearchedPinLayer = async () => {
  if (coordsMap.value) {
    coordsMap.value.addSource(coordsSourceName, geojson.value)
    await coordsMap.value.addImage(coordsIcon, coordsImageName)
    const layout: maplibregl.LayerSpecification['layout'] = {
      'icon-image': coordsImageName,
      'icon-size': 0.35
    }
    coordsMap.value.addLayer(coordsLayerName, { layout })
    flyToCoords()
  }
  return
}

const refreshCoordsLayer = async () => {
  if (coordsMap.value) {
    coordsMap.value.setData(sources.coords, geojson.value)
    flyToCoords()
  }
}

const flyToCoords = () => {
  if (coordsMap.value && coords.value) {
    coordsMap.value.flyTo(coords.value, 1)
  }
}
</script>

<style lang="scss">
.CoordinatesSelector {
  display: flex;
  flex-flow: column nowrap;
  gap: 1rem;
  .CoordinatesSelector__coordsPickerCtn {
    display: flex;
    flex-flow: row nowrap;
    gap: 0.5rem;
    align-items: flex-end;

    > * {
      flex: 0 0 auto;
    }

    .CoordinatesSelector__coordsCtn {
      display: flex;
      flex-flow: row nowrap;
      flex: 1 1 auto;
      gap: 1rem;

      > * {
        flex: 1 0 auto;
      }
    }
  }

  .CoordinatesSelector__map {
    height: 15rem;
    border-radius: $dim-radius;
    // border: solid 1px rgb(var(--v-theme-dark-grey));
    box-shadow: 0 0.25rem 0.5rem -0.25rem rgba(0, 0, 0, 0.1);
    .CoordinatesSelector__moveMap {
      position: absolute;
      width: 100%;
      text-align: center;
      pointer-events: none;
      top: 0.5rem;
      z-index: 1;
      display: inline-block;
    }
    .CoordinatesSelector__coordsValidateBtns {
      position: absolute;
      bottom: 0.5rem;
      z-index: 1;
      width: 100%;
      align-items: center;
      justify-content: center;
      display: flex;
      flex-flow: row nowrap;
      gap: 0.5rem;
    }
    .CoordinatesSelector__coordsIcon {
      position: absolute;
      inset: 0;
      z-index: 1;
      margin: auto;
    }
  }
}
</style>
