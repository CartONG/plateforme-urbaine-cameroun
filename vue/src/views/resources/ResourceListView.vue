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
          v-model="selectedResourceFormats"
          :items="Object.values(ResourceFormat)"
          :item-title="(item: ResourceFormat) => $t('resources.resourceFormat.' + item)"
          :item-value="(item: ResourceFormat) => item"
          :label="$t('resources.format')"
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
          <v-btn
            variant="text"
            class="fit-content font-weight-medium flex-basis-auto text-body-3"
            :prepend-icon="arePassedEventsShown ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
            @click="arePassedEventsShown = !arePassedEventsShown"
            >{{
              arePassedEventsShown
                ? $t('resources.hidePassedEvents')
                : $t('resources.showPassedEvents')
            }}</v-btn
          >
          <ListSortBy>
            <v-list-item @click="sortingResourcesSelectedMethod = SortMethod.NAME">{{
              $t('resources.name')
            }}</v-list-item>
            <v-list-item @click="sortingResourcesSelectedMethod = SortMethod.DATE">{{
              $t('resources.date')
            }}</v-list-item>
            <v-list-item @click="sortingResourcesSelectedMethod = SortMethod.UPDATED_AT">{{
              $t('resources.updatedAt')
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
import { ResourceFormat } from '@/models/enums/contents/ResourceFormat'
import { ResourceType } from '@/models/enums/contents/ResourceType'
import { useRoute } from 'vue-router'

const resourceStore = useResourceStore()
const userStore = useUserStore()
const thematicsStore = useThematicStore()
const route = useRoute()

const thematicsItems = computed(() => thematicsStore.thematics)
const searchQuery = ref('')
const arePassedEventsShown = ref(false)
const selectedThematic: Ref<string[]> = ref([])
const selectedResourceFormats: Ref<ResourceFormat[]> = ref([])
const selectedResourceTypes: Ref<ResourceType[]> = ref([])

onMounted(async () => {
  await resourceStore.getAll()
  await thematicsStore.getAll()

  initRouteFilters()
})

const filteredResources = computed(() => {
  let filteredResources = [...resourceStore.resources]

  if (searchQuery.value) {
    filteredResources = searchResources(filteredResources)
  }

  if (!arePassedEventsShown.value) {
    const todayDate = new Date(new Date().setHours(0, 0, 0, 0)).getTime()
    filteredResources = filteredResources.filter((resource: Resource) => {
      if (resource.type === ResourceType.EVENTS) {
        return new Date(resource.startAt).getTime() >= todayDate
      } else {
        return true
      }
    })
  }

  if (selectedThematic.value && selectedThematic.value.length > 0) {
    filteredResources = filteredResources.filter((resource: Resource) => {
      return resource.thematics.some((thematic) =>
        (selectedThematic.value as string[]).includes(thematic['@id'])
      )
    })
  }

  if (selectedResourceFormats.value && selectedResourceFormats.value.length > 0) {
    filteredResources = filteredResources.filter((resource: Resource) => {
      return selectedResourceFormats.value.includes(resource.format)
    })
  }

  if (selectedResourceTypes.value && selectedResourceTypes.value.length > 0) {
    filteredResources = filteredResources.filter((resource: Resource) => {
      return selectedResourceTypes.value.includes(resource.type)
    })
  }
  return filteredResources
})

enum SortMethod {
  NAME = 'name',
  DATE = 'date',
  UPDATED_AT = 'updatedAt'
}

const sortingResourcesSelectedMethod: Ref<SortMethod> = ref(SortMethod.NAME)

function getResourcesByDate() {
  const eventResources = filteredResources.value.filter((resource: Resource) => {
    return resource.type === ResourceType.EVENTS
  })
  const otherResources = filteredResources.value.filter((resource: Resource) => {
    return resource.type !== ResourceType.EVENTS
  })
  return [
    ...eventResources.slice().sort((a: Resource, b: Resource) => {
      return new Date(a.startAt).getTime() - new Date(b.startAt).getTime()
    }),
    ...otherResources
  ]
}

const sortedResources = computed(() => {
  switch (sortingResourcesSelectedMethod.value) {
    case SortMethod.DATE:
      return getResourcesByDate()
    case SortMethod.NAME:
      return filteredResources.value.slice().sort((a: Resource, b: Resource) => {
        return a.name.localeCompare(b.name)
      })
    case SortMethod.UPDATED_AT:
    default:
      return filteredResources.value.slice().sort((a: Resource, b: Resource) => {
        return new Date(b.updatedAt).getTime() - new Date(a.updatedAt).getTime()
      })
  }
})

const initRouteFilters = () => {
  if (Object.values(ResourceType).includes(route.query.type as ResourceType)) {
    selectedResourceTypes.value = [route.query.type as ResourceType]
    if (route.query.type === ResourceType.EVENTS) {
      arePassedEventsShown.value = false
      sortingResourcesSelectedMethod.value = SortMethod.DATE
    }
  }
}

const searchResources = (resources: Resource[]) => {
  const searchText = searchQuery.value.toLowerCase()
  return resources.filter((resource) => resource.name.toLowerCase().indexOf(searchText) > -1)
}

const resetFilters = () => {
  searchQuery.value = ''
  arePassedEventsShown.value = false
  selectedThematic.value = []
  selectedResourceFormats.value = []
  selectedResourceTypes.value = []
}
</script>

<style lang="scss">
@import '@/assets/styles/views/ListView';
</style>
