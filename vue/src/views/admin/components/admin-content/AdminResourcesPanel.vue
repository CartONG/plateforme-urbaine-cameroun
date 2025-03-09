<template>
  <div class="AdminPanel">
    <AdminTopBar
      page="Resources"
      :items="resourceStore.resources"
      :sortingListItems="sortOptions"
      searchKey="name"
      @updateSortingKey="(e) => (sortingResourcesSelectedMethod = e)"
      @create="resourceStore.isResourceFormShown = true"
      @update-search-query="(e) => (searchQuery = e)"
    >
      <template #right-buttons>
        <v-btn @click="showResourceForm" color="main-red">{{ $t('admin.add') }}</v-btn>
      </template>
    </AdminTopBar>
    <AdminTable
      :is-overlay-shown-function="(item) => !(item as Resource).isValidated"
      :items="sortedResources"
      :tableKeys="['name', 'type', 'format', 'updatedAt']"
      :column-widths="['5%', 'auto', '10%', '10%', '15%', '15%']"
    >
      <template #adminTableItemFirst="{ item }">
        <HighlightButton :item-id="(item as Resource).id" />
      </template>
      <template #editContentCell="{ item }">
        <template v-if="!(item as Resource).isValidated">
          <v-btn
            size="small"
            icon="mdi-arrow-right"
            class="text-main-blue"
            @click="editResource(item as Resource, FormType.VALIDATE)"
          ></v-btn>
        </template>
        <template v-else>
          <v-btn
            density="comfortable"
            icon="mdi-pencil-outline"
            @click="editResource(item as Resource)"
            class="mr-2"
          ></v-btn>
          <v-btn density="comfortable" icon="mdi-dots-vertical">
            <v-icon icon="mdi-dots-vertical"></v-icon>
            <v-menu activator="parent" location="left">
              <v-list class="AdminPanel__additionnalMenu">
                <v-list-item @click="resourceStore.deleteResource(item as Resource)">{{
                  $t('resources.admin.delete')
                }}</v-list-item>
              </v-list>
            </v-menu>
          </v-btn>
        </template>
      </template>
    </AdminTable>
  </div>
</template>
<script setup lang="ts">
import type { Resource } from '@/models/interfaces/Resource'
import { useResourceStore } from '@/stores/resourceStore'
import { computed, onBeforeMount, ref } from 'vue'
import AdminTopBar from '@/components/admin/AdminTopBar.vue'
import AdminTable from '@/components/admin/AdminTable.vue'
import { i18n } from '@/plugins/i18n'
import { FormType } from '@/models/enums/app/FormType'
import HighlightButton from '@/components/global/HighlightButton.vue'

const resourceStore = useResourceStore()
const sortingResourcesSelectedMethod = ref('isValidated')
const resources = computed(() => resourceStore.resources)

onBeforeMount(async () => await resourceStore.getAll())

const sortOptions = [
  {
    sortingKey: 'isValidated',
    text: i18n.t('resources.admin.sort.options.resourceToValidate')
  },
  {
    sortingKey: 'name',
    text: i18n.t('resources.admin.sort.options.resourceName')
  }
]
const showResourceForm = () => {
  resourceStore.isResourceFormShown = true
}

const editResource = async (resource: Resource, type: FormType = FormType.EDIT) => {
  resourceStore.editedResourceId = resource.id
  showResourceForm()
}

const sortedResources = computed(() => {
  switch (sortingResourcesSelectedMethod.value) {
    case 'isValidated':
      return filteredResources.value.slice().sort((a: Resource, b: Resource) => {
        if (a.isValidated !== b.isValidated) {
          return Number(a.isValidated) - Number(b.isValidated)
        }
        return a.name.localeCompare(b.name)
      })
    case 'name':
      return filteredResources.value.slice().sort((a: Resource, b: Resource) => {
        return a.name.localeCompare(b.name)
      })
    default:
      return filteredResources.value
  }
})

const searchQuery = ref('')
const filteredResources = computed(() => {
  if (!searchQuery.value) {
    return resources.value
  }
  return resources.value.filter((item: Resource) => {
    return item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  })
})
</script>
