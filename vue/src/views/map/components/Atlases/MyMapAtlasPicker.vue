<template>
  <div
    class="MyMapAtlasPicker mt-3"
    v-for="atlasMap in atlasMaps"
    :key="atlasMap.id"
    :active="atlasMap.mainLayer.isShown"
  >
    <v-checkbox
      hide-details
      v-model="atlasMap.mainLayer.isShown"
      @update:modelValue="(newValue) => handleCheckboxChange(atlasMap.id, newValue as boolean)"
    />
    <div class="MyMapAtlasPicker__descCtn">
      <img :src="atlasMap.mainLayer.icon" :alt="atlas.name" />
      <div class="MyMapAtlasPicker__desc ml-2">
        <span>{{ atlasMap.mainLayer.name }}</span>
        <span>
          {{ atlasMap.subLayers.length }}
          {{ $t('myMap.atlases.map', { count: atlasMap.subLayers.length }) }}
        </span>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import type { Atlas } from '@/models/interfaces/Atlas'
import type { AtlasMap } from '@/models/interfaces/map/AtlasMap'
import { useMyMapStore } from '@/stores/myMapStore'
import { computed, type Ref } from 'vue'
const props = defineProps<{
  atlas: Atlas
}>()

const myMapStore = useMyMapStore()
const atlasMaps: Ref<AtlasMap[]> = computed(() =>
  myMapStore.atlasMaps.filter((x) => x.atlasId === props.atlas['@id'])
)
const emits = defineEmits(['update'])
function handleCheckboxChange(id: string, value: boolean) {
  emits('update', id, value)
}
</script>

<style lang="scss">
.MyMapAtlasPicker {
  display: flex;
  align-items: center;
  width: 100%;
  border-radius: 5px;
  padding: 0.25rem 0.5rem;
  border: 1px solid rgb(var(--v-theme-dark-grey));
  background-color: white;
}
.MyMapAtlasPicker[active='true'] {
  border: 1px solid rgb(var(--v-theme-main-blue));
  box-shadow: 0px 2px 0px 0px rgb(var(--v-theme-main-blue));
  background-color: rgb(var(--v-theme-light-grey));
}
.MyMapAtlasPicker__descCtn {
  display: flex;
  align-items: center;

  img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
  }
}

.MyMapAtlasPicker__desc {
  display: flex;
  flex-direction: column;
}
</style>
