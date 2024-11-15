import type { ActorsCategories } from "../enums/contents/actors/ActorsCategories";
import type { ActorExpertise } from "./ActorExpertise";
import type { AdministrativeScope } from "./AdministrativeScope";
import type { User } from "./auth/User";
import type { Timestampable } from "./common/Timestampable";
import type { ContentImageFromUserFile } from "./ContentImage";
import type { MediaObject } from "./MediaObject";
import type { Project } from "./Project";
import type { Thematic } from "./Thematic";

export interface Actor extends Timestampable {
    id: string;
    createdBy: {
        id: User["id"]
    };
    isValidated: boolean;
    name: string;
    acronym: string;
    category: ActorsCategories;
    expertises: ActorExpertise[];
    thematics: Thematic[];
    creationDate: Date;
    lastUpdate: Date;
    description: string;
    administrativeScopes: AdministrativeScope[];
    officeName: string;
    officeAddress: string;
    officeLocation: string;
    contactName: string;
    contactPosition: string;
    projects: Project[];
    logo: MediaObject;
    images: MediaObject[];
    externalImages: string[];
    website: string;
    phone: string;
    email: string;
    slug: string;
  }

  export interface ActorSubmission extends Omit<Actor, "logo" | "officeLocation" > {
    logo: string,
    logoToUpload: ContentImageFromUserFile
    imagesToUpload: ContentImageFromUserFile[]
    officeLocation: string
  }
  