<template>
  <Modal
    :title="qgisMap.name"
    :show="qgisMapStore.isQgisMapVisualiserShown"
    fit-to-content
    @close="qgisMapStore.isQgisMapVisualiserShown = false"
  >
    <template #content>
      <div id="qgisMapViewer"></div>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/global/Modal.vue'
import { useQgisMapStore } from '@/stores/qgisMapStore'
import type { QgisMap } from '@/models/interfaces/QgisMap'
import maplibregl from 'maplibre-gl'
import { onMounted } from 'vue'
import { QgisMapMaplibreService } from '@/services/qgisMap/QgisMapMaplibreService'

const props = defineProps<{
  qgisMap: QgisMap
}>()

const qgisMapStore = useQgisMapStore()
let mapViewer: maplibregl.Map | null = null

onMounted(() => {
  initMap()
})

function initMap() {
  const apiKey = import.meta.env.VITE_MAPTILER_API_KEY
  mapViewer = new maplibregl.Map({
    container: 'qgisMapViewer',
    style: `https://api.maptiler.com/maps/dataviz-light/style.json?key=${apiKey}`,
    center: [12.3547, 7.3697],
    zoom: 5,
    attributionControl: false
  })
  mapViewer.dragRotate.disable()
  mapViewer.touchZoomRotate.disableRotation()
  mapViewer.on('load', () => {
    addSourceAndLayer()
  })
}

function addSourceAndLayer() {
  if (props.qgisMap.needsToBeVisualiseAsPlainImageInsteadOfWMS) {
    QgisMapMaplibreService.addImageSourceAndLayers(
      mapViewer as maplibregl.Map,
      'qgisMap',
      props.qgisMap.qgisProject.name,
      props.qgisMap.qgisProject.layers as string[]
    )
  } else {
    QgisMapMaplibreService.addWMSSourceAndLayer(
      mapViewer as maplibregl.Map,
      'qgisMap',
      props.qgisMap.qgisProject.name,
      props.qgisMap.qgisProject.layers as string[]
    )
  }
}
</script>

<style lang="scss">
#qgisMapViewer {
  height: 70vh;
  width: 80vw;
}
</style>
