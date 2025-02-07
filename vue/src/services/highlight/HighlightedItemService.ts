import type { HighlightedItem } from '@/models/interfaces/HighlightedItem'
import { apiClient } from '@/plugins/axios/api'

export class HighlightedItemService {
  static async post(item: Partial<HighlightedItem>): Promise<HighlightedItem> {
    return apiClient.post('/api/highlighted_items', item).then((response) => response.data)
  }
  static async patch(item: Partial<HighlightedItem>): Promise<HighlightedItem> {
    return apiClient
      .patch('/api/highlighted_items/' + item.itemId, item)
      .then((response) => response.data)
  }

  static async getAll(): Promise<HighlightedItem[]> {
    return await apiClient
      .get('/api/highlighted_items')
      .then((response) => response.data['hydra:member'])
  }

  static async getMainHighlights(): Promise<HighlightedItem[]> {
    return await apiClient
      .get('/api/highlighted_items/main')
      .then((response) => response.data['hydra:member'])
  }
}
