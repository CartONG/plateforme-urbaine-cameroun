import type { UserRoles } from "@/models/enums/auth/UserRoles"
import type { MediaObject } from "../MediaObject"

export interface User {
    id: number
    logo: MediaObject
    firstName: string
    lastName: string
    organisation: string
    position: string
    phoneNumber: string
    email: string
    roles: UserRoles[]
    requestedRoles: UserRoles[]
    isValidated: boolean
}

export interface UserSubmission extends Omit<User, "logo"> {
    logo: MediaObject | string
}