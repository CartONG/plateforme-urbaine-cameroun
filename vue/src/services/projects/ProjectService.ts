import { apiClient } from '@/assets/plugins/axios/api'
import type { Project, ProjectSubmission } from '@/models/interfaces/Project'

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

  static async patch(project: ProjectSubmission): Promise<Project> {
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
}
