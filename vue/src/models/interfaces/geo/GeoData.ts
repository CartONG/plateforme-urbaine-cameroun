import type { OsmType } from '@/models/enums/geo/OsmType'
import type { OsmId } from './OsmId'

export interface GeoData {
  name: string | null
  osmId: OsmId | null
  osmType: OsmType | null
  latitude?: number | null
  longitude?: number | null
  coords?: {
    lng: number
    lat: number
  }
  address?: string
  bbox?: [number, number, number, number]
}