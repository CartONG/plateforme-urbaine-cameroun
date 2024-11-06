<template>
    <div class="ProjectsView" :is-project-map-full-width="isProjectMapFullWidth">
        <div class="ProjectsView__listCtn">
            <div class="ProjectsView__listHeader">
                <h3 class="ProjectsView__projectCount">{{ projectsCount }} {{ projectsCount > 1 ? $t('projects.projects') : $t('projects.project') }}</h3>
                <v-text-field
                    v-model="projectStore.filters.searchValue"
                    class="ProjectsView__searchBar"
                    variant="outlined"
                    hide-details="auto"
                    :label="$t('filters.search')"
                    density="comfortable"
                >
                    <template v-slot:prepend-inner>
                        <v-icon icon="mdi-magnify" color="main-blue"></v-icon>
                    </template>
                </v-text-field>
                <v-select
                    class="fit"
                    variant="outlined"
                    hide-details="auto"
                    density="comfortable"
                    :label="$t('filters.sortBy.placeholder')"
                    :items="sortOptions"
                    @update:model-value="setSortKey"
                    item-title="label"
                    item-value="value"
                ></v-select>
            </div>
            <div class="ProjectsView__list">
                <ProjectCard
                    v-for="project in paginatedProjects"
                    :key="project.id"
                    :project="project"
                    @mouseover="setHoveredProject(project.id)" />
                <v-pagination
                    v-model="page"
                    :length="totalPages"
                    :total-visible="5"
                    class="mt-4"
                ></v-pagination>
            </div>
        </div>
        <div class="ProjectsView__mapCtn" v-if="!useApplicationStore().mobile">
            <ProjectMap />
        </div>
    </div>
</template>
<script setup lang="ts">
import { computed, onBeforeMount } from 'vue';
import { useProjectStore } from '@/stores/projectStore';
import ProjectCard from '@/views/projects/components/ProjectCard.vue';
import ProjectMap from '@/views/projects/components/ProjectMap.vue';
import { useApplicationStore } from '@/stores/applicationStore';
import { i18n } from '@/assets/plugins/i18n';
import { ref } from 'vue';
import { SortKey } from '@/models/enums/SortKey';
import type { Project } from '@/models/interfaces/Project';

const projectStore = useProjectStore();
const applicationStore = useApplicationStore();

const sortOptions = Object.values(SortKey).map((key) => {
    return {
        value: key,
        label: i18n.t('filters.sortBy.options.' + key)
    }
})

const setHoveredProject = (id: number) => {
    projectStore.hoveredProjectId = id
}

const setSortKey = (key: SortKey) => {
    projectStore.sortingProjectsSelectedMethod = key
}

onBeforeMount(async () => await projectStore.getAll())

const projects = computed(() => projectStore.projects)
const filteredProjects = computed(() => projectStore.filteredProjects)
const isProjectMapFullWidth = computed(() => projectStore.isProjectMapFullWidth)
const projectsCount = computed(() => filteredProjects.value.length)
const projectNames = computed(() => projects.value.map((project: Project) => project.name))

const page = ref(1);
const itemsPerPage = ref(applicationStore.mobile ? 5 : 10)
const paginatedProjects = computed(() => {
    const start = (page.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return projectStore.orderedProjects.slice(start, end);
})
const totalPages = computed(() => Math.ceil(projectsCount.value / itemsPerPage.value));
</script>

<style lang="scss">

.ProjectsView {
    display: flex;
    flex-flow: row nowrap;
    gap: 2rem;
    height: 100%;

    &[is-project-map-full-width="true"] {
        
        .ProjectsView__mapCtn {
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
            width: 100vw;
            height: inherit;
            border-left-width: 0;
        }
        .ProjectsView__listCtn {
            opacity: 0;
            display: none;
            position: absolute;
            pointer-events: none;
        }
    }
    .ProjectsView__listCtn {
        flex: 1 0 55%;
        margin-top: 3rem;
        display: flex;
        flex-flow: column nowrap;
        gap: 1rem;
        transition: opacity 0.15s ease-in-out;

        .ProjectsView__listHeader {
            display: flex;
            flex-flow: row nowrap;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;

            .ProjectsView__searchBar {
                .v-field__prepend-inner > .v-icon, .v-field__append-inner > .v-icon, .v-field__clearable > .v-icon {
                    opacity: 1;
                }
            }
            .ProjectsView__projectCount {
                flex: 1 0 auto;
                margin-left: 1.5rem;
                color: rgb(var(--v-theme-main-blue));
            }
        }
        .ProjectsView__list {
            display: flex;
            flex-flow: column nowrap;
            gap: 2rem;
        }
    }
    .ProjectsView__mapCtn {
        flex: 1 0 45%;
        position: sticky;
        transition: all .15s ease-in;
        border-left: 4px solid rgb(var(--v-theme-light-yellow));  
        top: 0;
        max-height: 100vh;
        margin-right: calc(-50vw + 50%);
    }
}
</style>