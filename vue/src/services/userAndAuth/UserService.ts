import { apiClient } from '@/assets/plugins/axios/api'
import type { User } from '@sentry/vue'
import type { AxiosResponse } from 'axios'

export class UserService {
  static async createUser(values: Partial<User>): Promise<AxiosResponse> {
    return apiClient.post('/api/users', JSON.stringify(values), {
      headers: { 'Content-Type': 'application/ld+json' }
    })
  }

  static async patchUser(values: Partial<User>, userID: number): Promise<AxiosResponse> {
    return apiClient.patch(`/api/users/${userID}`, JSON.stringify(values), {
      headers: { 'Content-Type': 'application/merge-patch+json' }
    })
  }
}
