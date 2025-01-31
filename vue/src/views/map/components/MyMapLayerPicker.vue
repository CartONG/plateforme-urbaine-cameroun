<template>
  <div class="MyMapLayerPicker" v-if="mainLayer">
    <div class="MyMapLayerPicker__parentBlock">
      <v-checkbox
        color="main-blue"
        :indeterminate="isIndeterminate"
        hide-details="auto"
        density="compact"
        v-model="mainLayer.isShown"
        class="text-body-2"
      >
        <template v-slot:label>
          <div class="MyMapLayerPicker__descCtn">
            <img :src="mainLayer.icon" :alt="mainLayer.name" />
            <span>{{ mainLayer.name }}</span>
          </div>
        </template>
      </v-checkbox>
      <div class="MyMapLayerPicker__actions">
        <v-btn
          v-if="subLayers"
          variant="text"
          density="comfortable"
          icon="mdi-layers"
          :color="isExpanded ? 'main-blue' : 'dark-grey'"
          @click="isExpanded = !isExpanded"
        />
        <v-menu
          v-if="withActions"
          location="bottom"
          @update:modelValue="isLayerOpacityShown = mainLayer?.opacity && mainLayer?.opacity < 100"
        >
          <template v-slot:activator="{ props, isActive }">
            <v-btn
              v-bind="props"
              variant="text"
              density="comfortable"
              icon="mdi-dots-horizontal"
              :color="isActive ? 'main-blue' : 'dark-grey'"
            />
          </template>
          <v-list class="MyMapLayerPicker__additionnalMenu mt-4">
            <v-list-item>
              <template v-slot:prepend>
                <v-icon color="main-blue" icon="mdi-information-outline"></v-icon>
              </template>
              <v-list-item-title>{{ $t('myMap.rightSidebar.actions.about') }}</v-list-item-title>
            </v-list-item>
            <v-list-item>
              <template v-slot:prepend
                ><v-icon color="main-blue" icon="mdi-share-variant-outline"></v-icon
              ></template>
              <v-list-item-title>{{ $t('myMap.rightSidebar.actions.share') }}</v-list-item-title>
            </v-list-item>
            <v-list-item @click="downloadSourceData">
              <template v-slot:prepend>
                <v-icon color="main-blue" icon="mdi-download-outline"></v-icon>
              </template>
              <v-list-item-title>{{
                $t('myMap.rightSidebar.actions.downloadLayer')
              }}</v-list-item-title>
            </v-list-item>
            <v-list-item @click.stop="isLayerOpacityShown = !isLayerOpacityShown">
              <template v-slot:prepend>
                <v-icon color="main-blue" icon="mdi-opacity"></v-icon>
              </template>
              <v-list-item-title>{{ $t('myMap.rightSidebar.actions.opacity') }}</v-list-item-title>
            </v-list-item>
            <MyMapLayerOpacityPicker
              v-if="isLayerOpacityShown"
              v-model="mainLayer.opacity"
              @click.prevent.stop
            />
          </v-list>
        </v-menu>
      </div>
    </div>
    <div class="MyMapLayerPicker__listBlock" :is-shown="isExpanded" v-if="subLayers">
      <div class="MyMapLayerPicker__listBlockWrapper">
        <v-checkbox
          v-for="(subLayer, key) in subLayers"
          v-model="subLayer.isShown"
          @update:model-value="changeSubLayer"
          color="main-blue"
          hide-details="auto"
          density="compact"
          class="text-body-2"
          :key="key"
        >
          <template v-slot:label>
            <div class="MyMapLayerPicker__iconCtn">
              <img :src="subLayer.icon" :alt="subLayer.name" v-if="sublayerIcon && subLayer.icon" />
              <span class="text-capitalize" :class="{ 'ml-1': sublayerIcon && subLayer.icon }">{{
                subLayer.name
              }}</span>
            </div>
          </template>
        </v-checkbox>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Layer } from '@/models/interfaces/map/Layer'
import { debounce, downloadJson } from '@/services/utils/UtilsService'
import { useMyMapStore } from '@/stores/myMapStore'
import { computed, ref, watch, type ModelRef } from 'vue'
import MyMapLayerOpacityPicker from '@/views/map/components/MyMapLayerOpacityPicker.vue'

const isExpanded = ref(false)
const isLayerOpacityShown = ref(false)
const mainLayer: ModelRef<Layer | undefined> = defineModel('mainLayer')
const subLayers: ModelRef<Layer[] | undefined> = defineModel('subLayers')
const myMapStore = useMyMapStore()
const emits = defineEmits(['update'])

withDefaults(
  defineProps<{
    sublayerIcon?: boolean
    withActions?: boolean
  }>(),
  {
    sublayerIcon: false,
    withActions: true
  }
)

const isIndeterminate = computed(() => {
  if (subLayers.value == undefined) return false
  return disabledLayers.value.length !== 0 && disabledLayers.value.length !== subLayers.value.length
})

const disabledLayers = computed(() => {
  return subLayers.value?.filter((subLayer: Layer) => subLayer.isShown === false) ?? []
})

watch(
  () => mainLayer.value?.isShown,
  (newValue) => {
    editAllSubLayers(newValue)
  }
)

watch(
  () => mainLayer.value?.opacity,
  (newValue) => {
    if (!mainLayer.value || !newValue) return
    changeLayerOpacity(mainLayer.value, newValue)
  }
)

const changeLayerOpacity = debounce(async (layer: Layer, opacityPercentage: number) => {
  if (mainLayer.value) {
    const opacity = opacityPercentage / 100
    myMapStore.myMap?.setPaintProperty(layer.id.toString(), 'icon-opacity', opacity)
  }
}, 100)

const changeSubLayer = () => {
  if (mainLayer.value) {
    if (disabledLayers.value.length === 0) {
      mainLayer.value.isShown = true
    } else if (disabledLayers.value.length === subLayers.value?.length) {
      mainLayer.value.isShown = false
    }
  }
  emits('update')
}

const editAllSubLayers = (show = true) => {
  if (myMapStore.deserializedMapState) return
  if (subLayers.value === undefined) return
  subLayers.value.forEach((subLayer, key) => {
    if (subLayers.value) {
      subLayers.value[key].isShown = show
    }
  })
  emits('update')
}

const downloadSourceData = async () => {
  const layerId = mainLayer.value?.id.toString()
  if (myMapStore.myMap?.map && layerId) {
    const data = await myMapStore.myMap?.getData(layerId)
    if (data) {
      downloadJson(data, layerId)
    }
  }
}
</script>

<style lang="scss">
.MyMapLayerPicker {
  display: flex;
  flex-flow: column nowrap;
  width: 100%;
  .MyMapLayerPicker__parentBlock {
    display: flex;
    flex-flow: row nowrap;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 0.25rem 0.5rem;
    border: solid 1px rgb(var(--v-theme-dark-grey));
    border-radius: $dim-radius;
    .v-label {
      opacity: 1;
    }
    .MyMapLayerPicker__descCtn {
      display: flex;
      flex-flow: row nowrap;
      align-items: center;
      gap: 0.5rem;
      margin: 0 0.25rem;
      flex: 1 0 auto;
      img {
        max-width: 1.8rem;
      }
      span {
        color: rgb(var(--v-theme-main-blue));
        font-weight: bold;
        font-size: $font-size-sm;
      }
    }
    .MyMapLayerPicker__actions {
      display: flex;
      flex-flow: row nowrap;
      align-items: center;

      .MyMapLayerPicker__additionnalMenu {
        margin-right: -0.375rem;
      }
    }
  }

  .MyMapLayerPicker__iconCtn {
    display: flex;
    flex-flow: row nowrap;
    align-items: center;
    img {
      width: 1rem;
    }
  }

  .MyMapLayerPicker__listBlock {
    display: grid;
    grid-template-rows: 0fr;
    transition: all 0.15s ease-in;
    border-radius: $dim-radius;
    border: solid 1px transparent;
    max-height: 11.5rem;

    &[is-shown='true'] {
      grid-template-rows: 1fr;
      border: solid 1px rgb(var(--v-theme-main-grey));
      .MyMapLayerPicker__listBlockWrapper {
        padding: 0.5rem 0.75rem;
      }
    }

    .MyMapLayerPicker__listBlockWrapper {
      padding: 0 0.75rem;
      display: flex;
      border-color: transparent;
      flex-flow: column nowrap;
      transition: all 0.15s ease-in;
      gap: 0.25rem;
      overflow-y: auto;
      .v-checkbox .v-selection-control {
        --v-input-control-height: 18px;
        gap: 0.25rem;
      }

      label {
        font-size: $font-size-sm;
        opacity: 1;
      }
    }
  }
}
</style>
