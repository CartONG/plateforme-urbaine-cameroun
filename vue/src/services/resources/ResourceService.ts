import { apiClient } from '@/plugins/axios/api'
import type { Resource, ResourceEvent } from '@/models/interfaces/Resource'
import { handleFileUpload } from '@/services/forms/FormService'
import FileUploader from '@/services/files/FileUploader'
import type { BaseMediaObject } from '../../models/interfaces/object/MediaObject'
import type { ContentImageFromUserFile } from '@/models/interfaces/ContentImage'
import { transformSymfonyRelationToIRIs } from '@/services/utils/UtilsService'

export class ResourceService {
  static async getAll(): Promise<Resource[]> {
    return await apiClient.get('/api/resources').then((response) => response.data['hydra:member'])
  }

  static async get(search: Partial<Resource>): Promise<Resource> {
    const { id } = search
    return await apiClient.get('/api/resources/' + id).then((response) => response.data)
  }

  static async post(resource: Resource): Promise<Resource> {
    resource = await handleFileUpload(resource)
    const createdResource = await apiClient
      .post('/api/resources', transformSymfonyRelationToIRIs(resource))
      .then((response) => response.data)
    return await this.uploadImagePreview(createdResource, resource.previewImageToUpload)
  }

  static async patch(resource: Resource): Promise<Resource> {
    resource = await handleFileUpload(resource)
    const updatedResource = await apiClient
      .patch('/api/resources/' + resource.id, transformSymfonyRelationToIRIs(resource))
      .then((response) => response.data)

    return await this.uploadImagePreview(updatedResource, resource.previewImageToUpload)
  }

  static async uploadImagePreview(
    resource: Resource,
    previewImageToUpload?: ContentImageFromUserFile
  ): Promise<Resource> {
    let previewImage = resource?.previewImage
    if (previewImageToUpload) {
      previewImage = (await FileUploader.uploadMedia(previewImageToUpload.file)) as BaseMediaObject
      return await apiClient
        .patch('/api/resources/' + resource.id, transformSymfonyRelationToIRIs({ previewImage }))
        .then((response) => response.data)
    } else {
      return resource
    }
  }

  static async delete(resource: Resource): Promise<Resource> {
    return await apiClient.delete('/api/resources/' + resource.id).then((response) => response.data)
  }

  static async getNearestEvents(): Promise<ResourceEvent[]> {
    return await apiClient
      .get('/api/resources/events/nearest')
      .then((response) => response.data['hydra:member'])
  }

  static getLink(resource: Resource): string {
    return resource.file?.contentUrl || resource.link || ''
  }
}
