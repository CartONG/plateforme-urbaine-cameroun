<template>
  <div class="MyMapRightSideBar">
    <MyMapPlatformLayers />
    <MyMapAtlasesList
      :title="$t('atlas.thematicData')"
      :type="AtlasGroup.THEMATIC_DATA"
      :atlases="
        atlasStore.atlasList
          .filter((atlas) => atlas.atlasGroup === AtlasGroup.THEMATIC_DATA)
          .sort((a, b) => a.position - b.position)
      "
      v-if="atlasStore.atlasList.length > 0"
    />
  </div>
</template>

<script setup lang="ts">
import MyMapPlatformLayers from '@/views/map/components/MyMapPlatformLayers.vue'
import MyMapAtlasesList from '@/views/map/components/Atlases/MyMapAtlasesList.vue'
import { useAtlasStore } from '@/stores/atlasStore'
import { AtlasGroup } from '@/models/enums/geo/AtlasGroup'

const atlasStore = useAtlasStore()
</script>

<style lang="scss">
.MyMapRightSideBar {
  display: flex;
  flex-flow: column nowrap;
  width: 30rem;
  background: #fff;
  padding: 1rem;
  gap: 1rem;
  overflow: auto;
  max-height: calc(100vh - $mymap-header-h);
}
</style>
