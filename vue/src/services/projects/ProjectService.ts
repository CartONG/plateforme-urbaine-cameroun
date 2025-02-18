import { apiClient } from '@/plugins/axios/api'
import type { Project, ProjectSubmission } from '@/models/interfaces/Project'
import { FormType } from '@/models/enums/app/FormType'
import FileUploader from '@/services/files/FileUploader'
import type { BaseMediaObject } from '@/models/interfaces/object/MediaObject'
import { transformSymfonyRelationToIRIs } from '@/services/utils/UtilsService'

export class ProjectService {
  static async getAll(): Promise<Project[]> {
    return await apiClient
      .get('/api/projects/all')
      .then((response) => response.data['hydra:member'])
  }

  static async get(search: Partial<Project>): Promise<Project> {
    return await apiClient
      .get('/api/projects', { params: search })
      .then((response) => response.data['hydra:member'][0])
  }

  static async post(project: ProjectSubmission): Promise<Project> {
    return await apiClient.post('/api/projects', project).then((response) => response.data)
  }

  static async patch(project: Partial<ProjectSubmission | Project>): Promise<Project> {
    return await apiClient
      .patch('/api/projects/' + project.id, project)
      .then((response) => response.data)
  }

  static async delete(project: Project): Promise<Project> {
    return await apiClient.delete('/api/projects/' + project.id).then((response) => response.data)
  }

  static async getSimilarProjects(project: Project): Promise<Project[]> {
    return await apiClient
      .get('/api/projects/' + project.id + '/similar')
      .then((response) => response.data['hydra:member'])
  }

  static async createOrUpdate(
    projectToSubmit: ProjectSubmission,
    type: FormType
  ): Promise<Project> {
    let symfonyProject = transformSymfonyRelationToIRIs<Project>(projectToSubmit)

    const project =
      type === FormType.CREATE ? await this.post(projectToSubmit) : await this.patch(symfonyProject)

    if (type === FormType.CREATE) {
      symfonyProject.id = project.id
    }

    if (projectToSubmit.logoToUpload) {
      symfonyProject.logo = (await FileUploader.uploadMedia(
        projectToSubmit.logoToUpload.file
      )) as BaseMediaObject
    }

    const images = await Promise.all(
      projectToSubmit.imagesToUpload.map(async (img) => await FileUploader.uploadMedia(img.file))
    )

    if (images.length > 0) {
      symfonyProject.images.push(...(images as BaseMediaObject[]))
    } else if (projectToSubmit.images.length === 0) {
      symfonyProject.images = []
    }

    const partnerImages = await Promise.all(
      projectToSubmit.imagesPartnerToUpload.map(
        async (img) => await FileUploader.uploadMedia(img.file)
      )
    )
    if (partnerImages.length > 0) {
      symfonyProject.partners.push(...(partnerImages as BaseMediaObject[]))
    } else if (projectToSubmit.partners.length === 0) {
      symfonyProject.partners = []
    }
    console.log(symfonyProject)
    symfonyProject = transformSymfonyRelationToIRIs<Project>(symfonyProject)
    console.log(symfonyProject)
    if (
      symfonyProject.id &&
      (images.length > 0 || projectToSubmit.logoToUpload || partnerImages.length > 0)
    ) {
      return await this.patchImages(symfonyProject)
    }

    return project
  }

  static async patchImages(project: Project): Promise<Project> {
    console.log({
      images: project.images,
      id: project.id,
      logo: project.logo,
      partners: project.partners
    })
    return this.patch({
      images: project.images,
      id: project.id,
      logo: project.logo,
      partners: project.partners
    })
  }
}
