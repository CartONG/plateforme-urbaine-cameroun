<template>
  <div class="MyMapRightSideBar">
    <div v-show="!mapStore.isMapExportActive" class="MyMapRightSideBar__subCtn">
      <MyMapPlatformLayers />
      <MyMapAtlasesList
        :title="$t('atlas.thematicData')"
        :type="AtlasGroup.THEMATIC_DATA"
        :atlases="filteredAtlases"
        search-box
        v-if="atlasStore.atlasList.length > 0"
      />
    </div>
    <div v-if="mapStore.isMapExportActive" class="MyMapRightSideBar__subCtn">
      <MyMapExportMenu />
    </div>
  </div>
</template>

<script setup lang="ts">
import { AtlasGroup } from '@/models/enums/geo/AtlasGroup'
import { useAtlasStore } from '@/stores/atlasStore'
import { useMyMapStore } from '@/stores/myMapStore'
import MyMapAtlasesList from '@/views/map/components/Atlases/MyMapAtlasesList.vue'
import MyMapPlatformLayers from '@/views/map/components/MyMapPlatformLayers.vue'
import { computed } from 'vue'
import MyMapExportMenu from './export/MyMapExportMenu.vue'

const atlasStore = useAtlasStore()
const mapStore = useMyMapStore()
const filteredAtlases = computed(() => {
  return atlasStore.atlasList
    .filter((atlas) => atlas.atlasGroup === AtlasGroup.THEMATIC_DATA)
    .sort((a, b) => a.position - b.position)
    .filter((atlas) => {
      if (!mapStore.atlasSearchText) {
        return true
      }
      return (
        atlas.name.toLowerCase().includes(mapStore.atlasSearchText.toLowerCase()) ||
        atlas.maps.some((map) =>
          map.name.toLowerCase().includes(mapStore.atlasSearchText.toLowerCase())
        ) ||
        atlas.maps
          .map((map) =>
            map.qgisProject.layers?.some((layer) =>
              layer.toLowerCase().includes(mapStore.atlasSearchText.toLowerCase())
            )
          )
          .some(Boolean)
      )
    })
})
</script>

<style lang="scss">
.MyMapRightSideBar {
  display: flex;
  flex-flow: column nowrap;
  width: 38rem;
  background: #fff;
  padding: 1rem;
  gap: 1rem;
  overflow: auto;
  max-height: calc(100vh - $mymap-header-h);
}
.MyMapRightSideBar__subCtn {
  display: flex;
  flex-flow: column nowrap;
  gap: 1rem;
  width: 100%;
  height: 100%;
  max-height: calc(100vh - $mymap-header-h);
}
</style>
