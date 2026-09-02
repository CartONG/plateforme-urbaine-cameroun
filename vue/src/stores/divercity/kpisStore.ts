import { StoresList } from '@/models/enums/app/StoresList'
import type { KpiData } from '@/models/interfaces/divercity/KpiData'
import { KpisService } from '@/services/divercity/KpisService'

import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useKpisStore = defineStore(StoresList.KPIS, () => {
  const kpis = ref<KpiData | null>(null)
  const isLoading = ref(false)

  async function getKpis(spaceId: string, from?: string, to?: string): Promise<void> {
    isLoading.value = true
    try {
      kpis.value = await KpisService.getKpis(spaceId, from, to)
    } finally {
      isLoading.value = false
    }
  }

  return { kpis, isLoading, getKpis }
})