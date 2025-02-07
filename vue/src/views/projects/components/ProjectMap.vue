<template>
  <div class="ProjectMap">
    <ProjectFilterModal
      v-if="projectStore.projects != null"
      :show="projectStore.isFilterModalShown"
      @close="projectStore.isFilterModalShown = false"
    />
    <ProjectCard
      class="ProjectCard ProjectCard--light"
      :project="projectStore.activeProject"
      :map="true"
      :active="true"
      ref="active-project-card"
    />
    <Map class="ProjectMap__map" ref="project-map" v-if="projectStore.projects != null" />
    <ShowProjectFiltersModalControl ref="show-project-filters-modal-control" />
    <ToggleSidebarControl
      v-model="projectStore.isProjectMapFullWidth"
      ref="toggle-sidebar-control"
    />
  </div>
</template>

<script setup lang="ts">
import Map from '@/components/map/Map.vue'
import MapService, { IControl } from '@/services/map/MapService'
import { useProjectStore } from '@/stores/projectStore'
import { computed, onMounted, onUnmounted, useTemplateRef, watch } from 'vue'
import projectIcon from '@/assets/images/icons/map/project_icon.png'
import projectHoverIcon from '@/assets/images/icons/map/project_icon_hover.png'
import ProjectCard from '@/views/projects/components/ProjectCard.vue'
import ProjectFilterModal from '@/views/projects/components/ProjectFilterModal.vue'
import { type ResolvedImageSpecification } from 'maplibre-gl'
import ShowProjectFiltersModalControl from '@/views/projects/components/map-controls/ShowProjectFiltersModalControl.vue'
import router from '@/router'
import ToggleSidebarControl from '@/components/map/controls/ToggleSidebarControl.vue'

type MapType = InstanceType<typeof Map>
type ProjectCard = InstanceType<typeof ProjectCard>

const projectStore = useProjectStore()
const projectMap = useTemplateRef<MapType>('project-map')
const toggleSidebarControl = useTemplateRef('toggle-sidebar-control')
const activeProjectCard = useTemplateRef<ProjectCard>('active-project-card')
const showProjectFiltersModalControl = useTemplateRef('show-project-filters-modal-control')
const geojson = computed(() => MapService.getGeojson(projectStore.filteredProjects))
const map = computed(() => projectMap.value?.map)
const sources = {
  projects: 'projects'
}
const projectsSourceName = sources.projects,
  projectsLayerName = sources.projects,
  projectsImageName = sources.projects,
  projectsImageHoverName = projectsImageName + '_hover'

watch(
  () => projectStore.filteredProjects,
  () => {
    refreshProjectLayer()
  }
)

watch(
  () => projectMap.value?.hoveredFeatureId,
  () => {
    if (projectMap.value == null) return
    projectStore.hoveredProjectId = projectMap.value?.hoveredFeatureId
  }
)

watch(
  () => projectStore.hoveredProjectId,
  () => {
    if (projectMap.value == null) return
    updatePin()
  }
)

watch(
  () => projectMap.value?.activeFeatureId,
  (to, from) => {
    const initializing = from === undefined
    if (projectMap.value == null || initializing) return
    projectStore.activeProjectId = projectMap.value?.activeFeatureId
    router.replace({
      ...router.currentRoute.value,
      query: { ...router.currentRoute.value.query, project: projectStore.activeProjectId }
    })
  }
)

watch(
  () => projectStore.activeProject,
  () => {
    if (projectMap.value == null) return
    updatePin()
    showPopupOnInit()
  }
)

const showPopupOnInit = () => {
  if (projectStore.activeProject != null && projectMap.value) {
    projectMap.value.addPopup(projectStore.activeProject.geoData.coords, activeProjectCard.value)
  }
}

onMounted(() => {
  if (map.value != null) {
    map.value.addControl(new IControl(showProjectFiltersModalControl), 'top-right')
    map.value.addControl(new IControl(toggleSidebarControl), 'top-left')
    map.value.on('load', async () => {
      await setProjectLayer()
      showPopupOnInit()
    })
  }
})

onUnmounted(() => {
  projectStore.activeProjectId = null
  projectStore.isProjectMapFullWidth = false
})

const updatePin = () => {
  if (projectMap.value) {
    projectMap.value.setLayoutProperty(
      sources.projects,
      'icon-image',
      imageHoverFilter(projectsImageName, projectsImageHoverName)
    )
  }
}

const refreshProjectLayer = async () => {
  if (projectMap.value) {
    projectMap.value.setData(sources.projects, geojson.value)
  }
}

const imageHoverFilter = (
  imageName: string,
  imageHoverName: string
): maplibregl.DataDrivenPropertyValueSpecification<ResolvedImageSpecification> => {
  return [
    'match',
    ['get', 'id'],
    [...new Set([projectStore.hoveredProjectId ?? '', projectStore.activeProjectId ?? ''])],
    imageHoverName,
    imageName
  ]
}

const setProjectLayer = async () => {
  if (projectMap.value) {
    projectMap.value.addSource(projectsSourceName, geojson.value)
    await projectMap.value.addImage(projectIcon, projectsImageName)
    await projectMap.value.addImage(projectHoverIcon, projectsImageHoverName)
    const layout: maplibregl.LayerSpecification['layout'] = {
      'icon-image': imageHoverFilter(projectsImageName, projectsImageHoverName),
      'icon-size': 0.45
    }
    projectMap.value.addLayer(projectsLayerName, { layout })
    projectMap.value.listenToHoveredFeature(projectsLayerName)
    projectMap.value.addPopupOnClick(projectsLayerName, activeProjectCard.value)
  }
  return
}
</script>

<style lang="scss">
.ProjectMap {
  width: 100%;
  height: 100%;
  position: relative;

  .ProjectCard {
    display: none;
  }
  .maplibregl-popup .ProjectCard {
    display: flex;
  }

  #map.ProjectMap__map {
    width: 100%;
    height: 100%;

    .maplibregl-ctrl-top-right {
      align-items: flex-end;
    }

    .maplibregl-ctrl-show-project-filters-ctn {
      order: -1;

      button.maplibregl-ctrl-show-project-filters {
        display: flex;
        flex-flow: row nowrap;
        border-radius: 2rem;
        align-items: center;
        padding: 0.5rem 1rem;
        width: fit-content;

        .maplibregl-ctrl-icon {
          margin-right: 0.5rem;
          color: rgb(var(--v-theme-main-blue));
          width: 1rem;
          background-image: url(@/assets/images/icons/map/mdi-filter.svg);
        }

        .maplibregl-ctrl-text {
          color: rgb(var(--v-theme-main-blue));
          text-wrap: none;
          white-space: nowrap;
          font-size: $font-size-sm;
          font-weight: 500;
        }
      }
    }
  }
}
</style>
