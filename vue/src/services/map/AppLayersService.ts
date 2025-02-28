import { ItemType } from '@/models/enums/app/ItemType'
import { i18n } from '@/plugins/i18n'
import LayerService from './LayerService'
import projectLayerIcon from '@/assets/images/icons/map/project_icon.png'
import resourceLayerIcon from '@/assets/images/icons/map/resource_icon.png'
import actorLayerIcon from '@/assets/images/icons/map/actor_icon.png'
import MapService from './MapService'
import type { ThematicItem } from '@/models/interfaces/common/ThematicItem'
import type { Layer } from '@/models/interfaces/map/Layer'
import type { Thematic } from '@/models/interfaces/Thematic'
import type { MapState } from '@/models/interfaces/map/MapState'

export class AppLayersService {
  // static mapStore: MyMapStoreType | null = null
  // static actorStore = useActorsStore()
  // static projectStore = useProjectStore()
  // static resourceStore = useResourceStore()
  // static thematicStore = useThematicStore()
  // static filteredProjects = computed(() => {
  //   return this.filterByThematic(
  //     this.projectStore.projects,
  //     this.mapStore?.projectSubLayers as Layer[]
  //   )
  // })
  // static filteredActors = computed(() => {
  //   return this.filterByThematic(this.actorStore.actors, this.mapStore?.actorSubLayers as Layer[])
  // })
  // static filteredResources = computed(() => {
  //   return this.filterByThematic(
  //     this.resourceStore.resources,
  //     this.mapStore?.resourceSubLayers as Layer[]
  //   )
  // })

  // static async initApplicationLayers(): Promise<void> {
  //   try {
  //     // this.mapStore = useMyMapStore()
  //     // await this.thematicStore.getAll()
  //     // if (!this.mapStore.isMapAlreadyBeenMounted) {
  //     //   // this.initMainLayers()
  //     //   // this.initSubLayers()
  //     // }

  //     // await Promise.all([
  //     //   this.resourceStore.getAll(),
  //     //   this.actorStore.getAll(),
  //     //   this.projectStore.getAll()
  //     // ])
  //     // await this.setPlatformDataLayers()
  //   } catch (error) {
  //     console.error('Erreur lors de l’initialisation des couches :', error)
  //   }
  // }

  static initMainLayers(deserializedMapState: MapState | null) {
    let showProject = true,
      showActor = true,
      showResource = true
    if (deserializedMapState) {
      if (!deserializedMapState?.layers?.projects) {
        showProject = false
      }
      if (!deserializedMapState?.layers?.actors) {
        showActor = false
      }
      if (!deserializedMapState?.layers?.resources) {
        showResource = false
      }
    }

    const projectLayer = LayerService.initLayer(
      {
        id: ItemType.PROJECT,
        name: i18n.t('myMap.rightSidebar.layers.itemType.' + ItemType.PROJECT),
        icon: projectLayerIcon
      },
      showProject
    )
    const actorLayer = LayerService.initLayer(
      {
        id: ItemType.ACTOR,
        name: i18n.t('myMap.rightSidebar.layers.itemType.' + ItemType.ACTOR),
        icon: actorLayerIcon
      },
      showActor
    )
    const resourceLayer = LayerService.initLayer(
      {
        id: ItemType.RESOURCE,
        name: i18n.t('myMap.rightSidebar.layers.itemType.' + ItemType.RESOURCE),
        icon: resourceLayerIcon
      },
      showResource
    )
    return { projectLayer, actorLayer, resourceLayer }
  }

  static initSubLayers(deserializedMapState: MapState | null, thematics: Thematic[]) {
    let projectThematics = thematics
    let actorsThematics = thematics
    let resourcesThematics = thematics
    if (deserializedMapState) {
      projectThematics = thematics.map((x) => {
        return {
          ...x,
          isShown: deserializedMapState?.layers?.projects?.includes(x.id)
        }
      })

      actorsThematics = thematics.map((x) => {
        return {
          ...x,
          isShown: deserializedMapState?.layers?.actors?.includes(x.id)
        }
      })

      resourcesThematics = thematics.map((x) => {
        return {
          ...x,
          isShown: deserializedMapState?.layers?.resources?.includes(x.id)
        }
      })
    }
    const projectSubLayers = LayerService.initSubLayer(projectThematics)
    const actorSubLayers = LayerService.initSubLayer(actorsThematics)
    const resourceSubLayers = LayerService.initSubLayer(resourcesThematics)
    return { projectSubLayers, actorSubLayers, resourceSubLayers }
  }

  static async setPlatformDataLayers(
    map: maplibregl.Map,
    filteredActors: ThematicItem[],
    filteredProjects: ThematicItem[],
    filteredResources: ThematicItem[]
  ) {
    Object.values(ItemType).forEach((itemType) => {
      this.setPlatformDataLayer(itemType, map, filteredActors, filteredProjects, filteredResources)
    })
    // if (this.mapStore!.isMapAlreadyBeenMounted) {
    //   this.mapStore!.setMapLayersOrderOnMapReMount()
    // }
    // this.mapStore!.isMapAlreadyBeenMounted = true
  }

  static async setPlatformDataLayer(
    itemType: ItemType,
    map: maplibregl.Map,
    filteredActors: ThematicItem[],
    filteredProjects: ThematicItem[],
    filteredResources: ThematicItem[]
  ) {
    const geojson = this.getGeojsonPerItemType(
      itemType,
      filteredActors,
      filteredProjects,
      filteredResources
    )
    const icon = new URL(`/src/assets/images/icons/map/${itemType}_icon.png`, import.meta.url).href
    MapService.addSource(map, itemType, geojson)
    await MapService.addImage(map, icon, itemType)
    const layout: maplibregl.LayerSpecification['layout'] = {
      'icon-image': itemType,
      'icon-size': 0.4
    }
    MapService.addLayer(map, itemType, { layout })
  }

  static getGeojsonPerItemType(
    itemType: ItemType,
    filteredActors: ThematicItem[],
    filteredProjects: ThematicItem[],
    filteredResources: ThematicItem[]
  ) {
    switch (itemType) {
      case ItemType.ACTOR:
        return MapService.getGeojson(filteredActors)
      case ItemType.PROJECT:
        return MapService.getGeojson(filteredProjects)
      case ItemType.RESOURCE:
        return MapService.getGeojson(filteredResources)
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
