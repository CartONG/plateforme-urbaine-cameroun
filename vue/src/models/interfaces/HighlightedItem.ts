import type { ItemType } from '../enums/app/ItemType'
import type { Item } from './Item'
import type { BaseMediaObject } from './object/MediaObject'

export type HighlightedItem = {
  id: number
  itemId: Item['id']
  isHighlighted: boolean
  position?: number
  itemType?: ItemType
  name?: string
  highlightedAt?: string
  description?: string
  updatedAt?: Date
  slug?: string
  link?: string
  image?: BaseMediaObject
}
