import type { SymfonyRelation } from '@/models/interfaces/SymfonyRelation'

export interface PublicBooking extends SymfonyRelation {
  id: string
  title: string
  date: string
  startTimeFormat: string
  endTimeFormat: string
  participantCount?: number
  organization?: string
  bookingPurpose?: string
  eventActivityType?: {
    id: string
    label: string
  }
}