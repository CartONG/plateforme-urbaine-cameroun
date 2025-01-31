import { StoresList } from '@/models/enums/app/StoresList'
import { defineStore } from 'pinia'
import { reactive, ref, type Ref } from 'vue'
import type { OsmData } from '@/models/interfaces/geo/OsmData'
import type { Layer } from '@/models/interfaces/map/Layer'
import type Map from '@/components/map/Map.vue'
import type { AtlasActive, AtlasMap } from '@/models/interfaces/map/AtlasMap'
import { AtlasMapService } from '@/services/map/AtlasMapService'
import type { AppLayerLegendItem, AtlasLayerLegendItem } from '@/models/interfaces/map/Legend'
import { LayerType } from '@/models/enums/geo/LayerType'
import { LegendService } from '@/services/map/LegendService'
import type { LngLatBounds } from 'maplibre-gl'
import { MapStoreSerializationService } from '@/services/map/MapStoreSerializationService'
import { AppLayersService } from '@/services/map/AppLayersService'
import type { MapState } from '@/models/interfaces/map/MapState'
import { useAtlasStore } from './atlasStore'

export const useMyMapStore = defineStore(StoresList.MY_MAP, () => {
  const myMap: Ref<InstanceType<typeof Map> | undefined> = ref()
  const isRightSidebarShown = ref(true)
  const isLeftSidebarShown = ref(true)
  const mapSearch: Ref<OsmData | null> = ref(null)
  const isMapAlreadyBeenMounted = ref(false)
  const isLayersReorderingAlreadyTriggering = ref(false)

  const actorLayer: Ref<Layer | null> = ref(null)
  const actorSubLayers: Ref<Layer[]> = ref([])
  const resourceLayer: Ref<Layer | null> = ref(null)
  const resourceSubLayers: Ref<Layer[]> = ref([])
  const projectLayer: Ref<Layer | null> = ref(null)
  const projectSubLayers: Ref<Layer[]> = ref([])

  const atlasMaps: Ref<AtlasMap[]> = ref([])
  const activeAtlas: AtlasActive = reactive({
    leftPanel: {
      active: false,
      atlasID: null
    },
    rightPanel: {
      active: false,
      atlasID: null
    }
  })
  let alreadyAddedImageSources: string[] = [] //Used to avoid triggering maplibre event as much time as the layer has been added to the map
  const legendList: Ref<(AppLayerLegendItem | AtlasLayerLegendItem)[]> = ref([])
  const bbox: Ref<LngLatBounds | undefined> = ref(undefined)
  const serializedMapState: Ref<string> = ref('')
  const deserializedMapState: Ref<MapState | null> = ref(null)
  async function initMapLayers() {
    await AppLayersService.initApplicationLayers(useMyMapStore())
    await AtlasMapService.initAtlasLayers(useMyMapStore(), useAtlasStore())
    if (deserializedMapState.value) {
      for (const thematicMap of atlasMaps.value) {
        if (thematicMap.mainLayer.isShown) {
          updateAtlasLayersVisibility(thematicMap.id)
        }
      }
      legendList.value.sort((a, b) => {
        const indexA = deserializedMapState.value!.order.indexOf(a.id)
        const indexB = deserializedMapState.value!.order.indexOf(b.id)
        return indexA - indexB
      })
      updateMapLayersOrder()
    }
    deserializedMapState.value = null
  }

  // Activate a watcher for app layers status
  LegendService.watchAppLayersVisibilityChanges(
    actorLayer,
    projectLayer,
    resourceLayer,
    legendList,
    atlasMaps
  )

  function getSerializedMapState(): string {
    return MapStoreSerializationService.serializeStore(useMyMapStore())
  }

  function deserializeMapState() {
    deserializedMapState.value = MapStoreSerializationService.deserializeStore(
      serializedMapState.value
    )
    serializedMapState.value = ''
  }

  function updateAtlasLayersVisibility(qgismapId: string, updateLegend = true) {
    AtlasMapService.handleAtlasLayersVisibility(
      atlasMaps.value,
      myMap.value?.map,
      qgismapId,
      alreadyAddedImageSources
    )
    alreadyAddedImageSources = [...new Set([...alreadyAddedImageSources, qgismapId])]
    if (updateLegend) {
      LegendService.updateLegendList(qgismapId, LayerType.ATLAS_LAYER, legendList, atlasMaps)
    }
  }

  function updateMapLayersOrder() {
    LegendService.updateLayersOrder(myMap.value?.map as maplibregl.Map, legendList)
  }

  function updateAtlasSubLayersOrder(atlasMapLayer: AtlasLayerLegendItem) {
    LegendService.updateAtlasSubLayersOrder(atlasMapLayer, atlasMaps)
    updateAtlasLayersVisibility(atlasMapLayer.id, false)
  }

  // When a user quit the map and come back, we wait for all the sources/layers to be loaded then we
  // reorder the layers according to the legend
  function setMapLayersOrderOnMapReMount() {
    const map = myMap.value?.map as maplibregl.Map
    if (!map) return
    const sources = map.getStyle().sources
    const numberOfSources = Object.keys(sources).length - 1 //We don't want basemap provider's (Maptiler/Mapbox) source
    const numberOfLegendItems = legendList.value.length
    if (numberOfSources === numberOfLegendItems) {
      if (map.loaded()) {
        updateMapLayersOrder()
      } else {
        map.on('idle', () => {
          // Avoid an infinite loop as layers ordering triggers 'idle' event
          if (isLayersReorderingAlreadyTriggering.value) return
          updateMapLayersOrder()
          isLayersReorderingAlreadyTriggering.value = true
        })
      }
    }
  }

  return {
    isRightSidebarShown,
    isLeftSidebarShown,
    isMapAlreadyBeenMounted,
    isLayersReorderingAlreadyTriggering,
    myMap,
    mapSearch,
    actorLayer,
    actorSubLayers,
    projectLayer,
    projectSubLayers,
    resourceLayer,
    resourceSubLayers,
    atlasMaps,
    activeAtlas,
    initMapLayers,
    updateAtlasLayersVisibility,
    legendList,
    updateMapLayersOrder,
    updateAtlasSubLayersOrder,
    setMapLayersOrderOnMapReMount,
    bbox,
    getSerializedMapState,
    serializedMapState,
    deserializedMapState,
    deserializeMapState
  }
})
