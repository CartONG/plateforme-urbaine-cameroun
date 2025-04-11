<template>
  <div class="MyMapItemPopup" ref="active-item-card">
    <ProjectCard
      v-if="myMapStore.activeItemType === ItemType.PROJECT"
      class="ProjectCard ProjectCard--light"
      :project="myMapStore.activeItem as Project"
      :map="true"
    />
    <ResourceCard
      v-else-if="myMapStore.activeItemType === ItemType.RESOURCE"
      :resource="myMapStore.activeItem as Resource"
      class="ResourceCard ResourceCard--light"
    />
    <ActorCard
      v-else-if="myMapStore.activeItemType === ItemType.ACTOR"
      :actor="myMapStore.activeItem as Actor"
      class="ActorCard ActorCard--light"
    />
    <QgisCard
      v-if="myMapStore.activeItemType === 'QGIS'"
      :features="myMapStore.activeItem as FilteredQGISLayerFeatures[]"
    />
  </div>
</template>

<script setup lang="ts">
import { useMyMapStore } from '@/stores/myMapStore'
import ProjectCard from '@/views/projects/components/ProjectCard.vue'
import { computed, onMounted, useTemplateRef, watch } from 'vue'
import ActorCard from '@/views/actors/components/ActorCard.vue'
import { ItemType } from '@/models/enums/app/ItemType'
import type { Project } from '@/models/interfaces/Project'
import type { Resource } from '@/models/interfaces/Resource'
import ResourceCard from '@/views/resources/components/ResourceCard.vue'
import type { Actor } from '@/models/interfaces/Actor'
import type { LngLat } from 'maplibre-gl'
import QgisCard from './QgisLayersQuery/QgisCard.vue'
import type { FilteredQGISLayerFeatures } from '@/models/interfaces/map/AtlasMap'
import MapService from '@/services/map/MapService'
import type { Item } from '@/models/interfaces/Item'

const myMapStore = useMyMapStore()
const activeItemCard = useTemplateRef('active-item-card')

const myMap = computed(() => myMapStore.myMap)
const map = computed(() => myMap.value?.map)

onMounted(async () => {
  await MapService.isLoaded(map.value, () => addPopupOnClick())
  await showPopupOnInit()
})

watch(
  () => [myMapStore.activeItem, myMapStore.isMapAlreadyBeenMounted],
  async () => {
    await MapService.isLoaded(map.value, () => addPopupOnClick())
    await showPopupOnInit()
  }
)

watch(
  () => myMap.value?.activeFeatureId,
  (to, from) => {
    const initializing = from === undefined
    if (myMap.value == null || initializing || to == null) return
    myMapStore.activeItemId = myMap.value?.activeFeatureId
  }
)

const addPopupOnClick = () => {
  if (myMap.value == null) return
  Object.values(ItemType).forEach((itemLayer) => {
    myMap.value?.addPopupOnClick(itemLayer, activeItemCard.value, false)
  })
}

const showPopup = () => {
  if (myMap.value == null) return
  if (myMapStore.activeItemType === 'QGIS') {
    myMap.value.addPopup(myMapStore.activeItemCoords as LngLat, activeItemCard.value, false)
  } else {
    Object.values(ItemType).forEach((itemType) => {
      myMap.value?.addPopupOnClick(itemType, activeItemCard.value, false)
    })
  }
  if (map.value) {
    if (myMapStore.activeItem != null && myMap.value && myMapStore.activeItemType !== 'QGIS') {
      if ((myMapStore.activeItem as Item)?.geoData?.coords) {
        myMap.value.addPopup(
          (myMapStore.activeItem as Item)?.geoData?.coords,
          activeItemCard.value,
          false
        )
      }
    }
  }
}

const showPopupOnInit = async () => {
  await MapService.isLoaded(map.value, () => {
    addPopupOnClick()
    showPopup()
  })
}
</script>

<style lang="scss">
.maplibregl-popup:not(.show),
.maplibregl-popup:not(.show) * {
  pointer-events: none !important;
}
.MyMap {
  width: 100%;
  height: 100%;
  position: relative;

  .maplibregl-popup,
  .MyMapItemPopup .v-card {
    max-width: 22rem !important;
  }

  .MyMap__map {
    width: 100%;
    height: 100%;

    .maplibregl-ctrl-top-right {
      align-items: flex-end;
    }
  }
}
</style>
