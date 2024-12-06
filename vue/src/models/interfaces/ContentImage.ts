import type { ContentImageType } from '../enums/app/ContentImageType'

interface ContentImage {
  preview: string
  type: ContentImageType
}
export interface ContentImageFromUserFile extends ContentImage {
  name: string
  type: ContentImageType.CONTENT_IMAGE_FROM_USER_FILE
  file: File
}
