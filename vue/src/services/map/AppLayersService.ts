import actorLayerIcon from '@/assets/images/icons/map/actor_icon.png'
import projectLayerIcon from '@/assets/images/icons/map/project_icon.png'
import resourceLayerIcon from '@/assets/images/icons/map/resource_icon.png'
import { ItemType } from '@/models/enums/app/ItemType'
import { Thematic } from '@/models/enums/contents/Thematic'
import type { ThematicItem } from '@/models/interfaces/common/ThematicItem'
import type { Layer } from '@/models/interfaces/map/Layer'
import { i18n } from '@/plugins/i18n'
import { useActorsStore } from '@/stores/actorsStore'
import { useApplicationStore } from '@/stores/applicationStore'
import { useMyMapStore } from '@/stores/myMapStore'
import { useProjectStore } from '@/stores/projectStore'
import { useResourceStore } from '@/stores/resourceStore'
import { computed } from 'vue'
import LayerService from './LayerService'
import MapService from './MapService'

export class AppLayersService {
  private static mapThematics = Object.values(Thematic).map((t, i) => {
    return {
      id: i,
      name: t,
      isShown: true
    }
  })
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
    const applicationStore = useApplicationStore()
    const myMapStore = useMyMapStore()
    const actorStore = useActorsStore()
    try {
      await Promise.all([resourceStore.getAll(), actorStore.getAll(), projectStore.getAll()])
      if (!myMapStore.isMapAlreadyBeenMounted) {
        this.initMainLayers()
        this.initSubLayers()
      }

      if (myMapStore.map == null) return
      MapService.isLoaded(myMapStore.map, async () => {
        await this.setPlatformDataLayers()
        applicationStore.isLoading = false
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
    const myMapStore = useMyMapStore()
    const mapThematics = Object.values(Thematic).map((t, i) => {
      return {
        id: i,
        name: t,
        isShown: true
      }
    })

    let projectThematics = this.mapThematics
    let actorsThematics = this.mapThematics
    let resourcesThematics = this.mapThematics

    if (myMapStore?.deserializedMapState) {
      projectThematics = mapThematics.map((x) => {
        return {
          ...x,
          isShown: myMapStore?.deserializedMapState?.layers?.projects?.includes(x.id) as boolean
        }
      })

      actorsThematics = mapThematics.map((x) => {
        return {
          ...x,
          isShown: myMapStore?.deserializedMapState?.layers?.actors?.includes(x.id) as boolean
        }
      })

      resourcesThematics = mapThematics.map((x) => {
        return {
          ...x,
          isShown: myMapStore?.deserializedMapState?.layers?.resources?.includes(x.id) as boolean
        }
      })
    }
    myMapStore!.projectSubLayers = LayerService.initSubLayer(projectThematics as any, true)
    myMapStore!.actorSubLayers = LayerService.initSubLayer(actorsThematics as any)
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
        return MapService.getGeojson(this.filteredProjects.value, true)
      case ItemType.RESOURCE:
        return MapService.getGeojson(this.filteredResources.value)
    }
  }

  static filterByThematic(items: ThematicItem[], subLayers: Layer[]) {
    const activeThematics: Layer['name'][] = subLayers
      .filter((layer: Layer) => layer.isShown)
      .map((layer: Layer) => layer.name)
    return items.filter((item) => {
      return activeThematics.some((thematic) => item.thematics.includes(thematic as Thematic))
    })
  }
}
