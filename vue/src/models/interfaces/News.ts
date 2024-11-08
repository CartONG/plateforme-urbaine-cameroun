import type { ItemType } from "@/models/enums/app/ItemType"

export type News = {
  id: string,
  type: ItemType,
  name: string,
  description: string,
  updatedAt: Date
  slug: string
  image?: string,
}