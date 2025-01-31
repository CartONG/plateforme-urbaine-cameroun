<template>
  <div class="MyMapAtlas" :type="type" v-show="isShown">
    <MyMapAtlasSummary :atlas="atlas" :type="type" v-show="hideDetails" />
    <MyMapAtlasDetails :atlas="atlas" :type="type" v-show="!hideDetails" />
  </div>
</template>

<script setup lang="ts">
import { AtlasGroup } from '@/models/enums/geo/AtlasGroup'
import type { Atlas } from '@/models/interfaces/Atlas'
import MyMapAtlasSummary from '@/views/map/components/Atlases/MyMapAtlasSummary.vue'
import MyMapAtlasDetails from '@/views/map/components/Atlases/MyMapAtlasDetails.vue'
import { computed, provide, ref } from 'vue'
import { useMyMapStore } from '@/stores/myMapStore'

const props = defineProps<{
  atlas: Atlas
  type: AtlasGroup
}>()
const hideDetails = ref(true)
provide('hideDetails', hideDetails)

const mapStore = useMyMapStore()

const isShown = computed(() => {
  if (props.type === AtlasGroup.PREDEFINED_MAP) {
    if (
      (mapStore.activeAtlas.leftPanel.active &&
        mapStore.activeAtlas.leftPanel.atlasID === props.atlas['@id']) ||
      !mapStore.activeAtlas.leftPanel.active
    ) {
      return true
    }
    return false
  }
  if (props.type === AtlasGroup.THEMATIC_DATA) {
    if (
      (mapStore.activeAtlas.rightPanel.active &&
        mapStore.activeAtlas.rightPanel.atlasID === props.atlas['@id']) ||
      !mapStore.activeAtlas.rightPanel.active
    ) {
      return true
    }
    return false
  }
  return true
})
</script>

<style lang="scss">
.MyMapAtlas {
  display: flex;
  flex-flow: row nowrap;
  align-items: center;
  justify-content: space-between;
  border-radius: 3px;
  padding: 0.25rem 0.5rem;
}
</style>
