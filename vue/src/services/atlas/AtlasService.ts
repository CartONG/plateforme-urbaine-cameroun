import type { Atlas } from '@/models/interfaces/Atlas'
import { apiClient } from '@/plugins/axios/api'

export class AtlasService {
  static getAll() {
    return apiClient.get('/api/atlases').then((response) => response.data['hydra:member'])
  }

  static async post(atlas: Atlas): Promise<Atlas> {
    return await apiClient.post('/api/atlases', atlas).then((response) => response.data)
  }

  static async patch(atlas: Atlas): Promise<Atlas> {
    return await apiClient
      .patch('/api/atlases/' + atlas.id, atlas)
      .then((response) => response.data)
  }

  static async delete(atlas: Atlas): Promise<Atlas> {
    return await apiClient.delete('/api/atlases/' + atlas.id).then((response) => response.data)
  }
}
