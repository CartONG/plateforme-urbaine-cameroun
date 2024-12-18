import { apiClient } from '@/plugins/axios/api'
import type { Kpi } from '@/models/interfaces/Kpi'

export class KpiService {
  static async getGlobal(): Promise<Kpi[]> {
    return await apiClient.get('/api/kpis/global').then((response) => response.data['hydra:member'])
  }
}
