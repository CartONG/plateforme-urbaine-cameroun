import type { Actor } from '@/models/interfaces/Actor'
import type { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import type { Timestampable } from '@/models/interfaces/common/Timestampable'
import type { Status } from '@/models/enums/contents/Status'
import type { BeneficiaryType } from '@/models/enums/contents/BeneficiaryType'
import type { iri, SymfonyRelation } from '@/models/interfaces/SymfonyRelation'
import type { Organisation } from '@/models/interfaces/Organisation'
import type { Validateable } from '@/models/interfaces/common/Validateable'
import type { Blameable } from '@/models/interfaces/common/Blameable'
import type { Localizable } from '@/models/interfaces/common/Localizable'
import type { ThematicItem } from '@/models/interfaces/common/ThematicItem'
import type { BaseMediaObject } from '@/models/interfaces/object/MediaObject'
import type { ContentImageFromUserFile } from '@/models/interfaces/ContentImage'
import type { Admin1Boundary, Admin2Boundary, Admin3Boundary } from './AdminBoundaries'

export interface Project
  extends Timestampable,
    Validateable,
    Blameable,
    Localizable,
    ThematicItem,
    SymfonyRelation {
  id: string
  name: string
  slug: string
  calendar: string
  deliverables: string
  status: Status
  description: string
  images: BaseMediaObject[]
  externalImages: string[]
  partners: BaseMediaObject[]
  administrativeScopes: AdministrativeScope[]
  admin1List?: Admin1Boundary[]
  admin2List?: Admin2Boundary[]
  admin3List?: Admin3Boundary[]
  beneficiaryTypes: BeneficiaryType[]
  focalPointName: string
  focalPointPosition: string
  focalPointEmail: string
  focalPointTel: string
  focalPointPhoto: string
  website: string
  logo?: BaseMediaObject
  donors: Organisation[]
  contractingOrganisation: Organisation
  actor: Partial<Actor>
  creatorMessage?: string
}

export interface ProjectSubmission extends Omit<Project, 'actor' | 'thematics' | 'logo'> {
  actor: iri
  thematics: iri[]
  logo: string
  logoToUpload: ContentImageFromUserFile
  imagesToUpload: ContentImageFromUserFile[]
  imagesPartnerToUpload: ContentImageFromUserFile[]
}
