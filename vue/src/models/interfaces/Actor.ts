import type { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import type { ActorsCategories } from '../enums/contents/actors/ActorsCategories'
import type { Thematic } from '../enums/contents/Thematic'
import type { Admin1Boundary, Admin3Boundary } from './AdminBoundaries'
import type { BanocItem } from './common/BanocItem'
import type { Blameable } from './common/Blameable'
import type { ODDItem } from './common/ODDItem'
import type { ThematicItem } from './common/ThematicItem'
import type { Timestampable } from './common/Timestampable'
import type { Validateable } from './common/Validateable'
import type { ContentImageFromUserFile } from './ContentImage'
import type { GeoData } from './geo/GeoData'
import type { BaseMediaObject } from './object/MediaObject'
import type { Project } from './Project'
import type { SymfonyRelation } from './SymfonyRelation'

export interface Actor
  extends SymfonyRelation,
    Timestampable,
    Validateable,
    Blameable,
    ThematicItem,
    ODDItem,
    BanocItem {
  id: string
  name: string
  acronym: string
  category: ActorsCategories
  otherCategory?: string
  thematics: Thematic[]
  otherThematic?: string
  description: string
  administrativeScopes: AdministrativeScope[]
  admin1List?: Admin1Boundary[]
  admin3List?: Admin3Boundary[]
  officeName: string
  officeAddress: string
  geoData: GeoData
  contactName: string
  contactPosition: string
  projects: Project[]
  logo?: BaseMediaObject
  images: BaseMediaObject[]
  externalImages: string[]
  website: string
  phone: string
  email: string
  creatorMessage?: string
  slug: string
}

export interface ActorSubmission extends Omit<Actor, 'logo'> {
  logo: string
  logoToUpload: ContentImageFromUserFile
  imagesToUpload: ContentImageFromUserFile[]
}
