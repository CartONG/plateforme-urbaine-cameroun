import type { SymfonyRelation } from './SymfonyRelation'

export interface Admin1Boundary extends SymfonyRelation {
  id: number
  adm1Name: string
  adm1Pcode: string
  geometryGeoJson?: string
}

export interface Admin3Boundary extends SymfonyRelation {
  id: number
  adm3Name: string
  adm3Pcode: string
  geometryGeoJson?: string
}
