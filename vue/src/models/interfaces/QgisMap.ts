import type { ContentImageFromUserFile } from './ContentImage'
import type { MediaObject } from './MediaObject'
import type { QgisProject } from './QgisProject'
import type { SymfonyRelation } from './SymfonyRelation'

export interface QgisMap extends SymfonyRelation {
  logo: MediaObject
  id: string
  name: string
  description: string
  needsToBeVisualiseAsPlainImageInsteadOfWMS: boolean
  updatedAt: string
  qgisProject: QgisProject
}

export interface QgisMapSubmission extends Omit<QgisMap, 'logo'> {
  logo: string
  logoToUpload?: ContentImageFromUserFile
}
