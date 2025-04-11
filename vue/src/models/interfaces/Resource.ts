import type { Timestampable } from '@/models/interfaces/common/Timestampable'
import type { Validateable } from '@/models/interfaces/common/Validateable'
import type { Blameable } from '@/models/interfaces/common/Blameable'
import type { ResourceFormat } from '@/models/enums/contents/ResourceFormat'
import type { ResourceType } from '@/models/enums/contents/ResourceType'
import type { FileObject } from '@/models/interfaces/object/FileObject'
import type { ThematicItem } from '@/models/interfaces/common/ThematicItem'
import type { SymfonyRelation } from '@/models/interfaces/SymfonyRelation'
import type { BaseMediaObject } from './object/MediaObject'
import type { ContentImageFromUserFile } from './ContentImage'
import type { GeoData } from './geo/GeoData'

export interface Resource
  extends Timestampable,
    Validateable,
    Blameable,
    ThematicItem,
    SymfonyRelation {
  id: string
  name: string
  description: string
  geoData: GeoData | null
  file: FileObject
  type: ResourceType
  format: ResourceFormat
  startAt: Date
  endAt: Date
  author: string
  previewImage?: BaseMediaObject
  creatorMessage?: string
  [key: string]: any
}

export interface ResourceEvent extends Resource {
  type: ResourceType.EVENTS
}

export interface ResourceSubmission extends Resource {
  previewImageToUpload: ContentImageFromUserFile
}
