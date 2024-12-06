import type { OsmType } from '@/models/enums/geo/OsmType'
import type { OsmId } from './OsmId'

export interface GeoData {
  name: string
  latitude: number
  longitude: number
  osmId: OsmId
  osmType: OsmType
  coords: {
    lng: number
    lat: number
  }
  address: string
}
