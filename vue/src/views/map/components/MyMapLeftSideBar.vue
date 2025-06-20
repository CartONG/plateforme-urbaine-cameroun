<template>
  <div class="MyMapLeftSideBar">
    <MyMapWelcomeBlock />
    <MyMapAtlasesList
      :title="$t('atlas.predefinedMap')"
      :type="AtlasGroup.PREDEFINED_MAP"
      :atlases="atlasesList"
      v-if="atlasesList.length"
    />
  </div>
</template>

<script setup lang="ts">
import { AtlasGroup } from '@/models/enums/geo/AtlasGroup'
import { useAtlasStore } from '@/stores/atlasStore'
import MyMapAtlasesList from '@/views/map/components/Atlases/MyMapAtlasesList.vue'
import MyMapWelcomeBlock from '@/views/map/components/MyMapWelcomeBlock.vue'
import { computed } from 'vue'

const atlasStore = useAtlasStore()
const atlasesList = computed(() =>
  atlasStore.atlasList
    .filter((atlas) => atlas.atlasGroup === AtlasGroup.PREDEFINED_MAP)
    .sort((a, b) => a.position - b.position)
)
</script>

<style lang="scss">
.MyMapLeftSideBar {
  display: flex;
  flex-flow: column nowrap;
  width: 23rem;
  flex: 1 0 auto;
  background: #fff;
  padding: 1rem;
  overflow: auto;
  max-height: calc(100vh - 4rem);
}
</style>
