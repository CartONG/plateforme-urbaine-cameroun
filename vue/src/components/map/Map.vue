<template>
  <div :id="mapId" :small="small" class="Map">
    <ResetMapExtentControl ref="reset-map-extent-control" />
    <slot></slot>
  </div>
</template>
<script setup lang="ts">
import { onMounted, watch, computed, ref, type Ref, useTemplateRef } from 'vue'
import maplibregl, { GeoJSONSource } from 'maplibre-gl'
import 'maplibre-gl/dist/maplibre-gl.css'
import ResetMapExtentControl from '@/components/map/controls/ResetMapExtentControl.vue'
import { useApplicationStore } from '@/stores/applicationStore'
import { IControl } from '@/services/map/MapService'
import cameroonMask from '@/assets/geojsons/mask_cameroun.json'
import { debounce } from '@/services/utils/UtilsService'

const applicationStore = useApplicationStore()
const triggerZoomReset = computed(() => applicationStore.triggerZoomReset)
const map: Ref<maplibregl.Map | null> = ref(null)
const resetMapExtentControl = useTemplateRef('reset-map-extent-control')
const props = withDefaults(
  defineProps<{
    bounds?: maplibregl.LngLatBounds
    small?: boolean
    mapId?: string
  }>(),
  {
    mapId: 'map',
    bounds: () =>
      new maplibregl.LngLatBounds([8.48881554529, 1.72767263428], [16.0128524106, 12.8593962671]),
    small: false
  }
)
const popup = ref(new maplibregl.Popup({ closeOnClick: false }))
const hoveredFeatureId: Ref<string | null> = ref(null)
const activeFeatureId: Ref<string | null> = ref(null)

onMounted(() => {
  const apiKey = import.meta.env.VITE_MAPTILER_API_KEY
  map.value = new maplibregl.Map({
    container: props.mapId,
    style: `https://api.maptiler.com/maps/openstreetmap/style.json?key=${apiKey}`,
    center: [0, 0],
    zoom: 1,
    attributionControl: false
  })

  map.value.dragRotate.disable()
  map.value.touchZoomRotate.disableRotation()

  const nav = new maplibregl.NavigationControl({
    showCompass: false
  })

  map.value.addControl(nav, 'top-right')
  map.value.addControl(new IControl(resetMapExtentControl), 'top-right')
  map.value.addControl(new maplibregl.AttributionControl({ compact: true }), 'bottom-right')
  setBBox()
  map.value.on('load', () => {
    addSource('cameroonMask', cameroonMask as GeoJSON.GeoJSON)
    map.value?.addLayer({
      id: 'cameroonMask',
      type: 'fill',
      source: 'cameroonMask',
      paint: {
        'fill-color': '#000000',
        'fill-opacity': 0.7
      },
      metadata: { isPersistent: true }
    })
  })
})

const removeSource = (sourceName: string) => {
  if (map.value?.getSource(sourceName)) {
    map.value.removeSource(sourceName)
  }
}

const removeLayer = (layerName: string) => {
  if (map.value && map.value?.getLayer(layerName)) {
    map.value.removeLayer(layerName)
  }
}

const setData = (sourceName: string, geojson: GeoJSON.GeoJSON) => {
  const source = map.value?.getSource(sourceName.toString()) as GeoJSONSource
  if (source) source.setData(geojson)
}

const getData = async (sourceName: string | number) => {
  const source = map.value?.getSource(sourceName.toString()) as GeoJSONSource
  if (source) return await source.getData()
}

const addSource = (sourceName: string, geojson: GeoJSON.GeoJSON) => {
  if (map.value?.getSource(sourceName)) return
  map.value?.addSource(sourceName, {
    type: 'geojson',
    data: geojson
  })
}

const setLayoutProperty = (layerName: string, property: string, value: any) => {
  if (map.value?.getLayer(layerName)) {
    map.value?.setLayoutProperty(layerName, property, value)
  }
}

const setPaintProperty = (layerName: string, property: string, value: any) => {
  if (map.value?.getLayer(layerName)) {
    map.value?.setPaintProperty(layerName, property, value)
  }
}

const addLayer = async (
  layerName: string,
  options: { layout: maplibregl.LayerSpecification['layout'] }
) => {
  if (map.value && map.value?.getLayer(layerName)) return
  map.value?.addLayer({
    id: layerName,
    type: 'symbol',
    source: layerName,
    layout: options.layout,
    metadata: { isPersistent: true } // used to have persistent layers when switching basemaps
  })
}

const addImage = async (path: string, name: string) => {
  if (map.value?.hasImage(name)) return
  const image = await map.value?.loadImage(path)
  if (!image) return
  map.value?.addImage(name, image.data)
  return
}

const addPopup = debounce(
  (coordinates: maplibregl.LngLatLike | undefined, popupHtml: any, isComponent = true) => {
    if (map.value == null || coordinates == null) return

    const domContent = isComponent ? popupHtml.$el : popupHtml

    popup.value.setLngLat(coordinates).setDOMContent(domContent).addTo(map.value)

    popup.value.addClassName('show')
    flyTo(coordinates)
    map.value.once('moveend', ensurePopupInView)
    popup.value._onClose = () => {
      activeFeatureId.value = null
      popup.value.removeClassName('show')
    }
  },
  100
)

const ensurePopupInView = () => {
  if (map.value == null || popup.value == null) return
  const popupAnchor = popup.value.getLngLat()
  const mapCanvas = map.value.getCanvas()
  const mapRect = mapCanvas.getBoundingClientRect()
  const popupElement = popup.value._container

  if (!popupAnchor || !popupElement) return

  const popupRect = popupElement.getBoundingClientRect()
  const offset = 60
  // Calculate overflow in pixels
  const overflow = {
    top: mapRect.top - popupRect.top + offset,
    bottom: popupRect.bottom - mapRect.bottom + offset,
    left: mapRect.left - popupRect.left + offset,
    right: popupRect.right - mapRect.right + offset
  }

  // Only move if overflow happens
  let dx = 0,
    dy = 0
  if (overflow.left > 0) dx = -overflow.left
  else if (overflow.right > 0) dx = overflow.right

  if (overflow.top > 0) dy = -overflow.top
  else if (overflow.bottom > 0) dy = overflow.bottom

  if (dx !== 0 || dy !== 0) {
    map.value.panBy([dx, dy], { duration: 300 })
  }
}

const addPopupOnClick = (layerName: string, popupHtml: any, isComponent = true) => {
  if (map.value == null || popupHtml == null) return
  map.value.on('click', layerName, (e: any) => {
    if (map.value == null) return
    activeFeatureId.value = e.features[0]?.properties?.id
    const coordinates = e.features[0].geometry?.coordinates?.slice()
    addPopup(coordinates, popupHtml, isComponent)
    e.preventDefault()
  })
}

const flyTo = (coordinates: maplibregl.LngLatLike, speed = 0.5) => {
  if (map.value == null) return
  map.value.flyTo({
    center: coordinates,
    zoom: map.value.getZoom() > 7 ? map.value.getZoom() : 7,
    speed
  })
}

const listenToHoveredFeature = (layerName: string) => {
  if (map.value == null) return
  map.value.on('mouseenter', layerName, (e: any) => {
    if (map.value == null) return
    map.value.getCanvas().style.cursor = 'pointer'
    hoveredFeatureId.value = e.features[0].properties.id
  })

  map.value.on('mouseleave', layerName, () => {
    if (map.value == null) return
    map.value.getCanvas().style.cursor = ''
    hoveredFeatureId.value = null
  })
}

const setBBox = () => {
  if (!map.value) return
  map.value.fitBounds(props.bounds, { padding: 75 })
}

watch(triggerZoomReset, () => {
  setBBox()
})

defineExpose({
  map,
  activeFeatureId,
  hoveredFeatureId,
  addSource,
  addPopup,
  addPopupOnClick,
  addImage,
  addLayer,
  flyTo,
  listenToHoveredFeature,
  removeLayer,
  removeSource,
  setData,
  getData,
  setLayoutProperty,
  setPaintProperty
})
</script>

<style lang="scss">
.maplibregl-popup {
  transition: opacity 0.15s ease-in-out;
  opacity: 0;
  user-select: none;

  &.show {
    opacity: 1;
    .maplibregl-popup-close-button {
      display: inherit;
    }
  }

  $dim-popup-offset: 2rem;
  &.maplibregl-popup-anchor-right .maplibregl-popup-content {
    transform: translateX(-$dim-popup-offset);
  }
  &.maplibregl-popup-anchor-left .maplibregl-popup-content {
    transform: translateX($dim-popup-offset);
  }
  &.maplibregl-popup-anchor-bottom .maplibregl-popup-content {
    transform: translateY(-$dim-popup-offset);
  }
  &.maplibregl-popup-anchor-top .maplibregl-popup-content {
    transform: translateY($dim-popup-offset);
  }
  &.maplibregl-popup-anchor-top-right .maplibregl-popup-content {
    transform: translate(-$dim-popup-offset, $dim-popup-offset);
  }
  &.maplibregl-popup-anchor-top-left .maplibregl-popup-content {
    transform: translate($dim-popup-offset, $dim-popup-offset);
  }
  &.maplibregl-popup-anchor-bottom-right .maplibregl-popup-content {
    transform: translate(-$dim-popup-offset, -$dim-popup-offset);
  }
  &.maplibregl-popup-anchor-bottom-left .maplibregl-popup-content {
    transform: translate($dim-popup-offset, -$dim-popup-offset);
  }

  .maplibregl-popup-content {
    padding: inherit;
    width: fit-content;
    transform: translateY(-2rem);
  }
  .maplibregl-popup-close-button {
    display: none;
    margin: 0.5rem;
    background: white;
    box-shadow: 0 0.25rem 0.25rem -0.125rem rgba(0, 0, 0, 0.15);
    color: black;
    $dim-map-btn-w: 2rem;
    background-image: url(@/assets/images/icons/map/mdi-close.svg);
    background-position: center;
    width: $dim-map-btn-w;
    height: $dim-map-btn-w;
    border-radius: 50%;
    transition: none;
  }

  .maplibregl-popup-tip {
    display: none;
    pointer-events: none;
  }
}

.Map {
  width: 100%;
  height: 100%;
  min-height: 10rem;
  position: relative;

  --map-margin: 1.5rem;

  .maplibregl-ctrl-top-left,
  .maplibregl-ctrl-bottom-right,
  .maplibregl-ctrl-bottom-left,
  .maplibregl-ctrl-top-right {
    margin: var(--map-margin);
    display: flex;
    flex-flow: column nowrap;
    gap: 0.62rem;
  }

  &[small='true'] {
    --map-margin: 0.5rem;
    min-height: 12rem;

    .maplibregl-ctrl-group button {
      --dim-btn-h: 2rem;
      box-sizing: border-box;
      width: var(--dim-btn-h);
      height: var(--dim-btn-h);
      max-height: var(--dim-btn-h);
      min-height: var(--dim-btn-h);
    }

    .maplibregl-ctrl-attrib.maplibregl-compact {
      margin: 0;
    }
  }

  .maplibregl-ctrl-group {
    display: flex;
    flex-flow: column nowrap;
    gap: 0.62rem;
    background: none;
    box-shadow: none;
    margin: 0;
    transition: all 0.15s ease-in;

    button {
      background: white;
      box-shadow: 0 0.25rem 0.25rem -0.125rem rgba(0, 0, 0, 0.15);
      color: black;
      $dim-map-btn-w: 2.5rem;
      width: $dim-map-btn-w;
      height: $dim-map-btn-w;
      border-radius: 50%;
      border-width: 0;
      position: relative;

      &.maplibregl-ctrl-zoom-in {
        .maplibregl-ctrl-icon {
          background-image: url(@/assets/images/icons/map/mdi-plus.svg);
        }
      }

      &.maplibregl-ctrl-zoom-out {
        .maplibregl-ctrl-icon {
          background-image: url(@/assets/images/icons/map/mdi-minus.svg);
        }
      }
    }
  }
}

.cursor-crosshair {
  cursor: crosshair !important;
}
</style>
