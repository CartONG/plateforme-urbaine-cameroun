<template>
  <div class="MyMapAtlasSummary" :type="type">
    <div class="d-flex flex-row flex-wrap">
      <div class="MyMapAtlasSummary_logo" :type="type">
        <img loading="lazy" :src="atlas.logo.contentUrl" v-if="atlas.logo" />
      </div>
      <div class="MyMapAtlasSummary_desc">
        <v-tooltip :text="atlas.name" location="top" v-if="atlas.name.length > 19">
          <template v-slot:activator="{ props }">
            <div class="MyMapAtlasSummary_title" v-bind="props">
              {{ reduceText(atlas.name, 19) }}
            </div>
          </template>
        </v-tooltip>
        <div class="MyMapAtlasSummary_title" v-else>
          {{ atlas.name }}
        </div>
        <div class="MyMapAtlasSummary_details">
          {{ filteredLength }}
          {{
            type === AtlasGroup.PREDEFINED_MAP
              ? $t('myMap.atlases.map', { count: filteredLength })
              : $t('myMap.atlases.data', { count: filteredLength })
          }}
        </div>
      </div>
    </div>
    <v-btn size="small" icon="$arrowRight" class="text-dark-grey" @click="setActiveAtlas"></v-btn>
  </div>
</template>
<script setup lang="ts">
import { AtlasGroup } from '@/models/enums/geo/AtlasGroup'
import type { Atlas } from '@/models/interfaces/Atlas'
import { reduceText } from '@/services/utils/UtilsService'
import { useMyMapStore } from '@/stores/myMapStore'
import { computed, inject, type Ref } from 'vue'

const props = defineProps<{
  atlas: Atlas
  type: AtlasGroup
}>()
const hideDetails: Ref<boolean> = inject('hideDetails') as Ref<boolean>
const mapStore = useMyMapStore()

function setActiveAtlas() {
  hideDetails.value = false
  if (props.type === AtlasGroup.PREDEFINED_MAP) {
    mapStore.activeAtlas.leftPanel.active = true
    mapStore.activeAtlas.leftPanel.atlasID = props.atlas['@id']
  } else {
    mapStore.activeAtlas.rightPanel.active = true
    mapStore.activeAtlas.rightPanel.atlasID = props.atlas['@id']
  }
}

const filteredLength = computed(() => {
  if (
    props.type === AtlasGroup.PREDEFINED_MAP ||
    !mapStore.atlasSearchText ||
    props.atlas.name.toLowerCase().includes(mapStore.atlasSearchText.toLowerCase())
  ) {
    return props.atlas.maps.length
  }
  return props.atlas.maps.filter(
    (map) =>
      map.name.toLowerCase().includes(mapStore.atlasSearchText.toLowerCase()) ||
      map.qgisProject.layers?.some((layer) =>
        layer.toLowerCase().includes(mapStore.atlasSearchText.toLowerCase())
      )
  ).length
})
</script>

<style>
.MyMapAtlasSummary {
  display: flex;
  width: 100%;
  justify-content: space-between;
  border-radius: 5px;
  padding: 0.5rem 1rem;
}
.MyMapAtlasSummary[style*='display: none'] {
  display: none !important;
}
.MyMapAtlasSummary[type='Catalogue'] {
  border: 1px solid rgb(var(--v-theme-dark-grey));
}

.MyMapAtlasSummary[type='Observatoire'] {
  border: 1px solid rgb(var(--v-theme-main-blue));
  box-shadow: 0px 2px 0px 0px rgb(var(--v-theme-main-blue));
  padding: 0.5rem 1rem;
}
.MyMapAtlasSummary_logo {
  display: flex;
  align-items: center;
  justify-content: center;
  img {
    width: 2rem;
    height: 2rem;
    object-fit: cover;
    margin-right: 10px;
    border-radius: 50%;
  }
  &[type='Observatoire'] {
    img {
      width: 3rem;
      height: 3rem;
    }
  }
}

.MyMapAtlasSummary_desc {
  display: flex;
  flex-flow: column nowrap;
  align-items: flex-start;
  justify-content: center;
}

.MyMapAtlasSummary_title {
  font-weight: 600;
}
</style>
