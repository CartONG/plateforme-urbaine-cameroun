import { apiClient } from '@/plugins/axios/api'
import type { Space, SpaceHighlight, SpaceHighlightSubmission} from '@/models/interfaces/divercity/Space'
import type { PublicBooking } from '@/models/interfaces/divercity/Booking'

export class SpacesService {
  static async getSpaces(): Promise<Space[]> {
    const data = (
      await apiClient.get('/api/spaces', { headers: { accept: 'application/ld+json' } })
    ).data
    return data['hydra:member'] as Space[]
  }

  static async getPublicBookings(): Promise<PublicBooking[]> {
    const data = (
      await apiClient.get('/api/divercity/bookings/public', {
        headers: { accept: 'application/ld+json' }
      })
    ).data
    return data['hydra:member'] as PublicBooking[]
  }

  static async patchSpace(id: string, values: Partial<Space>): Promise<Space> {
    return (await apiClient.patch(`/api/spaces/${id}`, values)).data
  }

  static async postHighlight(highlight: SpaceHighlightSubmission & { space: string }): Promise<SpaceHighlight> {
    return (await apiClient.post('/api/space_highlights', highlight)).data
  }

  static async patchHighlight(id: number, values: SpaceHighlightSubmission): Promise<SpaceHighlight> {
    return (await apiClient.patch(`/api/space_highlights/${id}`, values)).data
  }
}