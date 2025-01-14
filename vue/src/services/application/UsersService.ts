import { apiClient } from '@/plugins/axios/api'

export class UsersService {
  static async getMembers(onlyValidated = true) {
    let params = {}
    if (onlyValidated) {
      params = { ...params, isValidated: true }
    }
    const data = (
      await apiClient.get('/api/users', { headers: { accept: 'application/ld+json' }, params })
    ).data
    return data['hydra:member']
  }
}
