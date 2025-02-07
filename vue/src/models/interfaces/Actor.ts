import type { ActorsCategories } from '../enums/contents/actors/ActorsCategories'
import type { ActorExpertise } from './ActorExpertise'
import type { AdministrativeScope } from './AdministrativeScope'
import type { Blameable } from './common/Blameable'
import type { ThematicItem } from './common/ThematicItem'
import type { Timestampable } from './common/Timestampable'
import type { Validateable } from './common/Validateable'
import type { ContentImageFromUserFile } from './ContentImage'
import type { ActorMediaObject } from './object/MediaObject'
import type { Project } from './Project'

export interface Actor extends Timestampable, Validateable, Blameable, ThematicItem {
  id: string
  '@id': string
  name: string
  acronym: string
  category: ActorsCategories
  expertises: ActorExpertise[]
  description: string
  administrativeScopes: AdministrativeScope[]
  officeName: string
  officeAddress: string
  officeLocation: string
  contactName: string
  contactPosition: string
  projects: Project[]
  logo: ActorMediaObject
  images: ActorMediaObject[]
  externalImages: string[]
  website: string
  phone: string
  email: string
  slug: string
}

export interface ActorSubmission extends Omit<Actor, 'logo' | 'officeLocation'> {
  logo: string
  logoToUpload: ContentImageFromUserFile
  imagesToUpload: ContentImageFromUserFile[]
  officeLocation: string
}
