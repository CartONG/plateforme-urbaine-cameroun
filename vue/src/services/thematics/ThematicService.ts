import { apiClient } from '@/assets/plugins/axios/api'
import type { Thematic } from '@/models/interfaces/Thematic'

export class ThematicService {
  static async getAll(): Promise<Thematic[]> {
    return await apiClient.get('/api/thematics').then((response) => response.data['hydra:member'])
  }
}
