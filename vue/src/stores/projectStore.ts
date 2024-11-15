import { StoresList } from '@/models/enums/app/StoresList'
import { defineStore } from 'pinia'
import { computed, reactive, ref, type Ref, type Reactive, watch } from 'vue';
import type { Project } from '@/models/interfaces/Project'
import { ProjectService } from '@/services/projects/ProjectService'
import maplibregl from 'maplibre-gl';
import { SortKey } from '@/models/enums/SortKey'
import type { Status } from '@/models/enums/contents/Status';
import type { Thematic } from '@/models/interfaces/Thematic';
import type { AdministrativeScope } from '@/models/enums/AdministrativeScope';
import type { Actor } from '@/models/interfaces/Actor';
import { useApplicationStore } from './applicationStore';
import { ContentPagesList } from '@/models/enums/app/ContentPagesList';
import { BeneficiaryType } from '@/models/enums/contents/BeneficiaryType';

export const useProjectStore = defineStore(StoresList.PROJECTS, () => {
  const projects: Ref<Project[]> = ref([])
  const project: Ref<Project | null> = ref(null)
  const similarProjects: Ref<Project[]> = ref([])
  const hoveredProjectId: Ref<Project['id'] | null> = ref(null)
  const activeProjectId: Ref<Project['id'] | null> = ref(null)
  const map: Ref<maplibregl.Map | null> = ref(null)
  const isFilterModalShown: Ref<boolean> = ref(false)
  const sortingProjectsSelectedMethod = ref(SortKey.PROJECTS_AZ)
  const isProjectMapFullWidth = ref(false)

  const filters: Reactive<{
    searchValue: string,
    thematics: Thematic['id'][],
    statuses: Status[],
    interventionZones: AdministrativeScope[],
    beneficiaryTypes: BeneficiaryType[],
    contractingActors: Actor['id'][],
    financialActors: Actor['id'][],
  }> = reactive({
    searchValue: '',
    thematics: [],
    statuses: [],
    interventionZones: [],
    beneficiaryTypes: [],
    contractingActors: [],
    financialActors: [],
  })

  async function getAll(): Promise<void> {
    if (projects.value.length === 0) {
      projects.value = await ProjectService.getAll()
    }
  }

  async function loadProjectBySlug(slug: string | string []): Promise<void> {
    if (project.value?.slug !== slug && typeof slug === 'string') {
      const appStore = useApplicationStore()
      appStore.currentContentPage = ContentPagesList.PROJECT
      project.value = await ProjectService.get({ slug })
    }
  }

  async function loadSimilarProjects(): Promise<void> {
    if (project.value) {
      similarProjects.value = await ProjectService.getSimilarProjects(project.value)
    }
  }
  
  watch(() => project, () => {
    if (project.value == null) {
      similarProjects.value = []
    }
  })

  const hoveredProject = computed(() => {
    if (hoveredProjectId.value) {
      return projects.value.find((project) => project.id === hoveredProjectId.value)
    }
    return null
  })

  const activeProject = computed(() => {
    if (activeProjectId.value) {
      return projects.value.find((project) => project.id === activeProjectId.value) ?? null
    }
    return null
  })

  const filteredProjects = computed(() => {
    return projects.value.filter((project) => {
      const projectThematicIds = project.thematics.map((projectThematic) => projectThematic.id)
      const projectContractingActorIds = project.contractingActors.map((contractingActor) => contractingActor.id)
      const projectFinancialActorIds = project.financialActors.map((financialActor) => financialActor.id)
      const searchValue = filters.searchValue.toLowerCase()
      const valuesToSearchOn = [
        project.name,
        project.actor.name,
        project.location
      ].map((value) => value.toLowerCase())

      return (
        valuesToSearchOn.some((value) => value.includes(searchValue)) &&
        (filters.thematics.length === 0 || filters.thematics.some((thematic) => projectThematicIds.includes(thematic))) &&
        (filters.statuses.length === 0  || filters.statuses.some((status) => project.status === status)) &&
        (filters.interventionZones.length === 0  || filters.interventionZones.some((interventionZone) => project.interventionZone === interventionZone)) &&
        (filters.contractingActors.length === 0  || filters.contractingActors.some((contractingActor) => projectContractingActorIds.includes(contractingActor))) &&
        (filters.financialActors.length === 0  || filters.financialActors.some((financialActor) => projectFinancialActorIds.includes(financialActor))) &&
        (filters.beneficiaryTypes.length === 0  || filters.beneficiaryTypes.some((beneficiaryType) => project.beneficiaryTypes.includes(beneficiaryType)))
      )
    })
  })

  const orderedProjects = computed(() => {
    const sortedProjects = [...filteredProjects.value]; 
    switch (sortingProjectsSelectedMethod.value) {
      case SortKey.PROJECTS_AZ:
        return sortedProjects.sort((a, b) => a.name.localeCompare(b.name));
      case SortKey.PROJECTS_ZA:
        return sortedProjects.sort((a, b) => b.name.localeCompare(a.name));
      case SortKey.UPDATED_AT_AZ:
        return sortedProjects.sort((a, b) => (new Date(b.updatedAt).valueOf() - new Date(a.updatedAt).valueOf()));
      case SortKey.ACTORS_AZ:
        return sortedProjects.sort((a, b) => (a.actor.name as string).localeCompare(b.actor.name as string));
      case SortKey.ACTORS_ZA:
        return sortedProjects.sort((a, b) => (b.actor.name as string).localeCompare(a.actor.name as string));
      default:
        return sortedProjects
    }
  })

  const resetFilters = () => {
    filters.searchValue = ''
    filters.thematics = []
    filters.statuses = []
    filters.interventionZones = []
    filters.beneficiaryTypes = []
    filters.contractingActors = []
    filters.financialActors = []
  }

  return {
    projects, project, similarProjects,filters, isProjectMapFullWidth, isFilterModalShown, sortingProjectsSelectedMethod, hoveredProjectId, hoveredProject, activeProjectId, activeProject, filteredProjects, orderedProjects, map,
    getAll, resetFilters, loadProjectBySlug, loadSimilarProjects
  }
})
