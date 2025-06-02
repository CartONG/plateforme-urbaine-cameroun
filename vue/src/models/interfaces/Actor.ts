import type { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import type { ActorsCategories } from '../enums/contents/actors/ActorsCategories'
import type { ActorExpertise } from './ActorExpertise'
import type { Admin1Boundary, Admin2Boundary, Admin3Boundary } from './AdminBoundaries'
import type { Blameable } from './common/Blameable'
import type { ThematicItem } from './common/ThematicItem'
import type { Timestampable } from './common/Timestampable'
import type { Validateable } from './common/Validateable'
import type { ContentImageFromUserFile } from './ContentImage'
import type { GeoData } from './geo/GeoData'
import type { BaseMediaObject } from './object/MediaObject'
import type { Project } from './Project'
import type { Thematic } from './Thematic'

export interface Actor extends Timestampable, Validateable, Blameable, ThematicItem {
  id: string
  '@id': string
  name: string
  acronym: string
  category: ActorsCategories
  otherCategory?: string
  expertises: ActorExpertise[]
  otherExpertise?: string
  thematics: Thematic[]
  otherThematic?: string
  description: string
  administrativeScopes: AdministrativeScope[]
  admin1List?: Admin1Boundary[]
  admin2List?: Admin2Boundary[]
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
