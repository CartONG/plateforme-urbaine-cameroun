<template>
  <InfoCard id="test" typeLabel="test">
    <template #content>
      <div class="InfoCard__title flex-row">
        <span>
          {{ features[visibleLayer].name }}
          <v-icon
            icon="mdi:mdi-chevron-left"
            @click="decreaseVisibleLayer"
            :color="visibleLayer > 0 ? 'main-blue' : 'main-grey'"
          />
          <v-icon
            icon="mdi:mdi-chevron-right"
            @click="increaseVisibleLayer"
            :color="visibleLayer < features.length - 1 ? 'main-blue' : 'main-grey'"
          />
        </span>
      </div>

      <div class="InfoCard__subTitle flex-row mt-3">
        <span>
          {{ features[visibleLayer].data[visibleSubLayer][0] }}
          <v-icon
            icon="mdi:mdi-chevron-left"
            :color="visibleSubLayer > 0 ? 'main-blue' : 'main-grey'"
            @click="decreaseVisibleSubLayer"
          />
          <v-icon
            icon="mdi:mdi-chevron-right"
            :color="
              visibleSubLayer < features[visibleLayer].data.length - 1 ? 'main-blue' : 'main-grey'
            "
            @click="increaseVisibleSubLayer"
          />
        </span>
      </div>

      <v-table density="compact" class="mt-3">
        <thead>
          <tr>
            <th class="font-weight-bold">{{ $t('qgisQuery.attribute') }}</th>
            <th class="font-weight-bold">{{ $t('qgisQuery.value') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(value, key) in features[visibleLayer].data[visibleSubLayer][1][
              visibleFeature
            ] as QGISFeatureAttributes"
            :key="value"
          >
            <td>{{ key }}</td>
            <td>{{ value }}</td>
          </tr>
        </tbody>
      </v-table>
      <div class="d-flex justify-center">
        <v-icon
          icon="mdi:mdi-chevron-left"
          :color="visibleFeature > 0 ? 'main-blue' : 'main-grey'"
          @click="decreaseVisibleFeature"
        />
        <v-icon
          icon="mdi:mdi-chevron-right"
          :color="
            visibleFeature < features[visibleLayer].data[visibleSubLayer][1].length - 1
              ? 'main-blue'
              : 'main-grey'
          "
          @click="increaseVisibleFeature"
        />
      </div>
    </template>
  </InfoCard>
</template>

<script setup lang="ts">
import InfoCard from '@/components/global/InfoCard.vue'
import type {
  FilteredQGISLayerFeatures,
  QGISFeatureAttributes
} from '@/models/interfaces/map/AtlasMap'
import { ref } from 'vue'

const props = defineProps<{
  features: FilteredQGISLayerFeatures[]
}>()

const visibleLayer = ref(0)
function increaseVisibleLayer() {
  if (visibleLayer.value < props.features.length - 1) {
    visibleLayer.value++
    visibleSubLayer.value = 0
    visibleFeature.value = 0
  }
}
function decreaseVisibleLayer() {
  if (visibleLayer.value > 0) {
    visibleLayer.value--
    visibleSubLayer.value = 0
    visibleFeature.value = 0
  }
}

const visibleSubLayer = ref(0)
function increaseVisibleSubLayer() {
  if (visibleSubLayer.value < props.features[visibleLayer.value].data.length - 1) {
    visibleSubLayer.value++
    visibleFeature.value = 0
  }
}
function decreaseVisibleSubLayer() {
  if (visibleSubLayer.value > 0) {
    visibleSubLayer.value--
    visibleFeature.value = 0
  }
}

const visibleFeature = ref(0)
function increaseVisibleFeature() {
  if (
    visibleFeature.value <
    props.features[visibleLayer.value].data[visibleSubLayer.value][1].length - 1
  ) {
    visibleFeature.value++
  }
}
function decreaseVisibleFeature() {
  if (visibleFeature.value > 0) {
    visibleFeature.value--
  }
}
</script>
