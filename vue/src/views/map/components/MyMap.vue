<template>
  <div class="MyMap">
    <Map class="MyMap__map" ref="my-map" />
    <BasemapPicker ref="basemap-picker" v-model="basemap" />
    <ToggleSidebarControl
      v-model="myMapStore.isLeftSidebarShown"
      :inversed-direction="true"
      :is-higlighted-when-off="true"
      ref="toggle-left-sidebar-control"
    />
    <ToggleSidebarControl
      style="order: -1"
      v-model="myMapStore.isRightSidebarShown"
      :is-higlighted-when-off="true"
      ref="toggle-right-sidebar-control"
    />
  </div>
</template>

<script setup lang="ts">
import BasemapPicker from '@/components/map/controls/BasemapPicker.vue'
import ToggleSidebarControl from '@/components/map/controls/ToggleSidebarControl.vue'
import Map from '@/components/map/Map.vue'
import type Basemap from '@/models/interfaces/map/Basemap'
import MapService, { IControl } from '@/services/map/MapService'
import { useMyMapStore } from '@/stores/myMapStore'
import { computed, onMounted, ref, useTemplateRef, watch } from 'vue'

type MapType = InstanceType<typeof Map>
const basemap = ref<Basemap>()
const myMapStore = useMyMapStore()
const myMap = useTemplateRef<MapType>('my-map')
const toggleRightSidebarControl = useTemplateRef('toggle-right-sidebar-control')
const toggleLeftSidebarControl = useTemplateRef('toggle-left-sidebar-control')
const basemapPicker = useTemplateRef('basemap-picker')
const map = computed(() => myMap.value?.map)

onMounted(() => {
  if (map.value) {
    map.value.on('load', () => {
      if (myMap.value) {
        myMapStore.myMap = myMap.value
      }
    })
  }
  if (map.value != null) {
    map.value.addControl(new IControl(basemapPicker), 'bottom-right')
    map.value.addControl(new IControl(toggleRightSidebarControl), 'top-right')
    map.value.addControl(new IControl(toggleLeftSidebarControl), 'top-left')
  }
})

watch(basemap, () => {
  if (map.value != null && basemap.value != null) {
    MapService.updateStyle(map.value, basemap.value)
  }
})

watch(
  () => myMapStore.mapSearch?.bbox,
  (newValue) => {
    if (map.value != null && newValue != null && newValue != null) {
      map.value.fitBounds(newValue, { padding: 75 })
    }
  }
)
// const updatePin = () => {
//   if (myMap.value) {
//     myMap.value.setLayoutProperty(
//       sources.projects,
//       'icon-image',
//       imageHoverFilter(projectsImageName, projectsImageHoverName)
//     )
//   }
// }

// const refreshProjectLayer = async () => {
//   if (myMap.value) {
//     myMap.value.setData(sources.projects, geojson.value)
//   }
// }

// const imageHoverFilter = (
//   imageName: string,
//   imageHoverName: string
// ): maplibregl.DataDrivenPropertyValueSpecification<ResolvedImageSpecification> => {
//   return [
//     'match',
//     ['get', 'id'],
//     [...new Set([projectStore.hoveredProjectId ?? '', projectStore.activeProjectId ?? ''])],
//     imageHoverName,
//     imageName
//   ]
// }

// const setProjectLayer = async () => {
//   if (myMap.value) {
//     myMap.value.addSource(projectsSourceName, geojson.value)
//     await myMap.value.addImage(projectIcon, projectsImageName)
//     await myMap.value.addImage(projectHoverIcon, projectsImageHoverName)
//     const layout: maplibregl.LayerSpecification['layout'] = {
//       'icon-image': imageHoverFilter(projectsImageName, projectsImageHoverName),
//       'icon-size': 0.45
//     }
//     myMap.value.addLayer(projectsLayerName, { layout })
//     myMap.value.listenToHoveredFeature(projectsLayerName)
//     myMap.value.addPopupOnClick(projectsLayerName, activeProjectCard.value)
//   }
//   return
// }
</script>

<style lang="scss">
.MyMap {
  width: 100%;
  height: 100%;
  position: relative;

  #map.MyMap__map {
    width: 100%;
    height: 100%;

    .maplibregl-ctrl-top-right {
      align-items: flex-end;
    }
  }
}
</style>
