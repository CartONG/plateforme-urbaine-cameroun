<template>
  <div class="MapScale maplibregl-ctrl maplibregl-ctrl-group">{{ scale }}</div>
</template>
<script setup lang="ts">
import { useMyMapStore } from '@/stores/myMapStore'
import { computed, ref, watch, type Ref } from 'vue'
const mapStore = useMyMapStore()
const map = computed(() => mapStore.myMap?.map)
const zoomLevel = ref(0)
const mapCenter: Ref<maplibregl.LngLat | null> = ref(null)
watch(
  () => map.value,
  () => {
    if (map.value) {
      map?.value?.on('zoomend', () => {
        zoomLevel.value = map!.value!.getZoom()
        mapCenter.value = map!.value!.getCenter()
      })
    }
  },
  { immediate: true }
)

const scale = computed(() => {
  if (!map.value) return ''
  if (!mapCenter.value) return ''

  // This is the math formula to calculate the scale from a latitude and a zoom level and a device pixel ratio
  // This formula seems based for 256x256 tiles
  const dpi = 96 * devicePixelRatio
  const metersPerPixel =
    (156543.03 * Math.cos((mapCenter.value!.lat * Math.PI) / 180)) / Math.pow(2, zoomLevel.value)

  const pixelsPerCm = dpi / 2.54
  const groundDistanceMeters = metersPerPixel * pixelsPerCm
  let scaleDenominator: number | string = groundDistanceMeters * 100
  // Adapt formula for 512x512 tiles if needed
  if (mapStore.tileSize === 512) {
    scaleDenominator = scaleDenominator / 2
  }

  scaleDenominator = roundScale(scaleDenominator)
  // eslint-disable-next-line vue/no-side-effects-in-computed-properties
  mapStore.scaleDenominator = scaleDenominator

  return `1/${scaleDenominator.toLocaleString('fr-FR')}ème`
})

function roundScale(scale: number) {
  if (scale > 1000000) return Math.round(scale / 1000000) * 1000000
  if (scale > 1000) return Math.round(scale / 1000) * 1000
  return scale
}
</script>

<style scoped>
.MapScale {
  background-color: white !important;
  padding: 0.5rem 1rem;
  display: flex;
  align-items: center;
}
</style>
