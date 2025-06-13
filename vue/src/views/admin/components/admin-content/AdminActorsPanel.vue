<template>
  <div class="AdminPanel">
    <AdminTopBar
      page="Actors"
      :items="actorsStore.actors"
      :sortingListItems="[
        { sortingKey: 'isValidated', text: 'Acteurs à valider' },
        { sortingKey: 'name', text: 'Nom' }
      ]"
      searchKey="name"
      @updateSortingKey="sortingActorsSelectedMethod = $event"
      @update-search-query="searchQuery = $event"
    >
      <template #right-buttons>
        <v-btn @click="createActor" color="main-red">{{ $t('admin.add') }}</v-btn>
      </template>
    </AdminTopBar>
    <AdminTable
      :is-overlay-shown-function="(item) => !(item as Actor).isValidated"
      :items="filteredItems"
      :table-keys="['acronym', 'name', 'category']"
      :column-widths="['5%', 'auto', '40%', '30%', '15%']"
    >
      <template #adminTableItemFirst="{ item }">
        <HighlightButton :item-id="(item as Actor).id" />
      </template>
      <template #editContentCell="{ item }">
        <template v-if="!(item as Actor).isValidated">
          <v-btn
            size="small"
            icon="$arrowRight"
            class="text-main-blue"
            @click="editActor(item as Actor)"
          ></v-btn>
        </template>
        <template v-else>
          <v-btn
            density="comfortable"
            icon="$pencilOutline"
            @click="editActor(item as Actor)"
            class="mr-2"
          ></v-btn>
          <v-btn density="comfortable" icon="$dotsVertical">
            <v-icon icon="$dotsVertical"></v-icon>
            <v-menu activator="parent" location="left">
              <v-list class="AdminPanel__additionnalMenu">
                <v-list-item
                  :to="{ name: 'actorProfile', params: { slug: (item as Actor).slug } }"
                  >{{ $t('actors.admin.goToPage') }}</v-list-item
                >
                <v-list-item @click="actorsStore.deleteActor((item as Actor).id)">{{
                  $t('actors.admin.delete')
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
import AdminTable from '@/components/admin/AdminTable.vue'
import AdminTopBar from '@/components/admin/AdminTopBar.vue'
import HighlightButton from '@/components/global/HighlightButton.vue'
import type { Actor } from '@/models/interfaces/Actor'
import { useActorsStore } from '@/stores/actorsStore'
import { computed, ref } from 'vue'
const actorsStore = useActorsStore()
const sortingActorsSelectedMethod = ref('isValidated')

const createActor = () => {
  actorsStore.setActorEditionMode(null)
}

const editActor = async (actor: Actor) => {
  await actorsStore.setSelectedActor(actor.id, false)
  actorsStore.setActorEditionMode(actorsStore.selectedActor)
}
const sortedActors = computed(() => {
  if (sortingActorsSelectedMethod.value === 'isValidated') {
    return actorsStore.actors.slice().sort((a: Actor, b: Actor) => {
      if (a.isValidated !== b.isValidated) {
        return Number(a.isValidated) - Number(b.isValidated)
      }
      return a.name.localeCompare(b.name)
    })
  }
  if (sortingActorsSelectedMethod.value === 'name') {
    return actorsStore.actors.slice().sort((a: Actor, b: Actor) => {
      return a.name.localeCompare(b.name)
    })
  }
  return actorsStore.actors
})

const searchQuery = ref('')
const filteredItems = computed(() => {
  if (!searchQuery.value) {
    return sortedActors.value
  }
  return sortedActors.value.filter((item: Actor) => {
    return (
      (item.acronym && item.acronym.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
      item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.category.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  })
})
</script>
