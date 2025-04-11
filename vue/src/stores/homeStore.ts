import { StoresList } from '@/models/enums/app/StoresList'
import { defineStore } from 'pinia'
import { computed, ref, type Ref } from 'vue'
import type { Kpi } from '@/models/interfaces/Kpi'
import { KpiService } from '@/services/kpi/KpiService'
import { HighlightedItemService } from '@/services/highlight/HighlightedItemService'
import type { HighlightedItem } from '@/models/interfaces/HighlightedItem'

export const useHomeStore = defineStore(StoresList.HOME, () => {
  const mainHighlights: Ref<HighlightedItem[]> = ref([])
  const highlights: Ref<HighlightedItem[]> = ref([])
  const globalKpis: Ref<Kpi[]> = ref([])

  const getGlobalKpis = async () => {
    if (globalKpis.value.length > 0) return
    globalKpis.value = await KpiService.getGlobal()
  }

  const getMainHighlights = async (): Promise<void> => {
    highlights.value = await HighlightedItemService.getAll()
    mainHighlights.value = await HighlightedItemService.getMainHighlights()
  }

  const orderedMainHighlights = computed(() => {
    const mainHighlightsIds = mainHighlights.value.map((e) => e.itemId)
    const highlightsIds = highlights.value.map((e) => e.itemId)
    mainHighlightsIds.forEach((e, i) => {
      mainHighlights.value[i].position = highlights.value[highlightsIds.indexOf(e)].position
    })
    return mainHighlights.value.sort((a, b) => (a.position ?? 0) - (b.position ?? 0)).reverse()
  })

  return {
    mainHighlights,
    globalKpis,
    orderedMainHighlights,
    getGlobalKpis,
    getMainHighlights
  }
})
