import type { HighlightedResource } from '@/models/interfaces/divercity/HighlightedResource'
import { apiClient } from '@/plugins/axios/api'

export class DiverCityHighlightedResourceService {
  static async post(item: Partial<HighlightedResource>): Promise<HighlightedResource> {
    return apiClient.post('/api/highlighted_resources', item).then((r) => r.data)
  }

  static async patch(item: Partial<HighlightedResource>): Promise<HighlightedResource> {
    return apiClient
      .patch('/api/highlighted_resources/' + item.resourceId, item)
      .then((r) => r.data)
  }

  static async getAll(): Promise<HighlightedResource[]> {
    return apiClient
      .get('/api/highlighted_resources')
      .then((r) => r.data['hydra:member'])
  }

  static async getMainHighlights(): Promise<HighlightedResource[]> {
    return apiClient
      .get('/api/divercity/highlighted_resources/main', { params: { isHighlighted: true } })
      .then((r) => r.data['hydra:member'] ?? r.data)
  }

}