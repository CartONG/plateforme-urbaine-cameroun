<template>
  <Modal :title="$t('adminBoundaries.mapViewer')" show fit-to-content @close="$emit('close')">
    <template #content>
      <div id="adminBoundariesViewer"></div>
      <div class="adminBoundariesViewer__legend">
        <div class="adminBoundariesViewer__legendItem">
          <div class="adminBoundariesViewer__legendColor" style="border: 2px solid #a98467"></div>
          <span>{{ $t('adminBoundaries.admin1') }}</span>
        </div>
        <div class="adminBoundariesViewer__legendItem">
          <div class="adminBoundariesViewer__legendColor" style="border: 2px solid #adc178"></div>
          <span>{{ $t('adminBoundaries.admin2') }}</span>
        </div>
        <div class="adminBoundariesViewer__legendItem">
          <div class="adminBoundariesViewer__legendColor" style="border: 2px solid #fca311"></div>
          <span>{{ $t('adminBoundaries.admin3') }}</span>
        </div>
      </div>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import maplibregl from 'maplibre-gl'
import 'maplibre-gl/dist/maplibre-gl.css'
import Modal from '@/components/global/Modal.vue'
import { AdminBoundariesService } from '@/services/adminBoundaries/AdminBoundariesService'
import type { Project } from '@/models/interfaces/Project'
import type { Actor } from '@/models/interfaces/Actor'

const props = defineProps<{
  entity: Actor | Project
}>()

let mapViewer: maplibregl.Map | null = null

onMounted(() => {
  initMap()
})

interface AdminBoundariesConfig {
  list: any
  key: string
  color: string
  nameField: string
}

function initMap() {
  const apiKey = import.meta.env.VITE_MAPTILER_API_KEY
  mapViewer = new maplibregl.Map({
    container: 'adminBoundariesViewer',
    style: `https://api.maptiler.com/maps/dataviz-light/style.json?key=${apiKey}`,
    center: [12.3547, 7.3697],
    zoom: 5,
    attributionControl: false
  })

  mapViewer.dragRotate.disable()
  mapViewer.touchZoomRotate.disableRotation()

  mapViewer.on('load', addAdminLayers)
}

function addAdminLayers() {
  const adminLayersConfigs: AdminBoundariesConfig[] = [
    { list: props.entity.admin1List, key: 'admin1', color: '#a98467', nameField: 'adm1Name' },
    { list: props.entity.admin2List, key: 'admin2', color: '#adc178', nameField: 'adm2Name' },
    { list: props.entity.admin3List, key: 'admin3', color: '#fca311', nameField: 'adm3Name' }
  ]

  adminLayersConfigs.forEach((config) => addGeoJsonLayer(config))
}

function addGeoJsonLayer(config: AdminBoundariesConfig) {
  if (!config.list) return

  const geojson = AdminBoundariesService.getGeoJsonfromAdminBoundaries(config.list)

  mapViewer?.addSource(config.key, {
    type: 'geojson',
    data: geojson as any
  })

  mapViewer?.addLayer({
    id: config.key,
    type: 'line',
    source: config.key,
    layout: {
      'line-join': 'round',
      'line-cap': 'round'
    },
    paint: {
      'line-color': config.color,
      'line-width': 2
    }
  })

  mapViewer?.addLayer({
    id: `${config.key}-label`,
    type: 'symbol',
    source: config.key,
    layout: {
      'text-field': ['get', config.nameField],
      'text-font': ['Open Sans Bold', 'Arial Unicode MS Bold'],
      'text-size': 16,
      'text-offset': [0, 0.6],
      'text-anchor': 'top'
    },
    paint: {
      'text-color': config.color
    }
  })
}
</script>

<style lang="scss">
#adminBoundariesViewer {
  height: 70vh;
  width: 80vw;
}

.adminBoundariesViewer__legend {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  padding: 10px;
}

.adminBoundariesViewer__legendItem {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-right: 10px;
}

.adminBoundariesViewer__legendColor {
  width: 20px;
  height: 20px;
  border: 1px solid #000;
  margin-bottom: 5px;
}

.legend span {
  font-size: 14px;
}
</style>
