import type { UserRoles } from '@/models/enums/auth/UserRoles'
import type { MediaObject } from '../MediaObject'
import type { Validateable } from '../common/Validateable'

export interface User extends Validateable {
  id: number
  logo: MediaObject
  firstName: string
  lastName: string
  fullName: string
  organisation: string
  position: string
  phoneNumber: string
  email: string
  roles: UserRoles[]
  requestedRoles: UserRoles[]
}

export interface UserSubmission extends Omit<User, 'logo'> {
  logo: MediaObject | string
}
