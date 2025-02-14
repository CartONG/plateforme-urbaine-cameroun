import { StoresList } from '@/models/enums/app/StoresList'
import { defineStore } from 'pinia'
import { computed, reactive, ref, type Ref, type Reactive, watch, type ComputedRef } from 'vue'
import type { Project, ProjectSubmission } from '@/models/interfaces/Project'
import { ProjectService } from '@/services/projects/ProjectService'
import maplibregl, { LngLatBounds } from 'maplibre-gl'
import { SortKey } from '@/models/enums/SortKey'
import type { Status } from '@/models/enums/contents/Status'
import type { Thematic } from '@/models/interfaces/Thematic'
import type { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import { useApplicationStore } from './applicationStore'
import { ContentPagesList } from '@/models/enums/app/ContentPagesList'
import { BeneficiaryType } from '@/models/enums/contents/BeneficiaryType'
import { FormType } from '@/models/enums/app/FormType'
import type { Organisation } from '@/models/interfaces/Organisation'
import { OrganisationService } from '@/services/organisations/OrganisationService'
import { i18n } from '@/plugins/i18n'
import { addNotification } from '@/services/notifications/NotificationService'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { getBboxFromPointsGroup } from '@/services/utils/UtilsService'

export const useProjectStore = defineStore(StoresList.PROJECTS, () => {
  const projects: Ref<Project[]> = ref([])
  const project: Ref<Project | null> = ref(null)
  const similarProjects: Ref<Project[]> = ref([])
  const hoveredProjectId: Ref<Project['id'] | null> = ref(null)
  const activeProjectId: Ref<Project['id'] | null> = ref(null)
  const map: Ref<maplibregl.Map | null> = ref(null)
  const bbox: Ref<LngLatBounds | undefined> = ref(undefined)
  const isFilterModalShown: Ref<boolean> = ref(false)
  const sortingProjectsSelectedMethod = ref(SortKey.PROJECTS_AZ)
  const isProjectMapFullWidth = ref(false)
  const isProjectFormShown = ref(false)
  const donors: Ref<Organisation[]> = ref([])
  const contractingOrganisations: Ref<Organisation[]> = ref([])
  const filteredProjects: Ref<Project[]> = ref([...projects.value])

  const filters: Reactive<{
    searchValue: string
    thematics: Thematic['id'][]
    statuses: Status[]
    interventionZones: AdministrativeScope[]
    beneficiaryTypes: BeneficiaryType[]
    contractingOrganisations: Organisation['id'][]
    donors: Organisation['id'][]
  }> = reactive({
    searchValue: '',
    thematics: [],
    statuses: [],
    interventionZones: [],
    beneficiaryTypes: [],
    contractingOrganisations: [],
    donors: []
  })

  async function getAllDonors(): Promise<void> {
    if (donors.value.length === 0) {
      donors.value = await OrganisationService.getAllDonors()
    }
  }
  async function getAllContractingOrganisations(): Promise<void> {
    if (contractingOrganisations.value.length === 0) {
      contractingOrganisations.value = await OrganisationService.getAllContractingOrganisations()
    }
  }

  async function getAll(): Promise<void> {
    if (projects.value.length === 0) {
      projects.value = await ProjectService.getAll()
      filteredProjects.value = filterProjects()
    }
  }

  async function loadProjectBySlug(slug: string | string[]): Promise<void> {
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

  watch(
    () => project,
    () => {
      if (project.value == null) {
        similarProjects.value = []
      }
    }
  )

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

  watch(
    () => filters,
    () => {
      filteredProjects.value = filterProjects()
    },
    { deep: true }
  )

  type filteringTrigger = 'filters' | 'map'
  const filterProjects = (from: filteringTrigger = 'filters') => {
    const projectsList = projects.value.filter((project) => {
      const projectThematicIds = project.thematics.map((projectThematic) => projectThematic.id)
      const projectDonorIds = project.donors.map((donor) => donor.id)

      return (
        (filters.searchValue === '' ||
          valuesToSearchOn(project).some((value) =>
            value.includes(filters.searchValue.toLowerCase())
          )) &&
        (filters.thematics.length === 0 ||
          filters.thematics.some((thematic) => projectThematicIds.includes(thematic))) &&
        (filters.statuses.length === 0 ||
          filters.statuses.some((status) => project.status === status)) &&
        (filters.interventionZones.length === 0 ||
          filters.interventionZones.some(
            (interventionZone) => project.interventionZone === interventionZone
          )) &&
        (filters.contractingOrganisations.length === 0 ||
          filters.contractingOrganisations.some(
            (contractingOrganisation) =>
              project.contractingOrganisation.id === contractingOrganisation
          )) &&
        (filters.donors.length === 0 ||
          filters.donors.some((donor) => projectDonorIds.includes(donor))) &&
        (filters.beneficiaryTypes.length === 0 ||
          filters.beneficiaryTypes.some((beneficiaryType) =>
            project.beneficiaryTypes.includes(beneficiaryType)
          ))
      )
    })

    if (from === 'filters') {
      map.value?.fitBounds(getBboxFromPointsGroup(projectsList.map((x) => x.geoData.coords)))
    }
    if (from === 'map') {
      if (!map.value) return projectsList
      const bounds = map.value.getBounds()
      return projectsList.filter((proj) => {
        const { lat, lng } = proj.geoData.coords
        return bounds.contains([lng, lat])
      })
    }
    return projectsList
  }

  const valuesToSearchOn = (project: Project) => {
    return (
      [
        ...new Set([
          project.name,
          project.actor.name,
          project.geoData.name,
          project.contractingOrganisation.name,
          project.interventionZone,
          i18n.t(`projects.status.${project.status}`),
          project.focalPointName,
          ...project.thematics.map((thematic) => thematic.name),
          ...project.donors.map((donor) => donor.name),
          ...project.beneficiaryTypes.map((beneficiaryType) =>
            i18n.t(`beneficiaryType.${beneficiaryType}`)
          )
        ])
      ].filter((v) => v) as any[]
    ).map((value) => value.toLowerCase())
  }

  const orderedProjects: ComputedRef<Project[]> = computed(() => {
    const sortedProjects = [...filteredProjects.value]
    switch (sortingProjectsSelectedMethod.value) {
      case SortKey.PROJECTS_AZ:
        return sortedProjects.sort((a, b) => a.name.localeCompare(b.name))
      case SortKey.PROJECTS_ZA:
        return sortedProjects.sort((a, b) => b.name.localeCompare(a.name))
      case SortKey.UPDATED_AT_AZ:
        return sortedProjects.sort(
          (a, b) => new Date(b.updatedAt).valueOf() - new Date(a.updatedAt).valueOf()
        )
      case SortKey.ACTORS_AZ:
        return sortedProjects.sort((a, b) =>
          (a.actor.name as string).localeCompare(b.actor.name as string)
        )
      case SortKey.ACTORS_ZA:
        return sortedProjects.sort((a, b) =>
          (b.actor.name as string).localeCompare(a.actor.name as string)
        )
      default:
        return sortedProjects
    }
  })

  const submitProject = async (project: ProjectSubmission, type: FormType) => {
    const submittedProject =
      type === FormType.CREATE
        ? await ProjectService.post(project)
        : await ProjectService.patch(project)
    if (type === FormType.CREATE) {
      projects.value.push(submittedProject)
    } else if (type === FormType.EDIT || type === FormType.VALIDATE) {
      updateProject(submittedProject)
    }
    addNotification(i18n.t(`notifications.project.${type}`), NotificationType.SUCCESS)
    return submittedProject
  }

  const updateProject = (updatedProject: Project) => {
    projects.value.forEach((project, key) => {
      if (project.id === updatedProject.id) {
        projects.value[key] = updatedProject
      }
    })
    if (project.value && project.value.id === updatedProject.id) {
      project.value = updatedProject
    }
  }

  const deleteProject = async (project: Project) => {
    await ProjectService.delete(project)
    projects.value.forEach((p, key) => {
      if (p.id === project.id) {
        projects.value.splice(key, 1)
        addNotification(i18n.t('notifications.project.delete'), NotificationType.SUCCESS)
      }
    })
  }

  const resetFilters = () => {
    filters.searchValue = ''
    filters.thematics = []
    filters.statuses = []
    filters.interventionZones = []
    filters.beneficiaryTypes = []
    filters.contractingOrganisations = []
    filters.donors = []
  }

  return {
    projects,
    project,
    donors,
    contractingOrganisations,
    similarProjects,
    filters,
    isProjectMapFullWidth,
    isProjectFormShown,
    isFilterModalShown,
    sortingProjectsSelectedMethod,
    hoveredProjectId,
    hoveredProject,
    activeProjectId,
    activeProject,
    filteredProjects,
    orderedProjects,
    map,
    bbox,
    getAll,
    getAllDonors,
    getAllContractingOrganisations,
    filterProjects,
    resetFilters,
    loadProjectBySlug,
    loadSimilarProjects,
    submitProject,
    updateProject,
    deleteProject
  }
})
