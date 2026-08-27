import { apiClient } from '@/plugins/axios/api'
import type { Space, SpaceHighlight, SpaceHighlightSubmission} from '@/models/interfaces/divercity/Space'
import type { EventActivityType, InformationSource, Booking, BookingSubmission, SpaceAvailability } from '@/models/interfaces/divercity/Booking'
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

  static async getEventActivityTypes(): Promise<EventActivityType[]> {
    const data = (
      await apiClient.get('/api/event_activity_types', { headers: { accept: 'application/ld+json' } })
    ).data
    return data['hydra:member'] as EventActivityType[]
  }

  static async getInformationSources(): Promise<InformationSource[]> {
    const data = (
      await apiClient.get('/api/information_sources', { headers: { accept: 'application/ld+json' } })
    ).data
    return data['hydra:member'] as InformationSource[]
  }

  static async postBooking(booking: BookingSubmission): Promise<Booking> {
    return (await apiClient.post('/api/bookings', booking)).data
  }

  static async patchBookingInformationSource(bookingId: string, informationSource: string): Promise<Booking> {
    return (
      await apiClient.patch(`/api/divercity/bookings/${bookingId}/information-source`, {
        informationSource
      })
    ).data
  }

  static async getSpaceAvailability(
    spaceId: string,
    dateFrom: string,
    dateTo: string
  ): Promise<SpaceAvailability[]> {
    const data = (
      await apiClient.get(`/api/divercity/spaces/${spaceId}/availability`, {
        params: { date_from: dateFrom, date_to: dateTo },
        headers: { accept: 'application/ld+json' }
      })
    ).data
    return data['hydra:member'] as SpaceAvailability[]
  }
}