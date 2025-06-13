<template>
  <div class="ListView ListView--actor">
    <ListHeader
      :title="$t('actors.title')"
      :description="$t('actors.desc')"
      :search-placeholder="$t('actors.search')"
      v-model="searchQuery"
    />
    <div class="ListView__filters">
      <ListFilterBox>
        <ListFilterSelect
          v-model="selectedThematic"
          :items="Object.values(Thematic)"
          :label="$t('actors.thematic')"
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
          v-model="selectedCategory"
          :items="categoryItems"
          :label="$t('actors.category')"
        />
        <ListFilterSelect
          v-model="selectedAdminScope"
          :items="Object.values(AdministrativeScope)"
          :label="$t('actors.adminScope')"
        />
      </ListFilterBox>

      <div class="ListView__actions">
        <ListFilterResetButton
          :items-count="actorsStore.actors.length"
          :items-label-key="'actors.actors'"
          :filtered-items-count="filteredActors.length"
          :reset-function="resetFilters"
        />
        <div>
          <ListSortBy>
            <v-list-item @click="sortingActorsSelectedMethod = 'name'">{{
              $t('actors.name')
            }}</v-list-item>
            <v-list-item @click="sortingActorsSelectedMethod = 'acronym'">{{
              $t('actors.acronym')
            }}</v-list-item>
          </ListSortBy>
          <v-btn
            class="fixed-btn"
            color="main-red"
            prepend-icon="$plus"
            @click="addActor()"
            v-if="userStore.userIsAdmin() || userStore.userIsActorEditor()"
            >{{ $t('actors.add') }}</v-btn
          >
        </div>
      </div>
    </div>
    <ListItems :items="sortedActors">
      <template #card="{ item }">
        <ActorCard :actor="item as Actor" :key="item.id" />
      </template>
    </ListItems>
  </div>
</template>

<script setup lang="ts">
import { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import { ActorsCategories } from '@/models/enums/contents/actors/ActorsCategories'
import { ODD } from '@/models/enums/contents/ODD'
import { Thematic } from '@/models/enums/contents/Thematic'
import type { Actor } from '@/models/interfaces/Actor'
import { useActorsStore } from '@/stores/actorsStore'
import { useApplicationStore } from '@/stores/applicationStore'
import { useUserStore } from '@/stores/userStore'
import ListFilterBox from '@/views/_layout/list/ListFilterBox.vue'
import ListFilterResetButton from '@/views/_layout/list/ListFilterResetButton.vue'
import ListFilterSelect from '@/views/_layout/list/ListFilterSelect.vue'
import ListHeader from '@/views/_layout/list/ListHeader.vue'
import ListItems from '@/views/_layout/list/ListItems.vue'
import ListSortBy from '@/views/_layout/list/ListSortBy.vue'
import ActorCard from '@/views/actors/components/ActorCard.vue'
import { computed, onBeforeMount, ref, type Ref } from 'vue'

const actorsStore = useActorsStore()
const userStore = useUserStore()
const applicationStore = useApplicationStore()

onBeforeMount(async () => {
  applicationStore.isLoading = true
  await actorsStore.getActors()
  applicationStore.isLoading = false
})

const searchQuery = ref('')
const selectedThematic: Ref<string[] | null> = ref(null)
const selectedODD: Ref<ODD[] | null> = ref(null)
const selectedAdminScope: Ref<string[] | null> = ref(null)
const categoryItems = Object.values(ActorsCategories)
const selectedCategory: Ref<ActorsCategories[] | null> = ref(null)

const filteredActors = computed(() => {
  let filteredActors = [...actorsStore.actors]

  if (searchQuery.value) {
    filteredActors = searchActors(filteredActors)
  }

  if (selectedThematic.value && selectedThematic.value.length > 0) {
    filteredActors = filteredActors.filter((actor: Actor) => {
      return actor.thematics.some((thematic) =>
        (selectedThematic.value as string[]).includes(thematic)
      )
    })
  }

  if (selectedODD.value && selectedODD.value.length > 0) {
    filteredActors = filteredActors.filter((actor: Actor) => {
      return actor.odds.some((odd) => (selectedODD.value as ODD[]).includes(odd))
    })
  }
  if (selectedAdminScope.value && selectedAdminScope.value.length > 0) {
    filteredActors = filteredActors.filter((actor: Actor) => {
      return actor.administrativeScopes.some((scope) =>
        (selectedAdminScope.value as string[]).includes(scope)
      )
    })
  }
  if (selectedCategory.value && selectedCategory.value.length > 0) {
    filteredActors = filteredActors.filter((actor: Actor) => {
      return (selectedCategory.value as string[]).includes(actor.category)
    })
  }
  return filteredActors
})

function resetFilters() {
  searchQuery.value = ''
  selectedODD.value = null
  selectedThematic.value = null
  selectedAdminScope.value = null
  selectedCategory.value = null
}

const sortingActorsSelectedMethod = ref('name')
const sortedActors = computed(() => {
  if (sortingActorsSelectedMethod.value === 'name') {
    return filteredActors.value.slice().sort((a: Actor, b: Actor) => {
      return a.name.localeCompare(b.name)
    })
  }
  if (sortingActorsSelectedMethod.value === 'acronym') {
    return filteredActors.value.slice().sort((a: Actor, b: Actor) => {
      return a.acronym.localeCompare(b.acronym)
    })
  }
  return filteredActors.value
})

function addActor() {
  actorsStore.setActorEditionMode(null)
}

function searchActors(actors: Actor[]) {
  const searchText = searchQuery.value.toLowerCase()
  return actors.filter(
    (actor) =>
      actor.name?.toLowerCase().indexOf(searchText) > -1 ||
      actor.acronym?.toLowerCase().indexOf(searchText) > -1 ||
      actor.category.toLowerCase().indexOf(searchText) > -1 ||
      actor.administrativeScopes.some((scope: AdministrativeScope) =>
        scope.toLowerCase().includes(searchText)
      ) ||
      actor.officeName?.toLowerCase().indexOf(searchText) > -1 ||
      actor.officeAddress?.toLowerCase().indexOf(searchText) > -1 ||
      actor.contactName?.toLowerCase().indexOf(searchText) > -1
  )
}
</script>

<style lang="scss">
@import '@/assets/styles/views/ListView';
</style>
