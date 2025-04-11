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
          v-model="selectedExpertise"
          :items="expertisesItems"
          :label="$t('actors.expertise')"
        />
        <ListFilterSelect
          v-model="selectedThematic"
          :items="thematicsItems"
          :label="$t('actors.thematic')"
        />
        <ListFilterSelect
          v-model="selectedAdminScope"
          :items="Object.values(AdministrativeScope)"
          :label="$t('actors.adminScope')"
        />
        <ListFilterSelect
          v-model="selectedCategory"
          :items="categoryItems"
          :label="$t('actors.category')"
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
            prepend-icon="mdi-plus"
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
import { useActorsStore } from '@/stores/actorsStore'
import ListFilterBox from '@/views/_layout/list/ListFilterBox.vue'
import ListSortBy from '@/views/_layout/list/ListSortBy.vue'
import ListFilterResetButton from '@/views/_layout/list/ListFilterResetButton.vue'
import ListItems from '@/views/_layout/list/ListItems.vue'
import ListFilterSelect from '@/views/_layout/list/ListFilterSelect.vue'
import ActorCard from '@/views/actors/components/ActorCard.vue'
import { useUserStore } from '@/stores/userStore'
import { computed, ref, type Ref } from 'vue'
import type { Actor } from '@/models/interfaces/Actor'
import type { ActorExpertise } from '@/models/interfaces/ActorExpertise'
import { ActorsCategories } from '@/models/enums/contents/actors/ActorsCategories'
import { useThematicStore } from '@/stores/thematicStore'
import type { Thematic } from '@/models/interfaces/Thematic'
import { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import ListHeader from '@/views/_layout/list/ListHeader.vue'

const actorsStore = useActorsStore()
const userStore = useUserStore()
const thematicsStore = useThematicStore()

const searchQuery = ref('')
const expertisesItems = actorsStore.actorsExpertises
const selectedExpertise: Ref<string[] | null> = ref(null)
const thematicsItems = computed(() => thematicsStore.thematics)
const selectedThematic: Ref<string[] | null> = ref(null)
const selectedAdminScope: Ref<string[] | null> = ref(null)
const categoryItems = Object.values(ActorsCategories)
const selectedCategory: Ref<ActorsCategories[] | null> = ref(null)

const filteredActors = computed(() => {
  let filteredActors = [...actorsStore.actors]

  if (searchQuery.value) {
    filteredActors = searchActors(filteredActors)
  }

  if (selectedExpertise.value && selectedExpertise.value.length > 0) {
    filteredActors = filteredActors.filter((actor: Actor) => {
      return actor.expertises.some((exp) =>
        (selectedExpertise.value as string[]).includes(exp['@id'])
      )
    })
  }
  if (selectedThematic.value && selectedThematic.value.length > 0) {
    filteredActors = filteredActors.filter((actor: Actor) => {
      return actor.thematics.some((thematic) =>
        (selectedThematic.value as string[]).includes(thematic['@id'])
      )
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
  selectedExpertise.value = null
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
      actor.name.toLowerCase().indexOf(searchText) > -1 ||
      actor.acronym.toLowerCase().indexOf(searchText) > -1 ||
      actor.category.toLowerCase().indexOf(searchText) > -1 ||
      actor.expertises.some((exp: ActorExpertise) => exp.name.toLowerCase().includes(searchText)) ||
      actor.thematics.some((thematic: Thematic) =>
        thematic.name.toLowerCase().includes(searchText)
      ) ||
      actor.administrativeScopes.some((scope: AdministrativeScope) =>
        scope.toLowerCase().includes(searchText)
      ) ||
      (actor.officeName && actor.officeName.toLowerCase().indexOf(searchText) > -1) ||
      (actor.officeAddress && actor.officeAddress.toLowerCase().indexOf(searchText) > -1) ||
      (actor.contactName && actor.contactName.toLowerCase().indexOf(searchText) > -1)
  )
}
</script>

<style lang="scss">
@import '@/assets/styles/views/ListView';
</style>
