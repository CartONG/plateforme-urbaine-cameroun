import { StoresList } from '@/models/enums/app/StoresList'
import { defineStore } from 'pinia'
import { ref, type Ref } from 'vue'
import type { OsmData } from '@/models/interfaces/geo/OsmData'
import type Layer from '@/models/interfaces/map/Layer'
import type Map from '@/components/map/Map.vue'

export const useMyMapStore = defineStore(StoresList.MY_MAP, () => {
  const myMap: Ref<InstanceType<typeof Map> | undefined> = ref()
  const isRightSidebarShown = ref(true)
  const isLeftSidebarShown = ref(true)
  const mapSearch: Ref<OsmData | null> = ref(null)

  const actorLayer: Ref<Layer | null> = ref(null)
  const actorSubLayers: Ref<Layer[]> = ref([])
  const resourceLayer: Ref<Layer | null> = ref(null)
  const resourceSubLayers: Ref<Layer[]> = ref([])
  const projectLayer: Ref<Layer | null> = ref(null)
  const projectSubLayers: Ref<Layer[]> = ref([])

  return {
    isRightSidebarShown,
    isLeftSidebarShown,
    myMap,
    mapSearch,
    actorLayer,
    actorSubLayers,
    projectLayer,
    projectSubLayers,
    resourceLayer,
    resourceSubLayers
  }
})
