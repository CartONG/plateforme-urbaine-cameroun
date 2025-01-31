<template>
  <div class="AdminPanel">
    <AdminTopBar
      page="Actors"
      :items="actorsStore.actors"
      :sortingListItems="[
        { sortingKey: 'isValidated', text: 'Acteurs à valider' },
        { sortingKey: 'name', text: 'Nom' }
      ]"
      :createFunction="createActor"
      searchKey="name"
      @updateSortingKey="sortingActorsSelectedMethod = $event"
      @update-search-query="searchQuery = $event"
    />
    <AdminTable
      :items="filteredItems"
      :table-keys="['acronym', 'name', 'category']"
      :column-widths="['15%', '40%', '30%', '15%']"
    >
      <template #editContentCell="{ item }">
        <template v-if="!(item as Actor).isValidated">
          <v-btn
            size="small"
            icon="mdi-arrow-right"
            class="text-main-blue"
            @click="editActor(item as Actor)"
          ></v-btn>
        </template>
        <template v-else>
          <v-btn
            density="comfortable"
            icon="mdi-pencil-outline"
            @click="editActor(item as Actor)"
            class="mr-2"
          ></v-btn>
          <v-btn density="comfortable" icon="mdi-dots-vertical">
            <v-icon icon="mdi-dots-vertical"></v-icon>
            <v-menu activator="parent" location="left">
              <v-list class="AdminPanel__additionnalMenu">
                <v-list-item :to="`/actors/${(item as Actor).name}`">{{
                  $t('actors.admin.goToPage')
                }}</v-list-item>
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
import type { Actor } from '@/models/interfaces/Actor'
import { useActorsStore } from '@/stores/actorsStore'
import { computed, ref } from 'vue'
import AdminTopBar from '@/components/admin/AdminTopBar.vue'
import AdminTable from '@/components/admin/AdminTable.vue'
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
      item.acronym.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.category.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  })
})
</script>
