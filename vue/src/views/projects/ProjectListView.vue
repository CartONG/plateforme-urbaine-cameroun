<template>
  <div class="ProjectsView" :is-project-map-full-width="isProjectMapFullWidth">
    <div class="ProjectsView__listCtn">
      <div class="ProjectsView__listHeader">
        <div class="ProjectsView__listHeaderBlock ProjectsView__listHeaderBlock--top">
          <span class="SectionTitle">
            {{ projectsCount }} {{ $t('projects.projects', projectsCount) }}
          </span>
          <v-btn
            v-if="userStore.userHasRole(UserRoles.EDITOR_PROJECTS) || userStore.userIsAdmin()"
            @click="projectStore.isProjectFormShown = true"
            prepend-icon="$plus"
            color="main-red"
            >{{ $t('projects.form.title.create') }}</v-btn
          >
        </div>
        <div class="ProjectsView__listHeaderBlock ProjectsView__listHeaderBlock--bottom">
          <v-text-field
            v-model="projectStore.filters.searchValue"
            class="ProjectsView__searchBar"
            variant="outlined"
            hide-details="auto"
            :label="$t('filters.search')"
            density="comfortable"
          >
          <template v-slot:prepend-inner>
            <v-icon icon="$magnify" color="main-blue"></v-icon>
          </template>
        </v-text-field>
        <!-- <v-select
            class="ProjectsView__sortSelect fit"
            variant="outlined"
            hide-details="auto"
            density="comfortable"
            :label="$t('filters.sortBy.placeholder')"
            :items="sortOptions"
            @update:model-value="setSortKey"
            item-title="label"
            item-value="value"
          ></v-select> -->
                  <v-btn
          class="ProjectsView__filterBtn"
          variant="outlined"
          color="main-blue"
          @click="projectStore.isFilterModalShown = true"
        >
          <v-img :src="filterIcon" class="ProjectsView__filterBtnIcon" />
          <span class="ProjectsView__filterBtnText">{{ $t('projects.map.filterProjects') }}</span>
        </v-btn>

        <v-btn
          class="ProjectsView__exportBtn"
          variant="outlined"
          color="main-blue"
          :loading="isExporting"
          :disabled="isExporting"
          @click="exportProjects"
        >
          <v-icon :icon="mdiDownload" class="ProjectsView__exportBtnIcon" />
          <span class="ProjectsView__exportBtnText">{{ $t('projects.export.button') }}</span>
        </v-btn>

        <v-btn
          class="ProjectsView__resetFiltersBtn"
          :icon="mdiRefresh"
          variant="text"
          density="comfortable"
          @click="resetFilters"
          :title="$t('labels.reset')"
        ></v-btn>
      </div>
      </div>
      <div class="ProjectsView__list">
        <ProjectCard
          v-for="project in paginatedProjects"
          :key="project.id"
          :project="project"
          @mouseover="setHoveredProject(project.id)"
        />
        <Pagination :items="orderedProjects" v-model="paginatedProjects" />
      </div>
    </div>
    <div class="ProjectsView__mapCtn" v-if="!useApplicationStore().mobile">
      <ProjectMap />
    </div>
  </div>
</template>
<script setup lang="ts">
  import { mdiFilterVariant,mdiDownload, mdiRefresh } from '@mdi/js'
  import filterIcon from '@/assets/images/icons/map/mdi-filter.svg'
  import Pagination from '@/components/global/Pagination.vue'
  import { UserRoles } from '@/models/enums/auth/UserRoles'
  // import { SortKey } from '@/models/enums/SortKey'
  import type { Project } from '@/models/interfaces/Project'
  import { i18n } from '@/plugins/i18n'
  import { useApplicationStore } from '@/stores/applicationStore'
  import { useProjectStore } from '@/stores/projectStore'
  import { useUserStore } from '@/stores/userStore'
  import ProjectCard from '@/views/projects/components/ProjectCard.vue'
  import ProjectMap from '@/views/projects/components/ProjectMap.vue'
  import { computed, onBeforeMount, ref, type Ref } from 'vue'
  import { ProjectExportService } from '@/services/projects/ProjectExportService'


  const userStore = useUserStore()
  const applicationStore = useApplicationStore()
  const projectStore = useProjectStore()
  const isExporting = ref(false)


  // const sortOptions = Object.values(SortKey).map((key) => {
  //   return {
  //     value: key,
  //     label: i18n.t('filters.sortBy.options.' + key)
  //   }
  // })

  const setHoveredProject = (id: string) => {
    projectStore.hoveredProjectId = id
  }

  // const setSortKey = (key: SortKey) => {
  //   projectStore.sortingProjectsSelectedMethod = key
  // }

  onBeforeMount(async () => {
    applicationStore.isLoading = true
    await projectStore.getAll()
    applicationStore.isLoading = false
  })

  const exportProjects = async () => {
    isExporting.value = true
    try {
      await ProjectExportService.exportToExcel(orderedProjects.value)
    } finally {
      isExporting.value = false
    }
  }

  const orderedProjects = computed(() => projectStore.orderedProjects)
  const isProjectMapFullWidth = computed(() => projectStore.isProjectMapFullWidth)
  const projectsCount = computed(() => orderedProjects.value.length)
  const paginatedProjects: Ref<Project[]> = ref([])
  const resetFilters = () => {
    projectStore.resetFilters()
  }
</script>

<style lang="scss">
.ProjectsView {
  display: flex;
  flex-flow: row nowrap;
  gap: 2rem;
  height: 100%;

  &[is-project-map-full-width='true'] {
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
    margin-bottom: 5rem;
    display: flex;
    flex-flow: column nowrap;
    gap: 1rem;
    transition: opacity 0.15s ease-in-out;

    .ProjectsView__listHeader {
      display: flex;
      flex-flow: column nowrap;
      gap: 1.5rem;
      margin-bottom: 1rem;

      .ProjectsView__listHeaderBlock {
        display: flex;
        flex-flow: row nowrap;
        align-items: center;
        gap: 1rem;

        &--top {
          justify-content: space-between;
        }

        &--bottom {
          align-items: stretch; // au lieu de center, pour que tout ait la même hauteur

          .ProjectsView__searchBar {
            .v-field__prepend-inner > .v-icon,
            .v-field__append-inner > .v-icon,
            .v-field__clearable > .v-icon {
              opacity: 1;
            }
          }

          .ProjectsView__filterBtn {
            height: auto;

            :deep(.v-btn__prepend) {
              margin-inline-end: 0.5rem;
            }

            :deep(.v-btn__content) {
              gap: 0;
            }

            .ProjectsView__filterBtnIcon {
              width: 1.25rem;
              height: 1.25rem;
              flex: 0 0 auto;
            }

            @media (max-width: 960px) {
              min-width: 0;
              width: auto;
              padding: 0.5rem;
              aspect-ratio: 1 / 1; // bouton carré

              .ProjectsView__filterBtnText {
                display: none;
              }

              :deep(.v-btn__prepend) {
                margin-inline-end: 0;
              }

              :deep(.v-btn__content) {
                justify-content: center;
              }

              .ProjectsView__filterBtnIcon {
                width: 1.25rem;
                height: 1.25rem;
              }
            }
          }

                    .ProjectsView__exportBtn {
            height: auto;

            :deep(.v-btn__prepend) {
              margin-inline-end: 0.5rem;
            }

            .ProjectsView__exportBtnIcon {
              margin-inline-end: 0.5rem;
            }

            @media (max-width: 960px) {
              min-width: 0;
              width: auto;
              padding: 0.5rem;
              aspect-ratio: 1 / 1;

              .ProjectsView__exportBtnText {
                display: none;
              }

              .ProjectsView__exportBtnIcon {
                margin-inline-end: 0;
              }
            }
          }

          .ProjectsView__resetFiltersBtn {
            align-self: center; // le bouton icône reste centré verticalement, pas étiré
            flex: 0 0 auto;
          }
        }
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
    transition: all 0.15s ease-in;
    border-left: 4px solid rgb(var(--v-theme-light-yellow));
    top: 0;
    max-height: 100vh;
    margin-right: calc(-50vw + 50%);
  }
}
</style>
