import { StoresList } from '@/models/enums/app/StoresList'
import { defineStore } from 'pinia'
import { ref, watch, type Ref } from 'vue'
import type { OsmData } from '@/models/interfaces/geo/OsmData'
import type Layer from '@/models/interfaces/map/Layer'
import type Map from '@/components/map/Map.vue'
import type { Item } from '@/models/interfaces/Item'
import { useProjectStore } from './projectStore'
import { useActorsStore } from './actorsStore'
import { useResourceStore } from './resourceStore'
import { ProjectService } from '@/services/projects/ProjectService'
import { ActorsService } from '@/services/actors/ActorsService'
import { ResourceService } from '@/services/resources/ResourceService'
import { ItemType } from '@/models/enums/app/ItemType'

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
  const activeItemId: Ref<string | null> = ref(null)

  const activeItem: Ref<Item | null> = ref(null)
  const activeItemType: Ref<ItemType | null> = ref(null)

  watch(
    () => activeItemId.value,
    async () => {
      const projectStore = useProjectStore()
      const actorStore = useActorsStore()
      const resourceStore = useResourceStore()
      activeItem.value = null
      activeItemType.value = null
      console.log('activeItemId.value', activeItemId.value);

      if (activeItemId.value) {
        let item: Item | undefined = projectStore.projects.find(
          (project) => project.id === activeItemId.value
        )

        if (item) {
          activeItemType.value = ItemType.PROJECT
          activeItem.value = await ProjectService.get(item)
        } else {
          item = actorStore.actors.find((actor) => actor.id === activeItemId.value)
          if (item) {
            activeItemType.value = ItemType.ACTOR
            activeItem.value = await ActorsService.getActor(item.id)
          } else {
            item = resourceStore.resources.find((resource) => resource.id === activeItemId.value)
            if (item) {
              activeItemType.value = ItemType.RESOURCE
              activeItem.value = await ResourceService.get(item)
            }
          }
        }
      }
    }
  )

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
    resourceSubLayers,
    activeItemId,
    activeItemType,
    activeItem
  }
})
