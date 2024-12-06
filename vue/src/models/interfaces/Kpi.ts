import type { KpiKey } from '@/models/enums/app/KpiKey'

export interface Kpi {
  key: KpiKey
  count: number
}
