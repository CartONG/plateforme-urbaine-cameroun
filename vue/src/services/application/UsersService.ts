import { apiClient } from '@/plugins/axios/api'

export class UsersService {
  static async getMembers() {
    const data = (await apiClient.get('/api/users', { headers: { accept: 'application/ld+json' } }))
      .data
    return data['hydra:member']
  }
}
