import type { OsmId } from './OsmId'

export interface OsmData {
  osmId: OsmId
  osmType: string
  osmName: string
  coords?: [number, number]
  bbox?: [number, number, number, number]
}
