<template>
  <div
    class="AdminTable__row"
    :style="{ gridTemplateColumns: ['50%', '10%', '20%', '20%'].join(' ') }"
  >
    <div class="AdminTable__item d-flex align-center">
      <img
        loading="lazy"
        :src="map.logo.contentUrl"
        v-if="map.logo"
        class="AdminTable__item__logo"
      />
      {{ map.name }}
    </div>
    <div class="AdminTable__item">
      {{ map.qgisProject.layers?.length }}
    </div>
    <div class="AdminTable__item">{{ new Date(map.updatedAt).toLocaleDateString() }}</div>
    <div class="AdminTable__item--last d-flex">
      <v-btn
        density="comfortable"
        :icon="showDetails ? '$arrowUpBoldHexagonOutline' : '$arrowDownBoldHexagonOutline'"
        class="mr-2"
        @click="showDetails = !showDetails"
      ></v-btn>
      <v-btn
        density="comfortable"
        icon="$mapOutline"
        @click="showQgisProjectOnMap(map)"
        class="mr-2"
      ></v-btn>
      <v-btn density="comfortable" icon="$dotsVertical">
        <v-icon icon="$dotsVertical"></v-icon>
        <v-menu activator="parent" location="left">
          <v-list class="AdminPanel__additionnalMenu">
            <v-list-item @click="editQgisMap(map)">{{ $t('forms.edit') }}</v-list-item>
            <v-list-item @click="qgisStore.deleteQgisMap(map)">{{
              $t('actors.admin.delete')
            }}</v-list-item>
          </v-list>
        </v-menu>
      </v-btn>
    </div>
  </div>
  <div v-if="showDetails" class="AdminTable__item">
    <div
      class="AdminTable__qgisMapDetails"
      :style="{ gridTemplateColumns: ['50%', '50%'].join(' ') }"
    >
      <div class="AdminTable__item">
        <v-icon icon="$layersOutline"></v-icon>
        {{ map.qgisProject.layers?.join(', ') }}
      </div>
      <div class="AdminTable__item">
        {{ map.description }}
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { FormType } from '@/models/enums/app/FormType';
import type { QgisMap } from '@/models/interfaces/QgisMap';
import { useQgisMapStore } from '@/stores/qgisMapStore';
import { ref, type ModelRef } from 'vue';

defineProps<{ map: QgisMap }>()
const formType: ModelRef<FormType | undefined> = defineModel('formType')
const qgisMapToEdit: ModelRef<QgisMap | undefined> = defineModel('qgisMapToEdit')
const qgisMapToVisualise: ModelRef<QgisMap | undefined> = defineModel('qgisMapToVisualise')
const showDetails = ref(false)
const qgisStore = useQgisMapStore()

function editQgisMap(qgisMap: QgisMap) {
  formType.value = FormType.EDIT
  qgisMapToEdit.value = qgisMap
  qgisStore.isQgisMapFormShown = true
}

function showQgisProjectOnMap(qgisMap: QgisMap) {
  qgisMapToVisualise.value = qgisMap
  qgisStore.isQgisMapVisualiserShown = true
}
</script>

<style>
.AdminTable__qgisMapDetails {
  display: grid;
  /* height: 2.5rem; */
  align-items: center;
  padding-left: 30px;
  border-bottom: 1px dashed rgb(var(--v-theme-main-grey));
  background-color: rgba(var(--v-theme-main-yellow), 0.2);
}
</style>
