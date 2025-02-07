<template>
  <div class="AdminPanel AdminPanel--highlight">
    <AdminTable
      :items="orderedHighlights"
      :tableKeys="['name', 'itemType', 'highlightedAt']"
      :column-widths="['5%', 'auto', '15%', '20%']"
      :is-draggable="true"
      :is-overlay-shown-function="(item) => ((item as HighlightedItem)?.position ?? 0) < 3"
      @update:order="(orderedEvent) => updateOrder(orderedEvent)"
    >
      <template #adminTableItemFirst="{ item }">
        <HighlightButton
          :item-id="(item as HighlightedItem).itemId"
          @update:highlight="refreshHighlightedItems"
        />
      </template>
    </AdminTable>
  </div>
</template>
<script setup lang="ts">
import { useHighlightStore } from '@/stores/highlightStore'
import { computed, onMounted } from 'vue'
import AdminTable from '@/components/admin/AdminTable.vue'
import type { HighlightedItem } from '@/models/interfaces/HighlightedItem'
import HighlightButton from '@/components/global/HighlightButton.vue'
import { HighlightedItemService } from '@/services/highlight/HighlightedItemService'
import { localizeDate } from '@/services/utils/UtilsService'

const highlightsStore = useHighlightStore()
const highlights = computed(() => highlightsStore.highlights)
const orderedHighlights = computed(() =>
  highlightsStore.orderedHighlights.map((item) => ({
    ...item,
    highlightedAt: item.highlightedAt ? localizeDate(item.highlightedAt) : ''
  }))
)

onMounted(async () => await refreshHighlightedItems())

const updateOrder = async (orderedEvent: { id: number; oldIndex: number; newIndex: number }) => {
  const itemId = highlights.value.find((item) => item.id == orderedEvent.id)?.itemId
  await HighlightedItemService.patch({
    itemId,
    position: orderedEvent.newIndex
  }).then(async () => await refreshHighlightedItems())
}

const refreshHighlightedItems = async () => {
  await highlightsStore.getAll(true)
}
</script>

<style lang="scss">
.AdminPanel--highlight {
  .AdminTable {
    $dim-right-pad: 2rem;
    padding-left: $dim-right-pad;
    .AdminTable__row {
      position: relative;
      &::before {
        position: absolute;
        left: -$dim-right-pad;
        font-weight: bold;
        align-self: center;
      }
      &:nth-child(1)::before {
        content: '1';
      }
      &:nth-child(2)::before {
        content: '2';
      }
      &:nth-child(3)::before {
        content: '3';
      }
    }
  }
}
</style>
