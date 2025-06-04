<template>
  <v-menu
    location="bottom"
    @update:modelValue="isLayerOpacityShown = !!mainLayer?.opacity && mainLayer?.opacity < 100"
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
        <template v-slot:prepend
          ><v-icon color="main-blue" icon="mdi-share-variant-outline"></v-icon
        ></template>
        <v-list-item-title>{{ $t('myMap.rightSidebar.actions.share') }}</v-list-item-title>
      </v-list-item>
      <v-list-item @click="downloadSourceData">
        <template v-slot:prepend>
          <v-icon color="main-blue" icon="mdi-download-outline"></v-icon>
        </template>
        <v-list-item-title>{{ $t('myMap.rightSidebar.actions.downloadLayer') }}</v-list-item-title>
      </v-list-item>
      <v-list-item @click.stop="isLayerOpacityShown = !isLayerOpacityShown">
        <template v-slot:prepend>
          <v-icon color="main-blue" icon="mdi-opacity"></v-icon>
        </template>
        <v-list-item-title>{{ $t('myMap.rightSidebar.actions.opacity') }}</v-list-item-title>
      </v-list-item>
      <MyMapLayerOpacityPicker
        v-if="isLayerOpacityShown"
        v-model="mainLayer!.opacity"
        @click.prevent.stop
      />
    </v-list>
  </v-menu>
</template>
<script setup lang="ts">
import { NotificationType } from '@/models/enums/app/NotificationType'
import { LayerType } from '@/models/enums/geo/LayerType'
import { QgisMapType } from '@/models/enums/geo/QgisMapType'
import type { AtlasLayer, Layer } from '@/models/interfaces/map/Layer'
import { i18n } from '@/plugins/i18n'
import { addNotification } from '@/services/notifications/NotificationService'
import { QgisMapMaplibreService } from '@/services/qgisMap/QgisMapMaplibreService'
import { downloadJson } from '@/services/utils/UtilsService'
import { useMyMapStore } from '@/stores/myMapStore'
import type { ModelRef } from 'vue'
import MyMapLayerOpacityPicker from './MyMapLayerOpacityPicker.vue'

const isLayerOpacityShown: ModelRef<boolean | undefined> = defineModel('isLayerOpacityShown')
const mainLayer: ModelRef<Layer | undefined> = defineModel('mainLayer')
const myMapStore = useMyMapStore()
const props = defineProps<{ loadedLayerType?: LayerType }>()

const downloadSourceData = async () => {
  const layerId = mainLayer.value?.id.toString()
  if (myMapStore.myMap?.map && layerId) {
    if (props.loadedLayerType === LayerType.APP_LAYER) {
      const data = await myMapStore.myMap?.getData(layerId)
      if (data) {
        downloadJson(data, layerId)
      }
    } else {
      if ((mainLayer.value as AtlasLayer).qgisMapType === QgisMapType.VECTOR) {
        const atlas = myMapStore.atlasMaps.find((atlasMap) => atlasMap.id === layerId)
        if (atlas) {
          const qgisProject = atlas.qgisProjectName
          const qgisLayers = atlas.subLayers.map((subLayer) => subLayer.name)
          QgisMapMaplibreService.getData(qgisProject, qgisLayers)
        }
      } else {
        addNotification(i18n.t('myMap.atlases.dataNotFetchable'), NotificationType.ERROR)
      }
    }
  }
}
</script>
