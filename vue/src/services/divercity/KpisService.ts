import type { KpiData } from '@/models/interfaces/divercity/KpiData'
import { apiClient } from '@/plugins/axios/api'

export class KpisService {
  static async getKpis(spaceId: string, from?: string, to?: string): Promise<KpiData> {
    const { data } = await apiClient.get<KpiData>(`/api/divercity/kpis/${spaceId}`, {
      params: { from, to }
    })
    return data
  }
}