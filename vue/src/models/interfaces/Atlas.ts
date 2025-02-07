import type { AtlasGroup } from '../enums/geo/AtlasGroup'
import type { ContentImageFromUserFile } from './ContentImage'
import type { FileObject } from './object/FileObject'
import type { QgisMap } from './QgisMap'
import type { SymfonyRelation } from './SymfonyRelation'

export interface Atlas extends SymfonyRelation {
  id: string
  logo: FileObject
  updatedAt: string
  name: string
  atlasGroup: AtlasGroup
  position: number
  maps: QgisMap[]
}

export interface AtlasSubmission extends Omit<Atlas, 'logo'> {
  logo: string
  logoToUpload?: ContentImageFromUserFile
}
