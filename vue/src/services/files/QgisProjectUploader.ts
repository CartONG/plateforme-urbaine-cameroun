import { apiClient } from '@/plugins/axios/api'
import type { QgisProject } from '@/models/interfaces/QgisProject'

export default class QgisProjectUploader {
  public static async uploadFile(file: File): Promise<QgisProject> {
    const form = new FormData()
    form.append('file', file)
    const data = (
      await apiClient.post(`/api/qgis_projects`, form, {
        headers: {
          'Content-Type': 'multipart/form-data',
          accept: 'application/ld+json'
        }
      })
    ).data
    return data
  }
}
