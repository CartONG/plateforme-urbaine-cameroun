import { NotificationType } from '@/models/enums/app/NotificationType'
import { addNotification } from '../notifications/NotificationService'
import { i18n } from '@/plugins/i18n'
import type { LngLat } from 'maplibre-gl'
import type { FeatureAttributes, LayerFeatures } from '@/models/interfaces/map/AtlasMap'

export class QgisMapMaplibreService {
  static qgisServerURL = import.meta.env.VITE_QGIS_SERVER_URL
  // Get the URL of a plain image covering the map for Qgis projects not being visualised as WMS
  static getMapAsImageURL(map: maplibregl.Map, qgisProjectName: string, layers: string[]): string {
    if (!map || !qgisProjectName || !layers) return ''
    const width = map.getCanvas().width
    const height = map.getCanvas().height

    const bounds = map.getBounds()
    const southWest = bounds.getSouthWest()
    const northEast = bounds.getNorthEast()
    const bbox3857 = [southWest.lat, southWest.lng, northEast.lat, northEast.lng]
    return `${this.qgisServerURL}${qgisProjectName}?SERVICE=WMS&VERSION=1.3.0&LAYERS=${layers.join(',')}&REQUEST=GetMap&FORMAT=image/png&TRANSPARENT=true&CRS=EPSG:4326&TILED=false&DPI=72&WIDTH=${width}&HEIGHT=${height}&BBOX=${bbox3857}`
  }

  // Get the coordinates of the map in order to place a map image on the Maplibre map
  static getMapAsImageCoordinates(
    map: maplibregl.Map
  ): [[number, number], [number, number], [number, number], [number, number]] {
    if (!map)
      return [
        [0, 0],
        [0, 0],
        [0, 0],
        [0, 0]
      ]
    return [
      [map.getBounds().getWest(), map.getBounds().getNorth()],
      [map.getBounds().getEast(), map.getBounds().getNorth()],
      [map.getBounds().getEast(), map.getBounds().getSouth()],
      [map.getBounds().getWest(), map.getBounds().getSouth()]
    ]
  }

  // Called when the map moved in order to actualise the coordinates and the content of the map image
  static updateMapImageSourceCoordinates(
    map: maplibregl.Map,
    source: string,
    qgisProjectName: string,
    layers: string[]
  ): void {
    if (!map || !source || !qgisProjectName || !layers) return
    const maplibreSource = map.getSource(source) as maplibregl.ImageSource
    if (!maplibreSource) return
    maplibreSource.updateImage({
      url: this.getMapAsImageURL(map, qgisProjectName, layers),
      coordinates: this.getMapAsImageCoordinates(map)
    })
  }

  // An image source only have one layer as it's a raster source
  static addImageSourceAndLayers(
    map: maplibregl.Map,
    sourceName: string,
    qgisProjectName: string,
    layers: string[],
    firstAdd = true
  ): void {
    if (!map || !sourceName || !qgisProjectName || !layers) return
    if (map.getLayer(sourceName)) map.removeLayer(sourceName)
    if (map.getSource(sourceName)) map.removeSource(sourceName)

    map?.addSource(sourceName, {
      type: 'image',
      url: this.getMapAsImageURL(map, qgisProjectName, layers),
      coordinates: this.getMapAsImageCoordinates(map)
    })

    map?.addLayer({
      id: sourceName,
      type: 'raster',
      source: sourceName,
      paint: {
        'raster-fade-duration': 0
      },
      metadata: { isPersistent: true }
    })

    // Check if the layer has been already added to the map
    // if not the event will be triggered as much time as the layer has been added to the map
    if (firstAdd) {
      map?.on('moveend', () => {
        try {
          QgisMapMaplibreService.updateMapImageSourceCoordinates(
            map as maplibregl.Map,
            sourceName,
            qgisProjectName,
            layers
          )
        } catch (error) {
          console.error('Error updating map image source coordinates', error)
        }
      })
    }
  }

  // A WMS source only have one layer as it's a raster source
  static addWMSSourceAndLayer(
    map: maplibregl.Map,
    sourceName: string,
    qgisProjectName: string,
    layers: string[]
  ): void {
    if (!map || !sourceName) return
    if (map.getLayer(sourceName)) map.removeLayer(sourceName)
    if (map.getSource(sourceName)) map.removeSource(sourceName)

    map?.addSource(sourceName, {
      type: 'raster',
      tiles: [
        `${window.location.origin}${this.qgisServerURL}/${qgisProjectName}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.3.0&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layers.join(',')}`
      ],
      tileSize: 256
    })
    map.addLayer({
      id: sourceName,
      type: 'raster',
      source: sourceName,
      paint: {},
      metadata: { isPersistent: true }
    })
  }

  static removeSourceAndLayers(map: maplibregl.Map, sourceName: string) {
    if (!map || !sourceName) return
    map.removeLayer(sourceName)
    map.removeSource(sourceName)
  }

  static async getData(qgisProjectName: string, layers: string[]) {
    try {
      const url = `${window.location.origin}${this.qgisServerURL}/${qgisProjectName}?service=WFS&VERSION=1.1.0&request=GetFeature&srs=EPSG:4326&TYPENAME=${layers.join(',')}&OUTPUTFORMAT=geojson`
      const response = await fetch(url)
      if (!response.ok) throw new Error(`Error ${response.status}`)
      const geojson = await response.json()
      const geojsonStr = JSON.stringify(geojson, null, 2)
      const blob = new Blob([geojsonStr], { type: 'application/json' })
      const link = document.createElement('a')
      link.href = URL.createObjectURL(blob)
      link.download = layers.join('_') + '.geojson'
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
      URL.revokeObjectURL(link.href)
    } catch (error) {
      addNotification(i18n.t('myMap.atlases.errorFetchingData'), NotificationType.ERROR)
      console.error('Error fetching data', error)
      return null
    }
  }

  static async getFeatureInfo(
    map: maplibregl.Map,
    coords: LngLat,
    qgisProjectName: string,
    layers: string[]
  ): Promise<void> {
    try {
      const width = map.getCanvas().width
      const height = map.getCanvas().height
      const bounds = map.getBounds()
      const southWest = bounds.getSouthWest()
      const northEast = bounds.getNorthEast()
      const bbox3857 = [southWest.lat, southWest.lng, northEast.lat, northEast.lng]
      const x = Math.round(
        ((coords.lng - bounds.getWest()) / (bounds.getEast() - bounds.getWest())) * width
      )
      const y = Math.round(
        ((bounds.getNorth() - coords.lat) / (bounds.getNorth() - bounds.getSouth())) * height
      )
      const url = `${window.location.origin}${this.qgisServerURL}/${qgisProjectName}?service=WMS&VERSION=1.3.0&request=GetFeatureInfo&SRS=EPSG:4326&QUERY_LAYERS=${layers.join(',')}&WIDTH=${width}&HEIGHT=${height}&BBOX=${bbox3857}&I=${x}&J=${y}&&OutputFormat=application/json`
      const response = await fetch(url)
      if (!response.ok) throw new Error(`Error ${response.status}`)
      const result = await response.text()
      const parsedData: LayerFeatures = this.parseGetFeatureInfo(result)
      console.log(parsedData)
      return Promise.resolve()
    } catch (error) {
      Promise.reject(error)
    }
  }

  static parseGetFeatureInfo(responseText: string): LayerFeatures {
    const layers: LayerFeatures = {}
    let currentLayer: string | null = null
    let currentFeature: FeatureAttributes | null = null

    responseText.split('\n').forEach((line) => {
      line = line.trim()

      const matchLayer = line.match(/^Layer '(.*?)'/)
      if (matchLayer) {
        currentLayer = matchLayer[1]
        layers[currentLayer] = []
        return
      }

      const matchFeature = line.match(/^Feature (\d+)/)
      if (matchFeature) {
        currentFeature = {}
        if (currentLayer) {
          layers[currentLayer].push(currentFeature)
        }
        return
      }
      const matchAttr = line.match(/^([\w\d_]+)\s*=\s*'?(.*?)'?\s*$/)
      if (matchAttr && currentFeature) {
        const [, key, value] = matchAttr
        currentFeature[key] = value === 'NULL' ? null : value
      }
    })

    return layers
  }
}
