<template>
  <div id="map">
    <ResetMapExtentControl ref="reset-map-extent-control" />
  </div>
</template>
<script setup lang="ts">
import { onMounted, watch, computed, ref, type Ref, useTemplateRef } from 'vue'
import maplibregl from 'maplibre-gl'
import 'maplibre-gl/dist/maplibre-gl.css'
import ResetMapExtentControl from '@/components/map/controls/ResetMapExtentControl.vue'
import { useApplicationStore } from '@/stores/applicationStore'
import { useMyMapStore } from '@/stores/myMapStore'
import { useProjectStore } from '@/stores/projectStore'
import MapService, { IControl } from '@/services/map/MapService'
import cameroonMask from '@/assets/geojsons/mask_cameroun.json'

const applicationStore = useApplicationStore()
const myMapStore = useMyMapStore()
const projectStore = useProjectStore()
const triggerZoomReset = computed(() => applicationStore.triggerZoomReset)
// const map: Ref<maplibregl.Map | null> = ref(null)
const resetMapExtentControl = useTemplateRef('reset-map-extent-control')
const props = withDefaults(
  defineProps<{
    view: 'ProjectView' | 'MyMapView'
    bounds?: maplibregl.LngLatBounds
    toExport?: boolean
  }>(),
  {
    bounds: () =>
      new maplibregl.LngLatBounds([8.48881554529, 1.72767263428], [16.0128524106, 12.8593962671]),
    toExport: false
  }
)
const popup = ref(new maplibregl.Popup({ closeOnClick: false }))
const hoveredFeatureId: Ref<string | null> = ref(null)
const activeFeatureId: Ref<string | null> = ref(null)
let map: maplibregl.Map | null = null

onMounted(() => {
  const apiKey = import.meta.env.VITE_MAPTILER_API_KEY
  map = new maplibregl.Map({
    container: 'map',
    style: `https://api.maptiler.com/maps/openstreetmap/style.json?key=${apiKey}`,
    center: [0, 0],
    zoom: 1,
    attributionControl: false
  })

  if (props.view === 'MyMapView') {
    myMapStore.mapInstance = map
  } else if (props.view === 'ProjectView') {
    projectStore.mapInstance = map
  }

  map.dragRotate.disable()
  map.touchZoomRotate.disableRotation()

  const nav = new maplibregl.NavigationControl({
    showCompass: false
  })

  map.addControl(nav, 'top-right')
  map.addControl(new IControl(resetMapExtentControl), 'top-right')
  map.addControl(new maplibregl.AttributionControl({ compact: true }), 'bottom-right')
  setBBox()
  map.on('load', () => {
    MapService.addSource(map as maplibregl.Map, 'cameroonMask', cameroonMask as GeoJSON.GeoJSON)
    map!.addLayer({
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
  map.on('idle', setMapAsLoaded)
  // map.off('idle', setMapAsLoaded)

  // For an unknown reason, if we try to get the canvas to Data url from outside
  // it returns an empty image. It can be caused by the use of components like the map inside TemplateRef
  // This is a workaround waiting to refactor the use of template ref elements
  map.on('idle', () => {
    if (props.toExport) {
      myMapStore.mapCanvasToDataUrl = map!.getCanvas().toDataURL() as string
    }
  })
})

function setMapAsLoaded() {
  if (props.view === 'MyMapView') {
    console.log('setMapAsLoaded')
    myMapStore.isMapLoaded = true
    map?.off('idle', setMapAsLoaded)
  } else if (props.view === 'ProjectView') {
    // projectStore.isMapLoaded = true
  }
}

// const removeSource = (sourceName: string) => {
//   if (map.value?.getSource(sourceName)) {
//     map.value.removeSource(sourceName)
//   }
// }

// const removeLayer = (layerName: string) => {
//   if (map.value && map.value?.getLayer(layerName)) {
//     map.value.removeLayer(layerName)
//   }
// }

// const setData = (sourceName: string, geojson: GeoJSON.GeoJSON) => {
//   const source = map.value?.getSource(sourceName.toString()) as GeoJSONSource
//   if (source) source.setData(geojson)
// }

// const getData = async (sourceName: string | number) => {
//   const source = map.value?.getSource(sourceName.toString()) as GeoJSONSource
//   if (source) return await source.getData()
// }

// const addSource = (sourceName: string, geojson: GeoJSON.GeoJSON) => {
//   if (map.value?.getSource(sourceName)) return
//   map.value?.addSource(sourceName, {
//     type: 'geojson',
//     data: geojson
//   })
// }

// const setLayoutProperty = (layerName: string, property: string, value: any) => {
//   if (map.value?.getLayer(layerName)) {
//     map.value?.setLayoutProperty(layerName, property, value)
//   }
// }

// const setPaintProperty = (layerName: string, property: string, value: any) => {
//   if (map.value?.getLayer(layerName)) {
//     map.value?.setPaintProperty(layerName, property, value)
//   }
// }

// const addLayer = async (
//   layerName: string,
//   options: { layout: maplibregl.LayerSpecification['layout'] }
// ) => {
//   if (map.value && map.value?.getLayer(layerName)) return
//   map.value?.addLayer({
//     id: layerName,
//     type: 'symbol',
//     source: layerName,
//     layout: options.layout,
//     metadata: { isPersistent: true } // used to have persistent layers when switching basemaps
//   })
// }

// const addImage = async (path: string, name: string) => {
//   if (map.value?.hasImage(name)) return
//   const image = await map.value?.loadImage(path)
//   if (!image) return
//   map.value?.addImage(name, image.data)
//   return
// }

const addPopup = (coordinates: maplibregl.LngLatLike, popupHtml: any, isComponent = true) => {
  if (map === null) return
  flyTo(coordinates)
  console.log('popupHtml', popupHtml)
  popup.value
    .setLngLat(coordinates)
    .setDOMContent(isComponent ? popupHtml.$el : popupHtml)
    .addTo(map)
  popup.value.addClassName('show')
  popup.value._onClose = () => {
    activeFeatureId.value = null
    popup.value.removeClassName('show')
  }
}

const addPopupOnClick = (layerName: string, popupHtml: any, isComponent = true) => {
  if (map === null) return
  map.on('click', layerName, (e: any) => {
    activeFeatureId.value = e.features[0].properties.id
    if (map == null) return
    const coordinates = e.features[0].geometry.coordinates.slice()
    addPopup(coordinates, popupHtml, isComponent)
  })
}

const flyTo = (coordinates: maplibregl.LngLatLike) => {
  if (map === null) return
  map.flyTo({
    center: coordinates,
    zoom: map.getZoom() > 7 ? map.getZoom() : 7,
    speed: 0.5
  })
}

const listenToHoveredFeature = (layerName: string) => {
  if (map == null) return
  map.on('mouseenter', layerName, (e: any) => {
    if (map === null) return
    map.getCanvas().style.cursor = 'pointer'
    hoveredFeatureId.value = e.features[0].properties.id
  })

  map.on('mouseleave', layerName, () => {
    if (map === null) return
    map.getCanvas().style.cursor = ''
    hoveredFeatureId.value = null
  })
}

const setBBox = () => {
  if (!map) return
  map.fitBounds(props.bounds, { padding: 75 })
}

watch(triggerZoomReset, () => {
  setBBox()
})

defineExpose({
  map,
  activeFeatureId,
  hoveredFeatureId,
  addPopup,
  addPopupOnClick,
  listenToHoveredFeature
  // removeLayer,
  // removeSource,
  // getData,
  // setLayoutProperty,
  // setPaintProperty
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

#map {
  width: 100%;
  height: 100%;

  .maplibregl-ctrl-top-left,
  .maplibregl-ctrl-bottom-right,
  .maplibregl-ctrl-bottom-left,
  .maplibregl-ctrl-top-right {
    margin: 1.5rem;
    display: flex;
    flex-flow: column nowrap;
    gap: 0.62rem;
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
</style>
