import { apiClient } from '@/assets/plugins/axios/api'
import type { Organisation } from '@/models/interfaces/Organisation'

export class OrganisationService {
  static async getAllDonors(): Promise<Organisation[]> {
    return await apiClient
      .get('/api/organisations', { params: { donor: true } })
      .then((response) => response.data['hydra:member'])
  }
  static async getAllContractingOrganisations(): Promise<Organisation[]> {
    return await apiClient
      .get('/api/organisations', { params: { contracting: true } })
      .then((response) => response.data['hydra:member'])
  }
}
