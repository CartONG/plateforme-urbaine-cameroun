<template>
    <div class="Actors__header">
        <img src="@/assets/images/Mosaic_actors_page.svg" alt="">
        <div class="Actors__header__description">
            <PageTitle :title="$t('actors.title')"/>
            <span class="mt-6">{{ $t('actors.desc') }}</span>
            <Autocomplete 
                :placeholder="$t('actors.search')"
                :items="actorsStore.actors"
                :customFilter="searchActors"
                item-title="name"
                item-value="id"
                class="mt-6"
                @updateSelect="updateSelectedActor"
                @update:search="searchQuery = $event"
            />
        </div>
    </div>
    <div class="Actors__filters">
        <SectionTitle :title="$t('actors.filtersTitle')" />
        <div class="d-flex w-100 mt-3">
            <div class="w-25">
                <v-select
                    v-model="selectedExpertise"
                    density="compact" variant="outlined"
                    :label="$t('actors.expertise')"
                    :items="expertisesItems"
                    item-title="name"
                    item-value="@id"
                    class="mr-3"
                    multiple
                    clearable
                />
            </div>
            <div class="w-25">
                <v-select
                    v-model="selectedThematic"
                    density="compact" variant="outlined"
                    :label="$t('actors.thematic')"
                    :items="thematicsItems"
                    item-title="name"
                    item-value="@id"
                    class="mr-3"
                    multiple
                    clearable
                />
            </div>
            <div class="w-25">
                <v-select
                    v-model="selectedAdminScope"
                    density="compact" variant="outlined"
                    :label="$t('actors.adminScope')"
                    :items="administrativeScopesItems"
                    item-title="name"
                    item-value="@id"
                    class="mr-3"
                    multiple
                    clearable
                />
            </div>
            <div class="w-25">
                <v-select
                    density="compact" variant="outlined"
                    v-model="selectedCategory"
                    :label="$t('actors.category')"
                    :items="categoryItems"
                    multiple
                    clearable
                />
            </div>
        </div>

        <div class="d-flex w-100 justify-space-between align-center">
            <div class="d-flex ">
                <span class="text-body-2"> {{ `${totalActors.length.toString()} ${$t('admin.member', totalActors.length)}`}}</span>
                <span class="ml-3 cursor-pointer text-caption text-main-blue" @click="resetFilters">{{ $t('actors.resetFilters') }}</span>
                <div class="Actors__filteredItemsCount ml-1">{{ filteredActors.length }}</div>
            </div>
            <div>
                <v-btn color="white" class="mr-3">
                    <span>{{ $t('placeholders.sortBy') }}</span><v-icon icon="mdi mdi-arrow-down-drop-circle-outline" class="ml-2"></v-icon>
                    <v-menu activator="parent">
                        <v-list>
                            <v-list-item @click="sortingActorsSelectedMethod = 'name'">{{ $t('actors.name') }}</v-list-item>
                            <v-list-item @click="sortingActorsSelectedMethod = 'acronym'">{{ $t('actors.acronym') }}</v-list-item>
                        </v-list>
                    </v-menu>
                </v-btn>
                <v-btn color="main-red" prepend-icon="mdi-plus" @click="addActor()" v-if="userStore.userIsAdmin() || userStore.userHasRole(UserRoles.EDITOR_ACTORS)">{{ $t('actors.add') }}</v-btn>
            </div>
        </div>
    </div>
    <div class="Actors__content mt-4">
        <v-container class="pa-0">
            <v-row>
                <v-col v-for="actor in paginatedActors" cols="12" md="4">
                    <ActorCard :actor="actor"/>
                </v-col>
            </v-row>
            <v-pagination
                v-model="page"
                :length="totalPages"
                :total-visible="5"
                class="mt-4"
            ></v-pagination>
        </v-container>
    </div>
</template>
<script setup lang="ts">
import { useActorsStore } from '@/stores/actorsStore';
import Autocomplete from '@/components/global/Autocomplete.vue';
import PageTitle from '@/components/text-elements/PageTitle.vue';
import SectionTitle from '@/components/text-elements/SectionTitle.vue';
import ActorCard from '@/views/actors/components/ActorCard.vue';
import { useApplicationStore } from '@/stores/applicationStore';
import { useUserStore } from '@/stores/userStore';
import { computed, reactive, ref, type Ref } from 'vue';
import { UserRoles } from '@/models/enums/auth/UserRoles';
import type { Actor } from '@/models/interfaces/Actor';
import type { ActorExpertise } from '@/models/interfaces/ActorExpertise';
import { ActorsCategories } from '@/models/enums/contents/actors/ActorsCategories';
import { useThematicStore } from '@/stores/thematicStore';
import type { Thematic } from '@/models/interfaces/Thematic';
import type { AdministrativeScope } from '@/models/interfaces/AdministrativeScope';

const appStore = useApplicationStore();
const actorsStore = useActorsStore();
const userStore = useUserStore();
const thematicsStore = useThematicStore()

const searchQuery = ref('');
const expertisesItems = actorsStore.actorsExpertises
const selectedExpertise: Ref<string[] | null> = ref(null);
const thematicsItems = computed(() => thematicsStore.thematics)
const selectedThematic: Ref<string[] | null> = ref(null);
const administrativeScopesItems = actorsStore.actorsAdministrativesScopes
const selectedAdminScope: Ref<string[] | null> = ref(null);
const categoryItems = Object.values(ActorsCategories)
const selectedCategory: Ref<ActorsCategories[] | null> = ref(null);

const totalActors = computed(() => actorsStore.actors.filter(x => x.isValidated))
const filteredActors = computed(() => {
    let filteredActors = [...totalActors.value]

    if (searchQuery.value) {
        filteredActors = filteredActors.filter((actor: Actor) => {
            return actor.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                actor.acronym.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                actor.category.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                actor.expertises.some((exp) => exp.name.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
                actor.thematics.some((thematic) => thematic.name.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
                actor.administrativeScopes.some((scope) => scope.name.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
                actor.officeName.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                actor.officeAddress.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                actor.contactName.toLowerCase().includes(searchQuery.value.toLowerCase())
        })
    }
    if (selectedExpertise.value && selectedExpertise.value.length > 0) {
        filteredActors = filteredActors.filter((actor: Actor) => {
            return actor.expertises.some((exp) =>
                (selectedExpertise.value as string[]).includes(exp["@id"])
            )
        })
    }
    if (selectedThematic.value && selectedThematic.value.length > 0) {
        filteredActors = filteredActors.filter((actor: Actor) => {
            return actor.thematics.some((thematic) =>
                (selectedThematic.value as string[]).includes(thematic["@id"])
            )
        })
    }
    if (selectedAdminScope.value && selectedAdminScope.value.length > 0) {
        filteredActors = filteredActors.filter((actor: Actor) => {
            return actor.administrativeScopes.some((scope) =>
                (selectedAdminScope.value as string[]).includes(scope["@id"])
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
    selectedExpertise.value = null
    selectedThematic.value = null
    selectedAdminScope.value = null
    selectedCategory.value = null
}

const sortingActorsSelectedMethod = ref("name")
const sortedActors = computed(() => {
    if (sortingActorsSelectedMethod.value === "name") {
        return filteredActors.value.slice().sort((a: Actor, b: Actor) => {
            return a.name.localeCompare(b.name);
        })
    }
    if (sortingActorsSelectedMethod.value === "acronym") {
        return filteredActors.value.slice().sort((a: Actor, b: Actor) => {
            return a.acronym.localeCompare(b.acronym);
        })
    }
    return filteredActors.value
})

const page = ref(1);
const itemsPerPage = appStore.mobile ? 5 : 15
const paginatedActors = computed(() => {
      const start = (page.value - 1) * itemsPerPage;
      const end = start + itemsPerPage;
      return sortedActors.value.slice(start, end);
    })
const totalPages = computed(() => Math.ceil(sortedActors.value.length / itemsPerPage));

function addActor() {
    actorsStore.setActorEditionMode(null);
}

function searchActors(value: string, queryText: string, itemText: any) {
    const searchText = queryText.toLowerCase();    
    return (
        itemText.raw.name.toLowerCase().indexOf(searchText) > -1 ||
        itemText.raw.acronym.toLowerCase().indexOf(searchText) > -1 ||
        itemText.raw.category.toLowerCase().indexOf(searchText) > -1 ||
        itemText.raw.expertises.some((exp: ActorExpertise) => exp.name.toLowerCase().includes(searchText)) ||
        itemText.raw.thematics.some((thematic: Thematic) => thematic.name.toLowerCase().includes(searchText)) ||
        itemText.raw.administrativeScopes.some((scope: AdministrativeScope) => scope.name.toLowerCase().includes(searchText)) ||
        itemText.raw.officeName.toLowerCase().indexOf(searchText) > -1 ||
        itemText.raw.officeAddress.toLowerCase().indexOf(searchText) > -1 ||
        itemText.raw.contactName.toLowerCase().indexOf(searchText) > -1
    )
}
function updateSelectedActor(id: string) {
    actorsStore.setSelectedActor(id);
}
</script>

<style>
.Actors__filteredItemsCount{
    background-color: rgb(var(--v-theme-main-green));
    color: white;
    border-radius: 50%;
    height: 1rem;
    width: 1rem;
    font-size: 0.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>