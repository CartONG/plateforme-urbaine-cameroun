import type { Actor } from "@/models/interfaces/Actor";
import type { Thematic } from "@/models/interfaces/Thematic";
import type { AdministrativeScope } from "@/models/enums/AdministrativeScope";
import type { Timestampable } from "@/models/interfaces/common/Timestampable";
import type { Status } from "@/models/enums/contents/Status";
import type { User } from "@/models/interfaces/auth/User";

export interface Project extends Timestampable {
  id: string;
  name: string;
  slug: string;
  createdBy: User;
  location: string;
  coords: {
    lng: number,
    lat: number
  };
  calendar: string;
  deliverables: string;
  status: Status;
  description: string;
  images: string[];
  partners: string[];
  interventionZone: AdministrativeScope;
  thematics: Thematic[];
  projectManagerName: string;
  projectManagerPosition: string;
  projectManagerEmail: string;
  projectManagerTel: string;
  projectManagerPhoto: string;
  website: string;
  logo: string;
  financialActors: Actor[];
  contractingActors: Actor[];
  actor: Partial<Actor>;
}