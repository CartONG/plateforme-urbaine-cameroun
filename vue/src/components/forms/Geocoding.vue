<template>
  <v-autocomplete
    class="Geocoding"
    density="compact"
    featureType="city"
    variant="outlined"
    :placeholder="placeholder"
    :no-data-text="$t('geocoding.noData')"
    :items="geocodingItems"
    :clearable="true"
    :item-value="(val) => val"
    :item-title="(val) => val.osmName"
    v-model="osmData"
    @update:search="(e) => (searchQuery = e)"
    @click:clear="(e) => (searchQuery = '')"
    :error-messages="errorMessages"
  >
    <template v-slot:prepend-inner>
      <v-icon :icon="icon" color="main-blue" class="opacity-100"></v-icon>
    </template>
    <template v-slot:append-inner>
      <v-fade-transition>
        <v-progress-circular
          v-if="isLoading"
          color="#888"
          :width="2"
          :size="20"
          indeterminate
        ></v-progress-circular>
      </v-fade-transition>
    </template>
  </v-autocomplete>
</template>

<script setup lang="ts">
import type { Ref } from 'vue'
import { computed, ref, watch } from 'vue'
import GeocodingService from '@/services/map/GeocodingService'
import { debounce } from '@/services/utils/UtilsService'
import { NominatimSearchType } from '@/models/enums/geo/NominatimSearchType'
import type { GeocodingItem } from '@/models/interfaces/geo/GeocodingItem'
import type { OsmData } from '@/models/interfaces/geo/OsmData'
import { i18n } from '@/plugins/i18n'

const osmData = defineModel<OsmData | null>({
  default: null
})

const props = withDefaults(
  defineProps<{
    searchType: NominatimSearchType
    icon?: string
    geometryDetails?: boolean
    placeholder?: string
    errorMessages?: string
  }>(),
  {
    searchType: NominatimSearchType.FREE,
    icon: 'mdi-map-marker-outline',
    geometryDetails: false,
    placeholder: i18n.t('geocoding.placeholder')
  }
)

watch(
  () => osmData.value,
  async () => {
    if (osmData.value && props.geometryDetails) {
      osmData.value = await GeocodingService.getBbox(osmData.value)
    }
  }
)

const searchQuery = ref('')
const features: Ref<GeocodingItem[]> = ref([])
const isLoading = ref(false)

const geocodingItems = computed(() => {
  return features.value.length > 0 ? features.value : []
})

const update = debounce(async () => {
  features.value = await GeocodingService.forwardGeocode(searchQuery.value, props.searchType)
  isLoading.value = false
}, 250)

watch(
  () => searchQuery.value,
  async () => {
    if (searchQuery.value) {
      isLoading.value = true
      update()
    }
  }
)
</script>
