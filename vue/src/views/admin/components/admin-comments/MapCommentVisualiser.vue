<template>
  <Modal :title="$t('admin.comments.mapTitle')" fit-to-content @close="$emit('close')" show>
    <template #content>
      <div class="mapComment__ctn">
        <div id="mapComment__map"></div>
        <div class="mapComment__text">{{ comment }}</div>
      </div>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/global/Modal.vue'
import maplibregl from 'maplibre-gl'
import 'maplibre-gl/dist/maplibre-gl.css'
import { onMounted } from 'vue'

const props = defineProps<{
  coords: string
  comment: string
}>()

let mapViewer: maplibregl.Map | null = null

onMounted(() => {
  initMap()
})

function initMap() {
  const apiKey = import.meta.env.VITE_MAPTILER_API_KEY
  mapViewer = new maplibregl.Map({
    container: 'mapComment__map',
    style: `https://api.maptiler.com/maps/streets/style.json?key=${apiKey}`,
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
  const coords = props.coords.split(',')
  const lngLat = {
    lng: parseFloat(coords[1]),
    lat: parseFloat(coords[0])
  }
  new maplibregl.Marker().setLngLat(lngLat).addTo(mapViewer as maplibregl.Map)
  mapViewer?.flyTo({
    center: lngLat,
    zoom: 14
  })
}
</script>

<style lang="scss">
.mapComment__ctn {
  height: 70vh;
  max-height: 70vh;
  width: 80vw;
  max-width: 80vw;
}
#mapComment__map {
  height: 80%;
  max-height: 80%;
  width: 100%;
  max-width: 100%;
}
.mapComment__text {
  height: 20%;
  width: 100%;
  display: flex;
  align-items: center;
  flex-wrap: wrap;
}
</style>
