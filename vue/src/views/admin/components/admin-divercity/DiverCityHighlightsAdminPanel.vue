<template>
  <div class="AdminPanel AdminPanel--highlight">
    <AdminTopBar
      page="Highlights"
      :items="highlightsStore.highlights"
      searchKey="name"
      @update-search-query="(e) => (searchQuery = e)"
    >
      <template #right-buttons>
        <v-btn @click="isModalShown = true" color="main-red">
          {{ $t('admin.add') }}
        </v-btn>
      </template>
    </AdminTopBar>

    <AdminTable
      :items="orderedHighlights"
      :tableKeys="['name', 'highlightedAt']"
      :column-widths="['5%', 'auto', '20%']"
      :is-draggable="true"
      :is-overlay-shown-function="(item) => ((item as HighlightedResource)?.position ?? 0) < 3"
      @update:order="(orderedEvent) => updateOrder(orderedEvent)"
    >
      <template #adminTableItemFirst="{ item }">
        <DiverCityHighlightButton
          :resource-id="(item as HighlightedResource).resourceId"
          @update:highlight="refreshAll"
        />
      </template>
    </AdminTable>

    <!-- Modal d'association réservation <-> ressources -->
    <LinkBookingResourceModal
      :is-shown="isModalShown"
      @close="isModalShown = false"
      @saved="refreshAll"
    />
  </div>
</template>

<script setup lang="ts">
import { useDiverCityHighlightStore } from '@/stores/divercity/divercityHighlightStore'
import { computed, onMounted, ref } from 'vue'
import AdminTable from '@/components/admin/AdminTable.vue'
import AdminTopBar from '@/components/admin/AdminTopBar.vue'
import type { HighlightedResource } from '@/models/interfaces/divercity/HighlightedResource'
import DiverCityHighlightButton from './DiverCityHighlightButton.vue'
import LinkBookingResourceModal from './LinkBookingResourceModal.vue'
import { DiverCityHighlightedResourceService } from '@/services/divercity/DiverCityHighlightedResourceService'
import { localizeDate } from '@/services/utils/UtilsService'

const highlightsStore = useDiverCityHighlightStore()

const isModalShown = ref(false)
const searchQuery = ref('')

const highlights = computed(() => highlightsStore.highlights)

const filteredHighlights = computed(() => {
  if (!searchQuery.value) return highlightsStore.orderedHighlights
  return highlightsStore.orderedHighlights.filter((item) =>
    item.name?.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const orderedHighlights = computed(() =>
  filteredHighlights.value.map((item) => ({
    ...item,
    highlightedAt: item.highlightedAt ? localizeDate(item.highlightedAt) : ''
  }))
)

onMounted(async () => await refreshAll())

const updateOrder = async (orderedEvent: { id: number; oldIndex: number; newIndex: number }) => {
  const resourceId = highlights.value.find((item) => item.id == orderedEvent.id)?.resourceId
  if (!resourceId) return

  await DiverCityHighlightedResourceService.patch({
    resourceId,
    position: orderedEvent.newIndex
  }).then(async () => await refreshAll())
}

const refreshAll = async () => {
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