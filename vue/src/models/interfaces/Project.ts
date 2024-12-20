import type { Actor } from '@/models/interfaces/Actor'
import type { Thematic } from '@/models/interfaces/Thematic'
import type { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import type { Timestampable } from '@/models/interfaces/common/Timestampable'
import type { Status } from '@/models/enums/contents/Status'
import type { User } from '@/models/interfaces/auth/User'
import type { BeneficiaryType } from '@/models/enums/contents/BeneficiaryType'
import type { iri } from './SymfonyRelation'
import type { LocalizableSubmission } from '@/models/interfaces/common/LocalizableSubmission'
import type { Organisation } from '@/models/interfaces/Organisation'
import type { Validateable } from '@/models/interfaces/common/Validateable'
import type { Blameable } from '@/models/interfaces/common/Blameable'
import type { Localizable } from '@/models/interfaces/common/Localizable'

export interface Project extends Timestampable, Validateable, Blameable, Localizable {
  id: string
  name: string
  slug: string
  createdBy: User
  calendar: string
  deliverables: string
  status: Status
  description: string
  images: string[]
  partners: string[]
  interventionZone: AdministrativeScope
  beneficiaryTypes: BeneficiaryType[]
  thematics: Thematic[]
  focalPointName: string
  focalPointPosition: string
  focalPointEmail: string
  focalPointTel: string
  focalPointPhoto: string
  website: string
  logo: string
  donors: Organisation[]
  contractingOrganisation: Organisation
  actor: Partial<Actor>
}

export interface ProjectSubmission
  extends Omit<Project, 'actor' | 'thematics'>,
    LocalizableSubmission {
  actor: iri
  thematics: iri[]
}
