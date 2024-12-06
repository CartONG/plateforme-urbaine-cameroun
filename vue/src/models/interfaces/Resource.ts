import type { Timestampable } from '@/models/interfaces/common/Timestampable'
import type { Validateable } from '@/models/interfaces/common/Validateable'
import type { Blameable } from '@/models/interfaces/common/Blameable'
import type { ResourceType } from '@/models/enums/contents/ResourceType'

export interface Resource extends Timestampable, Validateable, Blameable {
  id: string
  name: string
  description: string
  type: ResourceType
}

export interface ResourceSubmission extends Resource {}
