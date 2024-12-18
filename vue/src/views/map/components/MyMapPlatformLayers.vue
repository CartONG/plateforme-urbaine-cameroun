<template>
  <div class="MyMapPlatformLayers">
    <MyMapLayerPicker
      v-model:main-layer="myMapStore.projectLayer"
      v-model:sub-layers="myMapStore.projectSubLayers"
      @update="refreshLayer(ItemType.PROJECT)"
    />
    <MyMapLayerPicker
      v-model:main-layer="myMapStore.actorLayer"
      v-model:sub-layers="myMapStore.actorSubLayers"
      @update="refreshLayer(ItemType.ACTOR)"
    />
    <MyMapLayerPicker
      v-model:main-layer="myMapStore.resourceLayer"
      v-model:sub-layers="myMapStore.resourceSubLayers"
      @update="refreshLayer(ItemType.RESOURCE)"
    />
  </div>
</template>

<script setup lang="ts">
import { useThematicStore } from '@/stores/thematicStore'
import { ItemType } from '@/models/enums/app/ItemType'
import MyMapLayerPicker from '@/views/map/components/MyMapLayerPicker.vue'
import projectLayerIcon from '@/assets/images/icons/map/project_icon.png'
import resourceLayerIcon from '@/assets/images/icons/map/resource_icon.png'
import actorLayerIcon from '@/assets/images/icons/map/actor_icon.png'
import { computed, onMounted } from 'vue'
import { i18n } from '@/plugins/i18n'
import LayerService from '@/services/map/LayerService'
import { useMyMapStore } from '@/stores/myMapStore'
import MapService from '@/services/map/MapService'
import { useResourceStore } from '@/stores/resourceStore'
import { useProjectStore } from '@/stores/projectStore'
import { useActorsStore } from '@/stores/actorsStore'
import type Layer from '@/models/interfaces/map/Layer'
import type { Thematic } from '@/models/interfaces/Thematic'
import type { ThematicItem } from '@/models/interfaces/common/ThematicItem'

const myMapStore = useMyMapStore()
const actorStore = useActorsStore()
const projectStore = useProjectStore()
const resourceStore = useResourceStore()
const thematicStore = useThematicStore()

const myMap = computed(() => myMapStore.myMap)
const map = computed(() => myMapStore.myMap?.map)

onMounted(async () => {
  await thematicStore.getAll()
  initMainLayers()
  initSubLayers()
  Promise.all([
    await resourceStore.getAll(),
    await actorStore.getAll(),
    await projectStore.getAll()
  ]).then(() => {
    if (map.value != null) {
      setPlatformDataLayers()
    }
  })
})

const initMainLayers = () => {
  myMapStore.projectLayer = LayerService.initLayer({
    id: ItemType.PROJECT,
    name: i18n.t('myMap.rightSidebar.layers.itemType.' + ItemType.PROJECT),
    icon: projectLayerIcon
  })
  myMapStore.actorLayer = LayerService.initLayer({
    id: ItemType.ACTOR,
    name: i18n.t('myMap.rightSidebar.layers.itemType.' + ItemType.ACTOR),
    icon: actorLayerIcon
  })
  myMapStore.resourceLayer = LayerService.initLayer({
    id: ItemType.RESOURCE,
    name: i18n.t('myMap.rightSidebar.layers.itemType.' + ItemType.RESOURCE),
    icon: resourceLayerIcon
  })
}

const initSubLayers = () => {
  myMapStore.projectSubLayers = LayerService.initSubLayer(thematicStore.thematics)
  myMapStore.actorSubLayers = LayerService.initSubLayer(thematicStore.thematics)
  myMapStore.resourceSubLayers = LayerService.initSubLayer(thematicStore.thematics)
}

const setPlatformDataLayers = async () => {
  Object.values(ItemType).forEach((itemType) => {
    setPlatformDataLayer(itemType)
  })
}

const filteredProjects = computed(() => {
  return filterByThematic(projectStore.projects, myMapStore.projectSubLayers)
})
const filteredActors = computed(() => {
  return filterByThematic(actorStore.actors, myMapStore.actorSubLayers)
})
const filteredResources = computed(() => {
  return filterByThematic(resourceStore.resources, myMapStore.resourceSubLayers)
})

const filterByThematic = (items: ThematicItem[], subLayers: Layer[]) => {
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

const getGeojsonPerItemType = (itemType: ItemType) => {
  switch (itemType) {
    case ItemType.ACTOR:
      return MapService.getGeojson(filteredActors.value)
    case ItemType.PROJECT:
      return MapService.getGeojson(filteredProjects.value)
    case ItemType.RESOURCE:
      return MapService.getGeojson(filteredResources.value)
  }
}
const refreshLayer = (itemType: ItemType) => {
  if (myMap.value) {
    myMap.value.setData(itemType, getGeojsonPerItemType(itemType))
  }
}

const setPlatformDataLayer = async (itemType: ItemType) => {
  if (myMap.value) {
    const geojson = getGeojsonPerItemType(itemType)
    const icon = new URL(`/src/assets/images/icons/map/${itemType}_icon.png`, import.meta.url).href
    // const hoverIcon = new URL(`@/assets/images/icons/map/${itemType}_icon_hover.png`, import.meta.url).href
    myMap.value.addSource(itemType, geojson)
    await myMap.value.addImage(icon, itemType)
    // await myMap.value.addImage(hoverIcon, itemType + '_hover')
    const layout: maplibregl.LayerSpecification['layout'] = {
      'icon-image': itemType,
      'icon-size': 0.4
    }
    myMap.value.addLayer(itemType, { layout })
    myMap.value.listenToHoveredFeature(itemType)
    // myMap.value.addPopupOnClick(projectsLayerName, activeProjectCard.value)
  }
  return
}
</script>

<style lang="scss">
.MyMapPlatformLayers {
  display: flex;
  flex-flow: column nowrap;
  gap: 0.75rem;
}
</style>
