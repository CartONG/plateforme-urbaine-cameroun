import type Basemap from '@/models/interfaces/map/Basemap'
import maplibregl from 'maplibre-gl'
import axios from 'axios'
import type { GeoJSONSource, Map } from 'maplibre-gl'
import type PersistentGeoJSON from '@/models/interfaces/map/PersistentGeoJSON'

export default class MapService {
  static getGeojson(data: any[]): PersistentGeoJSON {
    const geojson: any = []
    Array.prototype.forEach.call(data, (item: any) => {
      if (item.geoData?.coords) {
        geojson.push({
          id: item.id,
          properties: {
            id: item.id,
            name: item.name
          },
          geometry: {
            type: 'Point',
            coordinates: [item.geoData.coords.lng, item.geoData.coords.lat]
          }
        })
      }
    })

    return {
      type: 'FeatureCollection',
      features: geojson,
      isPersistent: true
    }
  }

  static getBounds(features: any) {
    const coordinates = features.map((f: any) => f.geometry.coordinates)
    return coordinates.reduce(
      (bounds: any, coord: any) => {
        return bounds.extend(coord)
      },
      new maplibregl.LngLatBounds(coordinates[0], coordinates[0])
    )
  }

  static getStyle(basemap: Basemap) {
    return `${basemap.style}?key=${import.meta.env.VITE_MAPTILER_API_KEY}`
  }

  static async updateStyle(map: Map, basemap: Basemap) {
    // This will get all the layers and sources of your current map
    const layers = map.getStyle().layers
    const sources = map.getStyle().sources

    // Filter them all out to keep only your added layers
    const filteredLayers = layers.filter((l: any) => l?.metadata?.isPersistent)
    const filteredSources: any = {}

    // Filter them all out to keep only your added sources
    Object.keys(sources).forEach((key: string) => {
      if ((sources[key] as any).url) return // Ignore basemap sources
      const source = sources[key]
      filteredSources[key] = source
    })

    axios
      .get(this.getStyle(basemap))
      .then((result: any) => {
        const newStyle = result.data

        // Merge newly fetched layers and sources with the filtered layers and sources
        newStyle.layers = [...newStyle.layers, ...filteredLayers]
        newStyle.sources = { ...newStyle.sources, ...filteredSources }

        map.setStyle(newStyle)
        return Promise.resolve()
      })
      .catch((error) => {
        console.log(error)
        return Promise.reject()
      })
  }

  static setData = (map: maplibregl.Map, sourceName: string, geojson: GeoJSON.GeoJSON) => {
    const source = map.getSource(sourceName.toString()) as GeoJSONSource
    if (source) source.setData(geojson)
  }

  static addSource = (map: maplibregl.Map, sourceName: string, geojson: GeoJSON.GeoJSON) => {
    if (map.getSource(sourceName)) return
    map.addSource(sourceName, {
      type: 'geojson',
      data: geojson
    })
  }

  static addImage = async (map: maplibregl.Map, path: string, name: string) => {
    if (map.hasImage(name)) return
    const image = await map.loadImage(path)
    if (!image) return
    map.addImage(name, image.data)
    return
  }

  static addLayer = async (
    map: maplibregl.Map,
    layerName: string,
    options: { layout: maplibregl.LayerSpecification['layout'] }
  ) => {
    if (map.getLayer(layerName)) return
    map.addLayer({
      id: layerName,
      type: 'symbol',
      source: layerName,
      layout: options.layout,
      metadata: { isPersistent: true } // used to have persistent layers when switching basemaps
    })
  }

  static setLayoutProperty = (
    map: maplibregl.Map,
    layerName: string,
    property: string,
    value: any
  ) => {
    if (map.getLayer(layerName)) {
      map.setLayoutProperty(layerName, property, value)
    }
  }

  static setPaintProperty = (
    map: maplibregl.Map,
    layerName: string,
    property: string,
    value: any
  ) => {
    if (map.getLayer(layerName)) {
      map.setPaintProperty(layerName, property, value)
    }
  }

  static getData = async (map: maplibregl.Map, sourceName: string | number) => {
    const source = map.getSource(sourceName.toString()) as GeoJSONSource
    if (source) return await source.getData()
  }
}

export class IControl {
  _map: any
  _container: any
  control: any

  constructor(control: any) {
    this.control = control
  }

  onAdd(map: any) {
    this._map = map
    this._container = this.control.value.$el
    return this._container
  }
  onRemove() {
    if (this._container.parentNode == null) return
    this._container.parentNode.removeChild(this._container)
    this._map.value = undefined
  }
}
