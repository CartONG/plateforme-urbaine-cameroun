import type { Actor } from "@/models/interfaces/Actor";
import type { Thematic } from "@/models/interfaces/Thematic";
import type { AdministrativeScope } from "@/models/enums/AdministrativeScope";
import type { Timestampable } from "@/models/interfaces/common/Timestampable";
import type { Status } from "@/models/enums/contents/Status";
import type { User } from "@/models/interfaces/auth/User";
import type { BeneficiaryType } from "@/models/enums/contents/BeneficiaryType";
import type { iri } from "./SymfonyRelation";

export interface Project extends Timestampable {
  id: number;
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
  beneficiaryTypes: BeneficiaryType[];
  thematics: Thematic[] | iri[];
  focalPointName: string;
  focalPointPosition: string;
  focalPointEmail: string;
  focalPointTel: string;
  focalPointPhoto: string;
  website: string;
  logo: string;
  financialActors: Actor[];
  contractingActors: Actor[];
  actor: Partial<Actor>;
}