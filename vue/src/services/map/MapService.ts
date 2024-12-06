import maplibregl from 'maplibre-gl'

export default class MapService {
  static getGeojson(data: any[]): any {
    const geojson: any = []
    Array.prototype.forEach.call(data, (item: any) => {
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
    })

    return {
      type: 'FeatureCollection',
      features: geojson
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
