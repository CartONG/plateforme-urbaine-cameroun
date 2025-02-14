<template>
  <div class="MyMapAtlas__maps">
    <div class="d-flex flex-row flex-wrap">
      <v-btn
        size="small"
        icon="mdi-arrow-left"
        class="text-dark-grey"
        elevation="4"
        @click="removeActiveAtlas()"
      ></v-btn>
      <div class="MyMapAtlas__desc ml-3">
        <div class="MyMapAtlas__title font-weight-bold">{{ atlas.name }}</div>
        <div class="MyMapAtlas__details">
          {{ atlas.maps.length }}
          {{
            type === AtlasGroup.PREDEFINED_MAP
              ? $t('myMap.atlases.map', { count: atlas.maps.length })
              : $t('myMap.atlases.data', { count: atlas.maps.length })
          }}
        </div>
      </div>
    </div>
    <template v-if="type === AtlasGroup.THEMATIC_DATA">
      <MyMapLayerPicker
        v-for="(qgisMap, index) in myMapStore.atlasMaps.filter(
          (map) => map.atlasId === atlas['@id']
        )"
        :class="index === 0 ? 'mt-3' : 'mt-1'"
        :key="qgisMap.id"
        v-model:main-layer="
          myMapStore.atlasMaps.filter((x) => x.atlasId === atlas['@id'])[index].mainLayer
        "
        v-model:sub-layers="
          myMapStore.atlasMaps.filter((x) => x.atlasId === atlas['@id'])[index].subLayers
        "
        @update="updateThematicData(qgisMap.id)"
        :loaded-layer-type="LayerType.ATLAS_LAYER"
      />
    </template>
    <template v-else>
      <MyMapAtlasPicker :atlas="atlas" @update="updatePreDefinedMap" />
    </template>
  </div>
</template>
<script setup lang="ts">
import { AtlasGroup } from '@/models/enums/geo/AtlasGroup'
import type { Atlas } from '@/models/interfaces/Atlas'
import { inject, type Ref } from 'vue'
import { useMyMapStore } from '@/stores/myMapStore'
import MyMapLayerPicker from '@/views/map/components/MyMapLayerPicker.vue'
import MyMapAtlasPicker from '@/views/map/components/Atlases/MyMapAtlasPicker.vue'
import { LayerType } from '@/models/enums/geo/LayerType'
const hideDetails: Ref<boolean> = inject('hideDetails') as Ref<boolean>
const myMapStore = useMyMapStore()

const props = defineProps<{
  atlas: Atlas
  type: AtlasGroup
}>()

const removeActiveAtlas = () => {
  hideDetails.value = true
  if (props.type === AtlasGroup.PREDEFINED_MAP) {
    myMapStore.activeAtlas.leftPanel.active = false
    myMapStore.activeAtlas.leftPanel.atlasID = null
  } else {
    myMapStore.activeAtlas.rightPanel.active = false
    myMapStore.activeAtlas.rightPanel.atlasID = null
  }
}
const updateThematicData = (qgismapId: string) => {
  myMapStore.updateAtlasLayersVisibility(qgismapId)
}

const updatePreDefinedMap = (id: string, value: boolean) => {
  for (const storeMap of myMapStore.atlasMaps) {
    if (storeMap.id === id) {
      storeMap.mainLayer.isShown = value
      storeMap.subLayers.forEach((subLayer) => {
        subLayer.isShown = value
      })
    }
  }
  myMapStore.updateAtlasLayersVisibility(id)
}
</script>

<style>
.MyMapAtlas__maps {
  display: flex;
  flex-flow: column nowrap;
  align-items: flex-start;
  justify-content: center;
  width: 100%;
}
</style>
