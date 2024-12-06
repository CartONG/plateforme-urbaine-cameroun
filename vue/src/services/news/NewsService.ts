import { apiClient } from '@/assets/plugins/axios/api'
import type { News } from '@/models/interfaces/News'

export class NewsService {
  static async getLatest(): Promise<News[]> {
    return await apiClient.get('/api/news/latest').then((response) => response.data['hydra:member'])
  }
}
