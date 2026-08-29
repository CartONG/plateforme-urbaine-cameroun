import type { SymfonyRelation } from '@/models/interfaces/SymfonyRelation'
import type { Space } from '@/models/interfaces/divercity/Space'

export interface BlockedPeriod extends SymfonyRelation {
  id: string
  space: Space | string
  date: string
  startTime: string
  endTime: string
  reason?: string | null
  recurrenceGroupId?: string | null
  createdAt?: string
}

export interface BlockedPeriodSubmission {
  space: string
  date: string
  startTime: string
  endTime: string
  reason?: string
  recurrenceGroupId?: string
}