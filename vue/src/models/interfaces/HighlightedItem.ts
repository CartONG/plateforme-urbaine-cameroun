import type { ItemType } from '../enums/app/ItemType'
import type { Item } from './Item'

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
  image?: string
}
