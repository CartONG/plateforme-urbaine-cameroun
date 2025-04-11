import { ItemType } from '@/models/enums/app/ItemType'
import { i18n } from '@/plugins/i18n'
import { useProjectStore } from '@/stores/projectStore'
import { computed } from 'vue'
import LayerService from './LayerService'
import projectLayerIcon from '@/assets/images/icons/map/project_icon.png'
import resourceLayerIcon from '@/assets/images/icons/map/resource_icon.png'
import actorLayerIcon from '@/assets/images/icons/map/actor_icon.png'
import MapService from './MapService'
import type { ThematicItem } from '@/models/interfaces/common/ThematicItem'
import type { Layer } from '@/models/interfaces/map/Layer'
import type { Thematic } from '@/models/interfaces/Thematic'
import { useMyMapStore } from '@/stores/myMapStore'
import { useActorsStore } from '@/stores/actorsStore'
import { useResourceStore } from '@/stores/resourceStore'
import { useThematicStore } from '@/stores/thematicStore'

export class AppLayersService {
  static filteredProjects = computed(() => {
    const projectStore = useProjectStore()
    const myMapStore = useMyMapStore()
    return this.filterByThematic(projectStore.projects, myMapStore.projectSubLayers as Layer[])
  })
  static filteredActors = computed(() => {
    const actorStore = useActorsStore()
    const myMapStore = useMyMapStore()
    return this.filterByThematic(actorStore.actors, myMapStore?.actorSubLayers as Layer[])
  })
  static filteredResources = computed(() => {
    const resourceStore = useResourceStore()
    const myMapStore = useMyMapStore()
    return this.filterByThematic(resourceStore.resources, myMapStore.resourceSubLayers as Layer[])
  })

  static async initApplicationLayers(): Promise<void> {
    const projectStore = useProjectStore()
    const resourceStore = useResourceStore()
    const thematicStore = useThematicStore()
    const myMapStore = useMyMapStore()
    const actorStore = useActorsStore()
    try {
      await thematicStore.getAll()

      if (!myMapStore.isMapAlreadyBeenMounted) {
        this.initMainLayers()
        this.initSubLayers()
      }

      await Promise.all([resourceStore.getAll(), actorStore.getAll(), projectStore.getAll()])

      if (myMapStore.map == null) return
      MapService.isLoaded(myMapStore.map, async () => {
        await this.setPlatformDataLayers()
      })
    } catch (error) {
      console.error('Erreur lors de l’initialisation des couches :', error)
      return Promise.reject(error)
    }
  }

  static initMainLayers() {
    const myMapStore = useMyMapStore()
    let showProject = true,
      showActor = true,
      showResource = true
    if (myMapStore?.deserializedMapState) {
      if (!myMapStore.deserializedMapState?.layers?.projects) {
        showProject = false
      }
      if (!myMapStore.deserializedMapState?.layers?.actors) {
        showActor = false
      }
      if (!myMapStore.deserializedMapState?.layers?.resources) {
        showResource = false
      }
    }

    myMapStore!.projectLayer = LayerService.initLayer(
      {
        id: ItemType.PROJECT,
        name: i18n.t('myMap.rightSidebar.layers.itemType.' + ItemType.PROJECT),
        icon: projectLayerIcon
      },
      showProject
    )
    myMapStore!.actorLayer = LayerService.initLayer(
      {
        id: ItemType.ACTOR,
        name: i18n.t('myMap.rightSidebar.layers.itemType.' + ItemType.ACTOR),
        icon: actorLayerIcon
      },
      showActor
    )
    myMapStore!.resourceLayer = LayerService.initLayer(
      {
        id: ItemType.RESOURCE,
        name: i18n.t('myMap.rightSidebar.layers.itemType.' + ItemType.RESOURCE),
        icon: resourceLayerIcon
      },
      showResource
    )
  }

  static initSubLayers() {
    const thematicStore = useThematicStore()
    const myMapStore = useMyMapStore()
    let projectThematics = thematicStore.thematics
    let actorsThematics = thematicStore.thematics
    let resourcesThematics = thematicStore.thematics
    if (myMapStore?.deserializedMapState) {
      projectThematics = projectThematics.map((x) => {
        return {
          ...x,
          isShown: myMapStore?.deserializedMapState?.layers?.projects?.includes(x.id)
        }
      })

      actorsThematics = actorsThematics.map((x) => {
        return {
          ...x,
          isShown: myMapStore?.deserializedMapState?.layers?.actors?.includes(x.id)
        }
      })

      resourcesThematics = resourcesThematics.map((x) => {
        return {
          ...x,
          isShown: myMapStore?.deserializedMapState?.layers?.resources?.includes(x.id)
        }
      })
    }
    myMapStore!.projectSubLayers = LayerService.initSubLayer(projectThematics)
    myMapStore!.actorSubLayers = LayerService.initSubLayer(actorsThematics)
    myMapStore!.resourceSubLayers = LayerService.initSubLayer(resourcesThematics)
  }

  static async setPlatformDataLayers() {
    const myMapStore = useMyMapStore()
    Object.values(ItemType).forEach((itemType) => {
      this.setPlatformDataLayer(itemType)
    })
    if (myMapStore!.isMapAlreadyBeenMounted) {
      myMapStore!.setMapLayersOrderOnMapReMount()
    }
    myMapStore!.isMapAlreadyBeenMounted = true
  }

  static async setPlatformDataLayer(itemType: ItemType) {
    const myMapStore = useMyMapStore()
    if (myMapStore.myMap) {
      const geojson = this.getGeojsonPerItemType(itemType)
      const icon = new URL(`/src/assets/images/icons/map/${itemType}_icon.png`, import.meta.url)
        .href
      myMapStore.myMap.addSource(itemType, geojson)
      await myMapStore.myMap.addImage(icon, itemType)
      const layout: maplibregl.LayerSpecification['layout'] = {
        'icon-image': itemType,
        'icon-size': 0.4
      }
      myMapStore.myMap.addLayer(itemType, { layout })
      myMapStore.myMap.listenToHoveredFeature(itemType)
    }
    return
  }

  static getGeojsonPerItemType(itemType: ItemType) {
    switch (itemType) {
      case ItemType.ACTOR:
        return MapService.getGeojson(this.filteredActors.value)
      case ItemType.PROJECT:
        return MapService.getGeojson(this.filteredProjects.value)
      case ItemType.RESOURCE:
        return MapService.getGeojson(this.filteredResources.value)
    }
  }

  static filterByThematic(items: ThematicItem[], subLayers: Layer[]) {
    const activeThematics: Layer['id'][] = subLayers
      .filter((layer: Layer) => layer.isShown)
      .map((layer: Layer) => layer.id)
    return items.filter((item) => {
      const itemThematicIds = item.thematics.map((itemThematic: Thematic) => itemThematic.id)
      return activeThematics.some(
        (thematic) => typeof thematic !== 'string' && itemThematicIds.includes(thematic)
      )
    })
  }
}
