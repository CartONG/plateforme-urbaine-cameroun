import { StoresList } from '@/models/enums/app/StoresList'
import { defineStore } from 'pinia'
import { computed, ref, type Ref } from 'vue'
import { HighlightedItemService } from '@/services/highlight/HighlightedItemService'
import type { HighlightedItem } from '@/models/interfaces/HighlightedItem'
import { debounce } from '@/services/utils/UtilsService'

export const useHighlightStore = defineStore(StoresList.HIGHLIGHTS, () => {
  const highlights: Ref<HighlightedItem[]> = ref([])

  const orderedHighlights = computed(() => {
    return highlights.value
      .filter((highlightedItem) => highlightedItem?.isHighlighted)
      .sort((a, b) => {
        return (a.position ?? 0) - (b.position ?? 0)
      })
  })

  async function getAll(force = false): Promise<void> {
    if (force) {
      highlights.value = await HighlightedItemService.getAll()
    } else {
      debouncedGetAll()
    }
  }

  const debouncedGetAll = debounce(async () => {
    if (highlights.value.length === 0) {
      highlights.value = await HighlightedItemService.getAll()
    }
  }, 100)

  const updateHighlightedItem = (updatedHighlightedItem: HighlightedItem) => {
    let found = false
    highlights.value.forEach((project, key) => {
      if (project.id === updatedHighlightedItem.id) {
        highlights.value[key] = updatedHighlightedItem
        found = true
      }
    })
    if (!found) {
      highlights.value.push(updatedHighlightedItem)
    }
  }

  return {
    highlights,
    orderedHighlights,
    getAll,
    updateHighlightedItem
  }
})
