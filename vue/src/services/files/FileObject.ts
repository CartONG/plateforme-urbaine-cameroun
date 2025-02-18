import type { BaseMediaObject, MediaObject } from '@/models/interfaces/object/MediaObject'

export class FileObject {
  static hasThumbnail(mediaObject: MediaObject): mediaObject is BaseMediaObject {
    return (
      (mediaObject as BaseMediaObject).contentsFilteredUrl !== undefined &&
      (mediaObject as BaseMediaObject).contentsFilteredUrl.thumbnail !== undefined
    )
  }
}
