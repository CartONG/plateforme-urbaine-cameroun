import type { Timestampable } from '@/models/interfaces/common/Timestampable'
import type { Validateable } from '@/models/interfaces/common/Validateable'
import type { Blameable } from '@/models/interfaces/common/Blameable'
import type { ResourceFormat } from '@/models/enums/contents/ResourceFormat'
import type { ResourceType } from '@/models/enums/contents/ResourceType'
import type { LocalizableSubmission } from '@/models/interfaces/common/LocalizableSubmission'
import type { Localizable } from '@/models/interfaces/common/Localizable'
import type { MediaObject } from '@/models/interfaces/MediaObject'
import type { ThematicItem } from '@/models/interfaces/common/ThematicItem'

export interface Resource
  extends Timestampable,
    Validateable,
    Blameable,
    Localizable,
    ThematicItem {
  id: string
  name: string
  description: string
  file: MediaObject
  type: ResourceType
  format: ResourceFormat
  startAt: Date
  endAt: Date
  author: string
  creatorMessage?: string
  [key: string]: any
}

export interface ResourceEvent extends Resource {
  type: ResourceType.EVENTS
}

export interface ResourceSubmission extends Resource, LocalizableSubmission {}
