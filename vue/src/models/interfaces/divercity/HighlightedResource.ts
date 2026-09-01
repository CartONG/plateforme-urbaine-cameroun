import type { SymfonyRelation } from '@/models/interfaces/SymfonyRelation'
import type { BaseMediaObject } from '@/models/interfaces/object/MediaObject'

export interface HighlightedResource extends SymfonyRelation {
  id: number
  resourceId: string
  isHighlighted: boolean
  highlightedAt?: string | null
  position?: number | null
  name?: string
  description?: string
  slug?: string | null
  image?: BaseMediaObject
  link?: string
  updatedAt?: string
}