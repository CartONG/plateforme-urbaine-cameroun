<!-- eslint-disable prettier/prettier -->
<template>
  <div class="AdminPanel">
    <AdminTopBar
      page="QgisMaps"
      :items="qgisStore.qgisMaps"
      :sortingListItems="[
        { sortingKey: 'name', text: 'Nom A-Z' },
        { sortingKey: 'name', text: 'Nom Z-A' }
      ]"
      searchKey="name"
      @updateSortingKey="sortingQgisMapsSelectedMethod = $event"
      @update-search-query="searchQuery = $event"
      >
      <template #right-buttons>
        <v-btn @click="createQgisMap" color="main-red">{{ $t('admin.add') }}</v-btn>
      </template>
    </AdminTopBar>
    <QgisMapsListItem
      v-for="item in filteredItems"
      :key="item.id"
      :map="item"
      v-model:form-type="formType"
      v-model:qgis-map-to-edit="qgisMapToEdit"
      v-model:qgis-map-to-visualise="qgisMapToVisualise"
    />

    <QgisMapForm :type="formType" :qgis-map="qgisMapToEdit" v-if="qgisStore.isQgisMapFormShown" />
    <QgisMapVisualiser
      :qgis-map="(qgisMapToVisualise as QgisMap)"
      v-if="qgisStore.isQgisMapVisualiserShown"
    />
  </div>
</template>

<script lang="ts" setup>
import AdminTopBar from '@/components/admin/AdminTopBar.vue'
import QgisMapForm from '@/views/admin/components/admin-maps/QgisMapForm.vue'
import type { QgisMap } from '@/models/interfaces/QgisMap'
import { useQgisMapStore } from '@/stores/qgisMapStore'
import { computed, onMounted, ref, type Ref } from 'vue'
import { FormType } from '@/models/enums/app/FormType'
import QgisMapVisualiser from './QgisMapVisualiser.vue'
import QgisMapsListItem from './QgisMapsListItem.vue'

const qgisStore = useQgisMapStore()
onMounted(async () => {
  await qgisStore.getAll()
})

type mapsSortingMethod = 'nameAZ' | 'nameZA'
const sortingQgisMapsSelectedMethod: Ref<mapsSortingMethod> = ref('nameAZ')
const searchQuery = ref('')

const sortedMaps = computed(() => {
  if (sortingQgisMapsSelectedMethod.value === 'nameAZ') {
    return qgisStore.qgisMaps.slice().sort((a, b) => a.name.localeCompare(b.name))
  } else if (sortingQgisMapsSelectedMethod.value === 'nameZA') {
    return qgisStore.qgisMaps.slice().sort((a, b) => b.name.localeCompare(a.name))
  }
  return qgisStore.qgisMaps.slice()
})

const filteredItems = computed(() => {
  if (!searchQuery.value) {
    return sortedMaps.value
  }
  return sortedMaps.value.filter((item: QgisMap) => {
    return (
      item.description.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  })
})

const formType: Ref<FormType> = ref(FormType.CREATE)
const qgisMapToEdit: Ref<QgisMap | null> = ref(null)
const qgisMapToVisualise: Ref<QgisMap | null> = ref(null)
function createQgisMap() {
  formType.value = FormType.CREATE
  qgisMapToEdit.value = null
  qgisStore.isQgisMapFormShown = true
}
</script>
