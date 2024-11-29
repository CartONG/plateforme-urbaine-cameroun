import type { User } from "@/models/interfaces/auth/User";

export interface Blameable {
  createdBy: User
}
