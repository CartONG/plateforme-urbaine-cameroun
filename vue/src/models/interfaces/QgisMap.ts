import type { QgisMapType } from '../enums/geo/QgisMapType'
import type { ContentImageFromUserFile } from './ContentImage'
import type { FileObject } from './object/FileObject'
import type { QgisProject } from './QgisProject'
import type { SymfonyRelation } from './SymfonyRelation'

export interface QgisMap extends SymfonyRelation {
  logo: FileObject
  id: string
  name: string
  description: string
  needsToBeVisualiseAsPlainImageInsteadOfWMS: boolean
  updatedAt: string
  qgisMapType: QgisMapType
  qgisProject: QgisProject
}

export interface QgisMapSubmission extends Omit<QgisMap, 'logo'> {
  logo: string
  logoToUpload?: ContentImageFromUserFile
}
