import { StoresList } from '@/models/enums/app/StoresList'
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useMyMapPluginsStore = defineStore(StoresList.MAP_PLUGINS, () => {
  const isPluginsMenuVisible = ref(false)
  return {
    isPluginsMenuVisible
  }
})
