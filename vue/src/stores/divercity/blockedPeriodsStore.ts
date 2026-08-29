import { defineStore } from 'pinia'
import { ref } from 'vue'
import { SpacesService } from '@/services/divercity/SpacesService'
import type { BlockedPeriod, BlockedPeriodSubmission } from '@/models/interfaces/divercity/BlockedPeriod'
import { StoresList } from '@/models/enums/app/StoresList'

export const useBlockedPeriodsStore = defineStore(StoresList.DIVERCITY_BLOCKED_PERIODS, () => {
  const blockedPeriods = ref<BlockedPeriod[]>([])

  async function getBlockedPeriods(spaceId: string): Promise<void> {
    blockedPeriods.value = await SpacesService.getBlockedPeriods(spaceId)
  }

  async function addBlockedPeriod(payload: BlockedPeriodSubmission): Promise<void> {
    const created = await SpacesService.postBlockedPeriod(payload)
    blockedPeriods.value.push(created)
  }

  async function removeBlockedPeriod(id: string): Promise<void> {
    await SpacesService.deleteBlockedPeriod(id)
    blockedPeriods.value = blockedPeriods.value.filter((bp) => bp.id !== id)
  }

  async function unblockBlockedPeriod(id: string): Promise<void> {
    await SpacesService.unblockBlockedPeriod(id)
    blockedPeriods.value = blockedPeriods.value.filter((bp) => bp.id !== id)
  }

  async function addRecurringBlockedPeriods(payloads: BlockedPeriodSubmission[]): Promise<void> {
    const created = await Promise.all(payloads.map((p) => SpacesService.postBlockedPeriod(p)))
    blockedPeriods.value.push(...created)
  }

  async function unblockSeries(recurrenceGroupId: string): Promise<void> {
    const ids = blockedPeriods.value
        .filter((bp) => bp.recurrenceGroupId === recurrenceGroupId)
        .map((bp) => bp.id)
    await Promise.all(ids.map((id) => SpacesService.unblockBlockedPeriod(id)))
    blockedPeriods.value = blockedPeriods.value.filter((bp) => bp.recurrenceGroupId !== recurrenceGroupId)
  }

  return {
    blockedPeriods,
    getBlockedPeriods,
    addBlockedPeriod,
    removeBlockedPeriod,
    addRecurringBlockedPeriods,
    unblockBlockedPeriod,
    unblockSeries
  }

})