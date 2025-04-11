<template>
  <div class="MyMapView">
    <MyMapHeader class="MyMapView__header" />
    <div class="MyMapView__mapCtn">
      <MyMapLeftSideBar class="MyMapView__welcomeSideBar" :shown="myMapStore.isLeftSidebarShown" />
      <MyMap class="MyMapView__myMap" />
      <MyMapRightSideBar class="MyMapView__layersSideBar" :shown="myMapStore.isRightSidebarShown" />
    </div>
  </div>
</template>
<script setup lang="ts">
import MyMap from '@/views/map/components/MyMap.vue'
import MyMapHeader from '@/views/map/components/MyMapHeader.vue'
import MyMapLeftSideBar from '@/views/map/components/MyMapLeftSideBar.vue'
import MyMapRightSideBar from '@/views/map/components/MyMapRightSideBar.vue'
import { useMyMapStore } from '@/stores/myMapStore'
import { computed, onMounted } from 'vue'
import { LayerType } from '@/models/enums/geo/LayerType'
import { useRoute } from 'vue-router'
import { AppLayersService } from '@/services/map/AppLayersService'
import MapService from '@/services/map/MapService'

const myMapStore = useMyMapStore()
const map = computed(() => myMapStore.myMap?.map)
const route = useRoute()
onMounted(() => {
  if (route.query.mapState) {
    myMapStore.serializedMapState = route.query.mapState as string
    myMapStore.deserializeMapState()
    myMapStore.initMapLayers()
    return
  }
  if (myMapStore.isMapAlreadyBeenMounted) {
    AppLayersService.initApplicationLayers()
    myMapStore.isLayersReorderingAlreadyTriggering = false
    MapService.isLoaded(map.value, () => reloadAtlasMaps())
  } else {
    myMapStore.initMapLayers()
  }
})

function reloadAtlasMaps() {
  for (const item of myMapStore.legendList) {
    if (item.layerType === LayerType.ATLAS_LAYER) {
      myMapStore.updateAtlasLayersVisibility(item.id, false)
    }
  }
  myMapStore.setMapLayersOrderOnMapReMount()
}
</script>

<style lang="scss">
.MyMapView {
  display: flex;
  flex-flow: column nowrap;
  height: 100vh;
  overflow: hidden;
  .MyMapView__header {
    height: $mymap-header-h;
  }
  .MyMapView__mapCtn {
    display: flex;
    flex-flow: row nowrap;
    flex: 1 0 auto;

    .MyMapView__welcomeSideBar,
    .MyMapView__layersSideBar {
      z-index: 1;
      transition: all 0.15s ease-in;

      &[shown='false'] {
        overflow-x: hidden;
        width: 0;
        padding-left: 0;
        padding-right: 0;
      }
    }
  }
}
</style>
