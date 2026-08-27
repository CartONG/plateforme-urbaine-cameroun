import type { SymfonyRelation } from '@/models/interfaces/SymfonyRelation'
import type { Space } from '@/models/interfaces/divercity/Space'
import type { FileObject } from '@/models/interfaces/object/FileObject'

export interface EventActivityType extends SymfonyRelation {
  id: number
  label: string
}

export interface InformationSource extends SymfonyRelation {
  id: number
  label: string
}

export interface BookingAttachment extends SymfonyRelation {
  id?: number
  fileObject: FileObject | string
  type: 'AGENDA' | 'RESOURCE_DOCUMENT' | 'OTHER'
}

export interface Booking extends SymfonyRelation {
  id: string
  space: Space | string
  eventActivityType?: EventActivityType | string | null
  informationSource?: InformationSource | string | null
  title: string
  lastName: string
  firstName: string
  organization?: string
  role: string
  email: string
  phone: string
  bookingPurpose: string
  date: string
  startTime: string
  endTime: string
  participantCount: number
  additionalInformation?: string
  bookingAttachments: BookingAttachment[]
  submittedAt?: string
}

// Payload envoyé au POST — mêmes champs, attachments déjà résolus en IRI
export interface BookingSubmission {
  space: string
  eventActivityType?: string | null
  title: string
  lastName: string
  firstName: string
  organization?: string
  role: string
  email: string
  phone: string
  bookingPurpose: string
  date: string
  startTime: string
  endTime: string
  participantCount: number
  additionalInformation?: string
  bookingAttachments: { fileObject: string; type: 'AGENDA' | 'RESOURCE_DOCUMENT' | 'OTHER' }[]
}

export interface SpaceAvailability {
  id: string
  date: string
  startTime: string
  endTime: string
  type: 'booking' | 'blocked_period'
  title?: string | null
  eventActivityTypeLabel?: string | null
}

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