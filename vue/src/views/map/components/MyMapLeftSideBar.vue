<template>
  <div class="MyMapLeftSideBar">
    <MyMapWelcomeBlock />
    <MyMapAtlasesList
      :title="$t('atlas.predefinedMap')"
      :type="AtlasGroup.PREDEFINED_MAP"
      :atlases="
        atlasStore.atlasList
          .filter((atlas) => atlas.atlasGroup === AtlasGroup.PREDEFINED_MAP)
          .sort((a, b) => a.position - b.position)
      "
      v-if="atlasStore.atlasList.length > 0"
    />
  </div>
</template>

<script setup lang="ts">
import MyMapWelcomeBlock from '@/views/map/components/MyMapWelcomeBlock.vue'
import MyMapAtlasesList from '@/views/map/components/Atlases/MyMapAtlasesList.vue'
import { AtlasGroup } from '@/models/enums/geo/AtlasGroup'
import { useAtlasStore } from '@/stores/atlasStore'

const atlasStore = useAtlasStore()
</script>

<style lang="scss">
.MyMapLeftSideBar {
  display: flex;
  flex-flow: column nowrap;
  width: 21rem;
  flex: 1 0 auto;
  background: #fff;
  padding: 1rem;
}
</style>
