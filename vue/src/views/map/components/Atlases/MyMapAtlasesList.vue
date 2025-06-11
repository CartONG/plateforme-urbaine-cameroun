<template>
  <div class="MyMapAtlases">
    <div class="MyMapAtlases__title" v-show="isShown">{{ title }}</div>
    <div v-if="searchBox">
      <v-text-field
        variant="outlined"
        v-model="mapStore.atlasSearchText"
        :label="$t('myMap.atlases.searchData')"
        prepend-inner-icon="$magnify"
        clearable
        color="main-blue"
        icon-color="main-blue"
      />
    </div>
    <MyMapAtlas v-for="atlas in atlases" :key="atlas.id" :atlas="atlas" :type="type" />
  </div>
</template>

<script setup lang="ts">
import { AtlasGroup } from '@/models/enums/geo/AtlasGroup'
import type { Atlas } from '@/models/interfaces/Atlas'
import { useMyMapStore } from '@/stores/myMapStore'
import MyMapAtlas from '@/views/map/components/Atlases/MyMapAtlas.vue'
import { computed } from 'vue'

const props = defineProps<{
  title: string
  atlases: Atlas[]
  type: AtlasGroup
  searchBox?: boolean
}>()

const mapStore = useMyMapStore()
const isShown = computed(() => {
  if (props.type === AtlasGroup.PREDEFINED_MAP) {
    return !mapStore.activeAtlas.leftPanel.active
  } else {
    return !mapStore.activeAtlas.rightPanel.active
  }
})
</script>

<style lang="scss">
.MyMapAtlases {
  display: flex;
  flex-flow: column nowrap;
  gap: 0.5rem;
}

.MyMapAtlases__title {
  color: rgb(var(--v-theme-main-blue));
  font-weight: 600;
}
</style>
