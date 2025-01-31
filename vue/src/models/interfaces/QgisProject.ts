import type { SymfonyRelation } from './SymfonyRelation'

export interface QgisProject extends SymfonyRelation {
  contentUrl: string
  name: string
  layers?: string[]
}
