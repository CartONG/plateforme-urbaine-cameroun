import { apiClient } from '@/plugins/axios/api'
import type { QgisMap } from '@/models/interfaces/QgisMap'
import QgisProjectUploader from '../files/QgisProjectUploader'

export class QgisMapService {
  static async getAll(): Promise<QgisMap[]> {
    return await apiClient.get('/api/qgis_maps').then((response) => response.data['hydra:member'])
  }

  static async get(search: Partial<QgisMap>): Promise<QgisMap> {
    return await apiClient
      .get('/api/qgis_maps', { params: search })
      .then((response) => response.data['hydra:member'][0])
  }

  static async post(qgisMap: QgisMap): Promise<QgisMap | null> {
    try {
      qgisMap = await this.handleQgisProjectUpload(qgisMap)
      return await apiClient.post('/api/qgis_maps', qgisMap).then((response) => response.data)
    } catch (error) {
      console.error(error)
      return null
    }
  }

  static async patch(qgisMap: QgisMap): Promise<QgisMap> {
    qgisMap = await this.handleQgisProjectUpload(qgisMap)
    return await apiClient
      .patch('/api/qgis_maps/' + qgisMap.id, qgisMap)
      .then((response) => response.data)
  }

  static async delete(qgisMap: QgisMap): Promise<QgisMap> {
    return await apiClient.delete('/api/qgis_maps/' + qgisMap.id).then((response) => response.data)
  }

  /**
   * Upload all files in the object and replace them with the media object iri
   */
  static handleQgisProjectUpload = async (object: any) => {
    try {
      const uploadPromises = Object.keys(object).map(async (fieldName) => {
        if (object[fieldName] instanceof File) {
          const response = await QgisProjectUploader.uploadFile(object[fieldName])
          object[fieldName] = response['@id']
        }
      })
      await Promise.all(uploadPromises)
      return object
    } catch (e) {
      console.error(e)
    }
  }
}
