import type Basemap from '@/models/interfaces/map/Basemap'
import maplibregl from 'maplibre-gl'
import axios from 'axios'
import type { Map } from 'maplibre-gl'
import type PersistentGeoJSON from '@/models/interfaces/map/PersistentGeoJSON'

export default class MapService {
  static getGeojson(data: any[], coordsField?: string): PersistentGeoJSON {
    if (coordsField) {
      console.log('coordsField', coordsField)
    }
    const geojson: any = []
    Array.prototype.forEach.call(data, (item: any) => {
      if (!coordsField) {
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
      } else {
        if (item[coordsField]) {
          const coords = item[coordsField].split(',').map((x: string) => parseFloat(x))
          geojson.push({
            id: item.id,
            properties: {
              id: item.id,
              name: item.name
            },
            geometry: {
              type: 'Point',
              coordinates: [coords[0], coords[1]]
            }
          })
        }
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
