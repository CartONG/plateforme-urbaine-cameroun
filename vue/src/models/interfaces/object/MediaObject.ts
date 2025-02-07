import type { SymfonyRelation } from '../SymfonyRelation'

export interface MediaObject extends SymfonyRelation {
  contentUrl: string
  contentsFilteredUrl?: object
}

export interface BaseMediaObject extends MediaObject {
  contentsFilteredUrl: {
    thumbnail: string
  }
}
