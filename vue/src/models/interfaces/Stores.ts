import type { useAtlasStore } from '@/stores/atlasStore'
import type { useMyMapStore } from '@/stores/myMapStore'

export type MyMapStoreType = ReturnType<typeof useMyMapStore>
export type AtlasStoreType = ReturnType<typeof useAtlasStore>
