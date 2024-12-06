<template>
  <div class="ListView ListView--resource">
    <ListHeader
      :title="$t('resources.title')"
      :description="$t('resources.desc')"
      :search-placeholder="$t('resources.search')"
      v-model="searchQuery"
    />
    <div class="ListView__filters">
      <ListFilterBox>
        <ListFilterSelect
          v-model="selectedThematic"
          :items="thematicsItems"
          :label="$t('resources.thematic')"
        />
        <ListFilterSelect
          v-model="selectedResourceTypes"
          :items="Object.values(ResourceType)"
          :item-title="(item: ResourceType) => $t('resources.resourceType.' + item)"
          :item-value="(item: ResourceType) => item"
          :label="$t('resources.type')"
        />
      </ListFilterBox>

      <div class="ListView__actions">
        <ListFilterResetButton
          :items-count="resourceStore.resources.length"
          :items-label-key="'resources.resources'"
          :filtered-items-count="filteredResources.length"
          :reset-function="resetFilters"
        />
        <div>
          <ListSortBy>
            <v-list-item @click="sortingResourcesSelectedMethod = 'name'">{{
              $t('resources.name')
            }}</v-list-item>
          </ListSortBy>
          <v-btn
            class="fixed-btn"
            color="main-red"
            prepend-icon="mdi-plus"
            @click="resourceStore.isResourceFormShown = true"
            v-if="userStore.userIsAdmin() || userStore.userHasRole(UserRoles.EDITOR_RESSOURCES)"
            >{{ $t('resources.add') }}</v-btn
          >
        </div>
      </div>
    </div>
    <ListItems :items="sortedResources">
      <template #card="{ item }">
        <ResourceCard :resource="item as Resource" :key="item.id" />
      </template>
    </ListItems>
  </div>
</template>

<script setup lang="ts">
import { useResourceStore } from '@/stores/resourceStore'
import ListFilterBox from '@/views/_layout/list/ListFilterBox.vue'
import ListHeader from '@/views/_layout/list/ListHeader.vue'
import ListSortBy from '@/views/_layout/list/ListSortBy.vue'
import ListFilterResetButton from '@/views/_layout/list/ListFilterResetButton.vue'
import ListItems from '@/views/_layout/list/ListItems.vue'
import ListFilterSelect from '@/views/_layout/list/ListFilterSelect.vue'
import ResourceCard from '@/views/resources/components/ResourceCard.vue'
import { useUserStore } from '@/stores/userStore'
import { computed, ref, type Ref } from 'vue'
import { UserRoles } from '@/models/enums/auth/UserRoles'
import type { Resource } from '@/models/interfaces/Resource'
import { useThematicStore } from '@/stores/thematicStore'
import { onMounted } from 'vue'
import { ResourceType } from '@/models/enums/contents/ResourceType'

const resourceStore = useResourceStore()
const userStore = useUserStore()
const thematicsStore = useThematicStore()

onMounted(async () => {
  await resourceStore.getAll()
})

const thematicsItems = computed(() => thematicsStore.thematics)
const searchQuery = ref('')
const selectedThematic: Ref<string[]> = ref([])
const selectedResourceTypes: Ref<ResourceType[]> = ref([])

const filteredResources = computed(() => {
  let filteredResources = [...resourceStore.resources]

  if (searchQuery.value) {
    filteredResources = searchResources(filteredResources)
  }

  // if (selectedThematic.value && selectedThematic.value.length > 0) {
  //     filteredResources = filteredResources.filter((resource: Resource) => {
  //         return resource.thematics.some((thematic) =>
  //             (selectedThematic.value as string[]).includes(thematic["@id"])
  //         )
  //     })
  // }
  if (selectedResourceTypes.value && selectedResourceTypes.value.length > 0) {
    filteredResources = filteredResources.filter((resource: Resource) => {
      return selectedResourceTypes.value.includes(resource.type)
    })
  }
  return filteredResources
})

function resetFilters() {
  searchQuery.value = ''
  selectedThematic.value = []
  selectedResourceTypes.value = []
}

const sortingResourcesSelectedMethod = ref('name')
const sortedResources = computed(() => {
  if (sortingResourcesSelectedMethod.value === 'name') {
    return filteredResources.value.slice().sort((a: Resource, b: Resource) => {
      return a.name.localeCompare(b.name)
    })
  }
  return filteredResources.value
})

function searchResources(resources: Resource[]) {
  const searchText = searchQuery.value.toLowerCase()
  return resources.filter((resource) => resource.name.toLowerCase().indexOf(searchText) > -1)
}
</script>

<style lang="scss">
@import '@/assets/styles/views/ListView';
</style>
