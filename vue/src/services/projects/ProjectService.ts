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

  static async createOrUpdate(project: ProjectSubmission, type: FormType): Promise<Project> {
    let submittedProject =
      type === FormType.CREATE ? await this.post(project) : await this.patch(project)

    const images = await Promise.all(
      project.imagesToUpload.map(async (img) => await FileUploader.uploadMedia(img.file))
    )

    console.log('images', images)

    if (images.length > 0) {
      submittedProject.images.push(...(images as BaseMediaObject[]))
    } else {
      submittedProject.images = []
    }

    console.log('submittedProject', submittedProject.images)

    submittedProject = transformSymfonyRelationToIRIs<Project>(submittedProject)

    console.log('submittedProject', submittedProject)

    if (submittedProject.id && images.length > 0) {
      return await this.patch({ images: submittedProject.images, id: submittedProject.id })
    }

    return submittedProject
  }

  static async patchImages(project: Project): Promise<Project> {
    return this.patch({ images: project.images, id: project.id })
  }
}
