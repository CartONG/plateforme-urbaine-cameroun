import type { Atlas } from '@/models/interfaces/Atlas'
import type { AtlasMap } from '@/models/interfaces/map/AtlasMap'
import type { AtlasLayer } from '@/models/interfaces/map/Layer'
import { QgisMapMaplibreService } from '../qgisMap/QgisMapMaplibreService'
import { apiClient } from '@/plugins/axios/api'
import { fetchImageAsBase64 } from '../utils/UtilsService'
import type { AtlasStoreType, MyMapStoreType } from '@/models/interfaces/Stores'

export class AtlasMapService {
  static qgisServerURL = import.meta.env.VITE_QGIS_SERVER_URL
  static mapStore: MyMapStoreType | null = null
  static atlasStore: AtlasStoreType | null = null

  static async initAtlasLayers(
    mapStore: MyMapStoreType,
    atlasStore: AtlasStoreType
  ): Promise<void> {
    this.mapStore = mapStore
    this.atlasStore = atlasStore
    await this.atlasStore.getAll()
    for (const atlas of this.atlasStore.atlasList) {
      const atlasLayers = await this.setAtlasLayers(atlas)
      this.mapStore.atlasMaps.push(...atlasLayers)
    }
  }

  static async setAtlasLayers(atlas: Atlas): Promise<AtlasMap[]> {
    const atlasMaps: AtlasMap[] = []

    for (const map of atlas.maps) {
      let isMainLayerShown = false
      const activeSubLayers: string[] = []

      if (this.mapStore?.deserializedMapState) {
        isMainLayerShown = !!this.mapStore.deserializedMapState.layers.atlasMaps?.some(
          (atlasMap) => atlasMap.id === map['@id']
        )
        if (isMainLayerShown) {
          const atlasMap = this.mapStore.deserializedMapState.layers.atlasMaps?.find(
            (atlasMap) => atlasMap.id === map['@id']
          )
          if (atlasMap) {
            activeSubLayers.push(...atlasMap.subLayers)
          }
        }
      }

      const mainLayer: AtlasLayer = {
        id: map['@id'],
        name: map.name,
        isShown: isMainLayerShown,
        icon: map.logo?.contentUrl || '',
        opacity: 100,
        qgisMapType: map.qgisMapType,
        atlasGroup: atlas.atlasGroup
      }

      // Initialisation of sublayers with a placeholder for icons
      const subLayers: AtlasLayer[] =
        map.qgisProject.layers?.map((item) => ({
          id: item,
          name: item,
          isShown: activeSubLayers.includes(item),
          icon: '',
          opacity: 100,
          mapOrder: 0, //Used for change layer order in Qgis Server requests, updated from the legend component
          atlasGroup: atlas.atlasGroup
        })) ?? []

      atlasMaps.push({
        id: map['@id'],
        mainLayer: mainLayer,
        subLayers: subLayers,
        qgisProjectName: map.qgisProject.name,
        atlasId: atlas['@id'],
        needsToBeVisualiseAsPlainImageInsteadOfWMS: map.needsToBeVisualiseAsPlainImageInsteadOfWMS
      })

      // Loading sublayers icons in parallel
      ;(async () => {
        for (const subLayer of subLayers) {
          subLayer.icon =
            (await this.getSubLayerIcon(subLayer.id as string, map.qgisProject.name)) ||
            subLayer.icon
        }
      })()
    }
    return atlasMaps
  }

  static async getSubLayerIcon(layer: string, qgisProjectName: string): Promise<string> {
    const url = `${this.qgisServerURL}${qgisProjectName}?SERVICE=WMS&REQUEST=GetLegendGraphic&FORMAT=application/json&LAYER=${layer}`
    const response = await (await apiClient.get(url)).data
    let imageSrc = ''
    if (response.nodes && response.nodes.length > 0) {
      const result = response.nodes[0]
      if (result.icon) imageSrc = 'data:image/png;base64,' + result.icon
      else if (result.symbols && result.symbols.length > 0) {
        const url = `${this.qgisServerURL}${qgisProjectName}?SERVICE=WMS&REQUEST=GetLegendGraphic&FORMAT=image/png&LAYER=${layer}`
        imageSrc = (await fetchImageAsBase64(url)) as string
      }
    }

    return imageSrc
  }

  static handleAtlasLayersVisibility(
    atlasMaps: AtlasMap[],
    map: maplibregl.Map | null | undefined,
    qgismapId: string,
    alreadyAddedImageSources: string[]
  ) {
    if (map) {
      for (const atlasMap of atlasMaps) {
        if (atlasMap.id !== qgismapId) continue

        const source = atlasMap.id
        let keepSource = true
        const refreshSource = atlasMap.needsToBeVisualiseAsPlainImageInsteadOfWMS
        const sourceExists = map.getSource(source)
        const layersToAdd = []

        if (atlasMap.mainLayer.isShown || atlasMap.subLayers.some((layer) => layer.isShown)) {
          layersToAdd.push(
            ...atlasMap.subLayers.filter((layer) => layer.isShown).map((layer) => layer.name)
          )
        } else {
          if (atlasMap.needsToBeVisualiseAsPlainImageInsteadOfWMS) {
            keepSource = false
          }
        }

        if (!keepSource) {
          QgisMapMaplibreService.removeSourceAndLayers(map, source)
          return
        }
        if (refreshSource && keepSource && sourceExists) {
          QgisMapMaplibreService.updateMapImageSourceCoordinates(
            map,
            source,
            atlasMap.qgisProjectName,
            layersToAdd
          )
        } else {
          if (atlasMap.needsToBeVisualiseAsPlainImageInsteadOfWMS) {
            QgisMapMaplibreService.addImageSourceAndLayers(
              map,
              source,
              atlasMap.qgisProjectName,
              layersToAdd,
              !alreadyAddedImageSources.includes(source)
            )
          } else {
            QgisMapMaplibreService.addWMSSourceAndLayer(
              map,
              source,
              atlasMap.qgisProjectName,
              layersToAdd
            )
          }
        }
      }
    }
  }
}
