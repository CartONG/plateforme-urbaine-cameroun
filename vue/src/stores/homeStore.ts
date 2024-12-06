import { StoresList } from '@/models/enums/app/StoresList'
import { defineStore } from 'pinia'
import { ref, type Ref } from 'vue'
import type { News } from '@/models/interfaces/News'
import type { Kpi } from '@/models/interfaces/Kpi'
import { NewsService } from '@/services/news/NewsService'
import { KpiService } from '@/services/kpi/KpiService'

export const useHomeStore = defineStore(StoresList.HOME, () => {
  const news: Ref<News[]> = ref([])
  const globalKpis: Ref<Kpi[]> = ref([])
  const getNews = async () => {
    if (news.value.length > 0) return
    news.value = await NewsService.getLatest()
  }
  const getGlobalKpis = async () => {
    if (globalKpis.value.length > 0) return
    globalKpis.value = await KpiService.getGlobal()
  }

  return {
    news,
    globalKpis,
    getNews,
    getGlobalKpis
  }
})
