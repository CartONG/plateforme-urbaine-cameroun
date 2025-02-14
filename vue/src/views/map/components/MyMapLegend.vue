<template>
  <div class="maplibregl-ctrl maplibregl-ctrl-group MapLegend" :isOpen="legendIsShown">
    <v-btn
      icon="mdi-layers"
      v-if="!legendIsShown"
      @click.stop="legendIsShown = true"
      class="text-main-blue"
    ></v-btn>
    <div v-else class="MapLegend__ctn">
      <div class="MapLegend__title">
        <FormSectionTitle :text="$t('myMap.legend.title')" />
        <v-icon icon="mdi-close" @click.stop="legendIsShown = false" color="main-blue"></v-icon>
      </div>

      <VueDraggable v-model="mapStore.legendList" @end="updateMainLayerOrder" group="parent">
        <div
          class="MapLegend__itemCtn"
          v-for="item in mapStore.legendList"
          :key="item.id"
          :item-type="item.layerType"
        >
          <div class="MapLegend__item">
            <div class="d-flex align-center">
              <v-icon icon="mdi-drag" color="dark-grey"></v-icon>
              <img :src="item.icon" v-if="item.layerType === layerType.APP_LAYER" />
              <v-icon icon="mdi-layers-outline" color="black" v-else></v-icon>
              <span class="text-subtitle-2 font-weight-medium text-capitalize ml-1">{{
                item.name
              }}</span>
            </div>
            <div class="d-flex align-center">
              <v-icon
                v-if="!('atlasGroup' in item && item.atlasGroup === atlasGroup.PREDEFINED_MAP)"
                color="dark-grey"
                icon="mdi-delete-outline"
                @click="removeMainLayer(item)"
              ></v-icon>
            </div>
          </div>
          <template v-if="item.layerType === layerType.ATLAS_LAYER">
            <VueDraggable
              v-model="item.subLayers"
              @end="updateSubLayerOrder(item)"
              :group="item.id"
            >
              <div
                class="MapLegend__item pl-3"
                v-for="subItem in item.subLayers"
                :key="subItem.name"
              >
                <div class="d-flex align-center">
                  <v-icon icon="mdi-drag" color="dark-grey"></v-icon>
                  <img :src="subItem.icon" />
                  <span class="text-caption text-capitalize ml-1">{{ subItem.name }}</span>
                </div>
                <div class="d-flex align-center">
                  <v-icon
                    v-if="'atlasGroup' in item && item.atlasGroup === atlasGroup.THEMATIC_DATA"
                    color="dark-grey"
                    icon="mdi-delete-outline"
                    class="mr-1"
                    @click="removeSubLayer(item, subItem.name)"
                  ></v-icon>
                </div>
              </div>
            </VueDraggable>
          </template>
        </div>
      </VueDraggable>
    </div>
  </div>
</template>

<script setup lang="ts">
import FormSectionTitle from '@/components/text-elements/FormSectionTitle.vue'
import { useMyMapStore } from '@/stores/myMapStore'
import { ref } from 'vue'
import { VueDraggable } from 'vue-draggable-plus'
import { LayerType } from '@/models/enums/geo/LayerType'
import type { AppLayerLegendItem, AtlasLayerLegendItem } from '@/models/interfaces/map/Legend'
import { ItemType } from '@/models/enums/app/ItemType'
import { AtlasGroup } from '@/models/enums/geo/AtlasGroup'
const layerType = LayerType
const atlasGroup = AtlasGroup

const legendIsShown = ref(false)
const mapStore = useMyMapStore()

function updateMainLayerOrder() {
  mapStore.legendList.forEach((item, index) => {
    item.order = index
  })
  mapStore.updateMapLayersOrder()
}

function updateSubLayerOrder(item: AtlasLayerLegendItem) {
  item.subLayers.forEach((subItem, index) => {
    subItem.order = index
  })
  mapStore.updateAtlasSubLayersOrder(item)
}

function removeMainLayer(item: AtlasLayerLegendItem | AppLayerLegendItem) {
  if (item.layerType === LayerType.ATLAS_LAYER) {
    mapStore.atlasMaps.map((x) => {
      if (x.id === item.id) {
        x.mainLayer.isShown = false
        x.subLayers.map((x) => (x.isShown = false))
      }
    })
  } else {
    switch (item.id) {
      case ItemType.ACTOR:
        mapStore.actorLayer!.isShown = false
        break
      case ItemType.PROJECT:
        mapStore.projectLayer!.isShown = false
        break
      case ItemType.RESOURCE:
        mapStore.resourceLayer!.isShown = false
        break
    }
  }
}

function removeSubLayer(item: AtlasLayerLegendItem, subLayerName: string) {
  mapStore.atlasMaps.map((x) => {
    if (x.id === item.id) {
      x.subLayers.map((x) => {
        if (x.name === subLayerName) {
          x.isShown = false
        }
      })
    }
  })
  mapStore.updateAtlasLayersVisibility(item.id)
}
</script>

<style lang="scss">
.MapLegend {
  flex-flow: row nowrap !important;

  &[isOpen='false'] {
    margin-right: 0.3rem !important;
  }
}

.MapLegend__ctn {
  display: flex;
  flex-flow: column nowrap;
  gap: 0.5rem;
  width: 15rem;
  background-color: white;
  // max-height: 20rem;
  padding: 0.5rem;
  border-radius: 5%;
}
.MapLegend__title {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.MapLegend__itemCtn {
  display: flex;
  flex-flow: column nowrap;
  &[item-type='ATLAS_LAYER'] {
    border: 1px solid rgb(var(--v-theme-main-grey));
    border-radius: 5px;
  }
}
.MapLegend__item {
  display: flex;
  flex-flow: row nowrap;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  cursor: grab;

  img {
    width: 1rem;
    height: 1rem;
    object-fit: cover;
    margin-left: 0.1rem;
  }
}
</style>
