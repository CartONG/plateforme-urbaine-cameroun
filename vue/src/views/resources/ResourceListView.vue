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
          :items="Object.values(Thematic)"
          :label="$t('resources.thematic')"
        />
        <v-select
          class="ListFilterSelect"
          v-model="selectedODD"
          density="compact"
          variant="outlined"
          :label="$t('forms.odds.title')"
          :items="Object.values(ODD)"
          :item-title="(item) => $t('forms.odds.' + item)"
          :item-value="(item) => item"
          multiple
          clearable
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
        <ListFilterSelect
          v-model="selectedAdminScope"
          :items="Object.values(AdministrativeScope)"
          :label="$t('actors.adminScope')"
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
            :prepend-icon="arePassedEventsShown ? '$eyeOffOutline' : '$eyeOutline'"
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
            prepend-icon="$plus"
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
import { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import { UserRoles } from '@/models/enums/auth/UserRoles'
import { ODD } from '@/models/enums/contents/ODD'
import { ResourceFormat } from '@/models/enums/contents/ResourceFormat'
import { ResourceType } from '@/models/enums/contents/ResourceType'
import { Thematic } from '@/models/enums/contents/Thematic'
import type { Resource } from '@/models/interfaces/Resource'
import { useApplicationStore } from '@/stores/applicationStore'
import { useResourceStore } from '@/stores/resourceStore'
import { useUserStore } from '@/stores/userStore'
import ListFilterBox from '@/views/_layout/list/ListFilterBox.vue'
import ListFilterResetButton from '@/views/_layout/list/ListFilterResetButton.vue'
import ListFilterSelect from '@/views/_layout/list/ListFilterSelect.vue'
import ListHeader from '@/views/_layout/list/ListHeader.vue'
import ListItems from '@/views/_layout/list/ListItems.vue'
import ListSortBy from '@/views/_layout/list/ListSortBy.vue'
import ResourceCard from '@/views/resources/components/ResourceCard.vue'
import { computed, onMounted, ref, type Ref } from 'vue'
import { useRoute } from 'vue-router'

const resourceStore = useResourceStore()
const userStore = useUserStore()
const appStore = useApplicationStore()
const route = useRoute()

const searchQuery = ref('')
const arePassedEventsShown = ref(false)
const selectedThematic: Ref<string[]> = ref([])
const selectedODD: Ref<ODD[] | null> = ref(null)
const selectedResourceFormats: Ref<ResourceFormat[]> = ref([])
const selectedResourceTypes: Ref<ResourceType[]> = ref([])
const selectedAdminScope: Ref<string[] | null> = ref(null)

onMounted(async () => {
  await resourceStore.getAll()
  appStore.isLoading = false

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
        (selectedThematic.value as string[]).includes(thematic)
      )
    })
  }

  if (selectedODD.value && selectedODD.value.length > 0) {
    filteredResources = filteredResources.filter((resource: Resource) => {
      return resource.odds.some((odd) => (selectedODD.value as ODD[]).includes(odd))
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
  if (selectedAdminScope.value && selectedAdminScope.value.length > 0) {
    filteredResources = filteredResources.filter((resource: Resource) => {
      return resource.administrativeScopes.some((scope) =>
        (selectedAdminScope.value as string[]).includes(scope)
      )
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
  selectedODD.value = null
  selectedAdminScope.value = null
  selectedThematic.value = []
  selectedResourceFormats.value = []
  selectedResourceTypes.value = []
}
</script>

<style lang="scss">
@import '@/assets/styles/views/ListView';
</style>
