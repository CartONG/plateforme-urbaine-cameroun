import { StoresList } from '@/models/enums/app/StoresList'
import { defineStore } from 'pinia'
import { computed, ref, type Ref } from 'vue'
import type { Kpi } from '@/models/interfaces/Kpi'
import { KpiService } from '@/services/kpi/KpiService'
import { HighlightedItemService } from '@/services/highlight/HighlightedItemService'
import type { HighlightedItem } from '@/models/interfaces/HighlightedItem'

export const useHomeStore = defineStore(StoresList.HOME, () => {
  const mainHighlights: Ref<HighlightedItem[]> = ref([])
  const globalKpis: Ref<Kpi[]> = ref([])

  const getGlobalKpis = async () => {
    if (globalKpis.value.length > 0) return
    globalKpis.value = await KpiService.getGlobal()
  }

  const getMainHighlights = async (): Promise<void> => {
    mainHighlights.value = await HighlightedItemService.getMainHighlights()
  }

  const orderedMainHighlights = computed(() => {
    return mainHighlights.value.sort((a, b) => (a.position ?? 0) - (b.position ?? 0))
  })

  return {
    mainHighlights,
    globalKpis,
    orderedMainHighlights,
    getGlobalKpis,
    getMainHighlights
  }
})
