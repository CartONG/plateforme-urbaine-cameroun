import type { ActorMediaObject, MediaObject } from '@/models/interfaces/object/MediaObject'

export class FileObject {
  static hasThumbnail(mediaObject: MediaObject): mediaObject is ActorMediaObject {
    return (
      (mediaObject as ActorMediaObject).contentsFilteredUrl !== undefined &&
      (mediaObject as ActorMediaObject).contentsFilteredUrl.thumbnail !== undefined
    )
  }
}
