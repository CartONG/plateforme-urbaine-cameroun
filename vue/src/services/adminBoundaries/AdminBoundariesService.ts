import type {
  Admin1Boundary,
  Admin2Boundary,
  Admin3Boundary
} from '@/models/interfaces/AdminBoundaries'
import { apiClient } from '@/plugins/axios/api'

export class AdminBoundariesService {
  static async getAdmin1List(): Promise<Admin1Boundary[]> {
    return await apiClient
      .get('/api/admin1_boundaries')
      .then((response) => response.data['hydra:member'])
  }

  static async getAdmin2List(): Promise<Admin2Boundary[]> {
    return await apiClient
      .get('/api/admin2_boundaries')
      .then((response) => response.data['hydra:member'])
  }

  static async getAdmin3List(): Promise<Admin3Boundary[]> {
    return await apiClient
      .get('/api/admin3_boundaries')
      .then((response) => response.data['hydra:member'])
  }

  static getGeoJsonfromAdminBoundaries(
    adminBoundaries: Admin1Boundary[] | Admin2Boundary[] | Admin3Boundary[]
  ) {
    const features: { type: string; geometry: any; properties: {} }[] = []
    adminBoundaries.forEach((obj) => {
      const copy: any = { ...obj }
      const properties: Record<string, any> = {}
      const feature = {
        type: 'Feature',
        geometry: JSON.parse(copy.geometryGeoJson),
        properties: {}
      }
      delete copy.geometryGeoJson
      for (const key in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, key)) {
          properties[key] = (obj as Record<string, any>)[key]
        }
      }
      feature.properties = properties
      features.push(feature)
    })

    return {
      type: 'FeatureCollection',
      features: features
    }
  }
}
